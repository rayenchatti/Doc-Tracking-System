<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Système',
                'telephone' => '71000000',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'statut' => 'actif',
            ]
        );

        User::updateOrCreate(
            ['email' => 'agent@gmail.com'],
            [
                'nom' => 'Agent',
                'prenom' => 'Test',
                'telephone' => '71000001',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'statut' => 'actif',
            ]
        );
    }
}
