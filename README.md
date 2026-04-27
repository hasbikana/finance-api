# Finance API - Laravel REST API

REST API untuk aplikasi manajemen keuangan pribadi (self finance management) dengan fitur autentikasi, kategori, transaksi, dan dashboard.

## Spesifikasi Teknis

- **Framework**: Laravel 13.x
- **Database**: MySQL atau PostgreSQL
- **Authentication**: Laravel Sanctum (Token-based)
- **Response Format**: JSON standar

## Fitur

### 1. Authentication
- `POST /api/user` - Register user baru
- `POST /api/user/login` - Login dan dapat token
- `POST /api/user/logout` - Logout (hapus token)
- `GET /api/user` - Get profil user

### 2. Categories (CRUD)
- `GET /api/categories` - List semua kategori
- `POST /api/categories` - Buat kategori baru
- `PUT /api/categories/{id}` - Update kategori
- `DELETE /api/categories/{id}` - Hapus kategori

### 3. Transactions (CRUD dengan filter)
- `GET /api/transactions` - List transaksi (optional: type, start_date, end_date)
- `POST /api/transactions` - Buat transaksi baru
- `PUT /api/transactions/{id}` - Update transaksi
- `DELETE /api/transactions/{id}` - Hapus transaksi

### 4. Dashboard
- `GET /api/dashboard/summary` - Ringkasan keuangan

## Struktur Response

```json
{
    "status": "success|error",
    "message": "Pesan response",
    "data": { ... }
}
```

## Setup Project

### 1. Install Dependencies
```bash
composer install
```

### 2. Install Sanctum
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Setup Database
Buat database baru di MySQL:
```sql
CREATE DATABASE finance_api;
```

Copy `.env.example` ke `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=finance_api
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Key & Migrate
```bash
php artisan key:generate
php artisan migrate
```

### 5. Seed Database (Optional)
```bash
php artisan db:seed
```

### 6. Run Server
```bash
php artisan serve
```

API akan tersedia di `http://localhost:8000/api`

## Testing

### Test User (after seeding)
- **Email**: test@example.com
- **Password**: password123

### Contoh Request

#### Register
```bash
curl -X POST http://localhost:8000/api/user \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123","password_confirmation":"password123"}'
```

#### Login
```bash
curl -X POST http://localhost:8000/api/user/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'
```

#### Get Categories (dengan token)
```bash
curl -X GET http://localhost:8000/api/categories \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

#### Create Transaction
```bash
curl -X POST http://localhost:8000/api/transactions \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"category_id":1,"type":"expense","amount":50000,"date":"2026-04-27","description":"Test transaksi"}'
```

#### Get Dashboard
```bash
curl -X GET http://localhost:8000/api/dashboard/summary \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

## Endpoint Summary

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | /api/user | No | Register new user |
| POST | /api/user/login | No | Login user |
| POST | /api/user/logout | Yes | Logout user |
| GET | /api/user | Yes | Get user profile |
| GET | /api/categories | Yes | List categories |
| POST | /api/categories | Yes | Create category |
| PUT | /api/categories/{id} | Yes | Update category |
| DELETE | /api/categories/{id} | Yes | Delete category |
| GET | /api/transactions | Yes | List transactions |
| POST | /api/transactions | Yes | Create transaction |
| PUT | /api/transactions/{id} | Yes | Update transaction |
| DELETE | /api/transactions/{id} | Yes | Delete transaction |
| GET | /api/dashboard/summary | Yes | Dashboard summary |

## Validasi

- Email harus unique dan valid format
- Password minimal 8 karakter
- Amount harus integer positif
- Date format YYYY-MM-DD
- Type harus 'income' atau 'expense'
- Category_id harus exist dan milik user yang login

## Security

- Password di-hash dengan bcrypt
- Semua data di-scoped per user
- Sanctum token-based authentication
- CORS dikonfigurasi untuk Flutter app

## License

MIT

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
