# Deployment Checklist — Mbu Titip by Arunga Arungi Dunia

> Baca dari atas ke bawah, centang satu per satu sebelum go-live.

---

## 1. Server Requirements

- PHP **≥ 8.1** (dev menggunakan 8.3.30)
- MySQL / MariaDB ≥ 5.7
- Apache dengan `mod_rewrite`, `mod_headers`, `mod_expires` aktif
- Composer ≥ 2.x

---

## 2. Upload & Clone

```bash
# Clone / upload semua file kecuali:
#   .env  →  buat manual di server (lihat langkah 3)
#   vendor/  →  generate ulang di server (lihat langkah 4)
#   node_modules/  →  tidak dibutuhkan di production
#   src/Logs/*.log  →  jangan upload log lokal
```

Pastikan **document root** server diarahkan ke folder `public/`, bukan ke root project.

```
/project-root/        ← root project (di luar public web)
  ├── public/         ← document root server
  ├── src/
  ├── vendor/
  └── .env
```

---

## 3. Konfigurasi `.env`

Buat file `.env` baru di root project (jangan copy dari lokal):

```env
DB_HOST=localhost
DB_PORT=3306
DB_USER=GANTI_DB_USER
DB_PASSWORD=GANTI_DB_PASSWORD_KUAT
DB_NAME=GANTI_NAMA_DATABASE
```

> **Jangan pernah commit `.env` ke Git.** Sudah ada di `.gitignore`. ✓

---

## 4. Install Dependencies

```bash
# Di server, jalankan dari root project:
composer install --no-dev --optimize-autoloader
```

Flag `--no-dev` menghapus PHPUnit (~10MB) dari production.
Flag `--optimize-autoloader` membuat classmap statis → lebih cepat.

---

## 5. Database

```bash
# Import schema ke database production:
mysql -u USER -p NAMA_DATABASE < database/schema.sql

# Buat akun admin pertama via script atau manual:
# Password harus di-hash dengan password_hash($pass, PASSWORD_BCRYPT)
```

---

## 6. Permissions Folder

```bash
# Upload foto produk harus bisa ditulis web server:
chmod 755 public/uploads/
chmod 755 public/uploads/products/

# Log harus bisa ditulis (tapi tidak bisa diakses web — sudah diblokir .htaccess):
chmod 755 src/Logs/

# File .env hanya bisa dibaca owner:
chmod 640 .env
```

---

## 7. Update `robots.txt`

Buka `public/robots.txt`, ganti placeholder domain:

```
# Sebelum:
Sitemap: https://GANTI_DOMAIN_ANDA/sitemap.xml

# Sesudah (contoh):
Sitemap: https://mbutitip.com/sitemap.xml
```

---

## 8. Verifikasi Security Headers

Setelah deploy, cek di [securityheaders.com](https://securityheaders.com):

| Header | Expected |
|--------|----------|
| `Content-Security-Policy` | ✓ ada |
| `X-Frame-Options` | `SAMEORIGIN` |
| `X-Content-Type-Options` | `nosniff` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |
| `Permissions-Policy` | ✓ ada |

Kalau header tidak muncul, pastikan `mod_headers` aktif di Apache:

```bash
sudo a2enmod headers
sudo systemctl restart apache2
```

---

## 9. Verifikasi SEO

Setelah deploy, cek:

- [ ] Buka homepage → lihat `<title>` dan `<meta description>` di source
- [ ] Akses `https://domain.com/sitemap.xml` → harus muncul XML
- [ ] Akses `https://domain.com/robots.txt` → harus muncul teks
- [ ] Uji di [Google Rich Results Test](https://search.google.com/test/rich-results) → structured data LocalBusiness terdeteksi
- [ ] Daftarkan sitemap di [Google Search Console](https://search.google.com/search-console)

---

## 10. Verifikasi HTTPS

```bash
# Pastikan SSL certificate terpasang di server.
# Aplikasi akan otomatis redirect HTTP → HTTPS (kecuali localhost).
# Cek redirect berjalan:
curl -I http://domain.com
# Harus return: HTTP/1.1 301 Moved Permanently
# Location: https://domain.com/
```

---

## 11. Error Logging

Di `php.ini` atau `.htaccess` production, arahkan error log ke file yang aman:

```apache
# Di public/.htaccess atau php.ini:
php_flag  log_errors on
php_value error_log  /path/to/project/src/Logs/php_errors.log
```

`display_errors` sudah `0` di `public/index.php`. ✓

---

## 12. Smoke Test Pasca Deploy

Jalankan checklist ini setelah deploy:

- [ ] Homepage terbuka normal
- [ ] Halaman Katalog terbuka, produk tampil
- [ ] Login admin berhasil (`?page=auth&action=login`)
- [ ] Tambah order → order muncul di daftar
- [ ] Upload foto produk berhasil
- [ ] Export PDF laporan berhasil didownload
- [ ] Lacak pesanan dengan nomor order valid
- [ ] HTTPS aktif, HTTP redirect ke HTTPS
- [ ] Coba login salah 5x → muncul pesan lockout

---

## Ringkasan Fitur yang Sudah Production-Ready

| Fitur | Status |
|-------|--------|
| CSRF protection semua form | ✓ |
| Rate limiting login (5x → lockout 15 mnt) | ✓ |
| Session: `httponly`, `secure`, `samesite=Lax` | ✓ |
| `session_regenerate_id()` setelah login | ✓ |
| File upload: MIME check + max 2MB | ✓ |
| Semua SQL query parameterized | ✓ |
| `src/`, `vendor/`, `.env` tidak bisa diakses web | ✓ |
| `display_errors = 0` | ✓ |
| HTTPS redirect (skip localhost) | ✓ |
| Security headers (CSP, dll) | ✓ |
| Database singleton (1 koneksi/request) | ✓ |
| Pagination order (DB-level) | ✓ |
| SEO: meta, OG, Twitter Card, JSON-LD | ✓ |
| `robots.txt` + `sitemap.xml` | ✓ |
| Export PDF laporan | ✓ |
