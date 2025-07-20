<?php

namespace App;

enum StatusPaket: string
{
    case AKTIF = 'aktif';
    case NONAKTIF = 'nonaktif';
    case HABIS = 'habis';
    case COMING_SOON = 'coming_soon';

    /**
     * Get label untuk display
     */
    public function label(): string
    {
        return match($this) {
            self::AKTIF => 'Aktif',
            self::NONAKTIF => 'Nonaktif',
            self::HABIS => 'Stok Habis',
            self::COMING_SOON => 'Segera Hadir',
        };
    }

    /**
     * Get color class untuk UI
     */
    public function colorClass(): string
    {
        return match($this) {
            self::AKTIF => 'success',
            self::NONAKTIF => 'secondary',
            self::HABIS => 'danger',
            self::COMING_SOON => 'warning',
        };
    }

    /**
     * Check if paket bisa dibeli
     */
    public function canPurchase(): bool
    {
        return match($this) {
            self::AKTIF => true,
            self::NONAKTIF, self::HABIS, self::COMING_SOON => false,
        };
    }

    /**
     * Get all values untuk dropdown
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }
}