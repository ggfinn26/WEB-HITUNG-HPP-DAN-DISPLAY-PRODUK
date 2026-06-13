<?php

namespace App{

use App\Database;
use App\Order;
use App\OrderInterface;
use App\Helper\AppLogger;
use Exception;
use PDO;

    class OrderRepositoryImpl implements OrderInterface{
        private \PDO $connection;

        public function __construct(){
            $this->connection = Database::getConnection();
        }

        public function saveOrder(Order $order): Order{
            try{
                AppLogger::info('SAVE ORDER');
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("INSERT INTO `order`(order_number,
                list_item_order,
                sub_total,
                order_status,
                status_history,
                nama_pemesan,
                alamat_pemesan,
                whatsapp_pemesan,
                instagram_username_pemesan) VALUES(?,?,?,?,?,?,?,?,?)");
                $stmt->execute([
                    $order->getOrderNumber(),
                    $order->getListItemOrder(),
                    $order->getSubTotal(),
                    $order->getOrderStatus(),
                    $order->getStatusHistory(),
                    $order->getNamaPemesan(),
                    $order->getAlamatPemesan(),
                    $order->getWhatsappPemesan(),
                    $order->getInstagramUserNamePemesan()
                ]);
                $lastId = $this->connection->lastInsertId();
                $order->setId((int)$lastId);
                $this->connection->commit();
                return $order;
            } catch(Exception $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }
        
        public function updateOrder(Order $order): Order{
            try{
                AppLogger::info('UPDATE ORDER id=' . $order->getId());
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("UPDATE `order` SET order_number = ?,
                list_item_order = ?,
                sub_total = ?,
                order_status = ?,
                status_history = ?,
                nama_pemesan = ?,
                alamat_pemesan = ?,
                whatsapp_pemesan = ?,
                instagram_username_pemesan = ?,
                created_at = ?,
                updated_at = ? WHERE id = ?");
                $stmt->execute([
                    $order->getOrderNumber(),
                    $order->getListItemOrder(),
                    $order->getSubTotal(),
                    $order->getOrderStatus(),
                    $order->getStatusHistory(),
                    $order->getNamaPemesan(),
                    $order->getAlamatPemesan(),
                    $order->getWhatsappPemesan(),
                    $order->getInstagramUserNamePemesan(),
                    $order->getCreatedAt()->format('Y-m-d H:i:s'),
                    $order->getUpdatedAt()->format('Y-m-d H:i:s'),
                    $order->getId()
                ]);
                $this->connection->commit();
                return $order;
            } catch(Exception $e){
                if($this->connection->inTransaction()){
                    $this->connection->rollBack();
                }
                throw $e;
            }
        }

        public function findAll(): array{
            $stmt = $this->connection->prepare("SELECT * FROM `order`");
            $stmt->execute();
            $result = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = new Order(
                    (int)$row['id'],
                    $row['order_number'],
                    $row['list_item_order'],
                    $row['sub_total'],
                    $row['order_status'],
                    $row['status_history'] ?? '[]',
                    $row['nama_pemesan'],
                    $row['alamat_pemesan'],
                    $row['whatsapp_pemesan'],
                    $row['instagram_username_pemesan'],
                    new \DateTime($row['created_at']),
                    new \DateTime($row['updated_at'])
                );
            }
            return $result;
        }

        public function countAll(): int{
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM `order`");
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        }

        public function findPaginated(int $limit, int $offset): array{
            $stmt = $this->connection->prepare("SELECT * FROM `order` ORDER BY created_at DESC LIMIT ? OFFSET ?");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            $result = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = new Order(
                    (int)$row['id'], $row['order_number'], $row['list_item_order'],
                    $row['sub_total'], $row['order_status'], $row['status_history'] ?? '[]',
                    $row['nama_pemesan'], $row['alamat_pemesan'], $row['whatsapp_pemesan'],
                    $row['instagram_username_pemesan'],
                    new \DateTime($row['created_at']), new \DateTime($row['updated_at'])
                );
            }
            return $result;
        }

        public function orderNumberExists(string $orderNumber): bool {
            $stmt = $this->connection->prepare("SELECT 1 FROM `order` WHERE order_number = ? LIMIT 1");
            $stmt->execute([$orderNumber]);
            return (bool)$stmt->fetchColumn();
        }

        public function findByOrderNumber(string $orderNumber): ?Order{
            $stmt = $this->connection->prepare("SELECT * FROM `order` WHERE order_number = ?");
            $stmt->execute([$orderNumber]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                return new Order(
                    (int)$row['id'],
                    $row['order_number'],
                    $row['list_item_order'],
                    $row['sub_total'],
                    $row['order_status'],
                    $row['status_history'] ?? '[]',
                    $row['nama_pemesan'],
                    $row['alamat_pemesan'],
                    $row['whatsapp_pemesan'],
                    $row['instagram_username_pemesan'],
                    new \DateTime($row['created_at']),
                    new \DateTime($row['updated_at'])
                );
            }
            return null;
        }

        public function findByNamaPemesan(string $namaPemesan): array{
            $stmt = $this->connection->prepare("SELECT * FROM `order` WHERE nama_pemesan = ?");
            $stmt->execute([$namaPemesan]);
            $result = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = new Order(
                    (int)$row['id'],
                    $row['order_number'],
                    $row['list_item_order'],
                    $row['sub_total'],
                    $row['order_status'],
                    $row['status_history'] ?? '[]',
                    $row['nama_pemesan'],
                    $row['alamat_pemesan'],
                    $row['whatsapp_pemesan'],
                    $row['instagram_username_pemesan'],
                    new \DateTime($row['created_at']),
                    new \DateTime($row['updated_at'])
                );
            }
            return $result;
        }

        public function findByOrderStatus(string $orderStatus): array{
            $stmt = $this->connection->prepare("SELECT * FROM `order` WHERE order_status = ?");
            $stmt->execute([$orderStatus]);
            $result = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = new Order(
                    (int)$row['id'],
                    $row['order_number'],
                    $row['list_item_order'],
                    $row['sub_total'],
                    $row['order_status'],
                    $row['status_history'] ?? '[]',
                    $row['nama_pemesan'],
                    $row['alamat_pemesan'],
                    $row['whatsapp_pemesan'],
                    $row['instagram_username_pemesan'],
                    new \DateTime($row['created_at']),
                    new \DateTime($row['updated_at'])
                );
            }
            return $result;
        }

        public function getDistinctMonthYears(): array {
            $stmt = $this->connection->prepare(
                "SELECT DISTINCT YEAR(created_at) AS year, MONTH(created_at) AS month
                 FROM `order` ORDER BY year DESC, month DESC"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function findByMonthYear(int $month, int $year): array{
            $stmt = $this->connection->prepare(
                "SELECT * FROM `order` WHERE created_at >= ? AND created_at < ? ORDER BY created_at ASC"
            );
            $from = sprintf('%04d-%02d-01', $year, $month);
            $to   = date('Y-m-01', strtotime($from . ' +1 month'));
            $stmt->execute([$from, $to]);
            $result = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = new Order(
                    (int)$row['id'], $row['order_number'], $row['list_item_order'],
                    $row['sub_total'], $row['order_status'], $row['status_history'] ?? '[]',
                    $row['nama_pemesan'], $row['alamat_pemesan'], $row['whatsapp_pemesan'],
                    $row['instagram_username_pemesan'],
                    new \DateTime($row['created_at']), new \DateTime($row['updated_at'])
                );
            }
            return $result;
        }

        public function deleteById(int $id): bool{
            try{
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("DELETE FROM `order` WHERE id = ?");
                $ok = $stmt->execute([$id]);
                $this->connection->commit();
                return $ok;
            } catch(Exception $e){
                if($this->connection->inTransaction()) $this->connection->rollBack();
                throw $e;
            }
        }

        public function deleteByIds(array $ids): int{
            if(empty($ids)) return 0;
            try{
                $this->connection->beginTransaction();
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $stmt = $this->connection->prepare("DELETE FROM `order` WHERE id IN ($placeholders)");
                $stmt->execute(array_values($ids));
                $count = $stmt->rowCount();
                $this->connection->commit();
                return $count;
            } catch(Exception $e){
                if($this->connection->inTransaction()) $this->connection->rollBack();
                throw $e;
            }
        }

        public function findByInstagramUsername(string $instagramUsernamePemesan): array{
            $stmt = $this->connection->prepare("SELECT * FROM `order` WHERE instagram_username_pemesan = ?");
            $stmt->execute([$instagramUsernamePemesan]);
            $result = [];
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result[] = new Order(
                    (int)$row['id'],
                    $row['order_number'],
                    $row['list_item_order'],
                    $row['sub_total'],
                    $row['order_status'],
                    $row['status_history'] ?? '[]',
                    $row['nama_pemesan'],
                    $row['alamat_pemesan'],
                    $row['whatsapp_pemesan'],
                    $row['instagram_username_pemesan'],
                    new \DateTime($row['created_at']),
                    new \DateTime($row['updated_at'])
                );
            }
            return $result;
        }
    }
}