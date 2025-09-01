# Expense Module for Laravel (Modular Architecture)

## Overview

This is a self-contained Expense Management module built with Laravel 11+ using modular architecture via `nwidart/laravel-modules`. It includes full CRUD operations for managing expenses with clean architecture best practices including service layer, form request validation, events, notifications, and optional Swagger documentation and testing support.

---

## ✅ Features

* Modular folder structure (Modules/Expenses)
* CRUD operations for expenses
* Uses UUID as primary key
* Form Request validation
* Service layer abstraction
* Event (`ExpenseCreated`) with Listener
* Database Notification (upon expense creation)
* Filtering by category & date range
* API routes only
* Optional OpenAPI (Scribe)
* Feature test using PHPUnit
* JSON API formatting via Laravel Resource

---

## ⚙️ Tech Stack

* Laravel 11/12
* PHP 8.2+
* MySQL/PostgreSQL
* `nwidart/laravel-modules`
* Laravel Resources & Events
* Laravel Notifications
* Scribe (OpenAPI)
* PHPUnit

---

## 🔧 Setup Instructions

### 1. Clone the Repo & Install

```bash
git clone https://github.com/anfathhifans/Laravel-Expense-Module.git
cd Laravel-Expense-Module
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure `.env`

Update your DB credentials:

```env
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_pass
```

### 3. Install Modules & Dependencies

```bash
composer require nwidart/laravel-modules
php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Serve the App

```bash
php artisan serve
```

---

## 🔌 API Endpoints

All routes are prefixed with `/api/expenses`

| Method | Endpoint           | Description        |
| ------ | ------------------ | ------------------ |
| GET    | /api/expenses      | List all expenses  |
| POST   | /api/expenses      | Create new expense |
| PUT    | /api/expenses/{id} | Update an expense  |
| DELETE | /api/expenses/{id} | Delete an expense  |

---

## 🧪 Running Tests

Run tests:
```
php artisan test
```
---

## 📘 API Documentation (Scribe)

Generate docs:
```
php artisan scribe:generate
```

Docs will be available at:
```
/docs
```

Then visit:
```
http://localhost:8000/docs
```

---

## 📦 Folder Structure

```
Modules/
  Expenses/
    Config/
    Database/
    Entities/
    Events/
    Http/
      Controllers/
      Requests/
    Listeners/
    Notifications/
    Providers/
    Resources/
    Routes/
    Services/
```

---

## 🎯 Assumptions

* Categories are stored as string enums (no separate table)
* UUID is used for expense IDs
* Auth is out of scope
* Only one user context assumed

---

## 🧩 Events & Notifications Setup

### Event: `ExpenseCreated`

* Located at: `Modules/Expenses/Events/ExpenseCreated.php`

```php
namespace Modules\Expenses\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Expenses\Entities\Expense;

class ExpenseCreated {
    use SerializesModels;

    public function __construct(public Expense $expense) {}
}
```

### Listener: `SendExpenseNotification`

* Located at: `Modules/Expenses/Listeners/SendExpenseNotification.php`

```php
namespace Modules\Expenses\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Expenses\Events\ExpenseCreated;
use Modules\Expenses\Notifications\ExpenseNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User; // Assumes a User model exists

class SendExpenseNotification implements ShouldQueue {
    public function handle(ExpenseCreated $event): void {
        $user = User::first();
        Notification::route('database', $user->id)
            ->notify(new ExpenseNotification($event->expense));
    }
}
```

### Notification: `ExpenseNotification`

* Located at: `Modules/Expenses/Notifications/ExpenseNotification.php`

```php
namespace Modules\Expenses\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Expenses\Entities\Expense;

class ExpenseNotification extends Notification {
    use Queueable;

    public function __construct(public Expense $expense) {}

    public function via($notifiable): array {
        return ['database'];
    }

    public function toDatabase($notifiable): DatabaseMessage {
        return new DatabaseMessage([
            'title' => $this->expense->title,
            'amount' => $this->expense->amount,
            'expense_date' => $this->expense->expense_date,
        ]);
    }
}
```

### Trigger the Event in Service

* Modify: `Modules/Expenses/Services/ExpenseService.php`

```php
use Modules\Expenses\Events\ExpenseCreated;

public function create(array $data): Expense {
    $expense = Expense::create($data);
    event(new ExpenseCreated($expense));
    return $expense;
}
```

### Register the Event and Listener

* File: `Modules/Expenses/Providers/EventServiceProvider.php`

```php
namespace Modules\Expenses\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Expenses\Events\ExpenseCreated;
use Modules\Expenses\Listeners\SendExpenseNotification;

class EventServiceProvider extends ServiceProvider {
    protected $listen = [
        ExpenseCreated::class => [
            SendExpenseNotification::class,
        ],
    ];
}
```

### Add Provider in `module.json`

```json
"providers": [
  "Modules\\Expenses\\Providers\\EventServiceProvider"
]
```
