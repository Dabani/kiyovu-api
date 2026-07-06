<?php

namespace Database\Seeders;

use App\Models\Lookups\LuDisciplinaryCaseSource;
use Illuminate\Database\Seeder;

class LuDisciplinaryCaseSourceSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'formal_complaint', 'label_en' => 'Formal Written Complaint', 'label_fr' => 'Plainte Écrite Formelle', 'label_rw' => 'Ikirego Cyanditse', 'sort_order' => 1],
            ['code' => 'incident_report', 'label_en' => 'Incident Report', 'label_fr' => 'Rapport d\'Incident', 'label_rw' => 'Raporo y\'Ikibazo', 'sort_order' => 2],
            ['code' => 'audit_referral', 'label_en' => 'Audit Organ Referral', 'label_fr' => 'Renvoi de l\'Organe d\'Audit', 'label_rw' => 'Kwoherezwa n\'Ikigo cy\'Igenzura', 'sort_order' => 3],
            ['code' => 'cro_referral', 'label_en' => 'CRO Referral', 'label_fr' => 'Renvoi de l\'ORC', 'label_rw' => 'Kwoherezwa na CRO', 'sort_order' => 4],
            ['code' => 'self_report', 'label_en' => 'Self-Reporting', 'label_fr' => 'Auto-Signalement', 'label_rw' => 'Kwiregura', 'sort_order' => 5],
            ['code' => 'external_referral', 'label_en' => 'External Referral (FERWAFA/CAF/FIFA/Law Enforcement)', 'label_fr' => 'Renvoi Externe', 'label_rw' => 'Kwoherezwa n\'Inzego z\'Amahanga', 'sort_order' => 6],
        ];

        foreach ($rows as $row) {
            LuDisciplinaryCaseSource::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
