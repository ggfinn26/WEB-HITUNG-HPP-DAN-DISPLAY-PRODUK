<?php

namespace App{

    require_once __DIR__. "/../Repository/ProductInterface.php";
    require_once __DIR__. "/../Repository/RincianHppInterface.php";
    require_once __DIR__. "/../Exception/ValidationException.php";
    use App\ProductInterface;
    use App\RincianHppInterface;
    use App\ValidationException;
    use App\ProductServiceInterface;
    use Geocoder\Provider\Nominatim\Nominatim;
    use Http\Adapter\Guzzle7\Client as HttpClient;
    use Geocoder\Query\GeocodeQuery;

    class ProductService implements ProductServiceInterface{
        private ProductInterface $productRepository;
        private RincianHppInterface $rincianHppRepository;
        
        public function __construct(ProductInterface $productRepository, RincianHppInterface $rincianHppRepository){
            $this->productRepository = $productRepository;
            $this->rincianHppRepository = $rincianHppRepository;
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
            return $this->productRepository->delete($id);
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