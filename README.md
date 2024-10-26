# Laravel User Management API

A RESTful API built with Laravel 10 for user management with JWT authentication.

## Requirements

- PHP ^8.1
- PostgreSQL
- Composer
- Laravel 10.x
- JWT Auth

## Installation

1. Clone the repository
```bash
git clone https://github.com/HarisTanone/Backend-api
```

2. Install dependencies
```bash
composer install
```

3. Copy the environment file
```bash
cp .env.example .env
```

4. Configure your PostgreSQL database connection in `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Generate application key
```bash
php artisan key:generate
```

6. Generate JWT secret
```bash
php artisan jwt:secret
```

7. Run migrations
```bash
php artisan migrate
```

## Project Structure

The project follows a service-repository pattern with the following key components:

- `app/Http/Controllers/`
  - `AuthController.php` - Handles authentication
  - `UserController.php` - Manages user operations

- `app/Services/`
  - `UserService.php` - Business logic for user management

## API Endpoints

### Authentication

#### Login
```
POST /api/login
```
Parameters:
- `username` (required|string|min:4|max:100)
- `password` (required|string|min:8|max:100)

### User Management

All endpoints require JWT authentication.

#### Get All Users
```
GET /api/users
```

#### Get Specific User
```
GET /api/users/{id}
```

#### Create User
```
POST /api/users
```
Parameters:
- `name` (required|string|min:4|max:100)
- `username` (required|string|min:4|max:100|unique)
- `password` (required|string|min:8|max:100)
- `confirm_password` (required|string|min:8|max:100|same:password)

#### Update User
```
PUT /api/users/{id}
```
Parameters:
- `name` (required|string|min:4|max:100)
- `username` (required|string|min:4|max:100|unique)

#### Update Password
```
PUT /api/users/{id}/password
```
Parameters:
- `password` (required|string|min:8|max:100)
- `confirm_password` (required|string|min:8|max:100|same:password)

#### Delete User
```
DELETE /api/users/{id}
```
Parameters:
- `confirm_password` (required|string|min:8|max:100)

## Testing

The project includes unit tests for all user operations. Run tests using:

```bash
php artisan test
```

Test cases cover:
- User listing
- User creation
- User details retrieval
- User update
- Password update
- Input validation
- Authentication

## Security

- All endpoints (except login) require JWT authentication
- Password hashing using Laravel's Hash facade
- Input validation for all requests
