<?php

use App\Admin;
use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate([
            'name' => 'super-admin'
        ]);

        $user = Admin::where(['email' => 'paxeldp@gmail.com'])->first();

        if($user == null)
        {
            $user = Admin::create([
                'name' => 'Axel Djaha',
                'email' => 'paxeldp@gmail.com',
                'password' => bcrypt("paxeldp@gmail.com"),
            ]);
        }

        $user->assignRole(Role::first());
    }
}
