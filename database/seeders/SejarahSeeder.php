<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sejarah;

class SejarahSeeder extends Seeder
{
    public function run(): void
    {
        Sejarah::create([
            'konten' => 'Sejarah Desa Ngadirejo dan keberadaan makam-makam tua di wilayah ini tidak terlepas dari kisah masa lalu yang diwariskan secara turun-temurun. Berdasarkan hasil wawancara dengan Nyoto Suwarno (lahir 28 Mei 1937), tokoh tertua Desa Ngadirejo, desa ini dipercaya sudah ada sejak abad ke-16, pada masa Aryo Penangsang dan Sunan Kudus. Pada masa tersebut juga terjadi proses mbabat tanah Mentawang yang dipimpin oleh tokoh-tokoh penting, salah satunya Danang Sutowijo (Joko Tingkir).

Nama Ngadirejo diyakini berasal dari ungkapan "ben ngadi lan rejo", yang bermakna harapan agar desa ini selalu hidup, makmur, dan sejahtera.

Di Desa Ngadirejo terdapat beberapa makam bersejarah yang hingga kini masih dijaga keberadaannya, antara lain Makam Ngadisimo, Makam Pende Setono, serta Makam Al-Huda yang khusus diperuntukkan bagi para kiai. Selain itu, terdapat pula Makam Raden Kusumo, tokoh yang dipercaya sebagai pembabat wilayah Ngadisimo. Makam-makam ini menjadi bagian penting dari identitas sejarah desa dan masih sering diziarahi masyarakat, terutama pada waktu-waktu tertentu seperti malam Jumat Legi yang biasanya diisi dengan kegiatan tahlilan.

Tradisi dan kepercayaan masyarakat Ngadirejo juga masih dijaga hingga sekarang. Salah satu tradisi adat yang rutin dilakukan adalah Bersih Desa setiap Selasa Kliwon, di mana masyarakat membawa berkat sesuai kemampuan masing-masing. Selain itu, masyarakat mempercayai adanya pantangan untuk mengadakan acara besar pada hari Pahing, seperti pindah rumah atau hajatan, karena diyakini dapat membawa kesialan, misalnya usaha bangkrut, keluarga tidak langgeng, hingga kematian.

Narasumber berharap agar makam-makam bersejarah di Ngadirejo terus dirawat dan dilestarikan. Hal ini penting mengingat pernah terjadi penggusuran pada tahun 1980 akibat pelebaran jalan, sehingga kejadian serupa tidak terulang kembali.',
            'narasumber_nama' => 'Nyoto Suwarno',
            'narasumber_lahir' => '28 Mei 1937',
            'narasumber_jabatan' => 'Tokoh Tertua Desa Ngadirejo',
            'aktif' => true,
        ]);
    }
}
