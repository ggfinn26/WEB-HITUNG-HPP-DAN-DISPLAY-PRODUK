<?php
namespace App\Helper;

class SesiHelper {
    /**
     * Parse JSON component string to array
     */
    public static function parseKomponen(string $json): array {
        $raw = json_decode($json, true);
        if (!is_array($raw)) return [];
        $result = [];
        foreach ($raw as $item) {
            $nama   = trim($item['nama'] ?? '');
            $jumlah = (float)($item['jumlah'] ?? 0);
            if ($nama !== '' && $jumlah > 0) {
                $result[] = ['nama' => $nama, 'jumlah' => $jumlah];
            }
        }
        return $result;
    }
}
