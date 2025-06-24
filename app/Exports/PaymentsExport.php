<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Студент',
            'Сумма',
            'Дата платежа',
            'Статус',
            'Метод оплаты'
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->student->full_name ?? 'Не указан',
            $payment->amount,
            $payment->payment_date->format('d.m.Y'),
            $payment->status,
            $payment->payment_method
        ];
    }
}