<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlokMakam;

class BlokMakamSeeder extends Seeder
{
    public function run(): void
    {
        $bloks = [
            // Blok A (4 sub-blok)
            ['nama_blok' => 'Blok A1', 'keterangan' => 'Blok A - Sub Blok 1'],
            ['nama_blok' => 'Blok A2', 'keterangan' => 'Blok A - Sub Blok 2'],
            ['nama_blok' => 'Blok A3', 'keterangan' => 'Blok A - Sub Blok 3'],
            ['nama_blok' => 'Blok A4', 'keterangan' => 'Blok A - Sub Blok 4'],
            ['nama_blok' => 'Blok A5', 'keterangan' => 'Blok A - Sub Blok 5'],
            
            // Blok B (4 sub-blok)
            ['nama_blok' => 'Blok B1', 'keterangan' => 'Blok B - Sub Blok 1'],
            ['nama_blok' => 'Blok B2', 'keterangan' => 'Blok B - Sub Blok 2'],
            ['nama_blok' => 'Blok B3', 'keterangan' => 'Blok B - Sub Blok 3'],
            ['nama_blok' => 'Blok B4', 'keterangan' => 'Blok B - Sub Blok 4'],
            ['nama_blok' => 'Blok B5', 'keterangan' => 'Blok B - Sub Blok 5'],
            
            // Blok C (4 sub-blok)
            ['nama_blok' => 'Blok C1', 'keterangan' => 'Blok C - Sub Blok 1'],
            ['nama_blok' => 'Blok C2', 'keterangan' => 'Blok C - Sub Blok 2'],
            ['nama_blok' => 'Blok C3', 'keterangan' => 'Blok C - Sub Blok 3'],
            ['nama_blok' => 'Blok C4', 'keterangan' => 'Blok C - Sub Blok 4'],
            ['nama_blok' => 'Blok C5', 'keterangan' => 'Blok C - Sub Blok 5'],
            // Blok D (4 sub-blok) + Pendopo (blok biasa, makam tertentu)
            ['nama_blok' => 'Blok D1', 'keterangan' => 'Blok D - Sub Blok 1'],
            ['nama_blok' => 'Blok D2', 'keterangan' => 'Blok D - Sub Blok 2'],
            ['nama_blok' => 'Blok D3', 'keterangan' => 'Blok D - Sub Blok 3'],
            ['nama_blok' => 'Blok Pendopo', 'keterangan' => 'Pendopo (blok biasa, makam tertentu)'],
            ['nama_blok' => 'Blok D4', 'keterangan' => 'Blok D - Sub Blok 4'],
            ['nama_blok' => 'Blok D5', 'keterangan' => 'Blok D - Sub Blok 5'],
            // Blok E (4 sub-blok)
            ['nama_blok' => 'Blok E1', 'keterangan' => 'Blok E - Sub Blok 1'],
            ['nama_blok' => 'Blok E2', 'keterangan' => 'Blok E - Sub Blok 2'],
            ['nama_blok' => 'Blok E3', 'keterangan' => 'Blok E - Sub Blok 3'],
            ['nama_blok' => 'Blok E4', 'keterangan' => 'Blok E - Sub Blok 4'],
            ['nama_blok' => 'Blok E5', 'keterangan' => 'Blok E - Sub Blok 5'],
        ];

        foreach ($bloks as $blok) {
            BlokMakam::create($blok);
        }
    }
}
