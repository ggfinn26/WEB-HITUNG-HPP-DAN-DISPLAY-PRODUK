<?php
namespace App;

class BiayaKomponenSesi {
    private int    $id;
    private int    $sesiId;
    private string $namaKomponen;
    private float  $jumlah;
    private \DateTime $createdAt;

    public function __construct(int $id, int $sesiId, string $namaKomponen, float $jumlah, \DateTime $createdAt) {
        $this->id           = $id;
        $this->sesiId       = $sesiId;
        $this->namaKomponen = $namaKomponen;
        $this->jumlah       = $jumlah;
        $this->createdAt    = $createdAt;
    }

    public function getId(): int               { return $this->id; }
    public function getSesiId(): int           { return $this->sesiId; }
    public function getNamaKomponen(): string  { return $this->namaKomponen; }
    public function getJumlah(): float         { return $this->jumlah; }
    public function getCreatedAt(): \DateTime  { return $this->createdAt; }
}
