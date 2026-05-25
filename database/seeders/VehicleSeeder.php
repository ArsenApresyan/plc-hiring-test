<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'Toyota' => ['Corolla', 'RAV4', 'Camry', 'Yaris', 'Hilux'],
            'BMW' => ['320i', 'X3', 'X5', '520d', 'i4'],
            'Audi' => ['A3', 'A4', 'Q5', 'Q7', 'A6'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Polo', 'ID.4'],
            'Ford' => ['Focus', 'Mondeo', 'Kuga', 'Fiesta', 'Puma'],
            'Mercedes-Benz' => ['C200', 'E220', 'GLA', 'GLC', 'A180'],
            'Skoda' => ['Octavia', 'Superb', 'Kodiaq', 'Fabia', 'Kamiq'],
            'Hyundai' => ['Tucson', 'i30', 'Kona', 'Santa Fe', 'i20'],
            'Kia' => ['Sportage', 'Ceed', 'Sorento', 'Niro', 'Rio'],
            'Volvo' => ['XC60', 'XC90', 'V60', 'S60', 'XC40'],
        ];

        $now = now();
        $rows = [];

        for ($i = 0; $i < 500; $i++) {
            $make = array_rand($catalog);
            $model = $catalog[$make][array_rand($catalog[$make])];
            $rows[] = [
                'make' => $make,
                'model' => $model,
                'year' => random_int(2010, 2025),
                'price' => random_int(5000, 65000),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Vehicle::insert($rows);
    }
}
