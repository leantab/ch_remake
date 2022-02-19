<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\CommunityUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        if (!file_exists(storage_path('app/logos'))) {
            File::makeDirectory(storage_path('app/logos'));
        }

        CommunityRole::create([
            'name' => 'admin',
        ]);

        $admin = User::create([
                'name' => 'Admin',
                'lastname' => 'CH',
                'email' => 'admin@companyhike.com',
                'password' => Hash::make('companyhike'),
                'ch_admin' => true,
            ]);

        $community1 = Community::create([
            'name' => 'TESTCom'
        ]);
        File::copy(base_path('resources/img/default.jpg'), storage_path('app/logos/1.jpg'));

        $admin->makeCommunityAdmin($community1);

        $community = Community::create([
            'name' => 'Demo'
        ]);
        File::copy(base_path('resources/img/default.jpg'), storage_path('app/logos/2.jpg'));

        for ($i = 1; $i < 10; $i++) {
            $user = User::create([
                'name' => 'User',
                'lastname' => $i,
                'email' => 'user_' . $i . '@companyhike.com',
                'password' => Hash::make('companyhike'),
                'ch_admin' => false,
            ]);

            // TODO: Crear comuinidad "Demo", Hacer admin de la comunidad a user1, los demas usuarios solo miembros de la comuunidad
            if ($i == 1) {
                $user->makeCommunityAdmin($community);
            } else {
                $user->allowAccessToCommunity($community);
            }
        }
    }
}
