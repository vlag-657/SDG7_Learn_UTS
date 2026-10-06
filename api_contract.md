# Kontrak API — SDG7Learn

| | |
|---|---|
| **Versi dokumen** | 1.0 (draf) |
| **Sumber** | SKPL SDG7Learn — Kelompok Android Level 2 (Politeknik Negeri Madiun) |
| **Gaya API** | REST, JSON melalui HTTPS |
| **Base URL** | `https://api.sdg7learn.example/api/v1` *(placeholder — ganti sesuai server)* |

---

## 1. Cakupan Dokumen

Dokumen ini mendefinisikan kontrak antara klien (aplikasi SDG7Learn) dan server: metode HTTP, path, header, path parameter, query parameter, request body, response sukses, dan response error untuk setiap endpoint.

### 1.1 Termasuk

| Modul | Sumber di SKPL |
|---|---|
| Akun & autentikasi | Proses 1.1 *Proses Akun*, KNF-07, entitas User/Admin |
| Artikel | KF-01, KF-04, Proses 1.3 |
| Kuis | KF-02, KF-06, KF-07, Proses 1.4 |
| Forum diskusi | KF-03, KF-09, Proses 1.2 |
| Pengelolaan pengguna oleh admin | §2.2 (Admin "mengelola pengguna"), DFD alur *Data User* |

### 1.2 Dikecualikan: Game

Semua yang berkaitan dengan game **tidak dimasukkan** ke kontrak ini:

- KF-05 (admin mengelola konten games) dan KF-08 (menampilkan score games)
- Proses 1.5 *Proses Game* dan data store *Data Game*
- Entitas Game (atribut ID, Poin, Level) dan relasi *Memainkan* / *Membuat Game*
- Alur data *Data Game*, *Respon Game*, *Rekap Respon Game*, *Score Game*, *Konsep & Isi Game*

---

## 2. Konvensi Umum

### 2.1 Format

| Aspek | Ketentuan |
|---|---|
| Protokol | **HTTPS wajib** (KNF-07). Permintaan HTTP biasa ditolak. |
| Versi | Prefix `/api/v1` pada semua path. |
| Format data | JSON, UTF-8. Request ber-body wajib memakai header `Content-Type: application/json`. |
| Penamaan field | `snake_case`. Istilah domain mengikuti SKPL (bahasa Indonesia); istilah teknis (`token`, `page`, `limit`, `meta`) memakai bahasa Inggris. |
| Tipe ID | Integer positif. |
| Tanggal & waktu | ISO 8601 UTC, contoh `2025-10-06T08:30:00Z`. Konversi ke WIB dilakukan klien. |
| Tanggal pada query | Format `YYYY-MM-DD`. |
| Nilai kosong | Field opsional yang tidak berisi dikirim sebagai `null` di response (tidak dihilangkan). |
| Field tak dikenal di request | Diabaikan server. |
| Target waktu respons | < 5 detik per request (KNF-06). |
| Timeout klien yang disarankan | connect 15 detik, read 30 detik. |

### 2.2 Peran & Autentikasi

| Peran | Keterangan |
|---|---|
| **Publik** | Tanpa token. |
| **User** | Token dengan `role = "user"` (mahasiswa). |
| **Admin** | Token dengan `role = "admin"`. Akun admin dibuat lewat seeding database, **tidak ada** endpoint registrasi admin. |

**Header autentikasi** untuk endpoint non-publik:

```
Authorization: Bearer <access_token>
```

**Token** *(nilai TTL adalah usulan)*:

| Token | Bentuk | Masa berlaku | Catatan |
|---|---|---|---|
| `access_token` | JWT (klaim `sub` = id pengguna, `role`, `exp`) | 3600 detik (1 jam) | Dikirim di header `Authorization`. |
| `refresh_token` | String acak (opaque) | 7 hari | Sekali pakai: setiap `/auth/refresh` menerbitkan pasangan token baru dan mencabut yang lama. Disimpan server dalam bentuk hash. |

**Alur di klien:**

1. `POST /auth/login` → simpan `access_token` dan `refresh_token`.
2. Sertakan `access_token` pada setiap request non-publik.
3. Jika menerima `401` dengan `error.code = "TOKEN_EXPIRED"` → panggil `POST /auth/refresh`, simpan token baru, ulangi request semula **satu kali**.
4. Jika refresh gagal (`401 INVALID_REFRESH_TOKEN`) → hapus token lokal, arahkan ke layar login.
5. Logout → `POST /auth/logout`, lalu hapus token lokal.

**Keamanan (KNF-07):** transport TLS, password disimpan sebagai hash (bcrypt/argon2) dan tidak pernah muncul di response mana pun.

### 2.3 Format Response

**Sukses — satu objek:**

```json
{
  "success": true,
  "message": "Pesan singkat dalam bahasa Indonesia",
  "data": { }
}
```

**Sukses — daftar (berpaginasi):**

```json
{
  "success": true,
  "message": "Daftar artikel berhasil diambil",
  "data": [ ],
  "meta": {
    "page": 1,
    "limit": 10,
    "total_items": 42,
    "total_pages": 5
  }
}
```

**Sukses tanpa data** (mis. DELETE, logout): `"data": null`.

**Error:**

```json
{
  "success": false,
  "message": "Pesan yang boleh ditampilkan ke pengguna",
  "error": {
    "code": "NAMA_KODE_ERROR",
    "details": []
  }
}
```

`error.code` adalah konstanta yang dipakai klien untuk percabangan logika (jangan bergantung pada `message`). `error.details` hanya terisi pada `VALIDATION_ERROR`:

```json
{
  "success": false,
  "message": "Data yang dikirim tidak valid",
  "error": {
    "code": "VALIDATION_ERROR",
    "details": [
      { "field": "email", "message": "Format email tidak valid" },
      { "field": "usia", "message": "Usia harus antara 10 dan 100" }
    ]
  }
}
```

Untuk field di dalam array, `field` memakai notasi indeks, contoh `pertanyaan[0].opsi[2].teks`.

### 2.4 Paginasi, Pencarian, Urutan

Berlaku untuk semua endpoint daftar yang memuat parameter berikut.

| Query | Tipe | Default | Aturan |
|---|---|---|---|
| `page` | integer | `1` | ≥ 1 |
| `limit` | integer | `10` | 1–50 |
| `q` | string | — | Pencarian *case-insensitive* (mengandung kata), maks 100 karakter. Kolom yang dicari disebut di tiap endpoint. |
| `sort` | string | beda per endpoint | Awalan `-` berarti menurun (*descending*). Nilai yang diizinkan disebut di tiap endpoint. |

- Nilai di luar aturan → `422 VALIDATION_ERROR`.
- `page` melebihi `total_pages` → `200` dengan `data: []`.

### 2.5 Kode Status HTTP

| Status | Dipakai untuk |
|---|---|
| `200 OK` | Permintaan berhasil (GET, PUT, DELETE, aksi non-pembuatan). |
| `201 Created` | Resource baru berhasil dibuat (POST pembuatan). |
| `400 Bad Request` | Body bukan JSON yang valid. |
| `401 Unauthorized` | Belum login / token tidak valid / token kedaluwarsa / kredensial salah. |
| `403 Forbidden` | Sudah login tetapi peran tidak berhak. |
| `404 Not Found` | Resource tidak ditemukan. |
| `409 Conflict` | Bentrok dengan keadaan data (mis. email sudah terdaftar). |
| `415 Unsupported Media Type` | `Content-Type` bukan `application/json`. |
| `422 Unprocessable Entity` | Validasi gagal (format, rentang, aturan bisnis). |
| `429 Too Many Requests` | Melebihi batas permintaan (diterapkan pada endpoint `/auth/*`). |
| `500 Internal Server Error` | Kesalahan server. |

### 2.6 Kode Error Umum

Berlaku untuk semua endpoint yang relevan, dan **tidak diulang** di tabel error tiap endpoint. Tabel error tiap endpoint hanya memuat error yang spesifik.

| Status | `error.code` | Kondisi |
|---|---|---|
| 400 | `INVALID_JSON` | Body bukan JSON valid. |
| 401 | `UNAUTHENTICATED` | Header `Authorization` tidak ada, formatnya salah, atau token tidak valid. |
| 401 | `TOKEN_EXPIRED` | `access_token` sudah kedaluwarsa (klien harus refresh). |
| 403 | `FORBIDDEN` | Peran pengguna tidak berhak mengakses endpoint. |
| 404 | `NOT_FOUND` | Path tidak dikenal. |
| 415 | `UNSUPPORTED_MEDIA_TYPE` | `Content-Type` bukan `application/json` pada request ber-body. |
| 422 | `VALIDATION_ERROR` | Body, path parameter, atau query parameter tidak memenuhi aturan (lihat `error.details`). |
| 429 | `TOO_MANY_REQUESTS` | Terlalu banyak permintaan; tunggu sebelum mencoba lagi. |
| 500 | `INTERNAL_SERVER_ERROR` | Kesalahan tak terduga di server. |

---

## 3. Ringkasan Endpoint

| No | Method | Path | Akses | Fungsi | Jejak SKPL | Bagian |
|---|---|---|---|---|---|---|
| 1 | POST | `/auth/register` | Publik | Registrasi akun user | KNF-07, Proses 1.1 | 5.1 |
| 2 | POST | `/auth/login` | Publik | Login (user & admin) | KNF-07, Proses 1.1 | 5.2 |
| 3 | POST | `/auth/refresh` | Publik* | Perbarui token | KNF-07 | 5.3 |
| 4 | POST | `/auth/logout` | User, Admin | Logout / cabut refresh token | KNF-07 | 5.4 |
| 5 | GET | `/profil` | User, Admin | Lihat profil sendiri | Proses 1.1 | 6.1 |
| 6 | PUT | `/profil` | User, Admin | Ubah profil sendiri | Proses 1.1 | 6.2 |
| 7 | PUT | `/profil/password` | User, Admin | Ganti password | KNF-07 | 6.3 |
| 8 | GET | `/artikel` | Publik | Daftar artikel | KF-01 | 7.1 |
| 9 | GET | `/artikel/{id}` | Publik | Detail artikel | KF-01 | 7.2 |
| 10 | GET | `/kuis` | User, Admin | Daftar kuis | KF-02 | 8.1 |
| 11 | GET | `/kuis/{id}` | User, Admin | Detail kuis + pertanyaan (tanpa kunci jawaban) | KF-02 | 8.2 |
| 12 | POST | `/kuis/{id}/percobaan` | User | Kirim jawaban kuis, dapatkan skor | KF-02, KF-07 | 8.3 |
| 13 | GET | `/hasil-kuis` | User | Riwayat skor kuis milik sendiri | KF-07 | 9.1 |
| 14 | GET | `/hasil-kuis/{id}` | User (milik sendiri), Admin | Detail satu hasil kuis | KF-07 | 9.2 |
| 15 | GET | `/forum/postingan` | User, Admin | Daftar postingan forum | KF-03 | 10.1 |
| 16 | POST | `/forum/postingan` | User, Admin | Buat postingan | KF-03 | 10.2 |
| 17 | GET | `/forum/postingan/{id}` | User, Admin | Detail postingan | KF-03 | 10.3 |
| 18 | GET | `/forum/postingan/{id}/balasan` | User, Admin | Daftar balasan postingan | KF-03 | 10.4 |
| 19 | POST | `/forum/postingan/{id}/balasan` | User, Admin | Balas postingan | KF-03 | 10.5 |
| 20 | POST | `/admin/artikel` | Admin | Tambah artikel | KF-04 | 11.1 |
| 21 | PUT | `/admin/artikel/{id}` | Admin | Ubah artikel | KF-04 | 11.2 |
| 22 | DELETE | `/admin/artikel/{id}` | Admin | Hapus artikel | KF-04 | 11.3 |
| 23 | GET | `/admin/kuis` | Admin | Daftar kuis (sisi admin) | KF-06 | 12.1 |
| 24 | GET | `/admin/kuis/{id}` | Admin | Detail kuis + kunci jawaban | KF-06 | 12.2 |
| 25 | POST | `/admin/kuis` | Admin | Buat kuis beserta pertanyaan | KF-06 | 12.3 |
| 26 | PUT | `/admin/kuis/{id}` | Admin | Ubah judul/deskripsi kuis | KF-06 | 12.4 |
| 27 | DELETE | `/admin/kuis/{id}` | Admin | Hapus kuis | KF-06 | 12.5 |
| 28 | POST | `/admin/kuis/{id}/pertanyaan` | Admin | Tambah pertanyaan ke kuis | KF-06 | 12.6 |
| 29 | PUT | `/admin/kuis/{id}/pertanyaan/{pertanyaan_id}` | Admin | Ubah pertanyaan | KF-06 | 12.7 |
| 30 | DELETE | `/admin/kuis/{id}/pertanyaan/{pertanyaan_id}` | Admin | Hapus pertanyaan | KF-06 | 12.8 |
| 31 | GET | `/admin/kuis/{id}/hasil` | Admin | Rekap hasil kuis semua user | DFD 1.4.5 | 12.9 |
| 32 | GET | `/admin/forum/postingan` | Admin | Rekap isi forum + info penulis | KF-09 | 13.1 |
| 33 | DELETE | `/admin/forum/postingan/{id}` | Admin | Hapus postingan (moderasi) | ER *Mengolah* | 13.2 |
| 34 | DELETE | `/admin/forum/balasan/{id}` | Admin | Hapus balasan (moderasi) | ER *Mengolah* | 13.3 |
| 35 | GET | `/admin/pengguna` | Admin | Daftar pengguna | §2.2, DFD *Data User* | 14.1 |
| 36 | GET | `/admin/pengguna/{id}` | Admin | Detail pengguna + ringkasan aktivitas | §2.2, DFD *Data User* | 14.2 |

\* `/auth/refresh` tidak memakai header `Authorization`; yang divalidasi adalah `refresh_token` di body.

---

## 4. Skema Data (Model)

Model berikut dipakai berulang di response. Contoh JSON lengkap ada di tiap endpoint.

### 4.1 `Pengguna`

| Field | Tipe | Null | Keterangan |
|---|---|---|---|
| `id` | integer | tidak | |
| `nama` | string | tidak | |
| `email` | string | tidak | Unik, disimpan huruf kecil. |
| `pekerjaan` | string | ya | `null` untuk admin. |
| `usia` | integer | ya | `null` untuk admin. |
| `role` | string | tidak | `"user"` atau `"admin"`. |
| `dibuat_pada` | string (datetime) | tidak | |

### 4.2 `Penulis` (ringkas, dipakai di forum)

| Field | Tipe | Null | Keterangan |
|---|---|---|---|
| `id` | integer | tidak | |
| `nama` | string | tidak | |
| `role` | string | tidak | `"user"` atau `"admin"`. |

Di endpoint admin (§13.1), `Penulis` diperluas dengan `pekerjaan` dan `usia`.

### 4.3 `Artikel`

| Field | Tipe | Null | Pada | Keterangan |
|---|---|---|---|---|
| `id` | integer | tidak | ringkas, detail | |
| `judul` | string | tidak | ringkas, detail | |
| `ringkasan` | string | tidak | ringkas | 150 karakter pertama `isi` (teks polos), diakhiri `…` bila terpotong. |
| `isi` | string | tidak | detail | Teks polos; paragraf dipisah `\n\n`. |
| `sumber` | string | tidak | ringkas, detail | Nama atau tautan sumber artikel. |
| `gambar_url` | string | ya | ringkas, detail | URL gambar sampul. |
| `tanggal` | string (datetime) | tidak | ringkas, detail | Waktu artikel dibuat (diisi server). |
| `diperbarui_pada` | string (datetime) | tidak | detail | |

### 4.4 `Kuis`, `Pertanyaan`, `Opsi`

**`Kuis` (sisi user)**

| Field | Tipe | Null | Pada | Keterangan |
|---|---|---|---|---|
| `id` | integer | tidak | ringkas, detail | |
| `judul` | string | tidak | ringkas, detail | |
| `deskripsi` | string | ya | ringkas, detail | |
| `jumlah_pertanyaan` | integer | tidak | ringkas, detail | |
| `total_poin` | integer | tidak | ringkas, detail | Jumlah `poin` seluruh pertanyaan (dihitung server). |
| `sudah_dikerjakan` | boolean | tidak | ringkas | `true` bila user pernah mengirim jawaban. |
| `skor_terbaik` | integer | ya | ringkas | Skor tertinggi user pada kuis ini; `null` bila belum pernah mengerjakan. |
| `dibuat_pada` | string (datetime) | tidak | ringkas, detail | |
| `pertanyaan` | array `Pertanyaan` | tidak | detail | Berurutan menurut `urutan`. |

**`Pertanyaan` (sisi user — tanpa kunci jawaban)**

| Field | Tipe | Null | Keterangan |
|---|---|---|---|
| `id` | integer | tidak | |
| `urutan` | integer | tidak | Mulai dari 1. |
| `teks` | string | tidak | |
| `poin` | integer | tidak | |
| `opsi` | array `Opsi` | tidak | |

**`Opsi` (sisi user)**: `{ "id": integer, "teks": string }`

**Sisi admin** (§12.2): `Kuis` ditambah `diperbarui_pada`; `Pertanyaan` ditambah `penjelasan` (string|null); `Opsi` ditambah `benar` (boolean).

### 4.5 `HasilKuis`

| Field | Tipe | Null | Pada | Keterangan |
|---|---|---|---|---|
| `id` | integer | tidak | ringkas, detail | ID percobaan. |
| `kuis_id` | integer | tidak | ringkas, detail | |
| `kuis_judul` | string | tidak | ringkas, detail | |
| `skor` | integer | tidak | ringkas, detail | Jumlah poin dari jawaban benar. |
| `skor_maks` | integer | tidak | ringkas, detail | `total_poin` kuis pada saat dikerjakan. |
| `persentase` | number | tidak | ringkas, detail | `skor / skor_maks × 100`, dibulatkan 2 desimal. |
| `jumlah_benar` | integer | tidak | ringkas, detail | |
| `jumlah_pertanyaan` | integer | tidak | ringkas, detail | |
| `dikerjakan_pada` | string (datetime) | tidak | ringkas, detail | |
| `tinjauan` | array `Tinjauan` | tidak | detail | |

**`Tinjauan`** (satu entri per pertanyaan; disimpan sebagai *snapshot* saat kuis dikerjakan, sehingga perubahan kuis oleh admin tidak mengubah riwayat):

| Field | Tipe | Null | Keterangan |
|---|---|---|---|
| `pertanyaan_id` | integer | tidak | |
| `urutan` | integer | tidak | |
| `teks` | string | tidak | |
| `poin` | integer | tidak | |
| `opsi` | array `Opsi` | tidak | Semua opsi `{id, teks}`. |
| `opsi_dipilih_id` | integer | ya | `null` bila pertanyaan tidak dijawab. |
| `opsi_benar_id` | integer | tidak | |
| `benar` | boolean | tidak | |
| `poin_diperoleh` | integer | tidak | `poin` bila benar, selain itu `0`. |
| `penjelasan` | string | ya | |

**Pada rekap admin** (§12.9), `HasilKuis` ringkas ditambah objek `pengguna` `{ id, nama, pekerjaan, usia }`.

### 4.6 `Postingan` dan `Balasan`

**`Postingan`**

| Field | Tipe | Null | Pada | Keterangan |
|---|---|---|---|---|
| `id` | integer | tidak | daftar, detail | |
| `judul` | string | tidak | daftar, detail | |
| `ringkasan` | string | tidak | daftar | 150 karakter pertama `isi`. |
| `isi` | string | tidak | detail | |
| `penulis` | `Penulis` | tidak | daftar, detail | |
| `jumlah_balasan` | integer | tidak | daftar, detail | |
| `tanggal` | string (datetime) | tidak | daftar, detail | |

**`Balasan`**

| Field | Tipe | Null | Keterangan |
|---|---|---|---|
| `id` | integer | tidak | |
| `postingan_id` | integer | tidak | |
| `isi` | string | tidak | |
| `penulis` | `Penulis` | tidak | |
| `tanggal` | string (datetime) | tidak | |