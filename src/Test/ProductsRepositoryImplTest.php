<?php

require_once __DIR__ . "/../Entity/Product.php";
require_once __DIR__ . "/../Repository/ProductInterface.php";
require_once __DIR__ . "/../Repository/ProductRepositoryImpl.php";
require_once __DIR__ . "/../Config/Database.php";

use PHPUnit\Framework\TestCase;
use App\Product;
use App\ProductInterface;
use App\ProductRepositoryImpl;

class ProductsRepositoryImplTest extends TestCase{

    private ProductInterface $productRepository;
    private Product $product;

    public function setUp() : void {
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
            "0",
            "",
            "",
            new \DateTime(),
            new \DateTime(),
            false
        );
    }

    public function testSaveProduct(){
        $this->product->setName("Produk 1");
        $this->product->setPrice("50000");
        $this->product->setDescription("Deskripsi Produk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);

        self::assertEquals("Produk 1", $this->product->getName());
        self::assertEquals("50000", $this->product->getPrice());
        self::assertEquals("Deskripsi Produk", $this->product->getDescription());
        self::assertEquals("image.jpg", $this->product->getImageUrl());
        self::assertEquals(false, $this->product->getIsDeleted());
    }

    public function testSaveFailed(){
        $this->product->setName("");
        $this->product->setPrice("");
        $this->product->setDescription("");
        $this->product->setImageUrl("");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        

        self::expectException("Exception");
        $this->productRepository->save($this->product);
    }

    public function testUpdateProduct() : void {
        $this->product->setName("Produk 2");
        $this->product->setPrice("60000");
        $this->product->setDescription("Deskripsi Produk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->update($this->product);

        self::assertEquals("Produk 2", $this->product->getName());
        self::assertEquals("60000", $this->product->getPrice());
        self::assertEquals("Deskripsi Produk", $this->product->getDescription());
        self::assertEquals("image.jpg", $this->product->getImageUrl());
        self::assertEquals(false, $this->product->getIsDeleted());
    }

    public function testUpdateFailed() : void {
        $this->product->setName("Valid Name");
        $this->product->setPrice("1000.00");
        $this->product->setDescription("Valid Desc");
        $this->product->setImageUrl("Valid.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $saved = $this->productRepository->save($this->product);

        $saved->setPrice(""); 

        self::expectException(\Exception::class);
        $this->productRepository->update($saved);
    }

    public function testSaveDuplicate(){
        // 1. Save data pertama
        $this->product->setName("Produk Kembar");
        $this->product->setPrice("1000.00");
        $this->product->setDescription("Desc");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);

        $product2 = new Product(
            0,
            "Produk Kembar",
            "2000.00",
            "Desc",
            "image.jpg",
            new \DateTime(),
            new \DateTime(),
            false
        );

        self::expectException(\Exception::class);
        $this->productRepository->save($product2);
    }

    public function testFindById() : void {
        $this->product->setName("Produk 3");
        $this->product->setPrice("70000");
        $this->product->setDescription("Deskripsi Produk");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);

        $product = $this->productRepository->findById($this->product->getId());
        self::assertEquals("Produk 3", $product->getName());
        self::assertEquals("70000.00", $product->getPrice());
        self::assertEquals("Deskripsi Produk", $product->getDescription());
        self::assertEquals("image.jpg", $product->getImageUrl());
        self::assertEquals(false, $product->getIsDeleted());
    }

    public function testFindByIdFailed() : void {
        $product = $this->productRepository->findById(999999);
        self::assertNull($product);
    }

    public function testFindAll(){
        $this->product->setName("Produk FindAll 1");
        $this->product->setPrice("5000");
        $this->product->setDescription("Terserah");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);
        
        $this->product->setName("Produk FindAll 2");
        $this->product->setPrice("5000");
        $this->product->setDescription("Terserah");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);

        $this->product->setName("Produk FindAll 3");
        $this->product->setPrice("5000");
        $this->product->setDescription("Terserah");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);


        $products = $this->productRepository->findAll();
        self::assertIsArray($products);
        
        self::assertGreaterThanOrEqual(3, count($products));

        $foundProduk1 = false;
        $foundProduk3 = false;

        foreach ($products as $p) {
            if ($p->getName() === "Produk FindAll 1" && $p->getPrice() === "5000.00") {
                $foundProduk1 = true;
            }
            if ($p->getName() === "Produk FindAll 3" && $p->getDescription() === "Terserah") {
                $foundProduk3 = true;
                self::assertEquals(false, $p->getIsDeleted());
            }
        }

        self::assertTrue($foundProduk1, "Produk FindAll 1 gagal ditemukan oleh findAll");
        self::assertTrue($foundProduk3, "Produk FindAll 3 gagal ditemukan oleh findAll");
    }

    public function testFindAllSortedByPriceAsc(){
        $this->product->setName("Produk Asc 1");
        $this->product->setPrice("3000");
        $this->product->setDescription("Desc");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);

        $this->product->setName("Produk Asc 2");
        $this->product->setPrice("1000");
        $this->productRepository->save($this->product);

        $this->product->setName("Produk Asc 3");
        $this->product->setPrice("5000");
        $this->productRepository->save($this->product);

        $products = $this->productRepository->findAllSortedByPriceAsc();
        self::assertIsArray($products);
        self::assertGreaterThanOrEqual(2, count($products));

        for ($i = 0; $i < count($products) - 1; $i++) {
            $currentPrice = (float) $products[$i]->getPrice();
            $nextPrice = (float) $products[$i + 1]->getPrice();
            self::assertLessThanOrEqual($nextPrice, $currentPrice);
        }
    }

    public function testFindAllSortedByPriceDesc(){
        $this->product->setName("Produk Desc 1");
        $this->product->setPrice("3000");
        $this->product->setDescription("Desc");
        $this->product->setImageUrl("image.jpg");
        $this->product->setCreatedAt(new \DateTime());
        $this->product->setUpdatedAt(new \DateTime());
        $this->product->setIsDeleted(false);
        $this->productRepository->save($this->product);

        $this->product->setName("Produk Desc 2");
        $this->product->setPrice("1000");
        $this->productRepository->save($this->product);

        $this->product->setName("Produk Desc 3");
        $this->product->setPrice("5000");
        $this->productRepository->save($this->product);

        $products = $this->productRepository->findAllSortedByPriceDesc();
        self::assertIsArray($products);
        self::assertGreaterThanOrEqual(2, count($products));

        for ($i = 0; $i < count($products) - 1; $i++) {
            $currentPrice = (float) $products[$i]->getPrice();
            $nextPrice = (float) $products[$i + 1]->getPrice();
            self::assertGreaterThanOrEqual($nextPrice, $currentPrice);
        }
    }
}