# 🛒 Toko Sembako 18 - Inventory Management System

Sistem manajemen barang untuk Toko Sembako 18, sebuah usaha ritel kebutuhan sehari-hari yang berdiri sejak tahun 2005 di Dampit, Kabupaten Malang. Sistem ini dibangun untuk menggantikan metode pencatatan manual dan meningkatkan efisiensi operasional toko.

## 🏬 Tentang Toko Sembako 18

Toko Sembako 18 merupakan toko kebutuhan pokok yang awalnya didirikan karena kebutuhan pribadi dan berkembang menjadi usaha ritel rumahan. Kini berlokasi di **Citraland, Puri Widya Kencana Blok J**, toko ini melayani berbagai kebutuhan masyarakat sekitar, mulai dari sembako hingga produk rumah tangga lainnya.

## 📌 Tujuan Sistem

- Mengganti pencatatan manual yang sebelumnya menggunakan buku tulis dan tumpukan nota
- Meningkatkan efisiensi pengelolaan stok barang masuk dan keluar
- Menyediakan pencatatan formal untuk barang retur dan barang rusak
- Mempermudah pencarian dan pengarsipan data barang

## ✨ Fitur Utama

- 📥 **Manajemen Barang Masuk**  
  Catat pemasukan stok beserta informasi supplier, harga, dan lokasi penyimpanan.

- 📤 **Manajemen Barang Keluar**  
  Pantau stok keluar dari gudang secara rapi dan terstruktur.

- 🔁 **Pencatatan Barang Retur**  
  Retur dicatat formal meski tidak melibatkan supplier secara langsung.

- ❌ **Barang Rusak**  
  Catat barang rusak beserta jumlah dan alasannya.

- 📦 **Data Produk**  
  Tambah/edit produk, lengkap dengan satuan, kategori, harga, dan stok minimal.

- 🧾 **Dashboard & Laporan**  
  Lihat total stok, barang hampir habis, stok aktif, dan histori transaksi.

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Blade Template + Tailwind CSS
- **Database**: MySQL
- **Icon UI**: Flowbite Icons

## 📄 Diagram Sistem (UML)

Sistem ini dirancang menggunakan pendekatan **UML**, meliputi:
- Use Case Diagram
- Activity Diagram
- Sequence Diagram
- Collaboration Diagram
- State Chart Diagram

## 📦 Struktur Data Utama

- **Produk**: Nama, Satuan, Stok, Harga, Kategori, Kadaluarsa
- **Barang Masuk**: Tanggal, Supplier, Jumlah, Harga, Nota
- **Barang Keluar**: Tanggal, Jumlah, Penerima
- **Barang Retur & Rusak**: Alasan, Jumlah, Tanggal, Status Konfirmasi

## 🧪 Proses Pengembangan

Setelah desain awal antarmuka selesai, sistem diuji secara langsung oleh pemilik toko untuk mendapat feedback, lalu dilakukan penyempurnaan berdasarkan masukan tersebut.

## 📍 Lokasi Toko

> **Toko Sembako 18**  
> Citraland, Puri Widya Kencana Blok J  
> Dampit, Kabupaten Malang  
> Jawa Timur, Indonesia  
