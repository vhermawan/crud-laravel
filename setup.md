# Food API — Setup Steps

## Prerequisites

- PHP 8.5+
- XAMPP running (MariaDB on port 3307)
- Composer installed

---

## Step 1 — Clone & Install Dependencies

```bash
composer install
```

---

## Step 2 — Configure Environment

Copy `.env.example` to `.env` and set database config:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_SOCKET=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock
DB_DATABASE=demo_app
DB_USERNAME=root
DB_PASSWORD=
```

Generate app key:

```bash
php artisan key:generate
```

---

## Step 3 — Install API Scaffolding

Creates `routes/api.php` and installs Laravel Sanctum:

```bash
php artisan install:api
```

---

## Step 4 — Create Food Model, Migration & Controller

```bash
php artisan make:model Food -mfsc
```

Generates:
- `app/Models/Food.php` — Eloquent model
- `database/migrations/xxxx_create_food_table.php` — DB schema
- `database/factories/FoodFactory.php` — test factory
- `database/seeders/FoodSeeder.php` — seeder
- `app/Http/Controllers/FoodController.php` — controller

---

## Step 5 — Define Migration Schema

Edit `database/migrations/xxxx_create_food_table.php`:

```php
$table->id();
$table->string('name');
$table->text('description')->nullable();
$table->decimal('price', 10, 2);
$table->integer('stock')->default(0);
$table->timestamps();
```

---

## Step 6 — Set Fillable on Model

Edit `app/Models/Food.php`:

```php
protected $fillable = [
    'name',
    'description',
    'price',
    'stock',
];
```

---

## Step 7 — Create API Resource

```bash
php artisan make:resource FoodResource
```

Edit `app/Http/Resources/FoodResource.php`:

```php
return [
    'id'          => $this->id,
    'name'        => $this->name,
    'description' => $this->description,
    'price'       => $this->price,
    'stock'       => $this->stock,
    'created_at'  => $this->created_at,
    'updated_at'  => $this->updated_at,
];
```

> API Resource controls the shape of JSON response. Without it, Laravel returns all model columns by default.

---

## Step 8 — Fill Controller with CRUD Logic

Edit `app/Http/Controllers/FoodController.php` with 5 methods:

| Method | Action |
|--------|--------|
| `index` | Return all foods |
| `store` | Validate & create new food |
| `show` | Return single food by ID |
| `update` | Validate & update food |
| `destroy` | Delete food, return 204 |

---

## Step 9 — Register Routes

Edit `routes/api.php`. Two options:

**Option A — apiResource (1 line, recommended):**
```php
use App\Http\Controllers\FoodController;

Route::apiResource('foods', FoodController::class);
```

**Option B — Manual routes (explicit control):**
```php
Route::get('/foods', [FoodController::class, 'index']);
Route::post('/foods', [FoodController::class, 'store']);
Route::get('/foods/{food}', [FoodController::class, 'show']);
Route::put('/foods/{food}', [FoodController::class, 'update']);
Route::delete('/foods/{food}', [FoodController::class, 'destroy']);
```

| | `apiResource` | Manual |
|---|---|---|
| Lines of code | 1 | 5 |
| Route names | Auto (`foods.index`, etc.) | None by default |
| PATCH support | Yes | No (only PUT) |
| Per-route middleware | Limited | Full control |

---

## Step 10 — Run Migrations

```bash
php artisan migrate
```

---

## Step 11 — Start Server

```bash
php artisan serve
```

Server runs at `http://localhost:8000`.

---

## Step 12 — Test with Postman

Set header on all requests:
```
Accept: application/json
Content-Type: application/json
```

| Action | Method | URL | Body |
|--------|--------|-----|------|
| Get all foods | `GET` | `/api/foods` | — |
| Create food | `POST` | `/api/foods` | `name`, `price`, `stock`, `description` |
| Get one food | `GET` | `/api/foods/{id}` | — |
| Update food | `PUT` | `/api/foods/{id}` | fields to update |
| Delete food | `DELETE` | `/api/foods/{id}` | — |

### Example POST body

```json
{
    "name": "Nasi Goreng",
    "description": "Fried rice with egg",
    "price": 25000,
    "stock": 50
}
```

### Example response

```json
{
    "data": {
        "id": 1,
        "name": "Nasi Goreng",
        "description": "Fried rice with egg",
        "price": "25000.00",
        "stock": 50,
        "created_at": "2026-06-04T01:21:03.000000Z",
        "updated_at": "2026-06-04T01:21:03.000000Z"
    }
}
```
