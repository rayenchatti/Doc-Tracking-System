<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['nom' => 'Service RH', 'description' => 'Ressources Humaines et Personnel'],
            ['nom' => 'Service Comptabilité', 'description' => 'Comptabilité et Finances'],
            ['nom' => 'Service Informatique', 'description' => 'Systèmes d\'Information et Support'],
            ['nom' => 'Service Juridique', 'description' => 'Affaires Juridiques et Contrats'],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['nom' => $service['nom']], $service);
        }
    }
}
