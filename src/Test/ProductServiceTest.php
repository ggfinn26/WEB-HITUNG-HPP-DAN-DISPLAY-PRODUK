<?php

namespace App\Test{

    require_once __DIR__ . '/../Service/ProductService.php';
    require_once __DIR__ . '/../Repository/ProductInterface.php';
    require_once __DIR__ . '/../Entity/Product.php';
    require_once __DIR__ . '/../Exception/ValidationException.php';

    use PHPUnit\Framework\TestCase;
    use App\ProductService;
    use App\ProductInterface;
    use App\Product;
    use App\ValidationException;

    class ProductServiceTest extends TestCase {
        private ProductInterface $productRepositoryMock;
        private ProductService $productService;

        protected function setUp(): void {
            parent::setUp();
            // Membuat mock untuk dependency ProductInterface
            $this->productRepositoryMock = $this->createMock(ProductInterface::class);
            // Menginjeksi mock ke dalam service
            $this->productService = new ProductService($this->productRepositoryMock);
        }

        public function testValidateProductNameSuccess() {
            // Skenario: Produk tidak ditemukan di database (null)
            $this->productRepositoryMock->method('findByName')->willReturn(null);

            $result = $this->productService->validateProductName("Produk Baru");
            
            // Assertion: Mengembalikan nama produk kembali
            self::assertEquals("Produk Baru", $result);
        }

        public function testValidateProductNameEmpty() {
            self::expectException(ValidationException::class);
            self::expectExceptionMessage("Nama produk tidak boleh kosong");

            // Skenario: Mengirim spasi kosong
            $this->productService->validateProductName("   ");
        }

        public function testValidateProductNameDuplicate() {
            // Skenario: Produk ditemukan di database (mengembalikan object Product)
            $dummyProduct = $this->createMock(Product::class);
            $this->productRepositoryMock->method('findByName')->willReturn($dummyProduct);

            self::expectException(ValidationException::class);
            self::expectExceptionMessage("Nama Duplikat! Produk Sudah Ada");

            $this->productService->validateProductName("Produk Lama");
        }

        public function testValidateProductPriceSuccess() {
            $result = $this->productService->validateProductPrice("15000");
            self::assertEquals("15000", $result);

            // Coba dengan format decimal
            $resultDecimal = $this->productService->validateProductPrice("15000.50");
            self::assertEquals("15000.50", $resultDecimal);
        }

        public function testValidateProductPriceNotNumeric() {
            self::expectException(ValidationException::class);
            self::expectExceptionMessage("Harga Produk Harus Berupa Angka");

            // Skenario: Mengirim karakter huruf
            $this->productService->validateProductPrice("Lima Belas Ribu");
        }
    }
}
