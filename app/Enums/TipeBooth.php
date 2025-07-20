<?php

namespace App;

enum TipeBooth: string
{
    case KECIL = 'kecil';
    case SEDANG = 'sedang';
    case BESAR = 'besar';
    case PREMIUM = 'premium';

    /**
     * Get label untuk display
     */
    public function label(): string
    {
        return match($this) {
            self::KECIL => 'Booth Kecil',
            self::SEDANG => 'Booth Sedang',
            self::BESAR => 'Booth Besar',
            self::PREMIUM => 'Booth Premium',
        };
    }

    /**
     * Get deskripsi untuk setiap tipe
     */
    public function deskripsi(): string
    {
        return match($this) {
            self::KECIL => 'Booth berukuran kecil untuk lokasi strategis dengan modal terjangkau',
            self::SEDANG => 'Booth berukuran sedang dengan fasilitas lengkap untuk omzet optimal',
            self::BESAR => 'Booth berukuran besar dengan kapasitas customer yang lebih banyak',
            self::PREMIUM => 'Booth premium dengan fasilitas terlengkap dan dukungan eksklusif',
        };
    }

    /**
     * Get estimasi luas area minimum
     */
    public function luasMinimum(): float
    {
        return match($this) {
            self::KECIL => 20.0,
            self::SEDANG => 50.0,
            self::BESAR => 100.0,
            self::PREMIUM => 200.0,
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