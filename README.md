# Fourfront Money Tracker API

A RESTful API built with **PHP Laravel** for the Fourfront Management Backend Assessment.
The system allows users to manage multiple wallets and track income/expense transactions.

---

## Tech Stack

| Tool               | Purpose                |
| ------------------ | ---------------------- |
| PHP Laravel 12     | API Framework          |
| MySQL              | Database               |
| Eloquent ORM       | Database relationships |
| Laravel Validation | Input validation       |

---

## Setup Instructions

### 1. Clone the repository

```bash
git clone https://github.com/Coder-Edwin/fourfront-backend.git
cd fourfront-backend
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

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fourfront_money_tracker
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Start the server

```bash
php artisan serve
```

API is now running at `http://127.0.0.1:8000`

---

## Database Structure

```
users
  id, name, email, phone, timestamps

wallets
  id, user_id (FK), name, currency, timestamps

transactions
  id, wallet_id (FK), type (income|expense), amount, description, timestamps
```

---

## API Endpoints

### Users

#### Create a User

```
POST /api/users
```

Request body:

```json
{
    "name": "Edwin Coder",
    "email": "edwin@fourfront.com",
    "phone": "0712345678"
}
```

Response `201`:

```json
{
    "message": "User created successfully.",
    "user": {
        "id": 1,
        "name": "Edwin Coder",
        "email": "edwin@fourfront.com",
        "phone": "0712345678"
    }
}
```

---

#### View User Profile

```
GET /api/users/{id}
```

Response `200`:

```json
{
    "user": {
        "id": 1,
        "name": "Edwin Coder",
        "email": "edwin@fourfront.com",
        "phone": "0712345678"
    },
    "wallets": [
        {
            "id": 1,
            "name": "Business Wallet",
            "currency": "KES",
            "balance": 35000
        }
    ],
    "total_balance": 35000
}
```

---

### Wallets

#### Create a Wallet

```
POST /api/users/{user}/wallets
```

Request body:

```json
{
    "name": "Business Wallet",
    "currency": "KES"
}
```

Response `201`:

```json
{
    "message": "Wallet created successfully.",
    "wallet": {
        "id": 1,
        "name": "Business Wallet",
        "currency": "KES"
    }
}
```

---

#### View a Wallet

```
GET /api/wallets/{wallet}
```

Response `200`:

```json
{
    "wallet": {
        "id": 1,
        "name": "Business Wallet",
        "currency": "KES",
        "balance": 35000
    },
    "transactions": [
        {
            "id": 1,
            "type": "income",
            "amount": "50000.00",
            "description": "Monthly salary"
        },
        {
            "id": 2,
            "type": "expense",
            "amount": "15000.00",
            "description": "Office rent"
        }
    ]
}
```

---

### Transactions

#### Add a Transaction

```
POST /api/wallets/{wallet}/transactions
```

Request body:

```json
{
    "type": "income",
    "amount": 50000,
    "description": "Monthly salary"
}
```

> `type` must be `income` or `expense`
> `amount` must be a positive number greater than 0

Response `201`:

```json
{
    "message": "Transaction added successfully.",
    "transaction": {
        "id": 1,
        "type": "income",
        "amount": 50000,
        "description": "Monthly salary"
    },
    "wallet_balance": 50000
}
```

---

## Balance Calculation Logic

Balance is calculated dynamically on the `Wallet` model:

```php
public function getBalanceAttribute()
{
    $income  = $this->transactions()->where('type', 'income')->sum('amount');
    $expense = $this->transactions()->where('type', 'expense')->sum('amount');
    return $income - $expense;
}
```

- **Income** adds to the balance
- **Expense** subtracts from the balance
- Balance is never stored in the database — always calculated fresh

---

## Validation Rules

| Field         | Rules                                   |
| ------------- | --------------------------------------- |
| `name`        | Required, string                        |
| `email`       | Required, valid email, unique           |
| `phone`       | Optional, string                        |
| `wallet name` | Required, string                        |
| `currency`    | Optional, defaults to KES               |
| `type`        | Required, must be `income` or `expense` |
| `amount`      | Required, numeric, minimum 0.01         |
| `description` | Optional, string                        |

---

## Commit History

| Commit | Description                                                            |
| ------ | ---------------------------------------------------------------------- |
| 1      | Initialized Laravel project and configured database                    |
| 2      | Created User, Wallet, Transaction models and migrations                |
| 3      | Set up database relationships between models                           |
| 4      | Implemented UserController, WalletController and TransactionController |
| 5      | Defined all API routes and fixed base Controller class                 |
| 6      | Added README with API documentation                                    |
