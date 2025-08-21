<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
            EglisesSeeder::class,
            DepartmentsSeeder::class,
            TribusSeeder::class,
            UsersSeeder::class,
            AnnoncesSeeder::class,
            EventsSeeder::class,
            SettingsSeeder::class,
            VisitesSeeder::class,
            NotificationsSeeder::class,
            PhotoSeeder::class,
        ]);
    }
}
