<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Usuarios de prueba (contraseña: "password"):
     *  - admin@example.com  → administrador
     *  - user@example.com   → usuario normal
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Usuario Demo',
            'email' => 'user@example.com',
        ]);

        Producto::factory(15)->for($admin)->create();
        Producto::factory(5)->for($user)->create();

        User::factory(8)->create();
    }
}
