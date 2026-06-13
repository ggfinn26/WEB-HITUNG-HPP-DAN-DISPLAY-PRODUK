<?php

namespace App{
    require_once __DIR__."/ProductInterface.php";
    require_once __DIR__."/../Config/Database.php";
    use App\ProductInterface;
    use App\Database;

    class ProductRepositoryImpl implements ProductInterface{
        private \PDO $connection;

        public function __construct(){
            $this->connection = Database::getConnection();
        }

        public function save(\App\Product $product): \App\Product {
            try {
                file_put_contents(__DIR__ . "/../Logs/process.log", "[" . date('Y-m-d H:i:s') . "] PROSES: Menyimpan produk baru bernama '" . $product->getName() . "'\n", FILE_APPEND);
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("INSERT INTO products (name, price, description, image_url, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $product->getName(),
                    $product->getPrice(),
                    $product->getDescription(),
                    $product->getImageUrl(),
                    $product->getLatitude(),
                    $product->getLongitude()
                ]);
                $lastId = $this->connection->lastInsertId();
                $product->setId((int)$lastId);
                $this->connection->commit();
                return $product;
            } catch (\Exception $e) {
                if ($this->connection->inTransaction()) {
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function update(\App\Product $product): \App\Product {
            try {
                file_put_contents(__DIR__ . "/../Logs/process.log", "[" . date('Y-m-d H:i:s') . "] PROSES: Mengupdate produk ID " . $product->getId() . "\n", FILE_APPEND);
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("UPDATE products SET name = ?, price = ?, description = ?, image_url = ?, latitude = ?, longitude = ? WHERE id = ?");
                $stmt->execute([
                    $product->getName(),
                    $product->getPrice(),
                    $product->getDescription(),
                    $product->getImageUrl(),
                    $product->getLatitude(),
                    $product->getLongitude(),
                    $product->getId()
                ]);
                $this->connection->commit();
                return $product;
            } catch (\Exception $e) {
                if ($this->connection->inTransaction()) {
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function delete(int $id): bool {
            try {
                file_put_contents(__DIR__ . "/../Logs/process.log", "[" . date('Y-m-d H:i:s') . "] PROSES: Menghapus (soft delete) produk ID " . $id . "\n", FILE_APPEND);
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("UPDATE products SET is_deleted = 1 WHERE id = ?");
                $success = $stmt->execute([$id]);
                $this->connection->commit();
                return $success;
            } catch (\Exception $e) {
                if ($this->connection->inTransaction()) {
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function findByName(string $name): ?Product{
            file_put_contents(__DIR__ . "/../Logs/process.log", "[" . date('Y-m-d H:i:s') . "] PROSES: Mencari produk dengan nama " . $name . "\n", FILE_APPEND);
            $stmt = $this->connection->prepare("SELECT * FROM products WHERE name = ? AND is_deleted = 0");
            $stmt->execute([$name]);
            
            if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new \App\Product(
                    $row['id'], $row['name'], $row['price'], $row['description'], $row['image_url'],
                    new \DateTime($row['created_at']), new \DateTime($row['updated_at']), (bool)$row['is_deleted'],
                    $row['latitude'] ?? null, $row['longitude'] ?? null
                );
            }
            return null;
        }

        public function findById(int $id): ?\App\Product {
            file_put_contents(__DIR__ . "/../Logs/process.log", "[" . date('Y-m-d H:i:s') . "] PROSES: Mencari produk dengan ID " . $id . "\n", FILE_APPEND);
            $stmt = $this->connection->prepare("SELECT * FROM products WHERE id = ? AND is_deleted = 0");
            $stmt->execute([$id]);
            
            if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new \App\Product(
                    $row['id'], $row['name'], $row['price'], $row['description'], $row['image_url'],
                    new \DateTime($row['created_at']), new \DateTime($row['updated_at']), (bool)$row['is_deleted'],
                    $row['latitude'] ?? null, $row['longitude'] ?? null
                );
            }
            return null;
        }

        public function findAll(): array {
            $stmt = $this->connection->prepare("SELECT * FROM products WHERE is_deleted = 0");
            $stmt->execute();
            
            $result = [];
            foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $result[] = new \App\Product(
                    $row['id'], $row['name'], $row['price'], $row['description'], $row['image_url'],
                    new \DateTime($row['created_at']), new \DateTime($row['updated_at']), (bool)$row['is_deleted'],
                    $row['latitude'] ?? null, $row['longitude'] ?? null
                );
            }
            return $result;
        }

        public function findAllSortedByPriceAsc(string $direction = 'ASC'): array {
            $dir = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
            $stmt = $this->connection->prepare("SELECT * FROM products WHERE is_deleted = 0 ORDER BY price $dir");
            $stmt->execute();
            
            $result = [];
            foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $result[] = new \App\Product(
                    $row['id'], $row['name'], $row['price'], $row['description'], $row['image_url'],
                    new \DateTime($row['created_at']), new \DateTime($row['updated_at']), (bool)$row['is_deleted'],
                    $row['latitude'] ?? null, $row['longitude'] ?? null
                );
            }
            return $result;
        }
        public function findAllSortedByPriceDesc(string $direction = 'DESC'): array {
            $dir = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
            $stmt = $this->connection->prepare("SELECT * FROM products WHERE is_deleted = 0 ORDER BY price $dir");
            $stmt->execute();
            
            $result = [];
            foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $result[] = new \App\Product(
                    $row['id'], $row['name'], $row['price'], $row['description'], $row['image_url'],
                    new \DateTime($row['created_at']), new \DateTime($row['updated_at']), (bool)$row['is_deleted'],
                    $row['latitude'] ?? null, $row['longitude'] ?? null
                );
            }
            return $result;
        }

        public function updatePrice(int $id, string $price): void {
            $stmt = $this->connection->prepare("UPDATE products SET price = ?, updated_at = NOW() WHERE id = ? AND is_deleted = 0");
            $stmt->execute([$price, $id]);
        }

        public function findByHppId(int $hppId): ?Product {
            $stmt = $this->connection->prepare(
                "SELECT p.* FROM products p
                 INNER JOIN rincian_hpp rh ON rh.product_id = p.id
                 WHERE rh.id = ? AND p.is_deleted = 0
                 LIMIT 1"
            );
            $stmt->execute([$hppId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row) return null;
            return new \App\Product(
                $row['id'], $row['name'], $row['price'], $row['description'], $row['image_url'],
                new \DateTime($row['created_at']), new \DateTime($row['updated_at']), (bool)$row['is_deleted'],
                $row['latitude'] ?? null, $row['longitude'] ?? null
            );
        }
    }
}