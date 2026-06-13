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
     * @param  ?SesiBobot $bobot Konfigurasi bobot dinamis
     * @return array  Sama seperti input, ditambah key 'beban_per_item', 'true_cost', 'sugesti_harga', 'selisih', 'is_boncos'
     */
    public function hitungDistribusi(array $produkList, float $totalBiayaTetap, ?SesiBobot $bobot = null): array {
        $totalQty   = array_sum(array_column($produkList, 'qty'));
        $totalNilai = array_sum(array_map(fn($p) => $p['harga_jual'] * $p['qty'], $produkList));

        // Default slider percentages if bobot is null or doesn't have it
        $persenProporsional = 60; 
        $itemPropConfig = $bobot ? $bobot->getItemProporsional() : null;
        $itemFlatConfig = $bobot ? $bobot->getItemFlat() : null;

        if ($itemPropConfig && isset($itemPropConfig['global_persen'])) {
            $persenProporsional = (int)$itemPropConfig['global_persen'];
        }

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
            $weight = $totalNilai > 0 ? ($p['harga_jual'] * $p['qty']) / $totalNilai : (1 / count($produkList));
            if ($itemPropConfig && isset($itemPropConfig['custom_weights'][$p['id'] ?? $p['hpp_id']])) {
                $weight = (float)$itemPropConfig['custom_weights'][$p['id'] ?? $p['hpp_id']];
            }
            
            $bebanProp   = $p['qty'] > 0 ? ($bagianProp * $weight) / $p['qty'] : 0;
            
            // Custom flat
            $bebanRataItem = $bebanRataPerItem;
            if ($itemFlatConfig && isset($itemFlatConfig['custom_weights'][$p['id'] ?? $p['hpp_id']])) {
                $bebanRataItem = (float)$itemFlatConfig['custom_weights'][$p['id'] ?? $p['hpp_id']];
            }
            
            $bebanTotal  = $bebanProp + $bebanRataItem;
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
    public function hitungLabaAktual(array $sesiProdukList, float $totalBiayaTetap): array {
        $input = array_map(fn(SesiProduk $p) => [
            'id'         => $p->getId(),
            'nama'       => $p->getNamaSnapshot(),
            'harga_jual' => $p->getHargaJualSnapshot(),
            'hpp'        => $p->getHppPerPcsSnapshot(),
            'margin'     => $p->getMarginSnapshot(),
            'qty'        => $p->getAktualQty() ?? 0,
        ], $sesiProdukList);

        $bobot = null;
        if (!empty($sesiProdukList)) {
            $bobot = $this->repo->findSesiBobotBySesiId($sesiProdukList[0]->getSesiId());
        }
        $kalkulasi   = $this->hitungDistribusi($input, $totalBiayaTetap, $bobot);
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

        $komponen        = \App\Helper\SesiHelper::parseKomponen($data['komponen'] ?? '[]');
        $totalBiayaTetap = array_sum(array_column($komponen, 'jumlah'));

        $sesi = new SesiJastip(
            0, $nama, $tanggal, 0, 0, $totalBiayaTetap,
            null, 'draft',
            trim($data['catatan'] ?? '') ?: null,
            new \DateTime(), new \DateTime()
        );

        $saved = $this->repo->save($sesi);

        // Parsing bobot proporsional and flat from inputs
        $itemProp = [
            'global_persen' => $persen,
            'custom_weights' => []
        ];
        $itemFlat = [
            'custom_weights' => []
        ];

        // We assume data contains custom_prop and custom_flat arrays
        if (isset($data['custom_prop']) && is_array($data['custom_prop'])) {
            foreach ($data['custom_prop'] as $hppId => $weight) {
                if ($weight !== '') $itemProp['custom_weights'][$hppId] = (float)$weight;
            }
        }
        if (isset($data['custom_flat']) && is_array($data['custom_flat'])) {
            foreach ($data['custom_flat'] as $hppId => $weight) {
                if ($weight !== '') $itemFlat['custom_weights'][$hppId] = (float)$weight;
            }
        }

        $this->repo->saveSesiBobot(new SesiBobot(0, $saved->getId(), $itemProp, $itemFlat, new \DateTime(), new \DateTime()));

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

    public function tutupSesi(int $id, array $aktualQtys, PengeluaranInterface $pengeluaranRepo): SesiJastip {
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
    public function findProdukBySesiId(int $id): array        { return $this->repo->findProdukBySesiId($id); }
    public function findAllProdukBySesiIds(array $ids): array { return $this->repo->findAllProdukBySesiIds($ids); }
    public function findSesiBobotBySesiId(int $id): ?SesiBobot { return $this->repo->findSesiBobotBySesiId($id); }

    public function hapusSesi(int $id): void {
        if (!$this->repo->findById($id)) throw new ValidationException("Sesi tidak ditemukan.");
        $this->repo->delete($id);
    }

}
