# 🚀 Panduan Struktur Laravel — SDG7 Learn UTS

> Panduan ini dibuat berdasarkan struktur proyek kamu yang sebenarnya di `d:\tugas kuliah\sem 3\web 2\uts`

---

## 📁 Peta Folder Penting

```
uts/
├── 📁 routes/
│   └── web.php                  ← 🔀 ROUTER
│
├── 📁 app/
│   ├── 📁 Http/
│   │   └── 📁 Controllers/      ← 🎮 CONTROLLER
│   └── 📁 Models/
│       └── User.php             ← 🗃️ MODEL
│
├── 📁 resources/
│   ├── 📁 views/
│   │   └── welcome.blade.php    ← 🎨 UI/UX (Tampilan)
│   ├── 📁 css/                  ← 🎨 Styling
│   └── 📁 js/                   ← ⚙️ JavaScript
│
└── 📁 database/
    └── 📁 migrations/           ← 🏗️ Struktur Tabel Database
```

---

## 🔀 1. ROUTER — `routes/web.php`

**Fungsi:** Menentukan URL apa → Controller mana yang dipanggil.

Kalau user buka `http://localhost/home`, router yang memutuskan harus pergi ke mana.

```php
<?php
// routes/web.php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// URL '/' → langsung tampilkan view 'welcome'
Route::get('/', function () {
    return view('welcome');
});

// URL '/home' → panggil HomeController, method index()
Route::get('/home', [HomeController::class, 'index']);

// URL '/pelajaran' → panggil PelajaranController, method index()
Route::get('/pelajaran', [PelajaranController::class, 'index']);
```

> [!NOTE]
> `Route::get` = untuk URL yang dibuka browser biasa.
> `Route::post` = untuk form yang dikirim (submit).

---

## 🎮 2. CONTROLLER — `app/Http/Controllers/`

**Fungsi:** Otak/logika aplikasi. Menerima request dari Router, ambil data dari Model, lalu kirim ke View.

Buat file baru controller dengan perintah:
```bash
php artisan make:controller HomeController
```

Contoh isi controller:
```php
<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Pelajaran; // import Model
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 📦 Fetch data dari database via Model
        $pelajaran = Pelajaran::all();

        // 📤 Kirim data ke View
        return view('home', ['pelajaran' => $pelajaran]);
    }

    public function show($id)
    {
        // Ambil 1 data berdasarkan ID
        $item = Pelajaran::find($id);

        return view('detail', ['item' => $item]);
    }
}
```

---

## 🗃️ 3. MODEL — `app/Models/`

**Fungsi:** Representasi tabel di database. Dipakai untuk **fetch (ambil), simpan, update, hapus** data.

Model yang sudah ada: [User.php](file:///d:/tugas%20kuliah/sem%203/web%202/uts/app/Models/User.php)

Buat model baru dengan perintah:
```bash
php artisan make:model Pelajaran
```

Contoh isi model:
```php
<?php
// app/Models/Pelajaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelajaran extends Model
{
    // Nama tabel di database (otomatis 'pelajarans' kalau tidak diisi)
    protected $table = 'pelajarans';

    // Kolom yang boleh diisi massal
    protected $fillable = ['judul', 'deskripsi', 'kategori'];
}
```

### 🔍 Cara Fetch Data (di Controller)

```php
// Ambil SEMUA data
$semua = Pelajaran::all();

// Ambil berdasarkan kondisi
$energi = Pelajaran::where('kategori', 'energi')->get();

// Ambil 1 data berdasarkan ID
$satu = Pelajaran::find(1);

// Ambil dengan urutan
$terbaru = Pelajaran::orderBy('created_at', 'desc')->get();
```

---

## 🎨 4. UI/UX (VIEW) — `resources/views/`

**Fungsi:** Tampilan yang dilihat user di browser. Laravel pakai **Blade** sebagai template engine.

File yang sudah ada: [welcome.blade.php](file:///d:/tugas%20kuliah/sem%203/web%202/uts/resources/views/welcome.blade.php)

Ciri khas file Blade: ekstensinya `.blade.php`

Contoh menampilkan data yang dikirim dari Controller:
```html
<!-- resources/views/home.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>SDG7 Learn</title>
</head>
<body>

    <h1>Daftar Pelajaran</h1>

    {{-- Ini komentar Blade --}}

    @foreach($pelajaran as $item)
        <div class="card">
            <h2>{{ $item->judul }}</h2>
            <p>{{ $item->deskripsi }}</p>
        </div>
    @endforeach

</body>
</html>
```

> [!TIP]
> `{{ $variabel }}` = menampilkan isi variabel (aman dari XSS).
> `@foreach` / `@if` = sintaks khusus Blade, lebih bersih dari PHP biasa.

---

## 🔄 Alur Lengkap Request → Response

```
User buka URL
      ↓
routes/web.php          ← Router mencocokkan URL
      ↓
HomeController@index()  ← Controller dijalankan
      ↓
Pelajaran::all()        ← Model fetch data dari Database
      ↓
return view('home', $data)  ← Data dikirim ke View
      ↓
home.blade.php          ← HTML ditampilkan ke browser
      ↓
User melihat halaman ✅
```

---

## 🛠️ Perintah Artisan yang Sering Dipakai

| Perintah | Fungsi |
|---|---|
| `php artisan make:controller NamaController` | Buat Controller baru |
| `php artisan make:model NamaModel` | Buat Model baru |
| `php artisan make:migration nama_tabel` | Buat file migrasi (struktur tabel) |
| `php artisan migrate` | Jalankan migrasi ke database |
| `php artisan serve` | Jalankan server lokal |
| `php artisan route:list` | Lihat semua route yang terdaftar |

---

## 📌 Ringkasan Cepat

| Kebutuhan | Lokasi File |
|---|---|
| 🎨 Tampilan/HTML | `resources/views/*.blade.php` |
| 🎨 CSS Styling | `resources/css/` |
| 🔀 Daftar URL/Route | `routes/web.php` |
| 🎮 Logika/Proses data | `app/Http/Controllers/` |
| 🗃️ Ambil/Simpan data DB | `app/Models/` |
| 🏗️ Struktur tabel | `database/migrations/` |
| ⚙️ Konfigurasi app | `config/` & `.env` |
