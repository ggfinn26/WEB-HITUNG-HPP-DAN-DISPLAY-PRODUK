<?php

namespace App{

    require_once __DIR__. "/../Repository/ProductInterface.php";
    require_once __DIR__. "/../Repository/RincianHppInterface.php";
    require_once __DIR__. "/../Exception/ValidationException.php";
    use App\ProductInterface;
    use App\RincianHppInterface;
    use App\ValidationException;
    use App\ProductServiceInterface;
    use App\ProductVariantRepository;
    use Geocoder\Provider\Nominatim\Nominatim;
    use Http\Adapter\Guzzle7\Client as HttpClient;
    use Geocoder\Query\GeocodeQuery;

    class ProductService implements ProductServiceInterface{
        private ProductInterface $productRepository;
        private RincianHppInterface $rincianHppRepository;
        private ?ProductVariantRepository $variantRepository;
        
        public function __construct(ProductInterface $productRepository, RincianHppInterface $rincianHppRepository, ?ProductVariantRepository $variantRepository = null){
            $this->productRepository = $productRepository;
            $this->rincianHppRepository = $rincianHppRepository;
            $this->variantRepository = $variantRepository;
        }

        public function getVariantRepository(): ?ProductVariantRepository {
            return $this->variantRepository;
        }

        public function validateProductName(string $name): string{
            $existingProduct = $this->productRepository->findByName(trim($name));
            if(trim($name) === ""){
                throw new ValidationException("Nama produk tidak boleh kosong");
            } elseif($existingProduct !== null){
                throw new ValidationException("Nama Duplikat! Produk Sudah Ada");
            } else {
                return trim($name);
            }
        }

        public function importProductPriceFromHpp(int $productId): void{
            $rincianHpp = $this->rincianHppRepository->findById($productId);
            if($rincianHpp === null){
                throw new ValidationException("Data HPP tidak ditemukan untuk produk ini");
            }
            $product = $this->productRepository->findById($productId);
            if($product === null){
                throw new ValidationException("Produk tidak ditemukan");
            }
            $product->setPrice($rincianHpp->getHargaJualProduk());
            $this->productRepository->update($product);
        }


        public function validateProductPrice(string $price): string{
            if(!is_numeric($price)){
                throw new ValidationException("Harga Produk Harus Berupa Angka");
            } else {
                return $price;
            }
        }

        public function findAll(): array{
            return $this->productRepository->findAll();
        }

        public function countAll(): int {
            return $this->productRepository->countAll();
        }

        public function findPaginated(int $page, int $perPage): array {
            return $this->productRepository->findPaginated($page, $perPage);
        }

        public function findById(int $id): ?\App\Product {
            return $this->productRepository->findById($id);
        }

        public function save(array $data): \App\Product {
            $hppId = (int)($data['hpp_id'] ?? 0);
            if ($hppId <= 0) {
                throw new ValidationException("Pilih kalkulasi HPP terlebih dahulu sebelum membuat produk.");
            }
            $hpp = $this->rincianHppRepository->findById($hppId);
            if ($hpp === null) {
                throw new ValidationException("Data HPP tidak ditemukan. Buat HPP terlebih dahulu.");
            }

            $name = $this->validateProductName($data['name'] ?? '');
            $price = $hpp->getHargaJualProduk();
            $description = $data['description'] ?? '';
            $imageUrl = $data['image_url'] ?? '';
            
            $latitude = isset($data['latitude']) && $data['latitude'] !== '' ? (float)$data['latitude'] : null;
            $longitude = isset($data['longitude']) && $data['longitude'] !== '' ? (float)$data['longitude'] : null;

            $product = new \App\Product(
                0, $name, $price, $description, $imageUrl,
                new \DateTime(), new \DateTime(), false, $latitude, $longitude
            );
            return $this->productRepository->save($product);
        }

        public function update(int $id, array $data): \App\Product {
            $product = $this->findById($id);
            if (!$product) {
                throw new ValidationException("Produk tidak ditemukan");
            }
            
            // Only validate name if it's changing
            if (isset($data['name']) && trim($data['name']) !== $product->getName()) {
                $product->setName($this->validateProductName($data['name']));
            }
            if (isset($data['price'])) {
                $product->setPrice($this->validateProductPrice($data['price']));
            }
            if (isset($data['description'])) {
                $product->setDescription($data['description']);
            }
            if (isset($data['image_url'])) {
                $product->setImageUrl($data['image_url']);
            }
            
            // Allow null coordinates
            if (array_key_exists('latitude', $data)) {
                $product->setLatitude($data['latitude'] !== '' && $data['latitude'] !== null ? (float)$data['latitude'] : null);
            }
            if (array_key_exists('longitude', $data)) {
                $product->setLongitude($data['longitude'] !== '' && $data['longitude'] !== null ? (float)$data['longitude'] : null);
            }

            return $this->productRepository->update($product);
        }

        public function delete(int $id): bool {
            if ($this->variantRepository) {
                $this->variantRepository->deleteVariantsByProduct($id);
                $this->variantRepository->deleteGroupsByProduct($id);
                $this->variantRepository->deleteImagesByProduct($id);
            }
            return $this->productRepository->delete($id);
        }

        public function saveVariants(int $productId, array $groups, array $options, array $variants, array $images): void {
            if (!$this->variantRepository) return;

            // Delete old variants
            $this->variantRepository->deleteVariantsByProduct($productId);
            $this->variantRepository->deleteGroupsByProduct($productId);
            $this->variantRepository->deleteImagesByProduct($productId);

            // Save Images
            $savedImages = $this->variantRepository->saveImages($productId, $images);
            // map old image index to new image id
            $imageMap = [];
            foreach ($savedImages as $idx => $img) {
                // assume images array keys are preserved or we match by url
                foreach ($images as $origIdx => $origImg) {
                    if ($origImg['url'] === $img['url']) {
                        $imageMap[$origIdx] = $img['id'];
                    }
                }
            }

            // Save Groups
            $savedGroups = $this->variantRepository->saveVariantGroups($productId, $groups);
            
            // map group name to group id
            $groupMap = [];
            foreach ($savedGroups as $sg) {
                $groupMap[$sg['name']] = $sg['id'];
            }

            // Save Options
            $optionMap = []; // old option name -> new option id
            foreach ($options as $groupName => $opts) {
                if (!isset($groupMap[$groupName])) continue;
                $savedOpts = $this->variantRepository->saveVariantOptions($groupMap[$groupName], $opts);
                foreach ($savedOpts as $so) {
                    $optionMap[$so['name']] = $so['id'];
                }
            }

            // Save Variants & Combinations
            foreach ($variants as $v) {
                // translate image
                if (isset($v['image_index']) && isset($imageMap[$v['image_index']])) {
                    $v['image_id'] = $imageMap[$v['image_index']];
                }
                
                $savedV = $this->variantRepository->saveVariants($productId, [$v])[0];
                
                // Save combinations
                $optIds = [];
                if (!empty($v['options'])) {
                    foreach ($v['options'] as $optName) {
                        if (isset($optionMap[$optName])) {
                            $optIds[] = $optionMap[$optName];
                        }
                    }
                }
                if (!empty($optIds)) {
                    $this->variantRepository->saveVariantCombinations($savedV['id'], $optIds);
                }
            }
        }

        public function geocodeCity(string $location): ?array {
            try {
                $httpClient = new HttpClient();
                $provider = new Nominatim($httpClient, 'https://nominatim.openstreetmap.org', 'JastipArungaApp/1.0');
                
                $result = $provider->geocodeQuery(GeocodeQuery::create($location));
                
                if (!$result->isEmpty()) {
                    $coordinates = $result->first()->getCoordinates();
                    return [
                        'latitude' => $coordinates->getLatitude(),
                        'longitude' => $coordinates->getLongitude()
                    ];
                }
            } catch (\Exception $e) {
                file_put_contents(__DIR__ . "/../Logs/process.log", "[" . date('Y-m-d H:i:s') . "] ERROR: Geocoding gagal (Library) - " . $e->getMessage() . "\n", FILE_APPEND);
            }
            return null;
        }


    }
}