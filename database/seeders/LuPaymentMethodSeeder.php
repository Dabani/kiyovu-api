<?php

namespace Database\Seeders;

use App\Models\Lookups\LuPaymentMethod;
use Illuminate\Database\Seeder;

class LuPaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'bank_transfer', 'label_en' => 'Bank Transfer', 'label_fr' => 'Virement Bancaire', 'label_rw' => 'Kohereza kuri Banki', 'sort_order' => 1],
            ['code' => 'mtn_momo', 'label_en' => 'MTN Mobile Money', 'label_fr' => 'MTN Mobile Money', 'label_rw' => 'MTN Mobile Money', 'sort_order' => 2],
            ['code' => 'airtel_money', 'label_en' => 'Airtel Money', 'label_fr' => 'Airtel Money', 'label_rw' => 'Airtel Money', 'sort_order' => 3],
            ['code' => 'cheque', 'label_en' => 'Cheque', 'label_fr' => 'Chèque', 'label_rw' => 'Cheki', 'sort_order' => 4],
            ['code' => 'other', 'label_en' => 'Other (Executive Organ authorised)', 'label_fr' => 'Autre (autorisé par l\'Organe Exécutif)', 'label_rw' => 'Ubundi buryo', 'sort_order' => 5],
        ];

        foreach ($rows as $row) {
            LuPaymentMethod::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
