<?php

namespace App;

interface OrderControllerInterface {
    public function index(): void;
    public function create(): void;
    public function store(array $data): void;
    public function show(string $orderNumber): void;
    public function edit(string $orderNumber): void;
    public function update(string $orderNumber, array $data): void;
}
