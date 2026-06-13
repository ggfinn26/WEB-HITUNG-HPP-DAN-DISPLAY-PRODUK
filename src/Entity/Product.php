<?php

namespace App{
    class Product{
        private int $id;
        private string $name;
        private string $price;
        private string $description;
        private string $imageUrl;
        private \DateTime $createdAt;
        private \DateTime $updatedAt;
        private bool $isDeleted;
        private ?float $latitude;
        private ?float $longitude;

        public function __construct(int $id, string $name, string $price, string $description, string $imageUrl, \DateTime $createdAt, \DateTime $updatedAt, bool $isDeleted, ?float $latitude = null, ?float $longitude = null){
            $this->id = $id;
            $this->name = $name;
            $this->price = $price;
            $this->description = $description;
            $this->imageUrl = $imageUrl;
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
            $this->isDeleted = $isDeleted;
            $this->latitude = $latitude;
            $this->longitude = $longitude;
        }

        public function getId(): int {
            return $this->id;
        }

        public function setId(int $id): void {
            $this->id = $id;
        }

        public function getName(): string {
            return $this->name;
        }

        public function setName(string $name): void {
            $this->name = $name;
        }

        public function getPrice(): string {
            return $this->price;
        }

        public function setPrice(string $price): void {
            $this->price = $price;
        }

        public function getDescription(): string {
            return $this->description;
        }

        public function setDescription(string $description): void {
            $this->description = $description;
        }

        public function getImageUrl(): string {
            return $this->imageUrl;
        }

        public function setImageUrl(string $imageUrl): void {
            $this->imageUrl = $imageUrl;
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

        public function getLatitude(): ?float {
            return $this->latitude;
        }

        public function setLatitude(?float $latitude): void {
            $this->latitude = $latitude;
        }

        public function getLongitude(): ?float {
            return $this->longitude;
        }

        public function setLongitude(?float $longitude): void {
            $this->longitude = $longitude;
        }

        public function toArray(): array {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'imageUrl' => $this->imageUrl,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            ];
        }
    }
}