<?php
namespace App;

use PDO;

class ProductVariantRepositoryImpl implements ProductVariantRepository {
    private PDO $pdo;

    public function __construct(?PDO $pdo = null) {
        $this->pdo = $pdo ?? \App\Database::getConnection();
    }

    public function saveVariantGroups(int $productId, array $groups): array {
        $savedGroups = [];
        $stmt = $this->pdo->prepare("INSERT INTO product_variant_groups (product_id, name, created_at) VALUES (?, ?, NOW())");
        foreach ($groups as $groupName) {
            $stmt->execute([$productId, $groupName]);
            $savedGroups[] = [
                'id' => (int)$this->pdo->lastInsertId(),
                'name' => $groupName
            ];
        }
        return $savedGroups;
    }

    public function saveVariantOptions(int $groupId, array $options): array {
        $savedOptions = [];
        $stmt = $this->pdo->prepare("INSERT INTO product_variant_options (group_id, name, created_at) VALUES (?, ?, NOW())");
        foreach ($options as $optionName) {
            $stmt->execute([$groupId, $optionName]);
            $savedOptions[] = [
                'id' => (int)$this->pdo->lastInsertId(),
                'name' => $optionName
            ];
        }
        return $savedOptions;
    }

    public function saveVariants(int $productId, array $variants): array {
        $savedVariants = [];
        $stmt = $this->pdo->prepare("
            INSERT INTO product_variants (product_id, name, sku, price, stock, image_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        foreach ($variants as $v) {
            $stmt->execute([
                $productId, 
                $v['name'], 
                $v['sku'] ?? null, 
                $v['price'] ?? null, 
                $v['stock'] ?? 0, 
                $v['image_id'] ?? null
            ]);
            $id = (int)$this->pdo->lastInsertId();
            $v['id'] = $id;
            $savedVariants[] = $v;
        }
        return $savedVariants;
    }

    public function saveVariantCombinations(int $variantId, array $optionIds): void {
        $stmt = $this->pdo->prepare("INSERT INTO product_variant_combinations (variant_id, option_id) VALUES (?, ?)");
        foreach ($optionIds as $optId) {
            $stmt->execute([$variantId, $optId]);
        }
    }

    public function getGroupsByProduct(int $productId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM product_variant_groups WHERE product_id = ? ORDER BY id ASC");
        $stmt->execute([$productId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $groups = [];
        foreach ($rows as $r) {
            $groups[] = new ProductVariantGroup((int)$r['id'], (int)$r['product_id'], $r['name'], new \DateTime($r['created_at']));
        }
        return $groups;
    }

    public function getOptionsByGroup(int $groupId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM product_variant_options WHERE group_id = ? ORDER BY id ASC");
        $stmt->execute([$groupId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $options = [];
        foreach ($rows as $r) {
            $options[] = new ProductVariantOption((int)$r['id'], (int)$r['group_id'], $r['name'], new \DateTime($r['created_at']));
        }
        return $options;
    }

    public function getVariantsByProduct(int $productId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM product_variants WHERE product_id = ? ORDER BY id ASC");
        $stmt->execute([$productId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $variants = [];
        foreach ($rows as $r) {
            $variant = new ProductVariant(
                (int)$r['id'], (int)$r['product_id'], $r['name'], $r['sku'], $r['price'], 
                (int)$r['stock'], $r['image_id'] ? (int)$r['image_id'] : null, 
                new \DateTime($r['created_at']), new \DateTime($r['updated_at'])
            );
            
            // Get option IDs
            $optStmt = $this->pdo->prepare("SELECT option_id FROM product_variant_combinations WHERE variant_id = ?");
            $optStmt->execute([$variant->getId()]);
            $optIds = $optStmt->fetchAll(PDO::FETCH_COLUMN);
            $variant->setOptionIds(array_map('intval', $optIds));
            
            $variants[] = $variant;
        }
        return $variants;
    }

    public function saveImages(int $productId, array $images): array {
        $savedImages = [];
        $stmt = $this->pdo->prepare("INSERT INTO product_images (product_id, image_url, is_primary, created_at) VALUES (?, ?, ?, NOW())");
        foreach ($images as $img) {
            $stmt->execute([$productId, $img['url'], $img['is_primary'] ? 1 : 0]);
            $savedImages[] = [
                'id' => (int)$this->pdo->lastInsertId(),
                'url' => $img['url']
            ];
        }
        return $savedImages;
    }

    public function getImagesByProduct(int $productId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC");
        $stmt->execute([$productId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $images = [];
        foreach ($rows as $r) {
            $images[] = new ProductImage((int)$r['id'], (int)$r['product_id'], $r['image_url'], (bool)$r['is_primary'], new \DateTime($r['created_at']));
        }
        return $images;
    }

    public function deleteVariantsByProduct(int $productId): void {
        // Cascade will probably handle combinations, but just in case:
        $stmt = $this->pdo->prepare("DELETE c FROM product_variant_combinations c JOIN product_variants v ON c.variant_id = v.id WHERE v.product_id = ?");
        $stmt->execute([$productId]);
        
        $stmt = $this->pdo->prepare("DELETE FROM product_variants WHERE product_id = ?");
        $stmt->execute([$productId]);
    }

    public function deleteGroupsByProduct(int $productId): void {
        $stmt = $this->pdo->prepare("DELETE o FROM product_variant_options o JOIN product_variant_groups g ON o.group_id = g.id WHERE g.product_id = ?");
        $stmt->execute([$productId]);
        
        $stmt = $this->pdo->prepare("DELETE FROM product_variant_groups WHERE product_id = ?");
        $stmt->execute([$productId]);
    }

    public function deleteImagesByProduct(int $productId): void {
        $stmt = $this->pdo->prepare("DELETE FROM product_images WHERE product_id = ?");
        $stmt->execute([$productId]);
    }
}
