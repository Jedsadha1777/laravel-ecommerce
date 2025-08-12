# Mini Commerce API

Laravel REST API with clean architecture - Admin & Shop domains separation.

## Architecture

```
app/
├── Domains/
│   ├── Admin/           # Admin domain
│   │   ├── Auth/       # Admin authentication
│   │   └── Product/    # Product management
│   ├── Shop/           # Shop domain
│   │   ├── Catalog/    # Product catalog
│   │   ├── Order/      # Order management
│   │   └── User/       # User authentication
│   └── Shared/         # Shared models
│       └── Product/
├── Helpers/            # Helper classes
└── Traits/             # Reusable traits
```

## Installation

```bash
# Clone repository
git clone <repository-url>
cd <project-folder>

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Passport setup
php artisan passport:install

# Run server
php artisan serve
```

## Default Credentials

| Type | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| User | user@example.com | password |

## API Endpoints

### Admin API `/admin-api/v1`
```
POST   /login                    # Admin login
POST   /logout                   # Admin logout (auth)
GET    /products                 # List products (auth)
POST   /products                 # Create product (auth)
GET    /products/{id}            # Get product (auth)
PUT    /products/{id}            # Update product (auth)
DELETE /products/{id}            # Delete product (auth)
```

### Shop API `/shop-api/v1`
```
POST   /register                 # User registration
POST   /login                    # User login
POST   /logout                   # User logout (auth)
GET    /profile                  # User profile (auth)
GET    /catalog                  # Product catalog
GET    /catalog/{slug}           # Product detail
GET    /orders                   # User orders (auth)
POST   /orders                   # Place order (auth)
GET    /orders/{orderNumber}     # Order detail (auth)
POST   /orders/{orderNumber}/cancel # Cancel order (auth)
```

## Tech Stack

- **Framework:** Laravel 11.x
- **Authentication:** Laravel Passport (Bearer Token)
- **Database:** MySQL
- **Architecture:** Service-Repository Pattern
- **API:** RESTful with versioning

## 📝 Request Examples

### Login
```json
POST /admin-api/v1/login
{
    "email": "admin@example.com",
    "password": "password"
}
```

### Create Product (Admin)
```json
POST /admin-api/v1/products
Authorization: Bearer {token}
{
    "name": "iPhone 15",
    "slug": "iphone-15",
    "price": 35900,
    "stock": 100,
    "sku": "IPH15",
    "description": "Latest iPhone"
}
```

### Place Order (User)
```json
POST /shop-api/v1/orders
Authorization: Bearer {token}
{
    "items": [
        {
            "product_id": "uuid",
            "quantity": 2
        }
    ],
    "shipping_address": "123 Bangkok, Thailand",
    "notes": "Please call before delivery"
}
```

## 🧪 Testing

```bash
# Run tests
php artisan test

# With coverage
php artisan test --coverage
```

## 📄 License

MIT