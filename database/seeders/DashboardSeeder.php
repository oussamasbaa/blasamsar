<?php

namespace Database\Seeders;

use App\Models\Worker;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Shift;
use App\Models\Attendance;
use App\Models\FleetVehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        // Companies
        $c1 = Company::firstOrCreate(['name' => 'Apex Auto Group'], ['name' => 'Apex Auto Group', 'address' => 'Casablanca, Maroc']);
        $c2 = Company::firstOrCreate(['name' => 'Atlas Logistics'], ['name' => 'Atlas Logistics', 'address' => 'Rabat, Maroc']);

        // Branches
        $b1 = Branch::firstOrCreate(['name' => 'Sieste Casablanca'], ['name' => 'Sieste Casablanca', 'company_id' => $c1->id, 'address' => 'Bd Zerktouni, Casablanca', 'latitude' => 33.5731, 'longitude' => -7.5898, 'radius_meters' => 200]);
        $b2 = Branch::firstOrCreate(['name' => 'Sieste Rabat'], ['name' => 'Sieste Rabat', 'company_id' => $c1->id, 'address' => 'Avenue Hassan II, Rabat', 'latitude' => 34.0209, 'longitude' => -6.8416, 'radius_meters' => 200]);
        $b3 = Branch::firstOrCreate(['name' => 'Entrepot Atlas'], ['name' => 'Entrepot Atlas', 'company_id' => $c2->id, 'address' => 'Zone Industrielle, Kenitra', 'latitude' => 34.2610, 'longitude' => -6.5802, 'radius_meters' => 300]);

        // Shifts
        $s1 = Shift::firstOrCreate(['name' => 'Matin'], ['name' => 'Matin', 'start_time' => '08:00', 'end_time' => '16:00', 'grace_period_minutes' => 15]);
        $s2 = Shift::firstOrCreate(['name' => 'Soir'], ['name' => 'Soir', 'start_time' => '14:00', 'end_time' => '22:00', 'grace_period_minutes' => 15]);
        $s3 = Shift::firstOrCreate(['name' => 'Nuit'], ['name' => 'Nuit', 'start_time' => '22:00', 'end_time' => '06:00', 'grace_period_minutes' => 10]);

        // Workers
        $workers = [
            ['name' => 'Youssef El Fassi', 'position' => 'Directeur Commercial', 'department' => 'Direction', 'phone' => '+212 6 12 34 56 78', 'email' => 'youssef@apexcar.com', 'company_id' => $c1->id, 'branch_id' => $b1->id, 'shift_id' => $s1->id, 'status' => 'active'],
            ['name' => 'Fatima Zahra Benani', 'position' => 'Responsable RH', 'department' => 'Ressources Humaines', 'phone' => '+212 6 23 45 67 89', 'email' => 'fatima@apexcar.com', 'company_id' => $c1->id, 'branch_id' => $b1->id, 'shift_id' => $s1->id, 'status' => 'active'],
            ['name' => 'Omar Alaoui', 'position' => 'Chef Mécanicien', 'department' => 'Technique', 'phone' => '+212 6 34 56 78 90', 'email' => 'omar@apexcar.com', 'company_id' => $c1->id, 'branch_id' => $b1->id, 'shift_id' => $s1->id, 'status' => 'active'],
            ['name' => 'Salma Idrissi', 'position' => 'Agent Commercial', 'department' => 'Ventes', 'phone' => '+212 6 45 67 89 01', 'email' => 'salma@apexcar.com', 'company_id' => $c1->id, 'branch_id' => $b2->id, 'shift_id' => $s1->id, 'status' => 'active'],
            ['name' => 'Hamza Tazi', 'position' => 'Mécanicien', 'department' => 'Technique', 'phone' => '+212 6 56 78 90 12', 'email' => 'hamza@apexcar.com', 'company_id' => $c1->id, 'branch_id' => $b1->id, 'shift_id' => $s2->id, 'status' => 'active'],
            ['name' => 'Nadia Berrada', 'position' => 'Comptable', 'department' => 'Finance', 'phone' => '+212 6 67 89 01 23', 'email' => 'nadia@apexcar.com', 'company_id' => $c2->id, 'branch_id' => $b3->id, 'shift_id' => $s1->id, 'status' => 'active'],
            ['name' => 'Rachid Mouline', 'position' => 'Chauffeur Livreur', 'department' => 'Logistique', 'phone' => '+212 6 78 90 12 34', 'email' => 'rachid@atlas.com', 'company_id' => $c2->id, 'branch_id' => $b3->id, 'shift_id' => $s2->id, 'status' => 'active'],
            ['name' => 'Amina Chraibi', 'position' => 'Réceptionniste', 'department' => 'Accueil', 'phone' => '+212 6 89 01 23 45', 'email' => 'amina@apexcar.com', 'company_id' => $c1->id, 'branch_id' => $b2->id, 'shift_id' => $s1->id, 'status' => 'active'],
        ];

        foreach ($workers as $w) {
            Worker::updateOrCreate(['email' => $w['email']], $w);
        }

        // Attendance for today
        $allWorkers = Worker::all();
        $presentCount = 0;
        $lateCount = 0;

        foreach ($allWorkers as $i => $worker) {
            if ($i < 5) {
                // Present - on time
                $checkIn = Carbon::today()->addHours(8)->addMinutes(rand(0, 10));
                Attendance::updateOrCreate(
                    ['worker_id' => $worker->id, 'date' => $today],
                    [
                        'check_in_at' => $checkIn,
                        'check_out_at' => null,
                        'status' => 'present',
                        'total_hours' => 0,
                    ]
                );
                $presentCount++;
            } elseif ($i < 7) {
                // Late
                $checkIn = Carbon::today()->addHours(8)->addMinutes(rand(20, 45));
                Attendance::updateOrCreate(
                    ['worker_id' => $worker->id, 'date' => $today],
                    [
                        'check_in_at' => $checkIn,
                        'check_out_at' => null,
                        'status' => 'late',
                        'total_hours' => 0,
                    ]
                );
                $lateCount++;
            }
            // 1 worker has no attendance (absent)
        }

        // Fleet Vehicles
        $vehicles = [
            ['brand' => 'Toyota', 'model' => 'Hilux', 'registration_number' => 'A-1234-B', 'year' => 2023, 'status' => 'available'],
            ['brand' => 'Renault', 'model' => 'Express', 'registration_number' => 'B-5678-C', 'year' => 2022, 'status' => 'available'],
            ['brand' => 'Ford', 'model' => 'Transit', 'registration_number' => 'C-9012-D', 'year' => 2024, 'status' => 'available'],
            ['brand' => 'Mercedes-Benz', 'model' => 'Sprinter', 'registration_number' => 'D-3456-E', 'year' => 2023, 'status' => 'maintenance'],
            ['brand' => 'Iveco', 'model' => 'Daily', 'registration_number' => 'E-7890-F', 'year' => 2021, 'status' => 'available'],
            ['brand' => 'Peugeot', 'model' => 'Partner', 'registration_number' => 'F-2345-G', 'year' => 2022, 'status' => 'maintenance'],
        ];

        foreach ($vehicles as $v) {
            FleetVehicle::updateOrCreate(['registration_number' => $v['registration_number']], $v);
        }

        $this->command->info('Dashboard data seeded: ' . count($workers) . ' workers, ' . ($presentCount + $lateCount) . ' attendances, ' . count($vehicles) . ' vehicles');
    }
}
