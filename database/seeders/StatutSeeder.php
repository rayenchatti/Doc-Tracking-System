<?php

namespace Database\Seeders;

use App\Models\Statut;
use Illuminate\Database\Seeder;

class StatutSeeder extends Seeder
{
    public function run(): void
    {
        $statuts = [
            ['id' => 1, 'nom' => 'En attente'],
            ['id' => 2, 'nom' => 'En cours'],
            ['id' => 3, 'nom' => 'Traité'],
            ['id' => 4, 'nom' => 'Archivé'],
        ];

        foreach ($statuts as $statut) {
            Statut::updateOrCreate(['id' => $statut['id']], $statut);
        }
    }
}
