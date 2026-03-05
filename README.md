# Task Management App

A full-stack Task Management application built with **Laravel 12** and **MySQL**.
Includes a REST API with token-based authentication and a browser-based frontend with role-based access control.

---

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Database:** MySQL
- **Authentication:** Laravel Sanctum (token-based)
- **Frontend:** Laravel Blade (server-side rendered)

---

## Features

### Authentication
- User registration and login
- Token-based authentication using Laravel Sanctum
- Role-based access control (Admin and User)
- Secure logout

### Admin Dashboard
- View ALL tasks from all users
- Create tasks and assign them to any user
- Edit any task
- Delete any task (soft delete — can be restored)
- Filter tasks by status, priority, and due date
- Pagination

### User Dashboard
- View only tasks assigned to them
- Mark tasks as complete
- Edit their own tasks
- Filter tasks by status and priority
- Pagination

### REST API
- Full CRUD for tasks
- Soft deletes with restore
- Filter and pagination
- Token-based authentication

---

## Setup Instructions

### 1. Clone the repository
```bash
git clone https://github.com/YOUR_USERNAME/task.git
cd task
```

### 2. Install dependencies
```bash
composer install
```

### 3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_api
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Install Sanctum
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 5. Run migrations
```bash
php artisan migrate
```

### 6. Seed the database
```bash
php artisan db:seed
```

### 7. Start the server
```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` — the login page will appear.

---

## Test Accounts

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password | Admin |
| user@example.com | password | User |

---

## Web Pages

| URL | Description | Access |
|-----|-------------|--------|
| `/login` | Login page | Public |
| `/register` | Register new account | Public |
| `/admin/dashboard` | Admin dashboard | Admin only |
| `/dashboard` | User task dashboard | User only |

---

## REST API Endpoints

### Auth (Public)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register new user |
| POST | `/api/login` | Login, returns token |
| POST | `/api/logout` | Logout (requires token) |

### Tasks (Requires `Authorization: Bearer {token}` header)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/tasks` | List tasks (filter + paginate) |
| POST | `/api/tasks` | Create a task |
| GET | `/api/tasks/{id}` | Get single task |
| PUT | `/api/tasks/{id}` | Update a task |
| DELETE | `/api/tasks/{id}` | Soft delete a task |
| GET | `/api/tasks/trashed` | View deleted tasks |
| POST | `/api/tasks/{id}/restore` | Restore deleted task |

### Filter Parameters for GET /api/tasks
| Parameter | Values | Example |
|-----------|--------|---------|
| `status` | pending, in_progress, completed | `?status=pending` |
| `priority` | low, medium, high | `?priority=high` |
| `due_before` | date (Y-m-d) | `?due_before=2026-12-31` |
| `per_page` | number | `?per_page=10` |

---

## API Usage Examples

### Register
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Safiya","email":"safiya@example.com","password":"password","password_confirmation":"password"}'
```

### Login
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'
```

### Create Task
```bash
curl -X POST http://127.0.0.1:8000/api/tasks \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"title":"My Task","priority":"high","status":"pending","due_date":"2026-12-01"}'
```

### Get All Tasks with Filter
```bash
curl -X GET "http://127.0.0.1:8000/api/tasks?status=pending&priority=high" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Soft Delete a Task
```bash
curl -X DELETE http://127.0.0.1:8000/api/tasks/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Restore a Deleted Task
```bash
curl -X POST http://127.0.0.1:8000/api/tasks/1/restore \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Design Decisions

**Sanctum over Passport**
Sanctum is Laravel's recommended solution for simple API token authentication. Passport adds full OAuth2 complexity which is unnecessary for this project.

**Soft Deletes**
Tasks are never permanently removed from the database. They can always be restored, which mirrors real-world applications where accidental deletions need recovery.

**Role Middleware**
Role-based access is enforced at the route level using a custom `RoleMiddleware`, not just after login. This means a user cannot access `/admin/dashboard` by typing the URL directly — they get redirected automatically.

**Query Scopes**
Filtering logic is kept inside the Task model as query scopes (`scopeOfStatus`, `scopeOfPriority`, `scopeDueBefore`). This keeps controllers clean and makes filters reusable.

**Task Assignment**
Tasks have both a `user_id` (the admin who created it) and an `assigned_to` (the user it is assigned to). This allows admins to manage all tasks while users only see their own assigned tasks.

**withQueryString() on Pagination**
Pagination preserves active filters by using `withQueryString()` on all paginated results. This means when a user filters by status and goes to page 2, the filter stays active.
