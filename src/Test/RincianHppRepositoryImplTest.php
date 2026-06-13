<?php

require_once __DIR__ . "/../Entity/RincianHpp.php";
require_once __DIR__ . "/../Repository/RincianHppInterface.php";
require_once __DIR__ . "/../Repository/RincianHppRepositoryImpl.php";
require_once __DIR__ . "/../Config/Database.php";
require_once __DIR__ . "/../Entity/Product.php";
require_once __DIR__ . "/../Repository/ProductInterface.php";
require_once __DIR__ . "/../Repository/ProductRepositoryImpl.php";

use PHPUnit\Framework\TestCase;
use App\RincianHpp;
use App\RincianHppInterface;
use App\RincianHppRepositoryImpl;
use App\Product;
use App\ProductInterface;
use App\ProductRepositoryImpl;

class RincianHppRepositoryImplTest extends TestCase{
    
    private RincianHppRepositoryImpl $rincianHppRepository;
    private RincianHpp $rincianHpp;

    private Product $product;

    private ProductRepositoryImpl $productRepository;

    public function setUp(): void{
        file_put_contents(__DIR__ . "/../Logs/process.log", "\n=== MEMULAI TEST BARU ===\n", FILE_APPEND);
        file_put_contents(__DIR__ . "/../Logs/process.log", "[" . date('Y-m-d H:i:s') . "] PROSES: Menghapus semua data tabel sebelum test...\n", FILE_APPEND);

        $connection = \App\Database::getConnection();
        $connection->exec("DELETE FROM rincian_hpp");
        $connection->exec("DELETE FROM products");
        $connection->exec("ALTER TABLE products AUTO_INCREMENT = 1");
        $connection->exec("ALTER TABLE rincian_hpp AUTO_INCREMENT = 1");


        $this->productRepository = new ProductRepositoryImpl();
        $this->product = new Product(
            0,
            "",
            "",
            "",
            "",
            new DateTime(),
            new DateTime(),
            false
        );

        $this->rincianHppRepository = new RincianHppRepositoryImpl();
        $this->rincianHpp = new RincianHpp(
            0,
            0,
            "",
            0,
            "[]", // Harus string kosong atau JSON string kosong
            0,
            "0",
            "0",
            "0",
            new \DateTime(),
            new \DateTime(),
            false
        );
    }

    public function testCreateRincianHpp() : void {
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("Deskripsi Produk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        self::assertEquals(1, $this->rincianHpp->getProductId());
        self::assertEquals("Produk 1", $this->rincianHpp->getName());
        self::assertEquals(20, $this->rincianHpp->getMarginKeuntungan());
        self::assertEquals(20, $this->rincianHpp->getJumlahProduksi());
        self::assertEquals(10000, $this->rincianHpp->getTotalBiayaHpp());
        self::assertEquals(10000, $this->rincianHpp->getHppPerPcs());
        self::assertEquals(1000000, $this->rincianHpp->getHargaJualProduk());
        self::assertEquals(false, $this->rincianHpp->getIsDeleted());
    }

    public function testCreateFailedDueToNameNotUnique(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("Deskripsi Produk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);
        
        $this->rincianHpp->setId(2);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);

        self::expectException(\PDOException::class);
        $this->rincianHppRepository->create($this->rincianHpp);

    }

    public function testUpdate(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("Deskripsi Produk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        $this->rincianHpp->setName("Produk 2");
        $this->rincianHpp->setMarginKeuntungan(10);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 2",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->update($this->rincianHpp);

        self::assertEquals(1, $this->rincianHpp->getProductId());
        self::assertEquals("Produk 2", $this->rincianHpp->getName());
        self::assertEquals(10, $this->rincianHpp->getMarginKeuntungan());
        self::assertEquals(20, $this->rincianHpp->getJumlahProduksi());
        self::assertEquals(10000, $this->rincianHpp->getTotalBiayaHpp());
        self::assertEquals(10000, $this->rincianHpp->getHppPerPcs());
        self::assertEquals(1000000, $this->rincianHpp->getHargaJualProduk());
        self::assertEquals(false, $this->rincianHpp->getIsDeleted());
    }

    public function testUpdateFailed(){
                $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("Deskripsi Produk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        // Menguji error update karena melanggar foreign key product_id = 999
        $this->rincianHpp->setProductId(999);
        $this->rincianHpp->setUpdatedAt(new DateTime());

        self::expectException(\PDOException::class);
        $this->rincianHppRepository->update($this->rincianHpp);
    }

    public function testUpdateHargaJualProdukOnly(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("Deskripsi Produk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        
        $this->rincianHpp->setHargaJualProduk(2000000);
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHppRepository->update($this->rincianHpp);

        
        self::assertEquals(2000000, $this->rincianHpp->getHargaJualProduk());
        self::assertEquals("Produk 1", $this->rincianHpp->getName());
    }

    public function testUpdateMarginKeuntunganOnly(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("DeskripsiProduk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        
        $this->rincianHpp->setMarginKeuntungan(25);
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHppRepository->update($this->rincianHpp);

        
        self::assertEquals(25, $this->rincianHpp->getMarginKeuntungan());
        self::assertEquals("Produk 1", $this->rincianHpp->getName());
    }

    public function testUpdateJumlahProduksiOnly(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("DeskripsiProduk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        
        $this->rincianHpp->setJumlahProduksi(25);
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHppRepository->update($this->rincianHpp);

        
        self::assertEquals(25, $this->rincianHpp->getJumlahProduksi());
        self::assertEquals("Produk 1", $this->rincianHpp->getName());
    }

    public function testUpdateItemProduksiAppend(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("DeskripsiProduk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            [
                "nama" => "Bahan 1",
                "harga" => 10000,
                "kuantitas" => 20,
                "satuan" => "ml",
            ]
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        // Ambil data yang ada, decode JSON menjadi array
        $itemList = json_decode($this->rincianHpp->getProductItemList(), true);
        
        // Tambahkan (push) item bahan baku yang baru
        $itemList[] = [
            "nama" => "Bahan 2",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ];
        
        // Set ulang item produksi ke entity (di-encode kembali)
        $this->rincianHpp->setProductItemList(json_encode($itemList));
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHppRepository->update($this->rincianHpp);

        // Fetch dari DB untuk memastikan benar-benar tersimpan
        $updatedRincian = $this->rincianHppRepository->findById(1);

        self::assertEquals([
            [
                "nama" => "Bahan 1",
                "harga" => 10000,
                "kuantitas" => 20,
                "satuan" => "ml",
            ],
            [
                "nama" => "Bahan 2",
                "harga" => 10000,
                "kuantitas" => 20,
                "satuan" => "ml",
            ]
        ], json_decode($updatedRincian->getProductItemList(), true));
        self::assertEquals("Produk 1", $this->rincianHpp->getName());
    }

    public function testUpdateItemProduksiReplace(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("DeskripsiProduk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 2",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHppRepository->update($this->rincianHpp);

        $updatedRincian = $this->rincianHppRepository->findById(1);

        self::assertEquals([
            "nama" => "Bahan 2",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ], json_decode($updatedRincian->getProductItemList(), true));
    }

    public function testDelete(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("DeskripsiProduk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);
        
        $this->rincianHppRepository->delete(1);
        
        self::assertNull($this->rincianHppRepository->findById(1));
    }

    public function testFindById(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("DeskripsiProduk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        
        $this->rincianHpp = $this->rincianHppRepository->findById(1);

        
        self::assertEquals(1, $this->rincianHpp->getId());
        self::assertEquals("Produk 1", $this->rincianHpp->getName());
    }

    public function testFindAll(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("DeskripsiProduk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        $this->rincianHpp->setName("Produk 2");
        $this->rincianHppRepository->create($this->rincianHpp);

        
        self::assertCount(2, $this->rincianHppRepository->findAll());
    }

    public function testCount(){
        $this->product->setId(1);
        $this->product->setName("Produk 1");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("DeskripsiProduk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->rincianHpp->setId(1);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 1");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);
        $this->rincianHpp->setId(2);
        $this->rincianHpp->setProductId(1);
        $this->rincianHpp->setName("Produk 2");
        $this->rincianHpp->setMarginKeuntungan(20);
        $this->rincianHpp->setProductItemList(json_encode([
            "nama" => "Bahan 1",
            "harga" => 10000,
            "kuantitas" => 20,
            "satuan" => "ml",
        ]));
        $this->rincianHpp->setJumlahProduksi(20);
        $this->rincianHpp->setTotalBiayaHpp(10000);
        $this->rincianHpp->setHppPerPcs(10000);
        $this->rincianHpp->setHargaJualProduk(1000000);
        $this->rincianHpp->setCreatedAt(new DateTime());
        $this->rincianHpp->setUpdatedAt(new DateTime());
        $this->rincianHpp->setIsDeleted(false);
        $this->rincianHppRepository->create($this->rincianHpp);

        self::assertCount(2, $this->rincianHppRepository->findAll());
        self::assertEquals(2, $this->rincianHppRepository->count());
    }

    public function testSearch(){
        $this->product->setId(1);
        $this->product->setName("Produk Utama");
        $this->product->setPrice("10000.00");
        $this->product->setDescription("DeskripsiProduk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new DateTime());
        $this->product->setUpdatedAt(new DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);

        for($i = 1; $i <= 10; $i++) {
            $this->rincianHpp->setId($i);
            $this->rincianHpp->setProductId(1);
            if ($i <= 5) {
                $this->rincianHpp->setName("Ayam Goreng " . $i);
                $this->rincianHpp->setHppPerPcs("10000");
            } else {
                $this->rincianHpp->setName("Bebek Bakar " . $i);
                $this->rincianHpp->setHppPerPcs("25000");
            }
            $this->rincianHpp->setMarginKeuntungan(20);
            $this->rincianHpp->setProductItemList(json_encode([
                "nama" => "Bahan " . $i,
                "harga" => 10000,
                "kuantitas" => 20,
                "satuan" => "ml",
            ]));
            $this->rincianHpp->setJumlahProduksi(20);
            $this->rincianHpp->setTotalBiayaHpp("10000");
            $this->rincianHpp->setHargaJualProduk("1000000");
            $this->rincianHpp->setCreatedAt(new DateTime());
            $this->rincianHpp->setUpdatedAt(new DateTime());
            $this->rincianHpp->setIsDeleted(false);
            $this->rincianHppRepository->create($this->rincianHpp);
        }

        // Test mencari "Ayam" (harus dapat 5 data)
        $resultAyam = $this->rincianHppRepository->search("Ayam");
        self::assertCount(5, $resultAyam);

        // Test mencari "Bebek" (harus dapat 5 data)
        $resultBebek = $this->rincianHppRepository->search("Bebek");
        self::assertCount(5, $resultBebek);

        // Test mencari "25000" di kolom hpp_per_pcs (harus dapat 5 data bebek)
        $resultHpp = $this->rincianHppRepository->search("25000");
        self::assertCount(5, $resultHpp);

        // Test string kosong (harus dapat semua data)
        $resultAll = $this->rincianHppRepository->search("");
        self::assertCount(10, $resultAll);
    }
}

