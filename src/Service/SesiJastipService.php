<?php
namespace App;

use App\ValidationException;

class SesiJastipService {
    private SesiJastipInterface $repo;

    public function __construct(SesiJastipInterface $repo) {
        $this->repo = $repo;
    }

    // ── Kalkulasi Distribusi Biaya Tetap (Proporsional + Rata) ───────────────

    /**
     * Hitung beban biaya tetap per item untuk setiap produk.
     *
     * Metode proporsional campuran:
     *   - persen_proporsional % dari biaya tetap didistribusi berdasarkan bobot nilai
     *     (harga_jual × qty). Produk lebih mahal menanggung lebih banyak.
     *   - (100 - persen_proporsional) % didistribusi rata per item.
     *
     * @param  array $produkList  [['harga_jual'=>float, 'hpp'=>float, 'margin'=>float, 'qty'=>int], ...]
     * @param  float $totalBiayaTetap
     * @param  int   $persenProporsional  0-100
     * @return array  Sama seperti input, ditambah key 'beban_per_item', 'true_cost', 'sugesti_harga', 'selisih', 'is_boncos'
     */
    public function hitungDistribusi(array $produkList, float $totalBiayaTetap, int $persenProporsional): array {
        $totalQty   = array_sum(array_column($produkList, 'qty'));
        $totalNilai = array_sum(array_map(fn($p) => $p['harga_jual'] * $p['qty'], $produkList));

        if ($totalQty <= 0 || $totalBiayaTetap <= 0) {
            return array_map(fn($p) => array_merge($p, [
                'beban_per_item' => 0, 'true_cost' => $p['hpp'],
                'sugesti_harga'  => $p['hpp'] + $p['margin'],
                'selisih'        => $p['harga_jual'] - ($p['hpp'] + $p['margin']),
                'is_boncos'      => false,
            ]), $produkList);
        }

        $bagianProp = $totalBiayaTetap * ($persenProporsional / 100);
        $bagianRata = $totalBiayaTetap * ((100 - $persenProporsional) / 100);
        $bebanRataPerItem = $totalQty > 0 ? $bagianRata / $totalQty : 0;

        $result = [];
        foreach ($produkList as $p) {
            $weight      = $totalNilai > 0
                ? ($p['harga_jual'] * $p['qty']) / $totalNilai
                : (1 / count($produkList));
            $bebanProp   = $p['qty'] > 0
                ? ($bagianProp * $weight) / $p['qty']
                : 0;
            $bebanTotal  = $bebanProp + $bebanRataPerItem;
            $trueCost    = $p['hpp'] + $bebanTotal;
            $sugesti     = $trueCost + $p['margin'];
            $selisih     = $p['harga_jual'] - $sugesti;

            $result[] = array_merge($p, [
                'beban_per_item' => $bebanTotal,
                'true_cost'      => $trueCost,
                'sugesti_harga'  => $sugesti,
                'selisih'        => $selisih,
                'is_boncos'      => $selisih < 0,
            ]);
        }
        return $result;
    }

    /**
     * Hitung BEP total sesi berdasarkan daftar produk dengan estimasi qty.
     * BEP = biaya_tetap / margin_rata_per_item_tertimbang
     */
    public function hitungBEPMultiProduk(array $produkList, float $totalBiayaTetap): ?float {
        $totalQty    = array_sum(array_column($produkList, 'qty'));
        $totalMargin = array_sum(array_map(fn($p) => $p['margin'] * $p['qty'], $produkList));
        if ($totalMargin <= 0 || $totalQty <= 0) return null;
        $marginRata = $totalMargin / $totalQty;
        return $marginRata > 0 ? $totalBiayaTetap / $marginRata : null;
    }

    /**
     * Hitung laba aktual pasca-trip menggunakan data aktual_qty.
     * Distribusi dihitung ulang berdasarkan qty aktual.
     */
    public function hitungLabaAktual(array $sesiProdukList, float $totalBiayaTetap, int $persenProporsional): array {
        $input = array_map(fn(SesiProduk $p) => [
            'id'         => $p->getId(),
            'nama'       => $p->getNamaSnapshot(),
            'harga_jual' => $p->getHargaJualSnapshot(),
            'hpp'        => $p->getHppPerPcsSnapshot(),
            'margin'     => $p->getMarginSnapshot(),
            'qty'        => $p->getAktualQty() ?? 0,
        ], $sesiProdukList);

        $kalkulasi   = $this->hitungDistribusi($input, $totalBiayaTetap, $persenProporsional);
        $totalLabaSesi = 0;
        $rows = [];
        foreach ($kalkulasi as $i => $k) {
            $qty        = $k['qty'];
            $labaPerItem = $k['harga_jual'] - $k['true_cost'];
            $labaTotal   = $labaPerItem * $qty;
            $totalLabaSesi += $labaTotal;
            $rows[] = array_merge($k, [
                'laba_per_item' => $labaPerItem,
                'laba_total'    => $labaTotal,
            ]);
        }
        return ['rows' => $rows, 'total_laba' => $totalLabaSesi];
    }

    // ── CRUD ──────────────────────────────────────────────────────────────────

    public function simpanSesi(array $data, array $produkRows): SesiJastip {
        $nama = trim($data['nama_sesi'] ?? '');
        if ($nama === '') throw new ValidationException("Nama sesi tidak boleh kosong.");

        try { $tanggal = new \DateTime($data['tanggal'] ?? ''); }
        catch (\Exception $e) { throw new ValidationException("Format tanggal tidak valid."); }

        $persen = max(0, min(100, (int)($data['persen_proporsional'] ?? 100)));

        if (empty($produkRows)) {
            throw new ValidationException("Pilih minimal satu produk untuk sesi ini.");
        }
        foreach ($produkRows as $r) {
            if ((int)($r['estimasi_qty'] ?? 0) < 1) {
                throw new ValidationException("Estimasi qty setiap produk harus minimal 1.");
            }
        }

        $komponen        = $this->parseKomponen($data['komponen'] ?? '[]');
        $totalBiayaTetap = array_sum(array_column($komponen, 'jumlah'));

        $sesi = new SesiJastip(
            0, $nama, $tanggal, 0, 0, $totalBiayaTetap,
            null, 'draft',
            trim($data['catatan'] ?? '') ?: null,
            'proporsional', $persen,
            new \DateTime(), new \DateTime()
        );

        $saved = $this->repo->save($sesi);

        foreach ($komponen as $k) {
            $this->repo->saveKomponen(new BiayaKomponenSesi(
                0, $saved->getId(), $k['nama'], $k['jumlah'], new \DateTime()
            ));
        }

        foreach ($produkRows as $r) {
            $this->repo->saveProduk(new SesiProduk(
                0, $saved->getId(), (int)$r['hpp_id'],
                $r['nama'], (float)$r['harga_jual'],
                (float)$r['hpp_per_pcs'], (float)$r['margin'],
                (int)$r['estimasi_qty'], null, new \DateTime()
            ));
        }

        return $saved;
    }

    public function tutupSesi(int $id, array $aktualQtys, PengeluaranRepositoryImpl $pengeluaranRepo): SesiJastip {
        $sesi = $this->repo->findById($id);
        if (!$sesi) throw new ValidationException("Sesi tidak ditemukan.");

        $produkList = $this->repo->findProdukBySesiId($id);
        foreach ($produkList as $p) {
            $aktual = isset($aktualQtys[$p->getId()])
                ? max(0, (int)$aktualQtys[$p->getId()])
                : 0;
            $this->repo->updateProdukAktualQty($p->getId(), $aktual);
        }

        // Catat biaya tetap ke pengeluaran
        $komponen = $this->repo->findKomponenBySesiId($id);
        foreach ($komponen as $k) {
            $pengeluaranRepo->save(new Pengeluaran(
                0, $sesi->getTanggal(),
                'Biaya Sesi Trip "' . $sesi->getNamaSesi() . '": ' . $k->getNamaKomponen(),
                (string)$k->getJumlah(),
                new \DateTime(), new \DateTime()
            ));
        }

        $sesi->setStatus('selesai');
        return $this->repo->update($sesi);
    }

    public function findById(int $id): ?SesiJastip      { return $this->repo->findById($id); }
    public function findAll(): array                     { return $this->repo->findAll(); }
    public function findKomponenBySesiId(int $id): array { return $this->repo->findKomponenBySesiId($id); }
    public function findProdukBySesiId(int $id): array   { return $this->repo->findProdukBySesiId($id); }

    public function hapusSesi(int $id): void {
        if (!$this->repo->findById($id)) throw new ValidationException("Sesi tidak ditemukan.");
        $this->repo->delete($id);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function parseKomponen(string $json): array {
        $raw = json_decode($json, true);
        if (!is_array($raw)) return [];
        $result = [];
        foreach ($raw as $item) {
            $nama   = trim($item['nama'] ?? '');
            $jumlah = (float)($item['jumlah'] ?? 0);
            if ($nama !== '' && $jumlah > 0) {
                $result[] = ['nama' => $nama, 'jumlah' => $jumlah];
            }
        }
        return $result;
    }
}
