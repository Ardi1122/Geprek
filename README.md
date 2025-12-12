# Geprek Dashboard System

Sistem manajemen restoran Geprek berbasis web menggunakan **PHP Native**, **Bootstrap 5**, dan **MySQL**.  
Aplikasi ini menyediakan fitur lengkap mulai dari pengelolaan menu, stok bahan baku, transaksi, hingga analisis bisnis melalui Chat Assistant.

---

## ✨ Fitur Utama

### 🔐 Autentikasi & Role Pengguna
- **Pemilik (Owner)**  
  Akses penuh ke seluruh fitur, termasuk Dashboard, Manajemen User, dan Chat Assistant.
- **Kasir (Cashier)**  
  Hanya dapat mengakses Transaksi dan monitoring Menu/Stok.

### 🍗 Manajemen Menu & Resep
- CRUD Menu (tambah, edit, hapus)
- Upload gambar menu
- Menghubungkan menu dengan bahan baku
- Pengurangan stok otomatis berdasarkan resep saat menu terjual

### 📦 Inventaris Bahan Baku
- Cek jumlah stok bahan baku secara real-time
- Peringatan stok menipis melalui Chat Assistant

### 💰 Transaksi (Kasir)
- Sistem POS dengan keranjang
- Hitung otomatis total & kembalian
- Cetak struk PDF siap cetak
- Riwayat transaksi lengkap (filtering tersedia)

### 🤖 Chat Assistant (Pemilik)
Asisten pintar untuk menjawab berbagai pertanyaan bisnis seperti:
- “Berapa penghasilan hari ini?”
- “Menu apa yang paling laris?”
- “Stok bahan apa yang mulai habis?”
- “Berapa total transaksi hari ini?”

---

## 🛠️ Instalasi & Setup

### 1️⃣ Clone dari GitHub
```bash
git clone https://github.com/username/nama-repo.git
cd nama-repo
