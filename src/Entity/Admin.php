<?php

namespace App{

    class Admin{
        private int $id;
        private string $name;
        private string $email;
        private string $passwordHash;
        private ?\DateTime $createdAt = null;
        private ?\DateTime $updatedAt = null;

        public function __construct(
            int $id = 0,
            string $name = '',
            string $email = '',
            string $passwordHash = '',
            ?\DateTime $createdAt = null,
            ?\DateTime $updatedAt = null
        ) {
            $this->id = $id;
            $this->name = $name;
            $this->email = $email;
            $this->passwordHash = $passwordHash;
            $this->createdAt = $createdAt;
            $this->updatedAt = $updatedAt;
        }

        public function getId(): int{
            return $this->id;
        }

        public function setId(int $id): void{
            $this->id = $id;
        }

        public function getName(): string{
            return $this->name;
        }

        public function setName(string $name): void{
            $this->name = $name;
        }

        public function getEmail(): string{
            return $this->email;
        }

        public function setEmail(string $email): void{
            $this->email = $email;
        }

        public function getPasswordHash(): string{
            return $this->passwordHash;
        }

        public function setPasswordHash(string $passwordHash): void{
            $this->passwordHash = $passwordHash;
        }

        public function getCreatedAt(): ?\DateTime{
            return $this->createdAt;
        }

        public function setCreatedAt(?\DateTime $createdAt): void{
            $this->createdAt = $createdAt;
        }

        public function getUpdatedAt(): ?\DateTime{
            return $this->updatedAt;
        }

        public function setUpdatedAt(?\DateTime $updatedAt): void{
            $this->updatedAt = $updatedAt;
        }
    }
}