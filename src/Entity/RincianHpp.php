<?php

namespace App{
    class RincianHpp{
        private int $id;
        private int $productId;
        private string $name;
        private int $marginKeuntungan;
        private string $productItemList;
        private int $jumlahProduksi;
        private string $totalBiayaHpp;
        private string $hppPerPcs;
        private string $hargaJualProduk;
        private \DateTime $createdAt;
        private \DateTime $updatedAt;
        private bool $isDeleted;

        public function __construct(
            int $id, 
            int $productId, 
            string $name, 
            int $marginKeuntungan, 
            string $productItemList, 
            int $jumlahProduksi, 
            string $totalBiayaHpp, 
            string $hppPerPcs, 
            string $hargaJualProduk, 
            \DateTime $createdAt, 
            \DateTime $updatedAt, 
            bool $isDeleted
        ){
            $this->id = $id;
            $this->productId = $productId;
            $this->name = $name;
            $this->marginKeuntungan = $marginKeuntungan;
            $this->productItemList = $productItemList;
            $this->jumlahProduksi = $jumlahProduksi;
            $this->totalBiayaHpp = $totalBiayaHpp;
            $this->hppPerPcs = $hppPerPcs;
            $this->hargaJualProduk = $hargaJualProduk;
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
            $this->isDeleted = $isDeleted;
        }

        public function getId(): int {
            return $this->id;
        }

        public function setId(int $id): void {
            $this->id = $id;
        }

        public function getProductId(): int {
            return $this->productId;
        }

        public function setProductId(int $productId): void {
            $this->productId = $productId;
        }

        public function getName(): string {
            return $this->name;
        }

        public function setName(string $name): void {
            $this->name = $name;
        }

        public function getMarginKeuntungan(): int {
            return $this->marginKeuntungan;
        }

        public function setMarginKeuntungan(int $marginKeuntungan): void {
            $this->marginKeuntungan = $marginKeuntungan;
        }

        public function getProductItemList(): string {
            return $this->productItemList;
        }

        public function setProductItemList(string $productItemList): void {
            $this->productItemList = $productItemList;
        }

        public function getJumlahProduksi(): int {
            return $this->jumlahProduksi;
        }

        public function setJumlahProduksi(int $jumlahProduksi): void {
            $this->jumlahProduksi = $jumlahProduksi;
        }

        public function getTotalBiayaHpp(): string {
            return $this->totalBiayaHpp;
        }

        public function setTotalBiayaHpp(string $totalBiayaHpp): void {
            $this->totalBiayaHpp = $totalBiayaHpp;
        }

        public function getHppPerPcs(): string {
            return $this->hppPerPcs;
        }

        public function setHppPerPcs(string $hppPerPcs): void {
            $this->hppPerPcs = $hppPerPcs;
        }

        public function getHargaJualProduk(): string {
            return $this->hargaJualProduk;
        }

        public function setHargaJualProduk(string $hargaJualProduk): void {
            $this->hargaJualProduk = $hargaJualProduk;
        }

        public function getCreatedAt(): \DateTime {
            return $this->createdAt;
        }

        public function setCreatedAt(\DateTime $createdAt): void {
            $this->createdAt = $createdAt;
        }

        public function getUpdatedAt(): \DateTime {
            return $this->updatedAt;
        }

        public function setUpdatedAt(\DateTime $updatedAt): void {
            $this->updatedAt = $updatedAt;
        }

        public function getIsDeleted(): bool {
            return $this->isDeleted;
        }

        public function setIsDeleted(bool $isDeleted): void {
            $this->isDeleted = $isDeleted;
        }
    }
}
