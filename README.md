<p align="center">
  <img src="public/logo.webp" alt="Mbu Titip by Arunga Logo" width="220" />
</p>

<h1 align="center">Mbu Titip by Arunga — Web HPP & Display Produk</h1>

<p align="center">
  Aplikasi manajemen jasa titip berbasis web untuk menghitung HPP, mengelola sesi jastip, order, dan menampilkan katalog produk dengan peta interaktif Indonesia.
</p>

---

## Fitur Utama

- **Katalog Produk Publik** — Tampilan produk dengan peta interaktif D3.js berbasis TopoJSON Indonesia, lengkap dengan pinpoint lokasi per produk
- **Lacak Pesanan** — Pelanggan dapat melacak status order secara publik menggunakan nomor resi
- **Manajemen Produk** — CRUD produk dengan upload gambar, validasi MIME type, dan geocoding otomatis via Nominatim
- **Sesi Jastip** — Kelola sesi perjalanan, distribusi biaya tetap ke tiap produk (proporsional & rata), dan hitung BEP
- **Kalkulasi HPP** — Hitung Harga Pokok Penjualan per produk dengan simulasi dan riwayat rincian
- **Manajemen Order** — Buat dan kelola order pelanggan, update status, dan generate laporan
- **Laporan PDF** — Export laporan sesi dan order ke PDF menggunakan DomPDF

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP 8.x (tanpa framework) |
| Database | MySQL |
| Frontend | Tailwind CSS, Material Symbols |
| Visualisasi | D3.js + TopoJSON (peta Indonesia) |
| PDF | dompdf/dompdf |
| Geocoding | geocoder-php/nominatim-provider |
| HTTP Client | Guzzle 7 |
| Testing | PHPUnit 12 |

## Struktur Proyek

```
├── public/
│   └── index.php          # Front controller & router
├── src/
│   ├── Controller/        # Request handlers
│   ├── Repository/        # Database access layer
│   ├── Service/           # Business logic
│   ├── Helper/            # Auth, CSRF, utility
│   ├── Model/             # Entity classes
│   └── Views/             # PHP templates
│       ├── Home/          # Halaman publik & tracking
│       ├── Product/       # Manajemen produk
│       ├── Order/         # Manajemen order
│       ├── Sesi/          # Sesi jastip & kalkulasi
│       ├── Hpp/           # Rincian HPP
│       ├── Laporan/       # Export laporan
│       └── Auth/          # Login
├── tests/
├── composer.json
└── .env
```

## Instalasi

### Prasyarat

- PHP >= 8.1
- MySQL
- Composer
- Web server (MAMP / XAMPP / Apache / Nginx)

### Langkah Setup

1. **Clone repositori**
   ```bash
   git clone <repo-url>
   cd WEB-HITUNG-HPP-DAN-DISPLAY-PRODUK
   ```

2. **Install dependensi**
   ```bash
   composer install
   ```

3. **Konfigurasi environment**

   Salin `.env.example` menjadi `.env` lalu isi sesuai konfigurasi lokal:

   ```bash
   cp .env.example .env
   ```

4. **Buat database**

   Buat database MySQL dengan nama sesuai `DB_NAME`, lalu import skema:
   ```bash
   mysql -u root -p hitung_hpp < database/schema.sql
   ```

5. **Jalankan via MAMP / web server**

   Arahkan document root ke folder `public/`, atau akses langsung melalui:
   ```
   http://localhost/WEB-HITUNG-HPP-DAN-DISPLAY-PRODUK/public/
   ```

## Penggunaan

| URL | Akses | Keterangan |
|---|---|---|
| `?page=home` | Publik | Katalog & peta produk |
| `?page=track` | Publik | Lacak pesanan |
| `?page=auth&action=login` | Publik | Login admin |
| `?page=product` | Admin | Manajemen produk |
| `?page=order` | Admin | Manajemen order |
| `?page=sesi` | Admin | Sesi jastip |
| `?page=hpp` | Admin | Rincian HPP |
| `?page=laporan` | Admin | Laporan & export PDF |

## Author

**Ahnaf Nadewa**
