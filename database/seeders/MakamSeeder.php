<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Makam;
use App\Models\BlokMakam;
use Carbon\Carbon;
use Faker\Factory as Faker;

class MakamSeeder extends Seeder
{
    /** Jumlah makam per blok (nama_blok harus match BlokMakamSeeder) */
    private array $config = [
        'Blok A1' => 12,
        'Blok A2' => 7,
        'Blok A3' => 3,
        'Blok A4' => 0,
        'Blok A5' => 0,
        'Blok B1' => 10,
        'Blok B2' => 6,
        'Blok B3' => 2,
        'Blok B4' => 0,
        'Blok B5' => 0,
        'Blok C1' => 15,
        'Blok C2' => 5,
        'Blok C3' => 1,
        'Blok C4' => 0,
        'Blok C5' => 0,
        'Blok D1' => 8,
        'Blok D2' => 4,
        'Blok D3' => 0,
        'Blok D4' => 11,
        'Blok D5' => 0,
        'Blok E1' => 10,
        'Blok E2' => 6,
        'Blok E3' => 2,
        'Blok E4' => 0,
        'Blok E5' => 15,
    ];

    /** Sekitar 15% makam "tidak dikenali" untuk testing */
    private float $rasioTidakDikenali = 0.15;

    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $namaLaki = [
            'Ahmad', 'Muhammad', 'Abdullah', 'Hasan', 'Husain', 'Ali', 'Umar', 'Usman',
            'Ibrahim', 'Ismail', 'Yusuf', 'Yakub', 'Daud', 'Sulaiman', 'Harun', 'Musa',
            'Mahmud', 'Ridwan', 'Hamzah', 'Bilal', 'Zainal', 'Fadil', 'Rizki', 'Arif',
            'Budi', 'Surya', 'Dedi', 'Eko', 'Fajar', 'Gunawan', 'Hadi', 'Indra',
        ];

        $namaPerempuan = [
            'Siti', 'Aminah', 'Fatimah', 'Khadijah', 'Aisyah', 'Zainab', 'Mariam', 'Sarah',
            'Hajar', 'Hawa', 'Laila', 'Nur', 'Rahma', 'Salsabila', 'Yasmin', 'Zahra',
            'Dewi', 'Sari', 'Indah', 'Rina', 'Lina', 'Sinta', 'Diana', 'Putri',
            'Kartika', 'Melati', 'Nurul', 'Wati', 'Yuni', 'Zulaikha',
        ];

        $namaBelakang = ['Sulaiman', 'Rahman', 'Hakim', 'Santoso', 'Wijaya', 'Prasetyo', 'Sari', 'Lestari'];
        $namaAyah = [
            'Abdullah', 'Ibrahim', 'Yusuf', 'Ahmad', 'Muhammad', 'Ali', 'Umar', 'Usman',
            'Hasan', 'Husain', 'Ridwan', 'Hamzah', 'Bilal', 'Sulaiman', 'Harun', 'Musa',
            'Mahmud', 'Zainal', 'Fadil', 'Rizki', 'Arif', 'Budi', 'Surya', 'Dedi',
            'Eko', 'Fajar', 'Gunawan', 'Hadi', 'Indra', 'Joko', 'Karno', 'Lukman',
        ];

        $bloks = BlokMakam::orderBy('nama_blok')->get()->keyBy('nama_blok');
        $totalTerdata = 0;
        $totalTidakDikenali = 0;

        foreach ($this->config as $namaBlok => $jumlah) {
            $blok = $bloks->get($namaBlok);
            if (!$blok) {
                $this->command->warn("Blok tidak ditemukan: {$namaBlok}. Jalankan BlokMakamSeeder dulu.");
                continue;
            }

            for ($i = 1; $i <= $jumlah; $i++) {
                $dikenali = $faker->boolean(100 - (int)($this->rasioTidakDikenali * 100));
                if ($dikenali) {
                    $totalTerdata++;
                } else {
                    $totalTidakDikenali++;
                }

                $jenisKelamin = $dikenali
                    ? $faker->randomElement(['laki-laki', 'perempuan'])
                    : $faker->randomElement(['laki-laki', 'perempuan', 'tidak-diketahui']);

                if ($dikenali) {
                    $namaLengkap = $jenisKelamin === 'laki-laki'
                        ? $faker->randomElement($namaLaki)
                        : $faker->randomElement($namaPerempuan);
                    if ($faker->boolean(70)) {
                        $namaLengkap .= ' ' . $faker->randomElement($namaBelakang);
                    }
                    $namaAyahValue = $faker->randomElement($namaAyah);
                } else {
                    $namaLengkap = $faker->optional(0.7)->passthrough($faker->randomElement($namaLaki) . ' / ' . $faker->randomElement($namaPerempuan)) ?? 'Tidak diketahui';
                    $namaAyahValue = $faker->optional(0.5)->passthrough($faker->randomElement($namaAyah)) ?? null;
                }

                $tahunLahir = $faker->numberBetween(1940, 1980);
                $tanggalLahir = Carbon::create($tahunLahir, $faker->numberBetween(1, 12), $faker->numberBetween(1, 28));
                $tahunWafat = $faker->numberBetween(max(2010, $tahunLahir + 30), (int) date('Y'));
                $tanggalWafat = Carbon::create($tahunWafat, $faker->numberBetween(1, 12), $faker->numberBetween(1, 28));
                if ($tanggalWafat->lte($tanggalLahir)) {
                    $tanggalWafat = $tanggalLahir->copy()->addYears($faker->numberBetween(30, 85));
                }

                $prefixBlok = preg_replace('/^Blok\s+/', '', $namaBlok);
                $nomorMakam = $prefixBlok . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

                $catatan = null;
                if ($faker->boolean(30)) {
                    $catatan = $faker->randomElement([
                        'Lokasi sebenarnya di sebelah kanan ' . $prefixBlok . '-' . str_pad(max(1, $i - 1), 3, '0', STR_PAD_LEFT),
                        'Makam berada di samping ' . $prefixBlok . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                        'Berada di area khusus',
                        'Dekat dengan jalan utama',
                    ]);
                }

                Makam::create([
                    'nama_lengkap' => $namaLengkap,
                    'dikenali' => $dikenali,
                    'jenis_kelamin' => $jenisKelamin,
                    'nama_ayah' => $namaAyahValue,
                    'tanggal_lahir' => $tanggalLahir,
                    'tanggal_wafat' => $tanggalWafat,
                    'blok_id' => $blok->id,
                    'nomor_makam' => $nomorMakam,
                    'catatan' => $catatan,
                    'ahli_waris' => $dikenali ? $faker->name() : ($faker->boolean(40) ? $faker->name() : null),
                    'telepon_ahli_waris' => $dikenali ? '08' . $faker->numerify('##########') : ($faker->boolean(30) ? '08' . $faker->numerify('##########') : null),
                    'keterangan' => $faker->boolean(20) ? $faker->randomElement(['Tokoh masyarakat', 'Alim ulama', 'Pendiri desa']) : null,
                ]);
            }
        }

        $this->command->info('MakamSeeder selesai.');
        $this->command->info('Terdata (dikenali): ' . $totalTerdata . ', Tidak dikenali: ' . $totalTidakDikenali);
    }
}
