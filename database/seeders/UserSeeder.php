<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si le rôle "user" existe
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Premier utilisateur normal
        $normalUser1 = User::firstOrCreate(
            ['email' => 'user@socialbook.net'],
            [
                'name' => 'Utilisateur Standard',
                'password' => Hash::make('User123!'),
                'email_verified_at' => now(),
            ]
        );
        $normalUser1->assignRole('user');

        // Deuxième utilisateur normal
        $normalUser2 = User::firstOrCreate(
            ['email' => 'john.doe@socialbook.net'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('John123!'),
                'email_verified_at' => now(),
            ]
        );
        $normalUser2->assignRole('user');

        $this->command->info('Deux utilisateurs standards créés avec succès !');
        $this->command->info('1️⃣ Email: user@socialbook.net | MDP: User123!');
        $this->command->info('2️⃣ Email: john.doe@socialbook.net | MDP: John123!');
    }
}
