<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte administrateur par défaut
        User::updateOrCreate(
            ['email' => 'admin@livraben.test'],
            [
                'name' => 'Administrateur',
                'phone' => '9700000000',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Un client de démo
        User::updateOrCreate(
            ['email' => 'client@livraben.test'],
            [
                'name' => 'Client Démo',
                'phone' => '9700000001',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        // Un livreur de démo
        User::updateOrCreate(
            ['email' => 'livreur@livraben.test'],
            [
                'name' => 'Livreur Démo',
                'phone' => '9700000002',
                'password' => Hash::make('password'),
                'role' => 'livreur',
                'vehicle_type' => 'moto',
                'is_online' => true,
                'current_lat' => 6.3703,
                'current_lng' => 2.3912,
            ]
        );
    }
}
