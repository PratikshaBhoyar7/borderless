<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\Taluka;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // firstOrCreate India Country
        $india = Country::firstOrCreate([
            'name' => 'India',
            'code' => 'IN',
            'phone_code' => '+91',
            'is_active' => true,
        ]);

        // firstOrCreate States for India
        $maharashtra = State::firstOrCreate([
            'country_id' => $india->id,
            'name' => 'Maharashtra',
            'code' => 'MH',
            'is_active' => true,
        ]);

        $karnataka = State::firstOrCreate([
            'country_id' => $india->id,
            'name' => 'Karnataka',
            'code' => 'KA',
            'is_active' => true,
        ]);

        $gujrat = State::firstOrCreate([
            'country_id' => $india->id,
            'name' => 'Gujarat',
            'code' => 'GJ',
            'is_active' => true,
        ]);

        $tamilnadu = State::firstOrCreate([
            'country_id' => $india->id,
            'name' => 'Tamil Nadu',
            'code' => 'TN',
            'is_active' => true,
        ]);

        $telangana = State::firstOrCreate([
            'country_id' => $india->id,
            'name' => 'Telangana',
            'code' => 'TG',
            'is_active' => true,
        ]);

        // firstOrCreate Districts for Maharashtra
        $pune = District::firstOrCreate([
            'state_id' => $maharashtra->id,
            'name' => 'Pune',
            'is_active' => true,
        ]);

        $mumbai = District::firstOrCreate([
            'state_id' => $maharashtra->id,
            'name' => 'Mumbai',
            'is_active' => true,
        ]);

        $nagpur = District::firstOrCreate([
            'state_id' => $maharashtra->id,
            'name' => 'Nagpur',
            'is_active' => true,
        ]);

        $aurangabad = District::firstOrCreate([
            'state_id' => $maharashtra->id,
            'name' => 'Aurangabad',
            'is_active' => true,
        ]);

        // firstOrCreate Districts for Karnataka
        $bangalore = District::firstOrCreate([
            'state_id' => $karnataka->id,
            'name' => 'Bangalore',
            'is_active' => true,
        ]);

        $mysore = District::firstOrCreate([
            'state_id' => $karnataka->id,
            'name' => 'Mysore',
            'is_active' => true,
        ]);

        // firstOrCreate Districts for Gujarat
        $ahmedabad = District::firstOrCreate([
            'state_id' => $gujrat->id,
            'name' => 'Ahmedabad',
            'is_active' => true,
        ]);

        $vadodara = District::firstOrCreate([
            'state_id' => $gujrat->id,
            'name' => 'Vadodara',
            'is_active' => true,
        ]);

        // firstOrCreate Districts for Tamil Nadu
        $chennai = District::firstOrCreate([
            'state_id' => $tamilnadu->id,
            'name' => 'Chennai',
            'is_active' => true,
        ]);

        $coimbatore = District::firstOrCreate([
            'state_id' => $tamilnadu->id,
            'name' => 'Coimbatore',
            'is_active' => true,
        ]);

        // firstOrCreate Talukas for Pune District
        Taluka::firstOrCreate([
            'district_id' => $pune->id,
            'name' => 'Pune City',
            'is_active' => true,
        ]);

        Taluka::firstOrCreate([
            'district_id' => $pune->id,
            'name' => 'Hadapsar',
            'is_active' => true,
        ]);

        Taluka::firstOrCreate([
            'district_id' => $pune->id,
            'name' => 'Pimpri-Chinchwad',
            'is_active' => true,
        ]);

        // firstOrCreate Talukas for Mumbai District
        Taluka::firstOrCreate([
            'district_id' => $mumbai->id,
            'name' => 'Mumbai Central',
            'is_active' => true,
        ]);

        Taluka::firstOrCreate([
            'district_id' => $mumbai->id,
            'name' => 'Mumbai South',
            'is_active' => true,
        ]);

        // firstOrCreate Talukas for Nagpur District
        Taluka::firstOrCreate([
            'district_id' => $nagpur->id,
            'name' => 'Nagpur City',
            'is_active' => true,
        ]);

        Taluka::firstOrCreate([
            'district_id' => $nagpur->id,
            'name' => 'Hingna',
            'is_active' => true,
        ]);

        // firstOrCreate Talukas for Aurangabad District
        Taluka::firstOrCreate([
            'district_id' => $aurangabad->id,
            'name' => 'Aurangabad City',
            'is_active' => true,
        ]);

        // firstOrCreate Talukas for Bangalore District
        Taluka::firstOrCreate([
            'district_id' => $bangalore->id,
            'name' => 'Bangalore City',
            'is_active' => true,
        ]);

        Taluka::firstOrCreate([
            'district_id' => $bangalore->id,
            'name' => 'Bangalore East',
            'is_active' => true,
        ]);

        // firstOrCreate Talukas for Mysore District
        Taluka::firstOrCreate([
            'district_id' => $mysore->id,
            'name' => 'Mysore City',
            'is_active' => true,
        ]);

        Taluka::firstOrCreate([
            'district_id' => $mysore->id,
            'name' => 'Heggadadevanakote',
            'is_active' => true,
        ]);

        // firstOrCreate Talukas for Ahmedabad District
        Taluka::firstOrCreate([
            'district_id' => $ahmedabad->id,
            'name' => 'Ahmedabad City',
            'is_active' => true,
        ]);

        Taluka::firstOrCreate([
            'district_id' => $ahmedabad->id,
            'name' => 'Bayad',
            'is_active' => true,
        ]);

        // firstOrCreate Talukas for Chennai District
        Taluka::firstOrCreate([
            'district_id' => $chennai->id,
            'name' => 'Chennai Central',
            'is_active' => true,
        ]);

        Taluka::firstOrCreate([
            'district_id' => $chennai->id,
            'name' => 'Tambaram',
            'is_active' => true,
        ]);
    }
}
