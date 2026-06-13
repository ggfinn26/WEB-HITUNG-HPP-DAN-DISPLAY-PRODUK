<?php

namespace App{

    require_once __DIR__ . "/../Repository/RincianHppInterface.php";
    require_once __DIR__ . "/../Exception/ValidationException.php";
use App\RincianHppInterface;
use App\ValidationException;
use App\RincianHpp;
use App\RincianHppServiceInterface;
    
    class RincianHppService implements RincianHppServiceInterface{
        private RincianHppInterface $rincianHppRepository;
        private ?ProductInterface   $productRepository;

        public function __construct(RincianHppInterface $rincianHppRepository, ?ProductInterface $productRepository = null){
            $this->rincianHppRepository = $rincianHppRepository;
            $this->productRepository    = $productRepository;
        }

        public const SATUAN_OPTION = [
            ["jenis"=> "umum", "satuan" => ["pcs", "buah", "biji", "unit", "item", "pack", "paket", "set", "pasang"]],
            ["jenis"=> "kemasan", "satuan" => ["lusin", "gross", "kodi", "rim", "lembar", "helai", "roll", "gulung"]],
            ["jenis"=> "grosir", "satuan" => ["box", "dus", "kotak", "karton", "cratic", "keranjang", "karung", "sak", 
            "pouch", "sachet", "tube", "botol", "kaleng", "toples", "jerigen", "drum"]],
            ["jenis"=> "logistik", "satuan" => ["ikat", "ikat kecil", "ikat besar", "gantung", "slop", "bal", "palet"]],
            ["jenis"=> "berat", "satuan" => ["kg", "g", "mg", "ons", "pon", "kuintal", "ton", "lbs"]],
            ["jenis"=> "volume", "satuan" => ["l", "ml", "cc", "liter", "mililiter", "galon", "barel", "tetes", "sendok makan", "sendok teh"]],
            ["jenis"=> "dimensi", "satuan" => ["m", "cm", "mm", "inch", "yard", "kaki", "m2", "cm2", "ru", "hektar", "m3"]],
            ["jenis"=> "waktu", "satuan" => ["jam", "hari", "bulan", "tahun", "shift", "orang", "man-hour", "proyek", "sesi", "kali"]],
            ["jenis"=> "lainnya", "satuan" => [""]]
        ];

        public const TEMPLATE_ITEM = [
            "nama" => "",
            "jumlah" => 0,
            "satuan" => "",
            "kuantitas" => 0,
            "harga" => 0
        ];

        public function validateHppName(string $name): string{
            $existingProduct = $this->rincianHppRepository->findByName(trim($name));
            if(trim($name) === ""){
                throw new ValidationException("Nama HPP Produk tidak boleh kosong");
            } elseif($existingProduct !== null){
                throw new ValidationException("Nama Duplikat! HPP Produk Sudah Ada");
            } else {
                return trim($name);
            }
        }

        public function validateProductItemList(array $itemList): array{

            $template = self::TEMPLATE_ITEM;
            $listSatuan = self::SATUAN_OPTION;
            foreach($itemList as $item){
                $itemKeys = is_array($item) ? array_keys($item) : [];
                $templateKeys = array_keys($template);
                sort($itemKeys);
                sort($templateKeys);
                
                if(empty($item) || !is_array($item)){
                    throw new ValidationException("Item Tidak Boleh Kosong!");
                } elseif($itemKeys !== $templateKeys){
                    throw new ValidationException("Format Item Tidak Sesuai!");
                } elseif(!is_numeric($item['kuantitas']) || !is_numeric($item['harga']) || !is_numeric($item['jumlah'])){
                    throw new ValidationException("Kuantitas, Jumlah dan Harga Harus Berupa Angka!");
                }

                $satuanValid = false;
                if($item['satuan'] === ""){
                    $satuanValid = true;
                } else {
                    foreach($listSatuan as $option){
                        if(in_array($item['satuan'], $option['satuan'])){
                            $satuanValid = true;
                            break;
                        }
                    }
                }

                if(!$satuanValid){
                    throw new ValidationException("Jenis Satuan Tidak Tersedia");
                }
            }

            return $itemList;
        }

        public function lexerHpp(array $itemList): array{
            $token = [];
            
            foreach ($itemList as $index => $item) {
                $n = $index + 1;
                foreach ($item as $key => $value) {
                    if ($key === "nama" || $key === "satuan") {
                        continue;
                    } elseif ($key === "jumlah") {
                        array_push($token, "jumlah_item_" . $n . ":" . $value);
                    } elseif ($key === "kuantitas") {
                        array_push($token, "kuantitas_item_" . $n . ":" . $value);
                    } elseif ($key === "harga") {
                        array_push($token, "harga_item_" . $n . ":" . $value);
                    }
                }
            }
            
            return $token;
        }
        public function parserHpp(array $tokens): array{
            $parserArray = [];
            $tempItems = [];
            foreach ($tokens as $tokenString) {
                $parts = explode(":", $tokenString, 2);
                
                if (count($parts) === 2) {
                    $key = $parts[0];   
                    $value = $parts[1]; 

                    $keyParts = explode("_item_", $key);
                    if (count($keyParts) === 2) {
                        $tipe = $keyParts[0]; 
                        $nomor = $keyParts[1]; 
                        $tempItems[$nomor][$tipe] = $value;
                    }
                }
            }

            foreach ($tempItems as $nomor => $data) {
                $newIndex = "Kalkulasi Item No $nomor";
                $jumlah = $data["jumlah"] ?? "";
                $kuantitas = $data["kuantitas"] ?? "";
                $harga = $data["harga"] ?? "";
                
                $parserArray[$newIndex] = "$jumlah, $kuantitas, $harga";
            }

            return $parserArray;
        }

        public function calculateItem(array $parserArray): array{
            $calculateItem = [];
            $n = 1;
            foreach($parserArray as $key => $valueString){
                $valueArray = explode(",", $valueString);
                $jumlah = (float)trim($valueArray[0]);
                $kuantitas = (float)trim($valueArray[1]);
                $harga = (float)trim($valueArray[2]);

                $subtotal = $jumlah * $kuantitas * $harga;

                // Kembalikan struktur detail_item_$n seperti awal
                $calculateItem[$key] = ["detail_item_$n" =>[
                    "jumlah" => $jumlah,
                    "kuantitas" => $kuantitas,
                    "harga" => $harga,
                    "subtotal" => $subtotal
                ]];
                
                $n++;
            }

            return $calculateItem;
        }
        public function calculateHpp(array $calculateItem, int $jumlahProduksi, int $marginKeuntungan): array{
            if ($jumlahProduksi <= 0) {
                throw new ValidationException("Jumlah Produksi harus lebih dari 0!");
            }

            $totalSubtotal = 0;
            $n = 1;
            
            // Jumlahkan semua subtotal bahan baku
            foreach($calculateItem as $key => $value){
                $totalSubtotal += $value["detail_item_$n"]["subtotal"];
                $n++;
            }

            // Hitung HPP dan Harga Jual final
            $hppProduksi = $totalSubtotal / $jumlahProduksi;
            $hargaJual = $hppProduksi + $marginKeuntungan;

            return [
                "total_biaya_bahan" => $totalSubtotal,
                "hpp_produksi_final" => $hppProduksi,
                "harga_jual_final" => $hargaJual
            ];
        }
        public function create(RincianHpp $rincianHpp): RincianHpp{
            try {
                $this->rincianHppRepository->create($rincianHpp);
                return $rincianHpp;
            } catch (\Exception $e){
                if($this->findByName($rincianHpp->getName()) !== null){
                    throw new ValidationException("Nama Produk Sudah Ada");
                }
                throw new \Exception($e->getMessage());
            }
        }
        public function update(RincianHpp $rincianHpp): RincianHpp{
            if ($this->findById($rincianHpp->getId()) === null) {
                throw new ValidationException("Produk Tidak Ditemukan");
            }
            try {
                $this->rincianHppRepository->update($rincianHpp);
                return $rincianHpp;
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }

        public function validateHppNameForUpdate(string $name, int $currentId): string {
            $name = trim($name);
            if ($name === '') throw new ValidationException("Nama HPP tidak boleh kosong.");
            $existing = $this->rincianHppRepository->findByName($name);
            if ($existing !== null && $existing->getId() !== $currentId) {
                throw new ValidationException("Nama Duplikat! HPP dengan nama ini sudah ada.");
            }
            return $name;
        }

        /**
         * Recalculate, save HPP, then cascade harga_jual to linked product.
         */
        public function updateFull(int $id, array $data): RincianHpp {
            $existing = $this->findById($id);
            if ($existing === null) throw new ValidationException("HPP tidak ditemukan.");

            $name           = $this->validateHppNameForUpdate($data['name'] ?? '', $id);
            $jumlahProduksi = max(1, (int)($data['jumlah_produksi'] ?? 1));
            $margin         = max(0, (int)($data['margin_keuntungan'] ?? 0));

            $itemList = json_decode($data['product_item_list'] ?? '[]', true);
            if (!is_array($itemList) || empty($itemList)) {
                throw new ValidationException("Minimal satu komponen biaya harus diisi.");
            }
            $this->validateProductItemList($itemList);

            $tokens     = $this->lexerHpp($itemList);
            $parsed     = $this->parserHpp($tokens);
            $calculated = $this->calculateItem($parsed);
            $result     = $this->calculateHpp($calculated, $jumlahProduksi, $margin);

            $existing->setName($name);
            $existing->setMarginKeuntungan($margin);
            $existing->setProductItemList(json_encode($itemList));
            $existing->setJumlahProduksi($jumlahProduksi);
            $existing->setTotalBiayaHpp((string)round($result['total_biaya_bahan'], 2));
            $existing->setHppPerPcs((string)round($result['hpp_produksi_final'], 2));
            $existing->setHargaJualProduk((string)round($result['harga_jual_final'], 2));

            $this->rincianHppRepository->update($existing);

            // Cascade ke produk yang terhubung
            if ($this->productRepository && $existing->getProductId() > 0) {
                $this->productRepository->updatePrice(
                    $existing->getProductId(),
                    (string)round($result['harga_jual_final'], 2)
                );
            }

            return $existing;
        }
        public function updateHargaJualProduk(int $id, int $hargaJualProduk): RincianHpp{
            if ($this->findById($id) === null) {
                throw new ValidationException("Produk Tidak Ditemukan");
            }
            try{
                $this->rincianHppRepository->updateHargaJualProduk($id, $hargaJualProduk);
                return $this->findById($id);
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }
        public function updateMarginKeuntungan(int $id, int $marginKeuntungan): RincianHpp{
            if ($this->findById($id) === null) {
                throw new ValidationException("Produk Tidak Ditemukan");
            }
            try {
                $this->rincianHppRepository->updateMarginKeuntungan($id, $marginKeuntungan);
                return $this->findById($id);
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }
        public function updateJumlahProduksi(int $id, int $jumlahProduksi): RincianHpp{
            if ($this->findById($id) === null) {
                throw new ValidationException("Produk Tidak Ditemukan");
            }
            try{
                $this->rincianHppRepository->updateJumlahProduksi($id, $jumlahProduksi);
                return $this->findById($id);
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }
        public function updateItemProduksi(int $id, array $productItemList): RincianHpp{
            if ($this->findById($id) === null) {
                throw new ValidationException("Produk Tidak Ditemukan");
            }
            try{
                $this->rincianHppRepository->updateItemProduksi($id, json_encode($productItemList));
                return $this->findById($id);
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }
        public function delete(int $id): bool{
            if ($this->findById($id) === null) {
                throw new ValidationException("Produk Tidak Ditemukan");
            }
            try{
                $this->rincianHppRepository->delete($id);
                return true;
            } catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }
        public function findById(int $id): ?RincianHpp{
            return $this->rincianHppRepository->findById($id);
        }
        public function findByName(string $name): ?RincianHpp{
            return $this->rincianHppRepository->findByName($name);
        }
        public function findAll(): array{
            return $this->rincianHppRepository->findAll();
        }
        public function count(): int{
            return $this->rincianHppRepository->count();
        }
        public function search(string $query): array{
            return $this->rincianHppRepository->search($query);
        }
    }
}