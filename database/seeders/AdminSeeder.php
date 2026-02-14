<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Administrator',
            'email' => 'admins@makam.com',
            'role' => 'superadmin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok A1',
            'email' => 'adminbloka1@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok A2',
            'email' => 'adminbloka2@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok A3',
            'email' => 'adminbloka3@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok A4',
            'email' => 'adminbloka4@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok B1',
            'email' => 'adminblokb1@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok B2',
            'email' => 'adminblokb2@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok B3',
            'email' => 'adminblokb3@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok B4',
            'email' => 'adminblokb4@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok C1',
            'email' => 'adminblokc1@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok C2',
            'email' => 'adminblokc2@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok C3',
            'email' => 'adminblokc3@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok C4',
            'email' => 'adminblokc4@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok D1',
            'email' => 'adminblokd1@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok D2',
            'email' => 'adminblokd2@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok D3',
            'email' => 'adminblokd3@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok D4',
            'email' => 'adminblokd4@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok E1',
            'email' => 'adminbloke1@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok E2',
            'email' => 'adminbloke2@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok E3',
            'email' => 'adminbloke3@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok E4',
            'email' => 'adminbloke4@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        Admin::create([
            'name' => 'Admin Blok E5',
            'email' => 'adminbloke5@makam.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
    }
}
