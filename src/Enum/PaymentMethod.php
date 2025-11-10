<?php

namespace App\Enum;

enum PaymentMethod: string
{
    case PIX = 'pix';
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case BANK_TRANSFER = 'bank_transfer';
    case FOOD_VOUCHER = 'food_voucher';
}
