<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StatutSeeder::class,
            AdminUserSeeder::class,
            ServiceSeeder::class,
            TypeDocumentSeeder::class,
            // Doit rester en dernier : depend des statuts, services, types
            // et comptes crees au-dessus.
            DocumentSeeder::class,
        ]);
    }
}
