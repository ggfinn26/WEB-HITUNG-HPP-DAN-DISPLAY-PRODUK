<?php
namespace App{
    require_once __DIR__ . '/PengeluaranInterface.php';
    require_once __DIR__ . '/../Config/Database.php';

    use App\Database;
    use App\Pengeluaran;
    use App\PengeluaranInterface;

    class PengeluaranRepositoryImpl implements PengeluaranInterface {
        private \PDO $connection;

        public function __construct() {
            $this->connection = Database::getConnection();
        }

        private function hydrate(array $row): Pengeluaran {
            return new Pengeluaran(
                (int)$row['id'],
                new \DateTime($row['tanggal']),
                $row['keterangan'],
                $row['jumlah'],
                new \DateTime($row['created_at']),
                new \DateTime($row['updated_at'])
            );
        }

        public function save(Pengeluaran $p): Pengeluaran {
            try {
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare(
                    "INSERT INTO pengeluaran (tanggal, keterangan, jumlah) VALUES (?, ?, ?)"
                );
                $stmt->execute([
                    $p->getTanggal()->format('Y-m-d'),
                    $p->getKeterangan(),
                    $p->getJumlah(),
                ]);
                $p->setId((int)$this->connection->lastInsertId());
                $this->connection->commit();
                return $p;
            } catch (\Exception $e) {
                if ($this->connection->inTransaction()) $this->connection->rollBack();
                throw $e;
            }
        }

        public function update(Pengeluaran $p): Pengeluaran {
            try {
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare(
                    "UPDATE pengeluaran SET tanggal = ?, keterangan = ?, jumlah = ? WHERE id = ?"
                );
                $stmt->execute([
                    $p->getTanggal()->format('Y-m-d'),
                    $p->getKeterangan(),
                    $p->getJumlah(),
                    $p->getId(),
                ]);
                $this->connection->commit();
                return $p;
            } catch (\Exception $e) {
                if ($this->connection->inTransaction()) $this->connection->rollBack();
                throw $e;
            }
        }

        public function delete(int $id): bool {
            try {
                $this->connection->beginTransaction();
                $stmt = $this->connection->prepare("DELETE FROM pengeluaran WHERE id = ?");
                $ok = $stmt->execute([$id]);
                $this->connection->commit();
                return $ok;
            } catch (\Exception $e) {
                if ($this->connection->inTransaction()) $this->connection->rollBack();
                throw $e;
            }
        }

        public function findById(int $id): ?Pengeluaran {
            $stmt = $this->connection->prepare("SELECT * FROM pengeluaran WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ? $this->hydrate($row) : null;
        }

        public function findAll(): array {
            $stmt = $this->connection->prepare("SELECT * FROM pengeluaran ORDER BY tanggal DESC");
            $stmt->execute();
            return array_map([$this, 'hydrate'], $stmt->fetchAll(\PDO::FETCH_ASSOC));
        }

        public function getDistinctMonthYears(): array {
            $stmt = $this->connection->prepare(
                "SELECT DISTINCT YEAR(tanggal) AS year, MONTH(tanggal) AS month
                 FROM pengeluaran ORDER BY year DESC, month DESC"
            );
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function findByMonthYear(int $month, int $year): array {
            $stmt = $this->connection->prepare(
                "SELECT * FROM pengeluaran WHERE tanggal >= ? AND tanggal < ? ORDER BY tanggal ASC"
            );
            $from = sprintf('%04d-%02d-01', $year, $month);
            $to   = date('Y-m-01', strtotime($from . ' +1 month'));
            $stmt->execute([$from, $to]);
            return array_map([$this, 'hydrate'], $stmt->fetchAll(\PDO::FETCH_ASSOC));
        }
    }
}
