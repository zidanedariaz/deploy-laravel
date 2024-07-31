<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner_role = Role::where('name', 'owner')->first();
        $user_role = Role::where('name', 'user')->first();

        User::create([
            'id' => Str::uuid(),
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('nnnnnnnn'),
            'role_id' => $owner_role->id,
        ]);
        
        User::create([
            'id' => Str::uuid(),
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('nnnnnnnn'),
            'role_id' => $user_role->id,
        ]);
    }
}
