<?php
namespace App{
    class Pengeluaran {
        private int $id;
        private \DateTime $tanggal;
        private string $keterangan;
        private string $jumlah;
        private \DateTime $createdAt;
        private \DateTime $updatedAt;

        public function __construct(int $id, \DateTime $tanggal, string $keterangan, string $jumlah, \DateTime $createdAt, \DateTime $updatedAt) {
            $this->id        = $id;
            $this->tanggal   = $tanggal;
            $this->keterangan = $keterangan;
            $this->jumlah    = $jumlah;
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
        }

        public function getId(): int { return $this->id; }
        public function setId(int $id): void { $this->id = $id; }

        public function getTanggal(): \DateTime { return $this->tanggal; }
        public function setTanggal(\DateTime $tanggal): void { $this->tanggal = $tanggal; }

        public function getKeterangan(): string { return $this->keterangan; }
        public function setKeterangan(string $keterangan): void { $this->keterangan = $keterangan; }

        public function getJumlah(): string { return $this->jumlah; }
        public function setJumlah(string $jumlah): void { $this->jumlah = $jumlah; }

        public function getCreatedAt(): \DateTime { return $this->createdAt; }
        public function getUpdatedAt(): \DateTime { return $this->updatedAt; }
    }
}
