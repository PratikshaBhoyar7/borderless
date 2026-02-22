<?php

namespace Database\Seeders;

use App\Models\CampaignType;
use Illuminate\Database\Seeder;

class CampaignTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaignTypes = [
            [
                'name' => 'Health Screening',
                'description' => 'Regular health screening and checkup campaign',
                'is_active' => true,
            ],
            [
                'name' => 'Vaccination Drive',
                'description' => 'Immunization and vaccination campaign',
                'is_active' => true,
            ],
            [
                'name' => 'Disease Awareness',
                'description' => 'Disease awareness and prevention campaign',
                'is_active' => true,
            ],
            [
                'name' => 'Maternal Health',
                'description' => 'Maternal and child health campaign',
                'is_active' => true,
            ],
            [
                'name' => 'Chronic Disease Management',
                'description' => 'Management of chronic diseases campaign',
                'is_active' => true,
            ],
            [
                'name' => 'Mental Health',
                'description' => 'Mental health awareness and support campaign',
                'is_active' => true,
            ],
        ];

        foreach ($campaignTypes as $type) {
            CampaignType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
