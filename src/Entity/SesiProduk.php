<?php
namespace App;

class SesiProduk {
    private int    $id;
    private int    $sesiId;
    private int    $hppId;
    private string $namaSnapshot;
    private float  $hargaJualSnapshot;
    private float  $hppPerPcsSnapshot;
    private float  $marginSnapshot;
    private int    $estimasiQty;
    private ?int   $aktualQty;
    private \DateTime $createdAt;

    public function __construct(
        int $id, int $sesiId, int $hppId,
        string $namaSnapshot, float $hargaJualSnapshot,
        float $hppPerPcsSnapshot, float $marginSnapshot,
        int $estimasiQty, ?int $aktualQty,
        \DateTime $createdAt
    ) {
        $this->id                = $id;
        $this->sesiId            = $sesiId;
        $this->hppId             = $hppId;
        $this->namaSnapshot      = $namaSnapshot;
        $this->hargaJualSnapshot = $hargaJualSnapshot;
        $this->hppPerPcsSnapshot = $hppPerPcsSnapshot;
        $this->marginSnapshot    = $marginSnapshot;
        $this->estimasiQty       = $estimasiQty;
        $this->aktualQty         = $aktualQty;
        $this->createdAt         = $createdAt;
    }

    public function getId(): int                  { return $this->id; }
    public function getSesiId(): int              { return $this->sesiId; }
    public function getHppId(): int               { return $this->hppId; }
    public function getNamaSnapshot(): string     { return $this->namaSnapshot; }
    public function getHargaJualSnapshot(): float { return $this->hargaJualSnapshot; }
    public function getHppPerPcsSnapshot(): float { return $this->hppPerPcsSnapshot; }
    public function getMarginSnapshot(): float    { return $this->marginSnapshot; }
    public function getEstimasiQty(): int         { return $this->estimasiQty; }
    public function getAktualQty(): ?int          { return $this->aktualQty; }
    public function setAktualQty(?int $v): void   { $this->aktualQty = $v; }
    public function getCreatedAt(): \DateTime     { return $this->createdAt; }

    public function getMarginKotor(): float {
        return $this->hargaJualSnapshot - $this->hppPerPcsSnapshot;
    }
}
