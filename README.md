# BTS Backend Developer Technical Test

REST API untuk manajemen produk dan autentikasi yang dibangun menggunakan Laravel, PostgreSQL, Redis, dan Docker.

---

## Tech Stack

- **Framework:** Laravel 12
- **Database:** PostgreSQL
- **Caching & Rate Limiting:** Redis
- **Authentication:** Laravel Sanctum
- **Environment:** Docker

---

## Features

- **Authentication:** Fitur Register & Login.
- **Token-based Authentication:** Pengamanan endpoint menggunakan Laravel Sanctum.
- **Product CRUD API:** Manajemen data produk secara lengkap (Create, Read, Update, Delete).
- **Advanced Query:** Fitur pencarian (Search), penyaringan (Filter), dan pembagian halaman (Pagination).
- **Rate Limiting:** Pembatasan request untuk keamanan API.
- **Dockerized Environment:** Kemudahan instalasi dan kompilasi menggunakan Docker.

---

## Run Project

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer Anda:

```bash
# 1. Salin file environment database
cp .env.example .env

# 2. Jalankan kontainer Docker
docker compose up -d --build
```

Aplikasi dapat diakses melalui URL:
 **`http://localhost:8000`**

---

## API Endpoints

### Auth Endpoints
- `POST /api/auth/register` - Mendaftarkan pengguna baru.
- `POST /api/auth/login` - Masuk ke akun dan mendapatkan token.

### Products Endpoints
- `GET /api/products` - Mengambil semua data produk (mendukung Search, Filter, Pagination).
- `GET /api/products/{id}` - Mengambil detail satu produk berdasarkan ID.
- `POST /api/products` - Menambahkan produk baru (Butuh Token).
- `PUT /api/products/{id}` - Memperbarui data produk berdasarkan ID (Butuh Token).
- `DELETE /api/products/{id}` - Menghapus produk berdasarkan ID (Butuh Token).

---

## Notes

- **Bearer Token:** Endpoint yang dilindungi wajib menyertakan `Bearer Token` pada header request.
- **Auto Migration:** Migrasi database akan berjalan secara otomatis saat kontainer Docker pertama kali dinyalakan.
- **Redis Utility:** Redis digunakan secara optimal untuk manajemen cache data dan pembatasan laju request (rate limiting).