<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiagnosisSeeder extends Seeder
{
    public function run(): void
    {
        $diagnoses = [

            'Osteoporosis',
            'Osteoarthritis',
            'Rheumatoid arthritis',
            'Low back pain syndrome',
            'Cervical spondylosis',
            'Peripheral neuropathy',
            'Pityriasis alba',
            'Tinea (fungal infection)',
            'Eczema',
            'Psoriasis',
            'Vitiligo',
            'Palmoplantar keratoderma',
            'Scabies',
            'Pediculosis',
            'Bacterial skin infection',
            'Skin abscess',
            'Cellulitis',
            'Upper respiratory tract infection',
            'Lower respiratory tract infection',
            'Common cold (coryza)',
            'Sinusitis',
            'Tonsillitis',
            'Otitis media',
            'Pneumonia',
            'Asthma',
            'Chronic obstructive pulmonary disease',
            'Labyrinthitis',
            'Vertigo',
            'Migraine',
            'Tension headache',
            'Gastritis',
            'Gastroesophageal reflux disease',
            'Acute gastroenteritis',
            'Dyspepsia',
            'Worm infestation',
            'Hypertension',
            'Type 2 diabetes mellitus',
            'Anaemia',
            'Dyslipidaemia',
            'Urinary tract infection',
            'Renal calculi (suspected)',
            'Dental caries',
            'Periodontitis',
            'Stomatitis',
            'Cheilitis',
            'Oral candidiasis',
            'Conjunctivitis',
            'Refractive error',
            'Menorrhagia',
            'Dysmenorrhoea',
            'Vaginal candidiasis',
            'Pelvic inflammatory disease (suspected)',
            'Pregnancy (confirmed)',
            'High-risk pregnancy (suspected)',
            'Malaria',
            'Dengue fever',
            'Typhoid fever',
            'Tuberculosis (suspected)',
            'Viral fever',
            'Acute febrile illness',
            'Malnutrition',
            'Underweight',
            'Overweight / obesity',
            'Alcohol use disorder',
            'Tobacco use disorder',
            'Anxiety disorder',
            'Depressive disorder',
        ];

        foreach ($diagnoses as $diagnosis) {
            DB::table('diagnoses')->updateOrInsert(
                ['title' => $diagnosis],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}