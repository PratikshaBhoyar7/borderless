<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KnownConditionSeeder extends Seeder
{
    public function run(): void
    {
        $conditions = [

            'Not known',
            'Hypertension',
            'Type 1 diabetes mellitus',
            'Type 2 diabetes mellitus',
            'Anaemia',
            'Asthma',
            'Chronic obstructive pulmonary disease',
            'Tuberculosis (past history)',
            'Epilepsy / seizure disorder',
            'Ischemic heart disease',
            'Heart failure',
            'Stroke (CVA) – past history',
            'Chronic kidney disease',
            'Chronic liver disease',
            'Thyroid disorder – hypothyroidism',
            'Thyroid disorder – hyperthyroidism',
            'Rheumatoid arthritis',
            'Osteoarthritis',
            'Osteoporosis',
            'Chronic back pain',
            'Peripheral neuropathy',
            'Visual impairment',
            'Hearing impairment',
            'Chronic sinusitis',
            'Migraine',
            'Gastroesophageal reflux disease',
            'Peptic ulcer disease',
            'Irritable bowel syndrome',
            'Chronic constipation',
            'Chronic diarrhoea',
            'Urinary incontinence',
            'Recurrent urinary tract infection',
            'HIV positive',
            'Hepatitis B',
            'Hepatitis C',
            'Depression',
            'Anxiety disorder',
            'Psychotic disorder',
            'Substance use disorder – alcohol',
            'Substance use disorder – tobacco',
            'Substance use disorder – other',
            'Cancer (any type)',
            'History of surgery',
            'History of blood transfusion',
            'History of major trauma',
            'Pregnancy (current)',
            'High-risk pregnancy (current)',
            'Recurrent abortions',
            'Infertility',
            'Congenital heart disease',
            'Developmental delay',
            'Intellectual disability',
            'Allergy – drug',
            'Allergy – food',
            'Allergy – environmental',
        ];

        foreach ($conditions as $condition) {
            DB::table('known_conditions')->updateOrInsert(
                ['title' => $condition],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}