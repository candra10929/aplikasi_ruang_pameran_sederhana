<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Mengisi database dengan data awal KitaPamer (diambil dari dump ruang_pameran.sql):
 * kategori, akun, karya, kolaborator, komentar, like, dan log aktivitas.
 *
 * ID asli dipertahankan supaya relasi antar tabel tetap benar. Memakai insertOrIgnore,
 * jadi aman dijalankan lagi (baris yang sudah ada dilewati, tidak diduplikasi).
 *
 *   php artisan migrate --seed          # database baru
 *   php artisan db:seed                 # database sudah dimigrasi
 *
 * Login contoh (password HANYA untuk demo/pengembangan, jangan dipakai di server sungguhan):
 *   admin / admin123
 *   tamu, diva, zakky, ayunda, farras, candra, zahra / password
 *
 * Catatan: file gambar karya ada di public/uploads/ (tidak ikut git). Karya yang
 * file-nya tidak ada akan tampil dengan placeholder.
 */
class RuangPameranSeeder extends Seeder
{
    /** Urutan penting: tabel induk dulu supaya foreign key terpenuhi. */
    private const KOLOM = [
        'kategori' => ['id_kategori', 'jenis_kategori'],
        'akun' => ['id_akun', 'name', 'username', 'password', 'level', 'bio', 'jurusan', 'program_studi', 'foto_profil', 'tanggal_daftar'],
        'karya' => ['id_karya', 'id_akun', 'id_kategori', 'judul', 'deskripsi', 'tools', 'file_karya', 'tanggal_upload', 'thumbnail'],
        'kolaborator' => ['id_karya', 'id_akun'],
        'komentar' => ['id_komentar', 'id_akun', 'id_karya', 'isi_komentar', 'tanggal_komentar'],
        'like_karya' => ['id_like', 'id_akun', 'id_karya', 'tanggal_like'],
        'log_aktivitas' => ['id_log', 'id_akun', 'id_karya', 'aksi', 'keterangan', 'waktu'],
    ];

    public function run(): void
    {
        foreach (self::KOLOM as $tabel => $kolom) {
            $baris = array_map(
                fn (array $nilai) => array_combine($kolom, $nilai),
                $this->{'data'.str_replace('_', '', ucwords($tabel, '_'))}()
            );

            foreach (array_chunk($baris, 100) as $potongan) {
                DB::table($tabel)->insertOrIgnore($potongan);
            }

            $this->command?->info("{$tabel}: ".count($baris).' baris');
        }
    }

    /** kategori: 13 baris */
    private function dataKategori(): array
    {
        return [
            // id_kategori, jenis_kategori
            [4, '3D'],
            [5, 'Animasi'],
            [11, 'Digital'],
            [2, 'Foto'],
            [1, 'Gambar/Ilustrasi'],
            [8, 'Logo'],
            [6, 'Poster'],
            [9, 'Typography'],
            [7, 'UI/UX'],
            [3, 'Video'],
            [10, 'Web'],
        ];
    }

    /** akun: 8 baris */
    private function dataAkun(): array
    {
        return [
            // id_akun, name, username, password, level, bio, jurusan, program_studi, foto_profil, tanggal_daftar
            [2, 'Admin KitaPamer', 'admin', '$2y$12$ym0JE/CMrPyWADKC9uwzVOqKYM0lgHW9F9FcbhqyV45TB68OIUUzO', 'admin', null, null, null, null, '2026-10-25'],
            [3, 'Tamu', 'tamu', '$2y$12$re0P9.B07Icu0xR8pI8eB.sWsVGUjvvbZO6f/8i9iRffkLmh.cTT2', 'user', null, null, null, null, '2026-10-25'],
            [4, 'Diva Maulana', 'diva', '$2y$12$.jf7znx5yqwMfXbURPc1jebGIa5ci7vvj1QEjws3ZJk1a0JA/9vP.', 'mahasiswa', 'Learning, creating, and growing through every experience.', 'Teknik Informatika', 'Rekayasa Perangkat Lunak', null, '2026-10-25'],
            [5, 'Zakky Mubarraq', 'zakky', '$2y$12$fWr1.8yXRJ43QqAZGcCzp.uPEHY13GMl5u4zc86YjnGoVaqjFdcz6', 'mahasiswa', 'Learning, creating, and growing through every experience.', 'Teknik Informatika', 'Rekayasa Perangkat Lunak', null, '2026-10-25'],
            [6, 'Ayunda Tiara R.', 'ayunda', '$2y$12$mq3fDoCo43uVtxMGMnl5C.Pq4aw2rBK7pXRgUy8BHxdgCYsrNztK2', 'mahasiswa', 'Learning, creating, and growing through every experience.', 'Teknik Informatika', 'Rekayasa Perangkat Lunak', null, '2026-10-25'],
            [7, 'Farras Radithya', 'farras', '$2y$12$k5Caz6Ux0fOMN0JDnMr8k.TDIWvgx4Pp8LOSK1IpTINnO8snuYtBa', 'mahasiswa', 'Learning, creating, and growing through every experience.', 'Teknik Informatika', 'Rekayasa Perangkat Lunak', null, '2026-10-25'],
            [8, 'Candra Winata', 'candra', '$2y$12$ID/ussgxCuu0xWD5PwKF1e3bmF.HpzY1GWPQ3suq7uczyg9Zw6Wba', 'mahasiswa', 'Learning, creating, and growing through every experience.', 'Teknik Informatika', 'Rekayasa Perangkat Lunak', null, '2026-10-25'],
            [11, 'E. Zahra', 'zahra', '$2y$12$A9EuvXbUx/JCl3OHBC0j6uzByzTRCUQ66ZvBgwxQIMAHFjma9WRHG', 'mahasiswa', null, 'Teknik Informatika', 'Rekayasa Perangkat Lunak', null, '2026-10-25'],
        ];
    }

    /** karya: 14 baris */
    private function dataKarya(): array
    {
        return [
            // id_karya, id_akun, id_kategori, judul, deskripsi, tools, file_karya, tanggal_upload, thumbnail
            [1, 5, 1, 'Echoes of Lights', 'The light that shines on dreams', 'Procreate', 'uploads/karya/demo-1.jpg', '2026-10-25', null],
            [2, 4, 1, 'Among the Flowers', 'Soothing natural beauty', 'Photoshop', 'uploads/karya/demo-2.jpg', '2026-10-25', null],
            [4, 5, 1, 'Pinky Ribbon Cat', 'Pinky cat for pinky boy.', 'Illustrator', 'uploads/karya/demo-4.jpg', '2026-10-25', null],
            [5, 4, 1, 'Dirty Mirror', 'Dirtiest side of person', 'Photoshop', 'uploads/karya/demo-5.jpg', '2026-10-25', null],
            [6, 6, 1, 'The Swing', 'Beauty on the swing', 'Procreate', 'uploads/karya/demo-6.jpg', '2026-10-25', null],
            [7, 5, 2, 'The Mountain', 'Highest thing and beauty at the same time', 'Lightroom', 'uploads/karya/demo-7.jpg', '2026-10-25', null],
            [8, 4, 2, 'The Journey', 'Flowing adventure', 'Lightroom', 'uploads/karya/demo-8.jpg', '2026-10-25', null],
            [9, 6, 2, 'Framing Beauty', 'Beauty memorable per frame', 'Lightroom', 'uploads/karya/demo-9.jpg', '2026-10-25', null],
            [10, 7, 3, 'Traces by the Lake', 'Mysterious traces was found by the lake', 'Premiere Pro', 'uploads/karya/demo-10.mp4', '2026-10-25', null],
            [11, 4, 13, 'Kucing Beyblade', 'Kucingnya bisa mutar-mutar like a beyblade.', 'Photoshop, Figma', 'uploads/karya/demo-11.jpg', '2026-10-25', null],
            [12, 4, 6, 'Poster B', 'Poster kegiatan kampus', 'Illustrator', 'uploads/karya/demo-12.jpg', '2026-10-25', null],
            [13, 4, 6, 'Poster C', 'Poster seminar teknologi', 'Illustrator', 'uploads/karya/demo-13.jpg', '2026-10-25', null],
            [14, 4, 6, 'Poster D', 'Poster lomba desain', 'Illustrator', 'uploads/karya/demo-14.jpg', '2026-10-25', null],
            [15, 5, 10, 'drawio', 'testing drawio', 'drawio', 'uploads/karya/94ea53b8-8a6b-4290-a484-9347106b22ee.png', '2026-10-25', 'uploads/thumbnail/9a52e7a9-5543-4537-bee9-f9ae68f0d74d.png'],
        ];
    }

    /** kolaborator: 2 baris */
    private function dataKolaborator(): array
    {
        return [
            // id_karya, id_akun
            [11, 5],
            [15, 4],
        ];
    }

    /** komentar: 19 baris */
    private function dataKomentar(): array
    {
        return [
            // id_komentar, id_akun, id_karya, isi_komentar, tanggal_komentar
            [1, 4, 1, 'Wow keren banget sih ini!!!', '2026-10-25'],
            [2, 3, 1, 'Sebuah mahakarya', '2026-10-25'],
            [6, 6, 4, 'Sebuah mahakarya', '2026-10-25'],
            [7, 3, 4, 'Suka banget sama komposisinya', '2026-10-25'],
            [8, 4, 4, 'Suka banget sama komposisinya', '2026-10-25'],
            [9, 3, 5, 'Detailnya rapi, mantap!', '2026-10-25'],
            [10, 5, 5, 'ini sih gila!! tetap konsisten bung', '2026-10-25'],
            [11, 6, 8, 'Sebuah mahakarya', '2026-10-25'],
            [12, 8, 8, 'Detailnya rapi, mantap!', '2026-10-25'],
            [13, 7, 9, 'ini sih gila!! tetap konsisten bung', '2026-10-25'],
            [14, 3, 10, 'Wow keren banget sih ini!!!', '2026-10-25'],
            [15, 8, 10, 'Wow keren banget sih ini!!!', '2026-10-25'],
            [16, 3, 11, 'ini sih gila!! tetap konsisten bung', '2026-10-25'],
            [17, 2, 11, 'Detailnya rapi, mantap!', '2026-10-25'],
            [18, 7, 13, 'Sebuah mahakarya', '2026-10-25'],
            [19, 5, 13, 'Detailnya rapi, mantap!', '2026-10-25'],
            [20, 3, 13, 'Suka banget sama komposisinya', '2026-10-25'],
            [21, 6, 14, 'Wow keren banget sih ini!!!', '2026-10-25'],
            [22, 7, 14, 'Suka banget sama komposisinya', '2026-10-25'],
        ];
    }

    /** like_karya: 33 baris */
    private function dataLikeKarya(): array
    {
        return [
            // id_like, id_akun, id_karya, tanggal_like
            [1, 7, 2, '2026-10-25'],
            [2, 6, 2, '2026-10-25'],
            [9, 7, 4, '2026-10-25'],
            [10, 3, 4, '2026-10-25'],
            [11, 8, 4, '2026-10-25'],
            [12, 6, 4, '2026-10-25'],
            [13, 2, 4, '2026-10-25'],
            [14, 4, 4, '2026-10-25'],
            [15, 2, 5, '2026-10-25'],
            [16, 5, 5, '2026-10-25'],
            [17, 3, 5, '2026-10-25'],
            [18, 8, 5, '2026-10-25'],
            [19, 7, 5, '2026-10-25'],
            [20, 4, 5, '2026-10-25'],
            [21, 2, 6, '2026-10-25'],
            [22, 3, 6, '2026-10-25'],
            [23, 4, 6, '2026-10-25'],
            [24, 7, 6, '2026-10-25'],
            [25, 2, 7, '2026-10-25'],
            [26, 7, 7, '2026-10-25'],
            [27, 8, 8, '2026-10-25'],
            [28, 6, 8, '2026-10-25'],
            [29, 4, 8, '2026-10-25'],
            [30, 3, 9, '2026-10-25'],
            [31, 7, 9, '2026-10-25'],
            [32, 8, 9, '2026-10-25'],
            [33, 2, 9, '2026-10-25'],
            [34, 6, 9, '2026-10-25'],
            [35, 3, 13, '2026-10-25'],
            [36, 5, 13, '2026-10-25'],
            [37, 7, 13, '2026-10-25'],
            [39, 8, 14, '2026-10-25'],
            [41, 5, 15, '2026-10-25'],
        ];
    }

    /** log_aktivitas: 28 baris */
    private function dataLogAktivitas(): array
    {
        return [
            // id_log, id_akun, id_karya, aksi, keterangan, waktu
            [1, null, null, 'Daftar', 'Membuat akun baru', '2026-10-25'],
            [2, 5, 1, 'Upload', 'Mengunggah karya “Echoes of Lights”', '2026-10-25'],
            [3, 4, 2, 'Upload', 'Mengunggah karya “Among the Flowers”', '2026-10-25'],
            [4, 6, null, 'Upload', 'Mengunggah karya “A Moment in Focus”', '2026-10-25'],
            [5, 5, 4, 'Upload', 'Mengunggah karya “Pinky Ribbon Cat”', '2026-10-25'],
            [6, 4, 5, 'Upload', 'Mengunggah karya “Dirty Mirror”', '2026-10-25'],
            [7, 6, 6, 'Upload', 'Mengunggah karya “The Swing”', '2026-10-25'],
            [8, 5, 7, 'Upload', 'Mengunggah karya “The Mountain”', '2026-10-25'],
            [9, 4, 8, 'Upload', 'Mengunggah karya “The Journey”', '2026-10-25'],
            [10, 6, 9, 'Upload', 'Mengunggah karya “Framing Beauty”', '2026-10-25'],
            [11, 7, 10, 'Upload', 'Mengunggah karya “Traces by the Lake”', '2026-10-25'],
            [12, 4, 11, 'Upload', 'Mengunggah karya “Kucing Beyblade”', '2026-10-25'],
            [13, 4, 12, 'Upload', 'Mengunggah karya “Poster B”', '2026-10-25'],
            [14, 4, 13, 'Upload', 'Mengunggah karya “Poster C”', '2026-10-25'],
            [15, 4, 14, 'Upload', 'Mengunggah karya “Poster D”', '2026-10-25'],
            [16, 4, 14, 'Like', 'Menyukai karya “Poster D”', '2026-10-25'],
            [17, null, null, 'Daftar', 'Membuat akun baru', '2026-10-25'],
            [18, 5, 15, 'Upload', 'Mengunggah karya “drawio”', '2026-10-25'],
            [19, 5, 15, 'Komentar', 'Mengomentari karya “drawio”', '2026-10-25'],
            [20, 5, 15, 'Like', 'Menyukai karya “drawio”', '2026-10-25'],
            [21, 2, null, 'Hapus', 'Menghapus karya “A Moment in Focus”', '2026-10-25'],
            [22, 2, null, 'Tambah Akun', 'Menambahkan akun test', '2026-10-25'],
            [23, 2, null, 'Hapus Akun', 'Menghapus akun test', '2026-10-25'],
            [24, 11, null, 'Daftar', 'Membuat akun baru', '2026-10-25'],
            [25, 2, null, 'Edit Akun', 'Mengubah akun Zahra', '2026-10-25'],
            [26, 2, null, 'Hapus Akun', 'Menghapus akun testing', '2026-10-25'],
            [27, 2, null, 'Edit Akun', 'Mengubah akun zahra', '2026-10-26'],
            [28, 5, 15, 'Hapus', 'Menghapus komentar di “drawio”', '2026-10-28'],
        ];
    }
}
