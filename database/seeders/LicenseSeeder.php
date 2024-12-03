<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\License;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $licenses = [
            [
                'name' => 'Licencia por Fallecimiento',
                'description' => 'Por el fallecimiento de familiares directos.',
                //'min_duration_days' => 2,
                'max_duration_days' => 5,
                'annual_limit' => null, // Sin límite anual
                'advance_notice_days' => null, // No requiere aviso previo
                'requires_justification' => true,
                'required_documents' => 'Certificado de defunción',
                'is_paid' => false,
            ],
            [
                'name' => 'Licencia por Enfermedad',
                'description' => 'Por problemas de salud de familiares, con certificado médico.',
                //'min_duration_days' => 2,
                'max_duration_days' => 5,
                'annual_limit' => null,
                'advance_notice_days' => 0, // Aviso inmediato
                'requires_justification' => true,
                'required_documents' => 'Certificado médico',
                'is_paid' => false,
            ],
            [
                'name' => 'Licencia por Razones Personales',
                'description' => 'Por motivos personales, con justificación válida.',
                //'min_duration_days' => 1, // VER QUE ONDA
                'max_duration_days' => null, //
                'annual_limit' => 12, // Máximo 12 días al año
                'advance_notice_days' => 0,
                'requires_justification' => true,
                'required_documents' => 'Certificado médico u otra documentación válida',
                'is_paid' => false,
            ],
            [
                'name' => 'Licencia por Estudio',
                'description' => 'Para asistir a exámenes académicos.',
                //'min_duration_days' => 1,
                'max_duration_days' => 5,
                'annual_limit' => 6, // 6 días por año
                'advance_notice_days' => 7, // Una semana de aviso previo
                'requires_justification' => true,
                'required_documents' => 'Certificado de inscripción al examen',
                'is_paid' => false,
            ],
            [
                'name' => 'Licencia por Maternidad',
                'description' => 'Para madres gestantes según legislación.',
                //'min_duration_days' => 90,
                'max_duration_days' => 90,
                'annual_limit' => null,
                'advance_notice_days' => 15, // Aviso previo de 15 días
                'requires_justification' => true,
                'required_documents' => 'Certificado médico confirmando fecha de parto',
                'is_paid' => false,
            ],
            [
                'name' => 'Licencia por Paternidad',
                'description' => 'Para padres al momento del nacimiento.',
                //'min_duration_days' => 2,
                'max_duration_days' => 5,
                'annual_limit' => null,
                'advance_notice_days' => 2,
                'requires_justification' => true,
                'required_documents' => 'Certificado médico o acta de nacimiento',
                'is_paid' => false,
            ],
            [
                'name' => 'Licencia por Casamiento',
                'description' => 'Por contraer matrimonio legalmente.',
                //'min_duration_days' => 15,
                'max_duration_days' => 15,
                'annual_limit' => null,
                'advance_notice_days' => 30,
                'requires_justification' => true,
                'required_documents' => 'Certificado de matrimonio',
                'is_paid' => true,
            ],
        ];

        foreach ($licenses as $license) {
            License::create($license);
        }
    }
}
