<?php
namespace App;

interface ProductVariantRepository {
    public function saveVariantGroups(int $productId, array $groups): array;
    public function saveVariantOptions(int $groupId, array $options): array;
    public function saveVariants(int $productId, array $variants): array;
    public function saveVariantCombinations(int $variantId, array $optionIds): void;
    
    public function getGroupsByProduct(int $productId): array;
    public function getOptionsByGroup(int $groupId): array;
    public function getVariantsByProduct(int $productId): array;
    
    public function saveImages(int $productId, array $images): array;
    public function getImagesByProduct(int $productId): array;

    public function deleteVariantsByProduct(int $productId): void;
    public function deleteGroupsByProduct(int $productId): void;
    public function deleteImagesByProduct(int $productId): void;
}
