<?php

namespace App{
    require_once __DIR__."/ProductInterface.php";
    require_once __DIR__."/../Config/Database.php";
    use App\ProductInterface;
    use App\Database;
    use App\Helper\AppLogger;

    class ProductRepositoryImpl implements ProductInterface{
        private \PDO $connection;

        public function __construct(){
            $this->connection = Database::getConnection();
        }

        public function save(\App\Product $product): \App\Product {
            try {
                AppLogger::info("Menyimpan produk baru bernama '" . $product->getName() . "'");
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
                AppLogger::info("Mengupdate produk ID " . $product->getId());
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
                AppLogger::info("Menghapus (soft delete) produk ID " . $id);
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
            AppLogger::info("Mencari produk dengan nama " . $name);
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
            AppLogger::info("Mencari produk dengan ID " . $id);
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

        public function countAll(): int {
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM products WHERE is_deleted = 0");
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        }

        public function findPaginated(int $page, int $perPage): array {
            $offset = ($page - 1) * $perPage;
            $stmt = $this->connection->prepare("SELECT * FROM products WHERE is_deleted = 0 ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
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

        /**
         * Sort all products by price.
         * @param string $direction 'ASC' or 'DESC'
         */
        public function findAllSortedByPrice(string $direction = 'ASC'): array {
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

        public function findAllSortedByPriceAsc(): array {
            return $this->findAllSortedByPrice('ASC');
        }

        public function findAllSortedByPriceDesc(): array {
            return $this->findAllSortedByPrice('DESC');
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