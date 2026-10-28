<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\CallCenter;
use App\Models\User;
use Illuminate\Database\Seeder;

class CallCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::first();
        $deco = User::where('email', 'deco@gmail.com')->first();
        $cristian = User::where('email', 'cristian@gmail.com')->first();
        $daffa = User::where('email', 'daffa@gmail.com')->first();

        if (! $deco && ! $cristian) {
            $deco = User::first();
        }

        if ($deco) {
            CallCenter::create([
                'user_id' => $deco->id,
                'admin_id' => $admin?->id,
                'nama' => $deco->nama,
                'email' => $deco->email,
                'nomor_telepon' => '081234567891',
                'kategori' => 'pertanyaan',
                'prioritas' => 'sedang',
                'subjek' => 'Pertanyaan Mengenai Perhitungan Efisiensi Panel Surya Pada Modul 2',
                'pesan' => "Halo Admin SDG7Learn,\n\nSaya sedang mempelajari modul energi surya fotovoltaik. Pada pembahasan perhitungan efisiensi sel surya polikristalin, apakah faktor suhu lingkungan di Indonesia (iklim tropis) sudah diperhitungkan dalam rumus standar STC (Standard Test Conditions)? Mohon penjelasannya.\n\nTerima kasih.",
                'status' => 'selesai',
                'tanggapan' => "Halo Sdr. Deco Prasetya,\n\nTerima kasih telah menghubungi Call Center SDG7Learn.\nStandar STC dihitung pada temperatur sel 25°C dan radiasi 1000 W/m². Di iklim tropis seperti Indonesia, suhu sel di lapangan umumnya mencapai 45°C - 60°C sehingga terjadi penurunan efisiensi sekitar 0.4% per kenaikan 1°C (koefisien suhu). Anda dapat merujuk ke modul pelengkap pada artikel Bagian 3 untuk formula koreksi temperatur NOCT.\n\nSalam,\nTim Administrator & Akademik SDG7Learn",
                'ditanggapi_pada' => now()->subDay(),
            ]);

            CallCenter::create([
                'user_id' => $deco->id,
                'admin_id' => null,
                'nama' => $deco->nama,
                'email' => $deco->email,
                'nomor_telepon' => '081234567891',
                'kategori' => 'masalah_teknis',
                'prioritas' => 'tinggi',
                'subjek' => 'Riwayat Skor Kuis Terkadang Perlu Refresh Ulang',
                'pesan' => 'Selamat pagi Admin, saat saya baru saja menyelesaikan kuis dan dialihkan ke riwayat hasil belajar, nilai kuis terbaru saya baru tampil setelah saya menekan reload 2 kali di browser Chrome Android. Mohon bantuannya untuk diperiksa.',
                'status' => 'menunggu',
                'tanggapan' => null,
                'ditanggapi_pada' => null,
            ]);
        }

        if ($cristian) {
            CallCenter::create([
                'user_id' => $cristian->id,
                'admin_id' => $admin?->id,
                'nama' => $cristian->nama,
                'email' => $cristian->email,
                'nomor_telepon' => '085712345678',
                'kategori' => 'kendala_kuis',
                'prioritas' => 'darurat',
                'subjek' => 'Kendala Sesi Pengerjaan Kuis Terputus Saat Gangguan Koneksi',
                'pesan' => 'Halo tim pengelola, tadi malam saya sedang mengerjakan kuis evaluasi energi bayu. Tiba-tiba koneksi wifi saya sempat terputus sekitar 1 menit dan ketika terhubung kembali waktu pengerjaan langsung habis dan skor otomatis tersimpan 0. Apakah saya bisa mendapatkan kesempatan ujian ulang?',
                'status' => 'diproses',
                'tanggapan' => 'Halo Cristian, laporan Anda telah diterima dan saat ini sedang kami koordinasikan dengan admin database untuk reset satu kesempatan ujian kuis terkait. Mohon ditunggu dalam 1x24 jam.',
                'ditanggapi_pada' => now()->subHours(2),
            ]);
        }

        if ($daffa) {
            CallCenter::create([
                'user_id' => $daffa->id,
                'admin_id' => null,
                'nama' => $daffa->nama,
                'email' => $daffa->email,
                'nomor_telepon' => '089611223344',
                'kategori' => 'saran',
                'prioritas' => 'rendah',
                'subjek' => 'Usulan Penambahan Studi Kasus PLTS Terapung Cirata Jawa Barat',
                'pesan' => 'Usul untuk tim penyusun materi edukasi SDG7Learn: alangkah baiknya jika ada artikel atau kuis khusus yang membahas PLTS Terapung Cirata sebagai salah satu proyek transisi energi bersih terbesar di Asia Tenggara. Pasti sangat menginspirasi mahasiswa.',
                'status' => 'menunggu',
                'tanggapan' => null,
                'ditanggapi_pada' => null,
            ]);
        }
    }
}
