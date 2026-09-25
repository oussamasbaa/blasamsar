<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        // Create 6 example workers
        $workersData = [
            ['name' => 'Alice Smith', 'position' => 'Sales Manager', 'phone' => '555-0101', 'email' => 'alice@example.com'],
            ['name' => 'Bob Johnson', 'position' => 'Technician', 'phone' => '555-0102', 'email' => 'bob@example.com'],
            ['name' => 'Carol Davis', 'position' => 'Receptionist', 'phone' => '555-0103', 'email' => 'carol@example.com'],
            ['name' => 'David Wilson', 'position' => 'Mechanic', 'phone' => '555-0104', 'email' => 'david@example.com'],
            ['name' => 'Eve Martinez', 'position' => 'Finance Officer', 'phone' => '555-0105', 'email' => 'eve@example.com'],
            ['name' => 'Frank Brown', 'position' => 'Inventory Manager', 'phone' => '555-0106', 'email' => 'frank@example.com'],
        ];
        foreach ($workersData as $data) {
            \App\Models\Worker::create($data);
        }

        $toyota = \App\Models\Brand::create(['name' => 'Toyota']);
        $bmw = \App\Models\Brand::create(['name' => 'BMW']);
        $mercedes = \App\Models\Brand::create(['name' => 'Mercedes-Benz']);
        $audi = \App\Models\Brand::create(['name' => 'Audi']);
        $porsche = \App\Models\Brand::create(['name' => 'Porsche']);
        $landrover = \App\Models\Brand::create(['name' => 'Land Rover']);
        $ferrari = \App\Models\Brand::create(['name' => 'Ferrari']);
        $bentley = \App\Models\Brand::create(['name' => 'Bentley']);
        $maserati = \App\Models\Brand::create(['name' => 'Maserati']);
        $lamborghini = \App\Models\Brand::create(['name' => 'Lamborghini']);
        $volkswagen = \App\Models\Brand::create(['name' => 'Volkswagen']);
        $jeep = \App\Models\Brand::create(['name' => 'Jeep']);
        $tesla = \App\Models\Brand::create(['name' => 'Tesla']);

        // Toyota
        \App\Models\CarModel::create(['brand_id' => $toyota->id, 'name' => 'Corolla', 'type' => 'Sedan']);
        \App\Models\CarModel::create(['brand_id' => $toyota->id, 'name' => 'Camry', 'type' => 'Sedan']);
        \App\Models\CarModel::create(['brand_id' => $toyota->id, 'name' => 'Rav4', 'type' => 'SUV']);

        // BMW
        \App\Models\CarModel::create(['brand_id' => $bmw->id, 'name' => 'M3', 'type' => 'Sports Sedan']);
        \App\Models\CarModel::create(['brand_id' => $bmw->id, 'name' => 'X5', 'type' => 'SUV']);
        \App\Models\CarModel::create(['brand_id' => $bmw->id, 'name' => 'i7', 'type' => 'Sedan']);

        // Mercedes
        \App\Models\CarModel::create(['brand_id' => $mercedes->id, 'name' => 'C-Class', 'type' => 'Sedan']);
        \App\Models\CarModel::create(['brand_id' => $mercedes->id, 'name' => 'G-Wagon G63', 'type' => 'SUV']);
        \App\Models\CarModel::create(['brand_id' => $mercedes->id, 'name' => 'S-Class', 'type' => 'Sedan']);
        \App\Models\CarModel::create(['brand_id' => $mercedes->id, 'name' => 'CLA 250 EQ-Technologie', 'type' => 'Coupe']);

        // Audi
        \App\Models\CarModel::create(['brand_id' => $audi->id, 'name' => 'A4', 'type' => 'Sedan']);
        \App\Models\CarModel::create(['brand_id' => $audi->id, 'name' => 'Q7', 'type' => 'SUV']);
        \App\Models\CarModel::create(['brand_id' => $audi->id, 'name' => 'RS3 Sportback', 'type' => 'Hatchback']);

        // Porsche
        \App\Models\CarModel::create(['brand_id' => $porsche->id, 'name' => '911 Carrera', 'type' => 'Sports Sedan']);
        \App\Models\CarModel::create(['brand_id' => $porsche->id, 'name' => 'Cayenne', 'type' => 'SUV']);
        \App\Models\CarModel::create(['brand_id' => $porsche->id, 'name' => 'Taycan', 'type' => 'Sedan']);

        // Land Rover
        \App\Models\CarModel::create(['brand_id' => $landrover->id, 'name' => 'Range Rover', 'type' => 'SUV']);
        \App\Models\CarModel::create(['brand_id' => $landrover->id, 'name' => 'Defender', 'type' => 'SUV']);

        // Ferrari
        \App\Models\CarModel::create(['brand_id' => $ferrari->id, 'name' => '488 Pista', 'type' => 'Sports Sedan']);
        \App\Models\CarModel::create(['brand_id' => $ferrari->id, 'name' => 'SF90 Stradale', 'type' => 'Sports Sedan']);

        // Bentley
        \App\Models\CarModel::create(['brand_id' => $bentley->id, 'name' => 'Continental GT', 'type' => 'Sedan']);
        \App\Models\CarModel::create(['brand_id' => $bentley->id, 'name' => 'Bentayga', 'type' => 'SUV']);

        // Maserati
        \App\Models\CarModel::create(['brand_id' => $maserati->id, 'name' => 'Ghibli', 'type' => 'Sedan']);
        \App\Models\CarModel::create(['brand_id' => $maserati->id, 'name' => 'Levante', 'type' => 'SUV']);

        // Lamborghini
        \App\Models\CarModel::create(['brand_id' => $lamborghini->id, 'name' => 'Urus', 'type' => 'SUV']);
        \App\Models\CarModel::create(['brand_id' => $lamborghini->id, 'name' => 'Huracan', 'type' => 'Sports Sedan']);

        // Volkswagen
        \App\Models\CarModel::create(['brand_id' => $volkswagen->id, 'name' => 'Golf 8 GTI', 'type' => 'Hatchback']);
        \App\Models\CarModel::create(['brand_id' => $volkswagen->id, 'name' => 'Tiguan', 'type' => 'SUV']);
        \App\Models\CarModel::create(['brand_id' => $volkswagen->id, 'name' => 'Touareg', 'type' => 'SUV']);

        // Jeep
        \App\Models\CarModel::create(['brand_id' => $jeep->id, 'name' => 'Wrangler', 'type' => 'SUV']);
        \App\Models\CarModel::create(['brand_id' => $jeep->id, 'name' => 'Grand Cherokee', 'type' => 'SUV']);

        // Tesla
        \App\Models\CarModel::create(['brand_id' => $tesla->id, 'name' => 'Model S Plaid', 'type' => 'Sedan']);
        \App\Models\CarModel::create(['brand_id' => $tesla->id, 'name' => 'Model X', 'type' => 'SUV']);
    }
}
