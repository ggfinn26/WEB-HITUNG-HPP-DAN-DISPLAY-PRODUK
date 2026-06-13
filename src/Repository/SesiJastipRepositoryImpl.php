<?php
namespace App;

use PDO;

class SesiJastipRepositoryImpl implements SesiJastipInterface {
    private \PDO $connection;

    public function __construct() {
        $this->connection = Database::getConnection();
    }

    // ── SesiJastip CRUD ───────────────────────────────────────────────────────

    public function save(SesiJastip $sesi): SesiJastip {
        $stmt = $this->connection->prepare(
            "INSERT INTO sesi_jastip
                (nama_sesi, tanggal, rata_harga_jual, rata_hpp_dasar,
                 total_biaya_tetap, jumlah_order_aktual, status, catatan,
                 metode_distribusi, persen_proporsional)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $sesi->getNamaSesi(),
            $sesi->getTanggal()->format('Y-m-d'),
            $sesi->getRataHargaJual(),
            $sesi->getRataHppDasar(),
            $sesi->getTotalBiayaTetap(),
            $sesi->getJumlahOrderAktual(),
            $sesi->getStatus(),
            $sesi->getCatatan(),
            $sesi->getMetodeDistribusi(),
            $sesi->getPersenProporsional(),
        ]);
        $sesi->setId((int)$this->connection->lastInsertId());
        return $sesi;
    }

    public function update(SesiJastip $sesi): SesiJastip {
        $stmt = $this->connection->prepare(
            "UPDATE sesi_jastip SET
                nama_sesi = ?, tanggal = ?, rata_harga_jual = ?,
                rata_hpp_dasar = ?, total_biaya_tetap = ?,
                jumlah_order_aktual = ?, status = ?, catatan = ?,
                metode_distribusi = ?, persen_proporsional = ?,
                updated_at = NOW()
             WHERE id = ?"
        );
        $stmt->execute([
            $sesi->getNamaSesi(),
            $sesi->getTanggal()->format('Y-m-d'),
            $sesi->getRataHargaJual(),
            $sesi->getRataHppDasar(),
            $sesi->getTotalBiayaTetap(),
            $sesi->getJumlahOrderAktual(),
            $sesi->getStatus(),
            $sesi->getCatatan(),
            $sesi->getMetodeDistribusi(),
            $sesi->getPersenProporsional(),
            $sesi->getId(),
        ]);
        return $sesi;
    }

    public function findById(int $id): ?SesiJastip {
        $stmt = $this->connection->prepare("SELECT * FROM sesi_jastip WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function findAll(): array {
        $stmt = $this->connection->prepare("SELECT * FROM sesi_jastip ORDER BY tanggal DESC, id DESC");
        $stmt->execute();
        return array_map(fn($r) => $this->hydrate($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function delete(int $id): bool {
        $stmt = $this->connection->prepare("DELETE FROM sesi_jastip WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ── BiayaKomponenSesi ─────────────────────────────────────────────────────

    public function saveKomponen(BiayaKomponenSesi $k): BiayaKomponenSesi {
        $stmt = $this->connection->prepare(
            "INSERT INTO biaya_komponen_sesi (sesi_id, nama_komponen, jumlah) VALUES (?, ?, ?)"
        );
        $stmt->execute([$k->getSesiId(), $k->getNamaKomponen(), $k->getJumlah()]);
        return new BiayaKomponenSesi(
            (int)$this->connection->lastInsertId(),
            $k->getSesiId(), $k->getNamaKomponen(), $k->getJumlah(), new \DateTime()
        );
    }

    public function findKomponenBySesiId(int $sesiId): array {
        $stmt = $this->connection->prepare(
            "SELECT * FROM biaya_komponen_sesi WHERE sesi_id = ? ORDER BY id ASC"
        );
        $stmt->execute([$sesiId]);
        return array_map(fn($r) => new BiayaKomponenSesi(
            (int)$r['id'], (int)$r['sesi_id'], $r['nama_komponen'],
            (float)$r['jumlah'], new \DateTime($r['created_at'])
        ), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function deleteKomponenBySesiId(int $sesiId): void {
        $stmt = $this->connection->prepare("DELETE FROM biaya_komponen_sesi WHERE sesi_id = ?");
        $stmt->execute([$sesiId]);
    }

    // ── SesiProduk ────────────────────────────────────────────────────────────

    public function saveProduk(SesiProduk $p): SesiProduk {
        $stmt = $this->connection->prepare(
            "INSERT INTO sesi_produk
                (sesi_id, hpp_id, nama_snapshot, harga_jual_snapshot,
                 hpp_per_pcs_snapshot, margin_snapshot, estimasi_qty, aktual_qty)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $p->getSesiId(), $p->getHppId(), $p->getNamaSnapshot(),
            $p->getHargaJualSnapshot(), $p->getHppPerPcsSnapshot(),
            $p->getMarginSnapshot(), $p->getEstimasiQty(), $p->getAktualQty(),
        ]);
        return new SesiProduk(
            (int)$this->connection->lastInsertId(),
            $p->getSesiId(), $p->getHppId(), $p->getNamaSnapshot(),
            $p->getHargaJualSnapshot(), $p->getHppPerPcsSnapshot(),
            $p->getMarginSnapshot(), $p->getEstimasiQty(), $p->getAktualQty(),
            new \DateTime()
        );
    }

    public function findProdukBySesiId(int $sesiId): array {
        $stmt = $this->connection->prepare(
            "SELECT * FROM sesi_produk WHERE sesi_id = ? ORDER BY id ASC"
        );
        $stmt->execute([$sesiId]);
        return array_map(fn($r) => new SesiProduk(
            (int)$r['id'], (int)$r['sesi_id'], (int)$r['hpp_id'],
            $r['nama_snapshot'], (float)$r['harga_jual_snapshot'],
            (float)$r['hpp_per_pcs_snapshot'], (float)$r['margin_snapshot'],
            (int)$r['estimasi_qty'],
            isset($r['aktual_qty']) ? (int)$r['aktual_qty'] : null,
            new \DateTime($r['created_at'])
        ), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function updateProdukAktualQty(int $sesiProdukId, int $aktualQty): void {
        $stmt = $this->connection->prepare(
            "UPDATE sesi_produk SET aktual_qty = ? WHERE id = ?"
        );
        $stmt->execute([$aktualQty, $sesiProdukId]);
    }

    public function deleteProdukBySesiId(int $sesiId): void {
        $stmt = $this->connection->prepare("DELETE FROM sesi_produk WHERE sesi_id = ?");
        $stmt->execute([$sesiId]);
    }

    // ── Hydrator ──────────────────────────────────────────────────────────────

    private function hydrate(array $row): SesiJastip {
        return new SesiJastip(
            (int)$row['id'],
            $row['nama_sesi'],
            new \DateTime($row['tanggal']),
            (float)$row['rata_harga_jual'],
            (float)$row['rata_hpp_dasar'],
            (float)$row['total_biaya_tetap'],
            isset($row['jumlah_order_aktual']) ? (int)$row['jumlah_order_aktual'] : null,
            $row['status'],
            $row['catatan'] ?? null,
            $row['metode_distribusi'] ?? 'proporsional',
            (int)($row['persen_proporsional'] ?? 100),
            new \DateTime($row['created_at']),
            new \DateTime($row['updated_at'])
        );
    }
}
