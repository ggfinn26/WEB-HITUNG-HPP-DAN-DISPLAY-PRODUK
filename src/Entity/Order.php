<?php

namespace App{
    class Order{

        private int $id;
        private string $orderNumber;
        private string $listItemOrder;
        private string $subTotal;
        private string $orderStatus;
        private ?string $statusHistory;
        private string $namaPemesan;
        private string $alamatPemesan;
        private string $whatsappPemesan;
        private string $instagramUserNamePemesan;
        private \DateTime $createdAt;
        private \DateTime $updatedAt;

        public function __construct(int $id, string $orderNumber, string $listItemOrder, 
        string $subTotal, string $orderStatus, ?string $statusHistory, string $namaPemesan, string $alamatPemesan, 
        string $whatsappPemesan, string $instagramUserNamePemesan,
        \DateTime $createdAt, \DateTime $updatedAt){
            $this->id = $id;
            $this->orderNumber = $orderNumber;
            $this->listItemOrder = $listItemOrder;
            $this->subTotal = $subTotal;
            $this->orderStatus = $orderStatus;
            $this->statusHistory = $statusHistory;
            $this->namaPemesan = $namaPemesan;
            $this->alamatPemesan = $alamatPemesan;
            $this->whatsappPemesan = $whatsappPemesan;
            $this->instagramUserNamePemesan = $instagramUserNamePemesan;
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
        }

        public function getId(): int {
            return $this->id;
        }

        public function setId(int $id): void {
            $this->id = $id;
        }

        public function getOrderNumber(): string {
            return $this->orderNumber;
        }

        public function setOrderNumber(string $orderNumber): void {
            $this->orderNumber = $orderNumber;
        }

        public function getListItemOrder(): string {
            return $this->listItemOrder;
        }

        public function setListItemOrder(string $listItemOrder): void {
            $this->listItemOrder = $listItemOrder;
        }

        public function getSubTotal(): string {
            return $this->subTotal;
        }

        public function setSubTotal(string $subTotal): void {
            $this->subTotal = $subTotal;
        }

        public function getOrderStatus(): string {
            return $this->orderStatus;
        }

        public function setOrderStatus(string $orderStatus): void {
            $this->orderStatus = $orderStatus;
        }

        public function getStatusHistory(): ?string {
            return $this->statusHistory;
        }

        public function setStatusHistory(?string $statusHistory): void {
            $this->statusHistory = $statusHistory;
        }

        public function getNamaPemesan(): string {
            return $this->namaPemesan;
        }

        public function setNamaPemesan(string $namaPemesan): void {
            $this->namaPemesan = $namaPemesan;
        }

        public function getAlamatPemesan(): string {
            return $this->alamatPemesan;
        }

        public function setAlamatPemesan(string $alamatPemesan): void {
            $this->alamatPemesan = $alamatPemesan;
        }

        public function getInstagramUserNamePemesan(): string {
            return $this->instagramUserNamePemesan;
        }

        public function setInstagramUserNamePemesan(string $instagramUserNamePemesan): void {
            $this->instagramUserNamePemesan = $instagramUserNamePemesan;
        }

        public function getWhatsappPemesan(): string {
            return $this->whatsappPemesan;
        }

        public function setWhatsappPemesan(string $whatsappPemesan): void {
            $this->whatsappPemesan = $whatsappPemesan;
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
    }
}