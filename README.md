# TOTS Full Stack Developer - Backend

This project is a small full-stack application built as part of the TOTS Full Stack Developer technical test (Option B).

The backend is implemented using Laravel, providing a RESTful API secured with JWT authentication, role-based authorization, and full test coverage.
The frontend (Angular) consumes this API.

## 🚀 Tech Stack

### Backend

- Laravel 11
- PHP 8.2
- JWT Authentication
- SQLite (for simplicity)
- PHPUnit (Feature & Unit tests)

### Frontend

- Angular
- TypeScript
- Angular Router
- JWT-based authentication

## 📁 Project Structure (Backend)

```
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
│   ├── User.php
│   ├── Space.php
│   └── Reservation.php
├── Policies/
├── Services/
│   └── ReservationService.php
database/
├── migrations/
├── seeders/
tests/
├── Feature/
└── Unit/
```

## 🗄️ Database Design

### Users

- `id`
- `name`
- `email`
- `password`
- `role` (admin | user)

### Spaces

- `id`
- `name`
- `type`
- `capacity`
- `created_at`
- `updated_at`

### Reservations

- `id`
- `user_id`
- `space_id`
- `event_name`
- `start_time`
- `end_time`
- `status`
- `created_at`
- `updated_at`

## 🔐 Authentication & Authorization

- JWT-based authentication
- Protected routes via middleware
- Role-based access using Laravel Policies

### Roles

**Admin**
- Can create, update and delete spaces

**User**
- Can create reservations
- Can only access their own reservations

## 🧠 Business Rules

### Reservation Overlapping

A reservation cannot overlap with another reservation for the same space.

Overlap detection is centralized using:
- A query scope in `Reservation`
- A dedicated `ReservationService`

This ensures:
- Single source of truth
- No duplicated logic
- High testability

## 📦 API Endpoints (Backend)

### Authentication
```
POST   /api/auth/login
GET    /api/auth/me
```

### Spaces
```
GET    /api/spaces
POST   /api/spaces           (admin only)
PUT    /api/spaces/{id}      (admin only)
DELETE /api/spaces/{id}      (admin only)
```

### Reservations
```
POST   /api/reservations
GET    /api/reservations/{id}
```

### Availability
```
GET /api/spaces/{space}/availability
```

## 🧪 Testing

The backend includes full test coverage for:
- Authentication (login, protected routes)
- Authorization (policies)
- Reservation overlapping
- Space management permissions

Run tests with:
```bash
php artisan test
```

## ⚙️ Local Setup (Backend)

### 1. Clone the repository
```bash
git clone <repository-url>
cd tots-backend
```

### 2. Install dependencies
```bash
composer install
```

### 3. Environment configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database (SQLite)
```bash
touch database/database.sqlite
```

Update `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 5. Migrations & Seeders
```bash
php artisan migrate --seed
```

### 6. Start the server
```bash
php artisan serve
```

## 🧪 Default Users (Seeded)

| Role  | Email            | Password |
|-------|------------------|----------|
| Admin | admin@tots.com   | password |
| User  | user@tots.com    | password |

## 🎯 Architectural Decisions

- Business rules are not implemented in controllers
- Controllers remain thin
- Core logic is extracted to services and model scopes
- Authorization handled via Policies
- Fully test-driven approach

## 📌 Notes

- SQLite was chosen for simplicity and portability.
- The project follows Laravel best practices and clean architecture principles.
- The codebase is easy to extend and maintain.

## 👨‍💻 Author

**Victor Toro**  
Full Stack Developer

## ✅ Status

- ✔ Backend completed
- ✔ Authentication & authorization
- ✔ Business rules enforced
- ✔ Tests passing
- ✔ Ready for review