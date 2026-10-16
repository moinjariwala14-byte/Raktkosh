<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\BloodBank;
use App\Models\BloodBatch;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create System Admin
        User::updateOrCreate(
            ['email' => 'admin@redcross.org'],
            [
                'name' => 'Red Cross Central Admin',
                'phone' => '9825012345',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
            ]
        );

        // 2. Create Model Blood Bank Center
        $bank = BloodBank::create([
            'name' => 'Red Cross Regional Blood Transfusion Center',
            'license_number' => 'RC-GUJ-2026-089',
            'category' => 'Red Cross',
            'parent_hospital_name' => 'Civil Hospital Transfusion Division',
            'state' => 'Gujarat',
            'district' => 'Navsari',
            'city_taluka' => 'Navsari',
            'pincode' => '396445',
            'latitude' => 20.9467000,
            'longitude' => 72.9520000,
            'contact_person' => 'Dr. R. K. Mehta (Chief Medical Officer)',
            'helpline_phone' => '02637-258900',
            'operating_hours' => '24 Hours Emergency Service',
            'has_component_facility' => true,
            'has_prbc' => true,
            'has_ffp' => true,
            'has_platelets' => true,
            'has_cryoprecipitate' => true,
        ]);

        // 3. Seed Batches with Real Shelf-Lives
        // Shelf life standards: PRBC = 42 days, Platelets = 5 days, FFP = 365 days, WB = 35 days
        $groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Bombay Phenotype'];
        $components = [
            'Whole Blood' => 35,
            'PRBC' => 42,
            'Platelet Concentrate' => 5,
            'FFP' => 365,
            'Cryoprecipitate' => 365,
        ];

        $counter = 1000;
        foreach ($groups as $group) {
            foreach ($components as $comp => $shelfDays) {
                for ($i = 0; $i < 3; $i++) {
                    $collected = Carbon::now()->subDays(rand(1, 4));
                    BloodBatch::create([
                        'blood_bank_id' => $bank->id,
                        'unit_barcode' => 'RCS-' . (++$counter),
                        'blood_group' => $group,
                        'component' => $comp,
                        'bag_volume' => ($comp === 'Platelet Concentrate') ? '350ml' : '450ml',
                        'collection_date' => $collected->toDateString(),
                        'expiry_date' => $collected->copy()->addDays($shelfDays)->toDateString(),
                        'testing_clearance_status' => 'Cleared',
                        'status' => 'Available',
                    ]);
                }
            }
        }
    }
}
