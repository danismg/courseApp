<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // membtuat beberapa role : admin, teacher, student
        // membuat defaukt user untuk super admin

        $ownerRole = Role::create(['name' => 'owner']);

        $adminRole = Role::create(['name' => 'student']);

        $teacherRole = Role::create(['name' => 'teacher']);

        //akun super admin
        $userOwner = User::create([
            'name' => 'Daniel',
            'occupation' => 'Educator',
            'avatar' => 'images/default-avatar.png',
            'email' => 'daniel@gmail.com',
            'password' => bcrypt('123123123')
        ]);

        $userOwner->assignRole($ownerRole);
    }
}
