<?php

namespace Database\Seeders;

use App\Models\PatientMealPlanning;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PatientMealPlanningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $startDate = now()->subYears(2);
        $endDate = now();
        $numberOfRecords = 1000;
        $uniqueCombinations = [];

        for ($i = 0; $i < $numberOfRecords; $i++) {
            do {
                $patientId = $faker->numberBetween(1, 500); // Assuming 500 patients exist
                $plannedDate = $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d');
                $combination = $patientId . '-' . $plannedDate;
            } while (in_array($combination, $uniqueCombinations));

            $uniqueCombinations[] = $combination;

            $createdAt = now();
            $updatedAt = $faker->optional()->dateTimeBetween($startDate, $endDate);

            $data = [
                'patient_id' => $patientId,
                'planned_date' => $plannedDate,
                'total_calories' => $faker->randomFloat(2, 100, 1000),
                'total_fats' => $faker->randomFloat(2, 10, 100),
                'total_carbs' => $faker->randomFloat(2, 10, 200),
                'total_proteins' => $faker->randomFloat(2, 10, 150),
                'is_active' => $faker->boolean,
                'created_by' => $faker->numberBetween(1, 100), // Assuming 100 users exist
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            if ($updatedAt) {
                $data['updated_by'] = $faker->numberBetween(1, 100); // Assuming 100 users exist
            } else {
                $data['updated_by'] = null;
            }

            PatientMealPlanning::insert($data);
        }
    }
}
