<?php

namespace App{

    use App\Database;
    use App\RincianHpp;
    use App\RincianHppInterface;
    use App\Helper\AppLogger;
    use Exception;

    require_once __DIR__ . '/RincianHppInterface.php';
    require_once __DIR__ . '/../Config/Database.php';

    class RincianHppRepositoryImpl implements RincianHppInterface{
        private \PDO $connection;

        public function __construct(){
            $this->connection = Database::getConnection();
        }

        public function create(RincianHpp $rincianHpp): RincianHpp{
            try{
            AppLogger::info('SAVE RINCIAN HPP - ' . $rincianHpp->getName());
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("INSERT INTO rincian_hpp(product_id, name, margin_keuntungan,
                product_item_list, jumlah_produksi, total_biaya_hpp, hpp_per_pcs, harga_jual_produk
                ) VALUES(?,?,?,?,?,?,?,?)");
                $stmt->execute([
                    $rincianHpp->getProductId(),
                    $rincianHpp->getName(),
                    $rincianHpp->getMarginKeuntungan(),
                    $rincianHpp->getProductItemList(),
                    $rincianHpp->getJumlahProduksi(),
                    $rincianHpp->getTotalBiayaHpp(),
                    $rincianHpp->getHppPerPcs(),
                    $rincianHpp->getHargaJualProduk()
                ]);
                $lastId = $this->connection->lastInsertId();
                $rincianHpp->setId((int)$lastId);
                $this->connection->commit();
                return $rincianHpp;
            } catch(Exception $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }
        
        public function update(RincianHpp $rincianHpp): RincianHpp{
            try{
            AppLogger::info('UPDATE RINCIAN HPP - ' . $rincianHpp->getName());
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("UPDATE rincian_hpp SET
                product_id = ?,
                name = ?,
                margin_keuntungan = ?,
                product_item_list = ?,
                jumlah_produksi = ?,
                total_biaya_hpp = ?,
                hpp_per_pcs = ?,
                harga_jual_produk = ? WHERE id = ?");
                $stmt->execute([
                    $rincianHpp->getProductId(),
                    $rincianHpp->getName(),
                    $rincianHpp->getMarginKeuntungan(),
                    $rincianHpp->getProductItemList(),
                    $rincianHpp->getJumlahProduksi(),
                    $rincianHpp->getTotalBiayaHpp(),
                    $rincianHpp->getHppPerPcs(),
                    $rincianHpp->getHargaJualProduk(),
                    $rincianHpp->getId()
                ]);
                $this->connection->commit();
                return $rincianHpp;
            } catch(Exception $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function updateHargaJualProduk(int $id, string $hargaJualProduk): RincianHpp{
            try{
            AppLogger::info('UPDATE HARGA JUAL id=' . $id . ' harga=' . $hargaJualProduk);
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("UPDATE rincian_hpp SET harga_jual_produk = ? WHERE id = ?");
                $stmt->execute([$hargaJualProduk, $id]);
                $this->connection->commit();
                return $this->findById($id);
            } catch(Exception $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function updateMarginKeuntungan(int $id, int $marginKeuntungan): RincianHpp{
            try{
            AppLogger::info('UPDATE MARGIN KEUNTUNGAN id=' . $id . ' margin=' . $marginKeuntungan);
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("UPDATE rincian_hpp SET margin_keuntungan = ? WHERE id = ?");
                $stmt->execute([$marginKeuntungan, $id]);
                $this->connection->commit();
                return $this->findById($id);
            } catch(Exception $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function updateJumlahProduksi(int $id, int $jumlahProduksi): RincianHpp{
            try{
            AppLogger::info('UPDATE JUMLAH PRODUKSI id=' . $id . ' jumlah=' . $jumlahProduksi);
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("UPDATE rincian_hpp SET jumlah_produksi = ? WHERE id = ?");
                $stmt->execute([$jumlahProduksi, $id]);
                $this->connection->commit();
                return $this->findById($id);
            } catch(Exception $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function updateItemProduksi(int $id, string $productItemList): RincianHpp{
            try{
            AppLogger::info('UPDATE ITEM PRODUKSI id=' . $id);
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("UPDATE rincian_hpp SET product_item_list = ? WHERE id = ?");
                $stmt->execute([$productItemList, $id]);
                $this->connection->commit();
                return $this->findById($id);
            } catch(Exception $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function delete(int $id): bool{
            try{
            AppLogger::info('DELETE RINCIAN HPP id=' . $id);
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("UPDATE rincian_hpp SET is_deleted = 1 WHERE id = ?");
                $stmt->execute([$id]);
                $this->connection->commit();
                return true;
            } catch(Exception $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function findById(int $id): ?RincianHpp{
            $stmt = $this->connection->prepare("SELECT * FROM rincian_hpp WHERE id = ? AND is_deleted = 0");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            if($result){
                return new RincianHpp(
                    (int)$result['id'],
                    (int)$result['product_id'],
                    $result['name'],
                    (int)$result['margin_keuntungan'],
                    $result['product_item_list'],
                    (int)$result['jumlah_produksi'],
                    $result['total_biaya_hpp'],
                    $result['hpp_per_pcs'],
                    $result['harga_jual_produk'],
                    new \DateTime($result['created_at']),
                    new \DateTime($result['updated_at']),
                    (bool)$result['is_deleted']
                );
            }
            return null;
        }

        public function findByName(string $name): ?RincianHpp{
            $stmt = $this->connection->prepare("SELECT * FROM rincian_hpp WHERE name = ? AND is_deleted = 0");
            $stmt->execute([$name]);
            $result = $stmt->fetch();
            if($result){
                return new RincianHpp(
                    (int)$result['id'],
                    (int)$result['product_id'],
                    $result['name'],
                    (int)$result['margin_keuntungan'],
                    $result['product_item_list'],
                    (int)$result['jumlah_produksi'],
                    $result['total_biaya_hpp'],
                    $result['hpp_per_pcs'],
                    $result['harga_jual_produk'],
                    new \DateTime($result['created_at']),
                    new \DateTime($result['updated_at']),
                    (bool)$result['is_deleted']
                );
            }
            return null;
        }

        public function findAll(): array{
            $stmt = $this->connection->prepare("SELECT * FROM rincian_hpp WHERE is_deleted = 0");
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $list = [];
            foreach($result as $row){
                $list[] = new RincianHpp(
                    (int)$row['id'],
                    (int)$row['product_id'],
                    $row['name'],
                    (int)$row['margin_keuntungan'],
                    $row['product_item_list'],
                    (int)$row['jumlah_produksi'],
                    $row['total_biaya_hpp'],
                    $row['hpp_per_pcs'],
                    $row['harga_jual_produk'],
                    new \DateTime($row['created_at']),
                    new \DateTime($row['updated_at']),
                    (bool)$row['is_deleted']
                );
            }
            return $list;
        }

        public function count(): int{
            $stmt = $this->connection->prepare("SELECT COUNT(*) as total FROM rincian_hpp WHERE is_deleted = 0");
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (int)$result['total'];
        }

        public function search(string $query): array{
            $stmt = $this->connection->prepare("SELECT * FROM rincian_hpp WHERE is_deleted = 0 AND (name LIKE ? OR hpp_per_pcs LIKE ?)");
            $stmt->execute(["%$query%", "%$query%"]);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $list = [];
            foreach($result as $row){
                $list[] = new RincianHpp(
                    (int)$row['id'],
                    (int)$row['product_id'],
                    $row['name'],
                    (int)$row['margin_keuntungan'],
                    $row['product_item_list'],
                    (int)$row['jumlah_produksi'],
                    $row['total_biaya_hpp'],
                    $row['hpp_per_pcs'],
                    $row['harga_jual_produk'],
                    new \DateTime($row['created_at']),
                    new \DateTime($row['updated_at']),
                    (bool)$row['is_deleted']
                );
            }
            return $list;
        }
        /**
         * Kembalikan semua HPP beserta catalog price dari tabel products.
         * Digunakan untuk form pemilihan produk di sesi trip.
         * @return array [['hpp_id', 'nama', 'hpp_per_pcs', 'margin', 'harga_jual_hpp', 'catalog_price'], ...]
         */
        public function findAllForSesi(): array {
            $stmt = $this->connection->prepare(
                "SELECT rh.id AS hpp_id, rh.name AS nama,
                        rh.hpp_per_pcs, rh.margin_keuntungan AS margin,
                        rh.harga_jual_produk AS harga_jual_hpp,
                        COALESCE(p.price, rh.harga_jual_produk) AS catalog_price
                 FROM rincian_hpp rh
                 LEFT JOIN products p ON p.id = rh.product_id AND p.is_deleted = 0
                 WHERE rh.is_deleted = 0
                 ORDER BY rh.name ASC"
            );
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
}
}
