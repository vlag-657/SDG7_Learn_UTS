# LAPORAN PRAKTIK

**REKAYASA KEBUTUHAN PERANGKAT LUNAK**
**SPESIFIKASI KEBUTUHAN PERANGKAT LUNAK (SKPL)**

**\<Nama Perangkat Lunak\>**

*[Logo Politeknik Negeri Madiun]*

**Oleh:**

- CRISTIAN REYNALDI - 254311017
- DECO AKBAR PRASETYA - 254311022
- DAFFA HAFIST ATHA KUNCORO - 254311025
- MUHAMMAD ADISTYA RAFIF RASENDRIYA - 254311026

**JURUSAN TEKNIK**
**PROGRAM STUDI TEKNOLOGI REKAYASA PERANGKAT LUNAK**
**POLITEKNIK NEGERI MADIUN**
**2025**

---

## Daftar Isi

1. Pendahuluan
   1. Tujuan Penulisan Dokumen
   2. Lingkup Masalah
   3. Definisi, Istilah dan Singkatan
2. Deskripsi Umum Perangkat Lunak
   1. Deskripsi Umum Sistem
   2. Karakteristik Pengguna
   3. Batasan
   4. Lingkungan Operasi
3. Deskripsi Kebutuhan
   1. Kebutuhan Antarmuka Eksternal
   2. Kebutuhan Non Fungsional
   3. Kebutuhan Fungsional
      1. Diagram Konteks
      2. Definisi Entitas Eksternal
      3. Diagram Overview
         1. DFD Level 2.1
         2. DFD Level 2.2
   4. Kebutuhan Data
      1. E-R Diagram
   5. Kerunutan (Traceability)
      1. Kebutuhan Fungsional vs Proses
      2. Data Store vs E-R

---

# 1. Pendahuluan

## 1.1 Tujuan Penulisan Dokumen

Dokumen Spesifikasi Kebutuhan Perangkat Lunak (SKPL) ini disusun untuk menjelaskan kebutuhan perangkat lunak yang akan dikembangkan dengan nama SDG7Learn. Dokumen ini menjadi acuan bagi tim pengembang dalam proses analisis, desain, implementasi, dan pengujian sistem. SKPL ini digunakan oleh pengembang, dosen pembimbing, dan pihak lain yang terlibat dalam proyek ini.

## 1.2 Lingkup Masalah

Aplikasi SDG7Learn merupakan platform edukasi berbasis web yang bertujuan untuk meningkatkan literasi mahasiswa mengenai energi bersih dan terjangkau sesuai dengan tujuan pembangunan berkelanjutan (SDG 7). Sistem ini menyediakan artikel, infografis, video edukatif, kuis interaktif, serta forum diskusi yang dapat diakses secara daring.

## 1.3 Definisi, Istilah dan Singkatan

| Istilah | Definisi |
|---|---|
| SDG7Learn | Aplikasi website edukasi energi bersih dan terjangkau. |
| SDG | Sustainable Development Goals (Tujuan Pembangunan Berkelanjutan). |
| User | Pengguna sistem, yaitu mahasiswa dan admin. |
| KNF | Kebutuhan Non Fungsional. |
| KF | Kebutuhan Fungsional. |

---

# 2. Deskripsi Umum Perangkat Lunak

## 2.1 Deskripsi Umum Sistem

SDG7Learn adalah website edukasi yang berfungsi sebagai media literasi digital tentang energi bersih. Sistem ini menyediakan konten pembelajaran berupa artikel, video, infografis, serta fitur interaktif seperti kuis dan forum diskusi. Aplikasi ini diharapkan dapat meningkatkan pemahaman mahasiswa terhadap konsep energi bersih dan terjangkau, serta mendorong partisipasi mereka dalam mendukung SDG 7.

## 2.2 Karakteristik Pengguna

| Kategori Pengguna | Tugas | Hak Akses ke Aplikasi |
|---|---|---|
| Admin | Mengelola konten, pengguna, dan memantau aktivitas sistem. | Akses penuh terhadap seluruh fitur. |
| User | Membaca artikel, mengikuti kuis, dan berpartisipasi dalam forum diskusi. | Akses ke konten publik dan interaktif. |

## 2.3 Batasan

Sistem hanya dapat diakses melalui koneksi internet dan menggunakan peramban web modern. Aplikasi ini tidak mencakup manajemen energi secara langsung, melainkan hanya berfokus pada penyediaan informasi dan edukasi. Konten dapat diperbarui secara berkala oleh admin.

## 2.4 Lingkungan Operasi

*Operating system, DBMS, Client, Server*

Aplikasi client-server ini akan berfungsi dengan spesifikasi:

- **Server:** Menggunakan hosting berbasis cloud (misalnya AWS atau Google Cloud).
- **Client:** Browser desktop dan mobile (Google Chrome, Mozilla Firefox, Safari).
- **OS:** Multi-platform (Windows, Linux, Android, iOS).
- **DBMS:** MySQL atau PostgreSQL.

---

# 3. Deskripsi Kebutuhan

## 3.1 Kebutuhan Antarmuka Eksternal

Antarmuka pengguna menggunakan perangkat input umum seperti keyboard, mouse, dan layar sentuh. Tampilan dirancang responsif agar dapat digunakan di berbagai ukuran layar perangkat.

## 3.2 Kebutuhan Non Fungsional

| ID | Parameter | Kebutuhan |
|---|---|---|
| KNF-01 | Availability | Aplikasi harus dapat diakses 24 jam per hari dengan downtime maksimal 1%. |
| KNF-02 | Reliability | Sistem harus dapat menangani minimal 100 pengguna secara bersamaan tanpa gagal. |
| KNF-03 | Ergonomy | Antarmuka pengguna harus mudah dipahami dan ramah bagi pengguna baru. |
| KNF-04 | Portability | Sistem harus dapat diakses melalui berbagai perangkat dan sistem operasi. |
| KNF-05 | Memory | Aplikasi harus dioptimalkan agar ukuran file tidak melebihi 50MB. |
| KNF-06 | Response time | Setiap halaman harus termuat dalam waktu kurang dari 5 detik. |
| KNF-07 | Security | Sistem harus dilengkapi autentikasi login dan enkripsi data pengguna. |

## 3.3 Kebutuhan Fungsional

| ID | Kebutuhan | Penjelasan |
|---|---|---|
| KF-01 | Sistem dapat menampilkan artikel tentang energi bersih dan terjangkau. | |
| KF-02 | Sistem menyediakan kuis interaktif untuk pengguna. | |
| KF-03 | Sistem menyediakan forum diskusi antar pengguna. | |
| KF-04 | Admin dapat mengelola konten artikel. | KF ini hanya dapat dilakukan oleh admin |
| KF-06 | Admin dapat mengelola konten kuis. | KF ini hanya dapat dilakukan oleh admin |
| KF-07 | Sistem menampilkan score kuis | KF ini hanya dapat dilakukan jika telah mengerjakan kuis |
| KF-09 | Sistem menampilkan isi forum ke Admin | KF ini hanya dapat dilakukan oleh admin |

### 3.3.1 Diagram Konteks

*[Gambar: Diagram Konteks]*

### 3.3.2 Definisi Entitas Eksternal

Entity utama dalam sistem meliputi User, Artikel, Kuis, Forum, dan Media. Relasi antar entitas adalah sebagai berikut:

- User dapat membuat dan membalas postingan pada Forum, serta mengerjakan kuis. User dapat melihat score dari kuis.
- Admin dapat menambahkan dan menghapus Artikel dan Kuis. Admin juga dapat melihat isi dari forum.
- Kuis memiliki beberapa Pertanyaan yang disimpan dalam entitas terpisah.

### 3.3.3 Diagram Overview

*[Gambar: Diagram Overview]*

#### 3.3.3.1 DFD Level 2.1

*[Gambar: DFD Level 2.1 (kosong pada dokumen asli)]*

#### 3.3.3.2 DFD Level 2.2

*[Gambar: DFD Level 2.2]*

## 3.4 Kebutuhan Data

### 3.4.1 E-R Diagram

*[Gambar: E-R Diagram]*

## 3.5 Kerunutan (Traceability)

### 3.5.1 Kebutuhan Fungsional vs Proses

*Mapping kebutuhan fungsional dengan proses pada DFD*

| ID Kebutuhan Fungsional | Nomor Proses pada DFD |
|---|---|
| KF-01 | 1 - Menampilkan Artikel |
| KF-02 | 2 - Menyajikan Kuis |
| KF-03 | 3 - Forum Diskusi |
| KF-04 | 4 - Memproses Artikel oleh Admin |
| KF-06 | 6 - Memproses Kuis oleh Admin |
| KF-07 | 7 - Memproses Hasil Kuis |
| KF-09 | 9 - Mengirim Postingan ke Admin |

### 3.5.2 Data Store vs E-R

*Mapping data store pada DFD dengan Entity atau Relasi*

| Data Store (DFD) | Entity (E-R Diagram) | Relasi/Deskripsi |
|---|---|---|
| Artikel | Artikel | Admin menambah, mengedit, menghapus artikel |
| Kuis | Kuis, Pertanyaan | Kuis berisi beberapa pertanyaan |
| Forum | Forum, Postingan | User membuat dan membalas postingan |
| User | User | User dapat login, mengikuti kuis, dan berpartisipasi di forum |