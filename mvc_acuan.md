# 📐 Acuan MVC — SDG7 Learn UTS

> Dokumen ini dibuat berdasarkan **kode yang benar-benar ada** di proyek ini.
> Bukan contoh generik — setiap file, method, dan relasi di sini bisa langsung dibuka.

---

## 🏗️ Arsitektur Proyek Ini

Proyek ini menerapkan arsitektur standar **Laravel MVC (Model-View-Controller)**:
- **Model**: `app/Models/` — Representasi tabel database via Eloquent ORM.
- **View**: `resources/views/` — Tampilan antarmuka berbasis template engine **Blade** yang terbagi rapi dalam folder fitur (`layouts`, `auth`, `artikel`, `kuis`, `hasil-kuis`, `forum`, `profil`, `admin`).
- **Controller**:
  - `app/Http/Controllers/Web/` — Logika penyajian halaman web & form processing yang mengembalikan Blade View (`view()`).
  - `app/Http/Controllers/` — Endpoint API pendukung yang mengembalikan JSON response (`$this->sukses()`).

```
Request Browser
    ↓
routes/web.php              ← Mendaftarkan URL web (Blade)
    ↓
Middleware (auth, role)     ← Sesi autentikasi & otorisasi peran
    ↓
Web Controller              ← Logika halaman & pengambilan data
    ↓
Model (Eloquent)            ← Baca/tulis database
    ↓
Blade View                  ← Render HTML responsif ke browser
```

---

## 📁 Peta Lengkap File

```
uts/
├── routes/
│   ├── web.php                          ← Semua rute web Blade MVC
│   └── api.php                          ← Endpoint API (v1)
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php           ← Base Controller (helper response)
│   │   │   ├── Web/                     ← Controller Web (Return Blade View)
│   │   │   │   ├── HomeController.php       ← Beranda
│   │   │   │   ├── AuthController.php       ← Login, register, logout web
│   │   │   │   ├── ArtikelController.php    ← Daftar & detail artikel
│   │   │   │   ├── KuisController.php       ← Daftar, info & pengerjaan kuis
│   │   │   │   ├── HasilKuisController.php  ← Riwayat & tinjauan hasil kuis
│   │   │   │   ├── ForumController.php      ← Forum diskusi, buat topik & balas
│   │   │   │   ├── ProfilController.php     ← Profil saya & ganti password
│   │   │   │   └── Admin/
│   │   │   │       ├── DashboardController.php ← Statistik admin
│   │   │   │       ├── ArtikelController.php   ← CRUD artikel admin
│   │   │   │       ├── KuisController.php      ← CRUD kuis + soal + hasil
│   │   │   │       ├── ForumController.php     ← Moderasi postingan & balasan
│   │   │   │       └── PenggunaController.php  ← Daftar & detail pengguna
│   │   │   │
│   │   │   ├── AuthController.php       ← Controller API
│   │   │   ├── ArtikelController.php    ← Controller API
│   │   │   ├── ProfilController.php     ← Controller API
│   │   │   ├── KuisController.php       ← Controller API
│   │   │   ├── HasilKuisController.php  ← Controller API
│   │   │   ├── ForumController.php      ← Controller API
│   │   │   └── Admin/                   ← Controller API Admin
│   │   │
│   │   └── Middleware/
│   │       └── CheckRole.php            ← Middleware verifikasi peran user/admin
│   │
│   ├── Models/
│   │   ├── Admin.php          ← Entitas Admin (ERD: ID, Nama, Password)
│   │   ├── User.php           ← Entitas User (ERD: ID, Nama, Pekerjaan, Usia, Password, ID_Forum)
│   │   ├── Artikel.php        ← Entitas Artikel (ERD: ID, ISI, Tanggal, Sumber + admin_id)
│   │   ├── Quiz.php           ← Entitas Quiz (ERD: ID, isi, Poin + admin_id)
│   │   ├── Kuis.php           ← Alias Quiz untuk kompatibilitas
│   │   ├── Pertanyaan.php     ← Soal pilihan ganda dalam quiz
│   │   ├── Opsi.php           ← Pilihan jawaban (A-D)
│   │   ├── Forum.php          ← Entitas Forum (ERD: ID, Isi, Tanggal + user_id, admin_id)
│   │   ├── Postingan.php      ← Alias Forum untuk kompatibilitas
│   │   ├── Balasan.php        ← Komentar/balasan diskusi forum
│   │   ├── HasilKuis.php      ← Relasi ERD: User - Mengerjakan dan Menampilkan skor - Quiz
│   │   └── RefreshToken.php   ← Token refresh auth
│   │
│   └── Fetch/                 ← Layer Repository (query builder)
│       ├── ArtikelRepository.php
│       ├── ForumRepository.php
│       ├── HasilKuisRepository.php
│       ├── KuisRepository.php
│       └── PenggunaRepository.php
│
└── resources/
    ├── views/                 ← View Layer (Blade Templates)
    │   ├── layouts/
    │   │   ├── app.blade.php      ← Layout utama (shell)
    │   │   ├── header.blade.php   ← Navbar navigasi & menu akun
    │   │   └── footer.blade.php   ← Footer
    │   ├── components/
    │   │   └── alert.blade.php    ← Alert notifikasi & error
    │   ├── home.blade.php         ← Beranda
    │   ├── auth/
    │   │   ├── login.blade.php    ← Form login
    │   │   └── register.blade.php ← Form pendaftaran akun
    │   ├── profil/
    │   │   └── index.blade.php    ← Profil & ganti password
    │   ├── artikel/
    │   │   ├── index.blade.php    ← Daftar artikel
    │   │   └── show.blade.php     ← Detail artikel
    │   ├── kuis/
    │   │   ├── index.blade.php    ← Daftar kuis
    │   │   ├── show.blade.php     ← Panduan & info kuis
    │   │   └── kerjakan.blade.php ← Form interaktif pengerjaan kuis
    │   ├── hasil-kuis/
    │   │   ├── index.blade.php    ← Riwayat skor hasil kuis
    │   │   └── show.blade.php     ← Tinjauan evaluasi jawaban per soal
    │   ├── forum/
    │   │   ├── index.blade.php    ← Daftar topik forum diskusi
    │   │   ├── create.blade.php   ← Form buat topik baru
    │   │   └── show.blade.php     ← Thread & daftar balasan
    │   └── admin/
    │       ├── dashboard.blade.php← Panel statistik admin
    │       ├── artikel/           ← CRUD artikel admin
    │       ├── kuis/              ← CRUD kuis + soal + rekap nilai
    │       ├── forum/             ← Moderasi forum diskusi
    │       └── pengguna/          ← Daftar & aktivitas pengguna
    │
    ├── css/
    │   └── app.css            ← Tailwind CSS v4 + Design tokens custom
    └── js/
        └── app.js             ← Interaktivitas minimal (mobile menu, alert)
```

---

## 🔀 ROUTES

### `routes/web.php` — Rute Web Blade MVC

Semua navigasi halaman pengguna dan admin dikelola di `routes/web.php`:

| Method | URL | Controller | Keterangan |
|--------|-----|------------|------------|
| `GET` | `/` | `Web\HomeController@index` | Beranda utama |
| `GET` | `/login` | `Web\AuthController@showLogin` | Halaman login |
| `POST` | `/login` | `Web\AuthController@login` | Proses login |
| `GET` | `/register` | `Web\AuthController@showRegister` | Halaman pendaftaran |
| `POST` | `/register` | `Web\AuthController@register` | Proses pendaftaran |
| `POST` | `/logout` | `Web\AuthController@logout` | Logout sesi |
| `GET` | `/artikel` | `Web\ArtikelController@index` | Daftar artikel |
| `GET` | `/artikel/{id}` | `Web\ArtikelController@show` | Detail artikel |
| `GET` | `/profil` | `Web\ProfilController@index` | Profil saya |
| `PUT` | `/profil` | `Web\ProfilController@update` | Simpan profil |
| `PUT` | `/profil/password` | `Web\ProfilController@ubahPassword` | Ganti password |
| `GET` | `/kuis` | `Web\KuisController@index` | Daftar kuis |
| `GET` | `/kuis/{id}` | `Web\KuisController@show` | Info kuis |
| `GET` | `/kuis/{id}/kerjakan` | `Web\KuisController@kerjakan` | Halaman ujian kuis |
| `POST` | `/kuis/{id}/kerjakan` | `Web\KuisController@simpanJawaban` | Kirim jawaban kuis |
| `GET` | `/hasil-kuis` | `Web\HasilKuisController@index` | Riwayat skor kuis |
| `GET` | `/hasil-kuis/{id}` | `Web\HasilKuisController@show` | Tinjauan per soal |
| `GET` | `/forum` | `Web\ForumController@index` | Forum diskusi |
| `GET` | `/forum/buat` | `Web\ForumController@create` | Form thread baru |
| `POST` | `/forum` | `Web\ForumController@store` | Simpan thread |
| `GET` | `/forum/{id}` | `Web\ForumController@show` | Detail thread & balasan |
| `POST` | `/forum/{id}/balas` | `Web\ForumController@balas` | Kirim balasan |
| `GET` | `/admin/dashboard` | `Web\Admin\DashboardController@index` | Dashboard admin |
| `GET` | `/admin/artikel` | `Web\Admin\ArtikelController@index` | Kelola artikel |
| `GET` | `/admin/kuis` | `Web\Admin\KuisController@index` | Kelola kuis |
| `GET` | `/admin/forum` | `Web\Admin\ForumController@index` | Moderasi forum |
| `GET` | `/admin/pengguna` | `Web\Admin\PenggunaController@index` | Data pengguna |


---

### `routes/api.php` — Semua Endpoint API

Semua route ada di dalam prefix `/api/v1/`. Tersusun dalam 4 lapisan middleware:

#### Lapisan 1: Publik (tanpa login)

| Method | URL | Controller | Method |
|--------|-----|------------|--------|
| `POST` | `/v1/auth/register` | `AuthController` | `register()` |
| `POST` | `/v1/auth/login` | `AuthController` | `login()` |
| `POST` | `/v1/auth/refresh` | `AuthController` | `refresh()` |
| `GET`  | `/v1/artikel` | `ArtikelController` | `index()` |
| `GET`  | `/v1/artikel/{id}` | `ArtikelController` | `show()` |

#### Lapisan 2: Harus Login (`auth:sanctum`)

| Method | URL | Controller | Method |
|--------|-----|------------|--------|
| `POST` | `/v1/auth/logout` | `AuthController` | `logout()` |
| `GET`  | `/v1/profil` | `ProfilController` | `show()` |
| `PUT`  | `/v1/profil` | `ProfilController` | `update()` |
| `PUT`  | `/v1/profil/password` | `ProfilController` | `ubahPassword()` |
| `GET`  | `/v1/kuis` | `KuisController` | `index()` |
| `GET`  | `/v1/kuis/{id}` | `KuisController` | `show()` |
| `GET`  | `/v1/hasil-kuis/{id}` | `HasilKuisController` | `show()` |
| `GET`  | `/v1/forum/postingan` | `ForumController` | `index()` |
| `POST` | `/v1/forum/postingan` | `ForumController` | `store()` |
| `GET`  | `/v1/forum/postingan/{id}` | `ForumController` | `show()` |
| `GET`  | `/v1/forum/postingan/{id}/balasan` | `ForumController` | `balasan()` |
| `POST` | `/v1/forum/postingan/{id}/balasan` | `ForumController` | `balas()` |

#### Lapisan 3: Hanya User (`role:user`)

| Method | URL | Controller | Method |
|--------|-----|------------|--------|
| `POST` | `/v1/kuis/{id}/percobaan` | `KuisController` | `kerjakan()` |
| `GET`  | `/v1/hasil-kuis` | `HasilKuisController` | `index()` |

#### Lapisan 4: Hanya Admin (`role:admin`, prefix `/admin`)

| Method | URL | Controller | Method |
|--------|-----|------------|--------|
| `POST` | `/v1/admin/artikel` | `Admin\ArtikelController` | `store()` |
| `PUT`  | `/v1/admin/artikel/{id}` | `Admin\ArtikelController` | `update()` |
| `DELETE` | `/v1/admin/artikel/{id}` | `Admin\ArtikelController` | `destroy()` |
| `GET`  | `/v1/admin/kuis` | `Admin\KuisController` | `index()` |
| `GET`  | `/v1/admin/kuis/{id}` | `Admin\KuisController` | `show()` |
| `POST` | `/v1/admin/kuis` | `Admin\KuisController` | `store()` |
| `PUT`  | `/v1/admin/kuis/{id}` | `Admin\KuisController` | `update()` |
| `DELETE` | `/v1/admin/kuis/{id}` | `Admin\KuisController` | `destroy()` |
| `POST` | `/v1/admin/kuis/{id}/pertanyaan` | `Admin\KuisController` | `tambahPertanyaan()` |
| `PUT`  | `/v1/admin/kuis/{id}/pertanyaan/{pertanyaan_id}` | `Admin\KuisController` | `ubahPertanyaan()` |
| `DELETE` | `/v1/admin/kuis/{id}/pertanyaan/{pertanyaan_id}` | `Admin\KuisController` | `hapusPertanyaan()` |
| `GET`  | `/v1/admin/kuis/{id}/hasil` | `Admin\KuisController` | `hasil()` |
| `GET`  | `/v1/admin/forum/postingan` | `Admin\ForumController` | `index()` |
| `DELETE` | `/v1/admin/forum/postingan/{id}` | `Admin\ForumController` | `hapusPostingan()` |
| `DELETE` | `/v1/admin/forum/balasan/{id}` | `Admin\ForumController` | `hapusBalasan()` |
| `GET`  | `/v1/admin/pengguna` | `Admin\PenggunaController` | `index()` |
| `GET`  | `/v1/admin/pengguna/{id}` | `Admin\PenggunaController` | `show()` |

---

## 🎮 CONTROLLER

### Base Controller — `app/Http/Controllers/Controller.php`

Semua controller **extends** class ini. Berisi helper untuk membuat response JSON yang konsisten.

| Method | Kegunaan | HTTP Status Default |
|--------|----------|---------------------|
| `sukses($message, $data, $status)` | Response 1 objek berhasil | 200 |
| `suksesDaftar($message, $data, $meta)` | Response daftar berpaginasi | 200 |
| `suksesKosong($message)` | Response berhasil tanpa data (DELETE, logout) | 200 |
| `error($message, $code, $status, $details)` | Response error generik | bebas |
| `errorValidasi($errors)` | Response error validasi dengan detail per field | 422 |
| `buildMeta($paginator)` | Buat array meta paginasi dari LengthAwarePaginator | — |
| `paginasiParams($request)` | Normalisasi `?page=` dan `?limit=` dari query string | — |

**Format response sukses:**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": { ... }
}
```

**Format response daftar (suksesDaftar):**
```json
{
  "success": true,
  "message": "Daftar kuis berhasil diambil",
  "data": [ ... ],
  "meta": {
    "page": 1,
    "limit": 10,
    "total_items": 25,
    "total_pages": 3
  }
}
```

**Format response error validasi:**
```json
{
  "success": false,
  "message": "Data yang dikirim tidak valid",
  "error": {
    "code": "VALIDATION_ERROR",
    "details": [
      { "field": "email", "message": "The email field is required." }
    ]
  }
}
```

---

### AuthController — `app/Http/Controllers/AuthController.php`

Menangani autentikasi. Memakai **Laravel Sanctum** untuk access token dan custom refresh token (opaque, disimpan hash SHA-256 di tabel `refresh_tokens`).

| Method | Route | Keterangan |
|--------|-------|------------|
| `register()` | `POST /auth/register` | Buat akun baru, terbitkan token |
| `login()` | `POST /auth/login` | Verifikasi email+password, terbitkan token |
| `refresh()` | `POST /auth/refresh` | Tukar refresh_token lama → pasangan token baru |
| `logout()` | `POST /auth/logout` | Cabut access token Sanctum + hapus refresh token |

**Private helpers:**
- `terbitkanToken(User $user): array` — buat access token (1 jam via Sanctum) + refresh token (7 hari, simpan hash SHA-256)
- `formatPengguna(User $user): array` — format data pengguna untuk response

---

### ArtikelController — `app/Http/Controllers/ArtikelController.php`

Endpoint publik untuk konten artikel.

| Method | Route | Keterangan |
|--------|-------|------------|
| `index()` | `GET /artikel` | Daftar artikel berpaginasi |
| `show()` | `GET /artikel/{id}` | Detail satu artikel |

> **Catatan:** `index()` saat ini menggabungkan data dari database dengan 3 item statis hardcoded (`statis-1`, `statis-2`, `statis-3`).


**Query parameter yang diterima `index()`:**
- `?q=` — cari judul artikel
- `?sort=tanggal` atau `?sort=-tanggal` (default: `-tanggal` = terbaru dulu)
- `?page=` dan `?limit=` (max 50)

---

### ProfilController — `app/Http/Controllers/ProfilController.php`

Manajemen profil pengguna yang sedang login.

| Method | Route | Keterangan |
|--------|-------|------------|
| `show()` | `GET /profil` | Ambil data profil sendiri |
| `update()` | `PUT /profil` | Ubah nama, pekerjaan, usia |
| `ubahPassword()` | `PUT /profil/password` | Ganti password (perlu verifikasi password lama) |

---

### KuisController — `app/Http/Controllers/KuisController.php`

Dipakai oleh user (dan admin) untuk melihat dan mengerjakan kuis.

| Method | Route | Keterangan |
|--------|-------|------------|
| `index()` | `GET /kuis` | Daftar kuis + status sudah/belum dikerjakan user |
| `show()` | `GET /kuis/{id}` | Detail kuis + soal **tanpa kunci jawaban** |
| `kerjakan()` | `POST /kuis/{id}/percobaan` | Kirim jawaban, hitung skor, simpan ke `hasil_kuis` |

> **Penting:** `kerjakan()` memakai `DB::beginTransaction()` dan `DB::rollBack()` karena menyimpan data lintas tabel. Ini pola yang harus diikuti untuk operasi multi-tabel.

---

### HasilKuisController — `app/Http/Controllers/HasilKuisController.php`

Riwayat dan detail pengerjaan kuis milik user.

| Method | Route | Keterangan |
|--------|-------|------------|
| `index()` | `GET /hasil-kuis` | Daftar riwayat skor kuis milik user yang login |
| `show()` | `GET /hasil-kuis/{id}` | Detail satu hasil + tinjauan per pertanyaan |

> **Catatan:** `show()` memiliki otorisasi manual — user biasa hanya bisa melihat milik sendiri, admin bisa melihat semua.

---

### ForumController — `app/Http/Controllers/ForumController.php`

Forum diskusi. User & admin bisa baca dan posting.

| Method | Route | Keterangan |
|--------|-------|------------|
| `index()` | `GET /forum/postingan` | Daftar postingan berpaginasi |
| `store()` | `POST /forum/postingan` | Buat postingan baru |
| `show()` | `GET /forum/postingan/{id}` | Detail satu postingan |
| `balasan()` | `GET /forum/postingan/{id}/balasan` | Daftar balasan suatu postingan |
| `balas()` | `POST /forum/postingan/{id}/balasan` | Kirim balasan |

---

### Admin\KuisController — `app/Http/Controllers/Admin/KuisController.php`

CRUD kuis lengkap dengan pertanyaan dan opsi.

| Method | Route | Keterangan |
|--------|-------|------------|
| `index()` | `GET /admin/kuis` | Daftar semua kuis |
| `show()` | `GET /admin/kuis/{id}` | Detail + **kunci jawaban** (`benar: true/false`) + `penjelasan` |
| `store()` | `POST /admin/kuis` | Buat kuis + pertanyaan + opsi sekaligus (dalam transaction) |
| `update()` | `PUT /admin/kuis/{id}` | Ubah judul/deskripsi kuis saja |
| `destroy()` | `DELETE /admin/kuis/{id}` | Hapus kuis (cascade ke pertanyaan & opsi) |
| `tambahPertanyaan()` | `POST /admin/kuis/{id}/pertanyaan` | Tambah soal baru ke kuis |
| `ubahPertanyaan()` | `PUT /admin/kuis/{id}/pertanyaan/{pertanyaan_id}` | Edit soal (opsi lama dihapus, diganti baru) |
| `hapusPertanyaan()` | `DELETE /admin/kuis/{id}/pertanyaan/{pertanyaan_id}` | Hapus soal |
| `hasil()` | `GET /admin/kuis/{id}/hasil` | Rekap skor semua pengguna untuk kuis ini |

---

## 🗃️ MODEL & ENTITAS ERD

Struktur Model dan Tabel database telah diselaraskan **100% sama persis dengan Diagram ERD**:

### 1. Admin — `app/Models/Admin.php`
> **Mewakili Entitas `Admin` pada ERD**

**Tabel:** `admin` | **Extends:** `Authenticatable`

| Kolom di Database | Atribut di ERD | Tipe | Keterangan |
|-------------------|----------------|------|------------|
| `id` | `ID` | bigint (PK) | Kunci utama Admin |
| `nama` | `Nama` | string | Nama lengkap Administrator |
| `password` | `Password` | hashed | Kata sandi terenkripsi |
| `email` | — | string (unique) | Kredensial masuk ke sistem |

**Relasi sesuai ERD:**
```php
hasMany(Artikel::class, 'admin_id')  // Admin "Membuat dan Mengelola artikel"
hasMany(Quiz::class, 'admin_id')     // Admin "Membuat Quiz"
hasMany(Forum::class, 'admin_id')    // Admin "Membuat mengelola forum"
```

---

### 2. User — `app/Models/User.php`
> **Mewakili Entitas `User` pada ERD**

**Tabel:** `users` | **Extends:** `Authenticatable`

| Kolom di Database | Atribut di ERD | Tipe | Keterangan |
|-------------------|----------------|------|------------|
| `id` | `ID` | bigint (PK) | Kunci utama Pengguna |
| `nama` | `Nama` | string | Nama lengkap pengguna |
| `pekerjaan` | `Pekerjaan` | string\|null | Pekerjaan / profesi |
| `usia` | `Usia` | integer\|null | Usia pengguna (10–100) |
| `password` | `Password` | hashed | Kata sandi terenkripsi |
| `id_forum` | `ID_Forum` | unsignedBigInteger\|null | Foreign Key ke forum utama/aktif pengguna |
| `email` | — | string (unique) | Kredensial email akun |

**Relasi sesuai ERD:**
```php
belongsTo(Forum::class, 'id_forum') // Atribut ID_Forum pada entitas User
hasMany(Forum::class, 'user_id')    // Relasi "Berdiskusi" di Forum
hasMany(HasilKuis::class)           // Relasi "Mengerjakan dan Menampilkan skor" pada Quiz
belongsToMany(Quiz::class, 'hasil_kuis', 'user_id', 'quiz_id')->withPivot('skor')
```

---

### 3. Artikel — `app/Models/Artikel.php`
> **Mewakili Entitas `Artikel` pada ERD**

**Tabel:** `artikel`

| Kolom di Database | Atribut di ERD | Tipe | Keterangan |
|-------------------|----------------|------|------------|
| `id` | `ID` | bigint (PK) | Kunci utama artikel |
| `admin_id` | — | foreignId | FK ke `admin` (Admin "Membuat dan Mengelola artikel") |
| `isi` | `ISI` | longText | Konten edukasi lengkap artikel |
| `tanggal` | `Tanggal` | dateTime | Waktu penerbitan artikel |
| `sumber` | `Sumber` | string | Sumber rujukan artikel |
| `judul` | — | string | Judul artikel |
| `gambar_url` | — | string\|null | URL gambar ilustrasi |

**Relasi sesuai ERD:**
```php
belongsTo(Admin::class, 'admin_id') // Admin Membuat dan Mengelola artikel
```

---

### 4. Quiz — `app/Models/Quiz.php` & `app/Models/Kuis.php`
> **Mewakili Entitas `Quiz` pada ERD**

**Tabel:** `quiz` (dan `kuis`)

| Kolom di Database | Atribut di ERD | Tipe | Keterangan |
|-------------------|----------------|------|------------|
| `id` | `ID` | bigint (PK) | Kunci utama kuis |
| `admin_id` | — | foreignId | FK ke `admin` (Admin "Membuat Quiz") |
| `isi` | `isi` | text\|null | Rincian / materi yang diujikan dalam kuis |
| `poin` | `Poin` | int | Nilai bobot total poin kuis |
| `judul` | — | string | Judul kuis |
| `deskripsi` | — | text\|null | Deskripsi singkat kuis |

**Relasi sesuai ERD:**
```php
belongsTo(Admin::class, 'admin_id')                           // Admin Membuat Quiz
hasMany(Pertanyaan::class, 'quiz_id')->orderBy('urutan')       // Butir soal pilihan ganda
hasMany(HasilKuis::class, 'quiz_id')                          // Rekap hasil percobaan
belongsToMany(User::class, 'hasil_kuis', 'quiz_id', 'user_id')->withPivot('skor') // User "Mengerjakan dan Menampilkan skor"
```

---

### 5. Forum — `app/Models/Forum.php` & `app/Models/Postingan.php`
> **Mewakili Entitas `Forum` pada ERD**

**Tabel:** `forum` (dan `postingan`)

| Kolom di Database | Atribut di ERD | Tipe | Keterangan |
|-------------------|----------------|------|------------|
| `id` | `ID` | bigint (PK) | Kunci utama topik forum |
| `user_id` | — | foreignId | FK ke `users` (User "Berdiskusi") |
| `admin_id` | — | foreignId | FK ke `admin` (Admin "Membuat mengelola forum") |
| `isi` | `Isi` | longText | Konten diskusi forum |
| `tanggal` | `Tanggal` | dateTime | Waktu dibuatnya diskusi |
| `judul` | — | string | Topik pembahasan forum |

**Relasi sesuai ERD:**
```php
belongsTo(User::class, 'user_id')    // User Berdiskusi
belongsTo(Admin::class, 'admin_id')  // Admin Membuat dan mengelola forum
hasMany(Balasan::class, 'forum_id')  // Komentar balasan diskusi
```

---

### 6. Relasi Transaksi: Mengerjakan dan Menampilkan Skor — `app/Models/HasilKuis.php`
> **Mewakili Relasi Belah Ketupat: `User` -- [Mengerjakan dan Menampilkan skor] -- `Quiz`**

**Tabel:** `hasil_kuis`

| Kolom | Atribut ERD | Tipe | Keterangan |
|-------|-------------|------|------------|
| `id` | — | bigint (PK) | ID hasil kuis |
| `user_id` | FK User | foreignId | Peserta kuis |
| `quiz_id` | FK Quiz | foreignId | Kuis yang dikerjakan |
| `skor` | `skor` | int | Skor nilai yang ditampilkan |
| `skor_maks` | — | int | Skor poin maksimal |
| `persentase`| — | decimal | Persentase nilai (0–100%) |
| `jumlah_benar` | — | int | Jumlah jawaban tepat |
| `jumlah_pertanyaan` | — | int | Total butir pertanyaan |
| `tinjauan` | — | json | Snapshot jawaban user vs kunci benar |

**Relasi:**
```php
belongsTo(User::class, 'user_id')
belongsTo(Quiz::class, 'quiz_id')
```

---

### Kuis — `app/Models/Kuis.php`

**Tabel:** `kuis` (set manual, default Laravel: `kuises`)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `judul` | string | Judul kuis |
| `deskripsi` | text\|null | Deskripsi kuis |

**Relasi:**
```php
hasMany(Pertanyaan::class)->orderBy('urutan')  // soal-soal (urut)
hasMany(HasilKuis::class)                       // semua rekap pengerjaan
```

---

### Pertanyaan — `app/Models/Pertanyaan.php`

**Tabel:** `pertanyaan`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `kuis_id` | int | FK ke `kuis` |
| `urutan` | int | Nomor urut soal |
| `teks` | text | Teks pertanyaan |
| `poin` | int | Nilai poin soal ini |
| `penjelasan` | text\|null | Penjelasan jawaban (hanya untuk admin & setelah kerjakan) |

**Relasi:**
```php
belongsTo(Kuis::class)
hasMany(Opsi::class)
```

---

### Opsi — `app/Models/Opsi.php`

**Tabel:** `opsi`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `pertanyaan_id` | int | FK ke `pertanyaan` |
| `teks` | string | Teks pilihan jawaban |
| `benar` | boolean | `true` jika ini jawaban benar |

> **Aturan wajib:** Setiap pertanyaan harus punya **tepat 1 opsi** dengan `benar = true`.  
> Validasi ini diterapkan di `Admin\KuisController::store()` dan `tambahPertanyaan()`.

---

### HasilKuis — `app/Models/HasilKuis.php`

**Tabel:** `hasil_kuis`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `user_id` | int | FK ke `users` |
| `kuis_id` | int | FK ke `kuis` |
| `kuis_judul` | string | **Snapshot** judul kuis saat dikerjakan |
| `skor` | int | Total poin yang diperoleh |
| `skor_maks` | int | Total poin maksimum |
| `persentase` | float | Persentase skor (dibulatkan 2 desimal) |
| `jumlah_benar` | int | Jumlah soal yang dijawab benar |
| `jumlah_pertanyaan` | int | Total soal saat dikerjakan |
| `tinjauan` | json | Array snapshot tiap soal + jawaban user |

**Relasi:**
```php
belongsTo(User::class)
belongsTo(Kuis::class)
```

---

### Postingan — `app/Models/Postingan.php`

**Tabel:** `postingan`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `user_id` | int | FK ke `users` (penulis) |
| `judul` | string | Judul thread |
| `isi` | text | Isi postingan |

**Relasi:**
```php
belongsTo(User::class, 'user_id')  // disebut 'penulis' di response
hasMany(Balasan::class)
```

---

### Balasan — `app/Models/Balasan.php`

**Tabel:** `balasan`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `postingan_id` | int | FK ke `postingan` |
| `user_id` | int | FK ke `users` (penulis) |
| `isi` | text | Isi balasan |

**Relasi:**
```php
belongsTo(Postingan::class)
belongsTo(User::class, 'user_id')  // disebut 'penulis' di response
```

---

### RefreshToken — `app/Models/RefreshToken.php`

**Tabel:** `refresh_tokens`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `user_id` | int | FK ke `users` |
| `token` | string | Hash SHA-256 dari token asli |
| `expired_at` | datetime | Waktu kedaluwarsa (7 hari dari dibuat) |
| `dipakai_pada` | datetime\|null | Null = belum dipakai; terisi = sudah dipakai (sekali pakai) |

**Relasi:**
```php
belongsTo(User::class)
```

---

## 📦 LAYER REPOSITORY — `app/Fetch/`

Layer ini memisahkan **query database** dari Controller ke dalam kelas tersendiri.  
Saat ini kelima repository sudah ada tapi **belum digunakan oleh controller aktif** — controller langsung memakai Eloquent.  
Repository ini bisa jadi fondasi refactoring ke depan.

| File | Fungsi |
|------|--------|
| `ArtikelRepository.php` | Query daftar & detail Artikel |
| `ForumRepository.php` | Query postingan & balasan forum |
| `HasilKuisRepository.php` | Query riwayat & detail hasil kuis |
| `KuisRepository.php` | Query daftar & detail kuis |
| `PenggunaRepository.php` | Query daftar & detail pengguna (admin) |

**Contoh pola Repository (`ArtikelRepository.php`):**
```php
class ArtikelRepository
{
    // Daftar artikel berpaginasi dengan filter & sort
    public function daftar(?string $q = null, int $limit = 10, string $sort = 'terbaru'): LengthAwarePaginator
    {
        return Artikel::query()
            ->when($q, fn ($query) => $query->where('judul', 'like', "%{$q}%"))
            ->when($sort === 'terlama', fn ($q) => $q->oldest(),
                fn ($q) => $sort === 'judul' ? $q->orderBy('judul') : $q->latest())
            ->paginate($limit)
            ->withQueryString();
    }

    // Detail satu artikel — otomatis 404 bila tidak ada
    public function detail(int $id): Artikel
    {
        return Artikel::findOrFail($id);
    }
}
```

---

## 🔄 Alur Request Lengkap (Contoh: User Kerjakan Kuis)

```
POST /api/v1/kuis/3/percobaan
Authorization: Bearer <access_token>
Body: { "jawaban": [ { "pertanyaan_id": 1, "opsi_id": 4 }, ... ] }

    ↓  routes/api.php
    Cocokkan ke: Route::post('kuis/{id}/percobaan', [KuisController::class, 'kerjakan'])
    Middleware: auth:sanctum → role:user

    ↓  KuisController@kerjakan()
    1. Ambil Kuis::with(['pertanyaan.opsi'])->find($id)
    2. Validasi input jawaban
    3. DB::beginTransaction()
    4. Loop tiap pertanyaan → hitung skor → buat array tinjauan
    5. HasilKuis::create([...])
    6. DB::commit()

    ↓  Response JSON 201
{
  "success": true,
  "message": "Jawaban kuis berhasil dikirim",
  "data": {
    "id": 12,
    "kuis_id": 3,
    "skor": 80,
    "skor_maks": 100,
    "persentase": 80.0,
    "jumlah_benar": 4,
    "jumlah_pertanyaan": 5,
    "tinjauan": [ ... ]
  }
}
```

---

## ✅ Konvensi yang Harus Diikuti

### 1. Selalu extends Base Controller

```php
class NamaController extends Controller
{
    // Gunakan $this->sukses(), $this->error(), dst.
}
```

### 2. Format response konsisten (pakai helper)

```php
// ✅ BENAR
return $this->sukses('Berhasil', $data);
return $this->suksesDaftar('Daftar', $data, $this->buildMeta($paginator));
return $this->error('Tidak ditemukan', 'NOT_FOUND', 404);
return $this->suksesKosong('Data berhasil dihapus');

// ❌ SALAH — jangan langsung pakai response()->json()
return response()->json(['data' => $data]);
```

### 3. Validasi sebelum proses

```php
$validator = Validator::make($request->all(), [
    'judul' => 'required|string|max:255',
    'isi'   => 'required|string',
]);

if ($validator->fails()) {
    return $this->errorValidasi($validator->errors()->toArray());
}
```

### 4. Paginasi selalu lewat `paginasiParams()`

```php
$params = $this->paginasiParams($request);
if ($params === null) {
    return $this->errorValidasi([
        'page'  => ['Nilai page harus >= 1'],
        'limit' => ['Nilai limit harus antara 1 dan 50'],
    ]);
}
// Gunakan $params['page'] dan $params['limit']
$paginator = Model::paginate($params['limit'], ['*'], 'page', $params['page']);
```

### 5. Operasi multi-tabel pakai Database Transaction

```php
DB::beginTransaction();
try {
    // ... operasi ke beberapa tabel ...
    DB::commit();
    return $this->sukses('Berhasil', $data, 201);
} catch (\Throwable $e) {
    DB::rollBack();
    return $this->error('Terjadi kesalahan pada server', 'INTERNAL_SERVER_ERROR', 500);
}
```

### 6. Namespace controller Admin

```php
// Controller Admin ada di subfolder Admin/
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // tetap extends base yang sama

class KuisController extends Controller { ... }
```

---

## 🗂️ Ringkasan Cepat

| Kebutuhan | Lokasi File |
|-----------|-------------|
| Tambah endpoint baru | `routes/api.php` |
| Tambah logika bisnis | `app/Http/Controllers/` |
| Tambah/edit struktur tabel | `database/migrations/` |
| Representasi tabel di PHP | `app/Models/` |
| Controller admin | `app/Http/Controllers/Admin/` |
| Helper response JSON | `app/Http/Controllers/Controller.php` |
| Query builder terpisah | `app/Fetch/` (Repository) |
| View frontend (shell) | `resources/views/welcome.blade.php` |
| Konfigurasi | `config/` dan `.env` |

---

## 🖥️ VIEW / FRONTEND — `resources/views/`

Antarmuka pengguna dibangun secara utuh menggunakan **Blade Template Engine** bawaan Laravel dengan pemisahan berkas dan folder yang modular dan rapi (tidak ada lagi SPA monolitik di JavaScript).

### Stack Antarmuka

| Teknologi | Lokasi | Keterangan |
|-----------|--------|------------|
| Template Engine | `resources/views/` | Blade templates (`@extends`, `@include`, `@yield`, `@auth`) |
| Layout Utama | `resources/views/layouts/app.blade.php` | Kerangka HTML, header, footer, alert container |
| Styling CSS | `resources/css/app.css` | **Tailwind CSS v4** + Design Tokens custom |
| Script Interaktivitas | `resources/js/app.js` | Script ringan untuk interaktivitas DOM (menu mobile, alert) |
| Font | DM Sans + Manrope | Google Fonts / Bunny Fonts |
| Bundler | Vite | `npm run build` atau `npm run dev` |

---

### Struktur Folder `resources/views/`

```
resources/views/
├── layouts/
│   ├── app.blade.php              ← Layout utama shell (@yield('content'))
│   ├── header.blade.php           ← Komponen navbar navigasi & status akun
│   └── footer.blade.php           ← Komponen footer & hak cipta
│
├── components/
│   └── alert.blade.php            ← Alert notifikasi flash success, error & validasi
│
├── home.blade.php                 ← Halaman beranda (Hero, statistik, artikel, kuis, CTA)
│
├── auth/
│   ├── login.blade.php            ← Halaman login pengguna & admin (dengan info akun demo)
│   └── register.blade.php         ← Halaman pendaftaran akun baru
│
├── profil/
│   └── index.blade.php            ← Halaman profil, edit data diri, & ganti kata sandi
│
├── artikel/
│   ├── index.blade.php            ← Daftar artikel dengan pencarian, filter sort & pagination
│   └── show.blade.php             ← Halaman baca artikel lengkap & artikel terkait
│
├── kuis/
│   ├── index.blade.php            ← Katalog kuis dengan status skor pengerjaan terbaik
│   ├── show.blade.php             ← Panduan & informasi kuis sebelum mulai
│   └── kerjakan.blade.php         ← Antarmuka ujian kuis interaktif (radio button pilihan jawaban)
│
├── hasil-kuis/
│   ├── index.blade.php            ← Riwayat pengerjaan kuis pengguna
│   └── show.blade.php             ← Tinjauan evaluasi hasil kuis per soal + pembahasan edukatif
│
├── forum/
│   ├── index.blade.php            ← Daftar topik diskusi forum & jumlah balasan
│   ├── create.blade.php           ← Form membuat topik diskusi baru
│   └── show.blade.php             ← Konten thread diskusi, komentar balasan, & form balas
│
└── admin/
    ├── dashboard.blade.php        ← Dashboard ringkasan metrik & jalan pintas navigasi admin
    ├── artikel/
    │   ├── index.blade.php        ← Tabel kelola artikel (tambah, edit, hapus)
    │   ├── create.blade.php       ← Form tambah artikel baru
    │   └── edit.blade.php         ← Form edit artikel
    ├── kuis/
    │   ├── index.blade.php        ← Tabel kelola kuis (tambah, kelola soal, rekap nilai)
    │   ├── show.blade.php         ← Detail kuis & daftar bank soal + kunci jawaban
    │   ├── create.blade.php       ← Form buat kuis baru
    │   ├── edit.blade.php         ← Form edit kuis
    │   ├── hasil.blade.php        ← Tabel rekap nilai semua peserta kuis
    │   └── pertanyaan/
    │       ├── create.blade.php   ← Form tambah soal pertanyaan & 4 opsi jawaban
    │       └── edit.blade.php     ← Form edit soal pertanyaan & opsi
    ├── forum/
    │   ├── index.blade.php        ← Moderasi daftar thread forum
    │   └── show.blade.php         ← Moderasi detail thread & hapus balasan komentar
    └── pengguna/
        ├── index.blade.php        ← Tabel data pengguna & filter peran
        └── show.blade.php         ← Detail profil pengguna & rekap riwayat aktivitasnya
```

---

### Design System — `resources/css/app.css`

CSS menggunakan **Tailwind CSS v4** dengan variabel warna dan kelas komponen:

| CSS Variable | Nilai | Penggunaan |
|-------------|-------|------------|
| `--ink` | `#20291f` | Warna teks utama |
| `--muted` | `#727c70` | Teks sekunder / placeholder |
| `--green` | `#23764d` | Warna utama brand |
| `--deep-green` | `#153f30` | Warna gelap brand |
| `--lime` | `#c6e977` | Aksen cerah (ikon brand) |
| `--paper` | `#fff` | Background kartu |
| `--line` | `#e6e9e0` | Garis pemisah / border |
| `--sun` | `#f5c954` | Aksen kuning |

---

## 🗂️ Ringkasan Cepat Arsitektur MVC

| Komponen MVC | Lokasi Berkas | Tanggung Jawab |
|--------------|---------------|----------------|
| **Model** | `app/Models/` | Definisi skema tabel, relasi antar tabel Eloquent, casting atribut |
| **View** | `resources/views/` | Seluruh template Blade (`.blade.php`), layout, komponen UI |
| **Controller (Web)** | `app/Http/Controllers/Web/` | Menangani request browser, memanggil model, me-return view Blade |
| **Controller (API)** | `app/Http/Controllers/` | Menangani endpoint REST API, me-return JSON standar |
| **Routes Web** | `routes/web.php` | Routing untuk halaman Blade pengguna dan admin |
| **Routes API** | `routes/api.php` | Routing endpoint REST API (v1) |
| **Database** | `database/migrations/` & `seeders/` | Struktur tabel dan data awal sistem |
| **Aset Frontend** | `resources/css/` & `resources/js/` | Desain Tailwind CSS v4 dan interaktivitas ringan |

