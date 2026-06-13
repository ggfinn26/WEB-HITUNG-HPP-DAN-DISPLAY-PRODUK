<?php
namespace App;

class SesiJastip {
    private int     $id;
    private string  $namaSesi;
    private \DateTime $tanggal;
    private float   $rataHargaJual;
    private float   $rataHppDasar;
    private float   $totalBiayaTetap;
    private ?int    $jumlahOrderAktual;
    private string  $status;
    private ?string $catatan;
    private string  $metodeDistribusi;
    private int     $persenProporsional;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(
        int $id, string $namaSesi, \DateTime $tanggal,
        float $rataHargaJual, float $rataHppDasar, float $totalBiayaTetap,
        ?int $jumlahOrderAktual, string $status, ?string $catatan,
        string $metodeDistribusi, int $persenProporsional,
        \DateTime $createdAt, \DateTime $updatedAt
    ) {
        $this->id                 = $id;
        $this->namaSesi           = $namaSesi;
        $this->tanggal            = $tanggal;
        $this->rataHargaJual      = $rataHargaJual;
        $this->rataHppDasar       = $rataHppDasar;
        $this->totalBiayaTetap    = $totalBiayaTetap;
        $this->jumlahOrderAktual  = $jumlahOrderAktual;
        $this->status             = $status;
        $this->catatan            = $catatan;
        $this->metodeDistribusi   = $metodeDistribusi;
        $this->persenProporsional = $persenProporsional;
        $this->createdAt          = $createdAt;
        $this->updatedAt          = $updatedAt;
    }

    public function getId(): int               { return $this->id; }
    public function setId(int $id): void       { $this->id = $id; }

    public function getNamaSesi(): string                { return $this->namaSesi; }
    public function setNamaSesi(string $v): void         { $this->namaSesi = $v; }

    public function getTanggal(): \DateTime              { return $this->tanggal; }
    public function setTanggal(\DateTime $v): void       { $this->tanggal = $v; }

    public function getRataHargaJual(): float            { return $this->rataHargaJual; }
    public function setRataHargaJual(float $v): void     { $this->rataHargaJual = $v; }

    public function getRataHppDasar(): float             { return $this->rataHppDasar; }
    public function setRataHppDasar(float $v): void      { $this->rataHppDasar = $v; }

    public function getTotalBiayaTetap(): float          { return $this->totalBiayaTetap; }
    public function setTotalBiayaTetap(float $v): void   { $this->totalBiayaTetap = $v; }

    public function getJumlahOrderAktual(): ?int         { return $this->jumlahOrderAktual; }
    public function setJumlahOrderAktual(?int $v): void  { $this->jumlahOrderAktual = $v; }

    public function getStatus(): string                  { return $this->status; }
    public function setStatus(string $v): void           { $this->status = $v; }

    public function getCatatan(): ?string                { return $this->catatan; }
    public function setCatatan(?string $v): void         { $this->catatan = $v; }

    public function getMetodeDistribusi(): string           { return $this->metodeDistribusi; }
    public function setMetodeDistribusi(string $v): void    { $this->metodeDistribusi = $v; }
    public function getPersenProporsional(): int            { return $this->persenProporsional; }
    public function setPersenProporsional(int $v): void     { $this->persenProporsional = $v; }

    public function getCreatedAt(): \DateTime            { return $this->createdAt; }
    public function getUpdatedAt(): \DateTime            { return $this->updatedAt; }
    public function setUpdatedAt(\DateTime $v): void     { $this->updatedAt = $v; }

    // Untuk backward compat dengan sesi lama (rata-rata based)
    public function getMarginKotor(): float {
        return $this->rataHargaJual - $this->rataHppDasar;
    }
    public function getBEP(): ?float {
        $mg = $this->getMarginKotor();
        return $mg > 0 ? $this->totalBiayaTetap / $mg : null;
    }
}
