<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si le rôle admin existe, sinon le créer
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Créer l'utilisateur admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@socialbook.net'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('Admin123!'), // Changez ce mot de passe
                'email_verified_at' => now(),
            ]
        );

        // Assigner le rôle admin
        $adminUser->assignRole('admin');

        $this->command->info('Utilisateur admin créé avec succès!');
        $this->command->info('Email: admin@socialbook.net');
        $this->command->info('Mot de passe: Admin123!'); // À changer après la première connexion
    }
}
