<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case BANK_TRANSFER = 'bank_transfer';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case E_WALLET = 'e_wallet';
    case CICILAN = 'cicilan';

    /**
     * Get label untuk display
     */
    public function label(): string
    {
        return match($this) {
            self::CASH => 'Cash/Tunai',
            self::BANK_TRANSFER => 'Transfer Bank',
            self::CREDIT_CARD => 'Kartu Kredit',
            self::DEBIT_CARD => 'Kartu Debit',
            self::E_WALLET => 'E-Wallet',
            self::CICILAN => 'Cicilan',
        };
    }

    /**
     * Get icon untuk UI
     */
    public function icon(): string
    {
        return match($this) {
            self::CASH => 'fas fa-money-bill-wave',
            self::BANK_TRANSFER => 'fas fa-university',
            self::CREDIT_CARD => 'fas fa-credit-card',
            self::DEBIT_CARD => 'fas fa-credit-card',
            self::E_WALLET => 'fas fa-wallet',
            self::CICILAN => 'fas fa-calendar-alt',
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

/**
 * Enum untuk payment gateway
 */
enum PaymentGateway: string
{
    case MIDTRANS = 'midtrans';
    case XENDIT = 'xendit';
    case DOKU = 'doku';
    case FASPAY = 'faspay';
    case TRIPAY = 'tripay';

    /**
     * Get label untuk display
     */
    public function label(): string
    {
        return match($this) {
            self::MIDTRANS => 'Midtrans',
            self::XENDIT => 'Xendit',
            self::DOKU => 'DOKU',
            self::FASPAY => 'Faspay',
            self::TRIPAY => 'TriPay',
        };
    }

    /**
     * Get supported payment methods
     */
    public function supportedMethods(): array
    {
        return match($this) {
            self::MIDTRANS => [
                PaymentMethod::CREDIT_CARD,
                PaymentMethod::BANK_TRANSFER,
                PaymentMethod::E_WALLET,
                PaymentMethod::CICILAN
            ],
            self::XENDIT => [
                PaymentMethod::CREDIT_CARD,
                PaymentMethod::BANK_TRANSFER,
                PaymentMethod::E_WALLET,
            ],
            self::DOKU => [
                PaymentMethod::CREDIT_CARD,
                PaymentMethod::BANK_TRANSFER,
            ],
            self::FASPAY => [
                PaymentMethod::BANK_TRANSFER,
                PaymentMethod::E_WALLET,
            ],
            self::TRIPAY => [
                PaymentMethod::BANK_TRANSFER,
                PaymentMethod::E_WALLET,
            ],
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