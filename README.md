# FinanceApp

Aplikasi Manajemen Keuangan Pribadi berbasis Laravel 13 dengan Blade Frontend + API Backend.

## Tech Stack

- **Backend:** Laravel 13, Sanctum Auth, SQLite/MySQL
- **Frontend:** Blade, Bootstrap 5, Chart.js, SweetAlert2, DataTables
- **Mobile Sync:** REST API (consumed by Flutter Mobile App)

## Fitur

### Fitur Wajib
- Authentication (Login, Register, Logout, Profile)
- Dashboard (Balance, Income/Expense, Chart, Recent Transactions)
- Categories (CRUD + Search + Pagination)
- Transactions (CRUD + Filter + Search + Pagination + Export CSV)
- Reports (Monthly, Yearly, Category Charts, Export PDF/CSV)

### Fitur Tambahan
- Wallet Management (Cash, Bank, E-Wallet)
- Budget Planning (Budget per Category + Progress)
- Savings Goal (Target + Progress Tracking)
- Financial Insight (Monthly Comparison + Auto Insights)
- Quick Transaction (Dashboard)
- Notification Center
- Recurring Transactions (API)
- Device Tokens (API)

## Arsitektur

```
Controllers (Api/Web) → Services → Repositories → Models → Database
```

- **Service Layer:** Business logic (App\Services)
- **Repository Pattern:** Data access (App\Repositories)
- **Form Request Validation:** Input validation (App\Http\Requests)
- **Policy Authorization:** Owner-based access (App\Policies)

## Instalasi

### Prasyarat
- PHP 8.3+
- Composer
- MySQL / SQLite
- Node.js & NPM (opsional - UI menggunakan CDN)

### Langkah Instalasi

```bash
# 1. Clone repo
git clone <repo-url> finance-api
cd finance-api

# 2. Copy .env dan sesuaikan database
cp .env.example .env
# Edit .env: DB_CONNECTION, DB_DATABASE, etc.

# 3. Install dependencies
composer install

# 4. Generate app key
php artisan key:generate

# 5. Migrate & seed database
php artisan migrate --seed

# 6. (Opsional) Install NPM dan build assets
npm install
npm run build

# 7. Jalankan server
php artisan serve
```

### Login Test User
- Email: `test@example.com`
- Password: `password123`

## API Endpoints

### Public
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/user | Register |
| POST | /api/user/login | Login |

### Protected (Bearer Token)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/user | Profile |
| POST | /api/user/logout | Logout |
| PUT | /api/user/profile | Update Profile |
| PUT | /api/user/password | Change Password |
| GET/POST/PUT/DELETE | /api/categories | Categories CRUD |
| GET/POST/PUT/DELETE | /api/transactions | Transactions CRUD |
| POST | /api/transactions/quick | Quick Transaction |
| GET | /api/dashboard/summary | Dashboard Summary |
| GET/POST/PUT/DELETE | /api/goals | Goals CRUD |
| POST | /api/goals/{id}/add-savings | Add Savings |
| GET | /api/reports/charts | Chart Data |
| GET | /api/reports/monthly | Monthly Report |
| GET | /api/reports/export | Export Data (JSON) |
| GET | /api/reports/export-pdf | Export PDF |
| GET | /api/reports/export-csv | Export CSV |
| GET/POST/PUT/DELETE | /api/wallets | Wallets CRUD |
| GET/POST/PUT/DELETE | /api/budgets | Budgets CRUD |
| GET | /api/notifications | List Notifications |
| GET | /api/notifications/unread-count | Unread Count |
| POST | /api/notifications/{id}/read | Mark Read |
| POST | /api/notifications/read-all | Mark All Read |
| POST | /api/device-tokens | Register Device |
| GET | /api/insights/monthly-comparison | Monthly Comparison |
| GET | /api/insights/auto-insights | Auto Insights |
| GET/POST/PUT/DELETE | /api/recurring-transactions | Recurring CRUD |

## Struktur Folder Penting

```
app/
├── Http/
│   ├── Controllers/       # API & Web controllers
│   │   ├── Api/           # API controllers
│   │   └── Web/           # Blade controllers  
│   ├── Requests/          # Form validation
│   │   ├── Api/           # API validation
│   │   └── Web/           # Web validation
│   ├── Resources/         # API resources
│   └── Middleware/         # Custom middleware
├── Models/                # Eloquent models
├── Services/              # Business logic layer
├── Repositories/          # Data access layer
└── Policies/              # Authorization

database/
├── migrations/            # DB migrations
├── factories/             # Model factories
└── seeders/               # DB seeders

resources/views/
├── layouts/               # App layout + auth layout
├── dashboard/             # Dashboard page
├── transactions/          # Transaction pages
├── categories/            # Category pages
├── wallets/               # Wallet pages
├── budgets/               # Budget pages
├── goals/                 # Goal pages
├── reports/               # Report pages
├── insights/              # Insight pages
├── notifications/         # Notification pages
├── profile/               # Profile page
└── auth/                  # Login/Register pages
```

## Warna Aplikasi

| Warna | Hex |
|-------|-----|
| Primary | #0F766E |
| Secondary | #14B8A6 |
| Accent | #22C55E |
