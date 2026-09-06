# TrackXa – Professional Shipment Tracking Platform

A complete, production-ready shipment tracking system built with PHP 8+, MySQL, Bootstrap 5, and Vanilla JavaScript.

## Features

- **Real-time Shipment Tracking** – 17 status types with full timeline
- **REST API** – Connect any e-commerce platform (ParruParrot, WooCommerce, etc.)
- **Admin Dashboard** – Full shipment management with stats and charts
- **Blog CMS** – SEO-optimized articles with categories and tags
- **Multi-language** – EN, FR, ES, DE, IT, PT, AR (RTL support)
- **Email Notifications** – Automatic alerts on status changes
- **Webhook System** – Push events to external systems
- **API Keys & Rate Limiting** – Secure multi-tenant API access
- **SEO Ready** – Sitemaps, Open Graph, Schema.org, canonical URLs

## Requirements

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache with `mod_rewrite` enabled
- PHP extensions: PDO, PDO_MySQL, cURL, mbstring, fileinfo

## Installation

### Option 1 – Web Installer (Recommended)

1. Upload all files to your web server
2. Point your domain to the `/public` folder (or set document root)
3. Visit `http://yoursite.com/install.php`
4. Fill in database credentials and admin details
5. **Delete `install.php`** after installation

### Option 2 – Manual

1. Create a MySQL database
2. Import `database/trackxa.sql`
3. Copy `config/config.php` and update:
   - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
   - `SECRET_KEY` (use a random 64-char string)
4. Set document root to the `/public` folder
5. Login at `/admin/login` with `admin@trackxa.com` / `Admin@123456`
6. **Change the password immediately**

## Folder Structure

```
trackxa/
├── api/docs/          # API documentation
├── app/
│   ├── Controllers/   # Public controllers
│   │   ├── Admin/     # Admin controllers
│   │   └── Api/       # REST API controllers
│   ├── Models/        # Database models
│   ├── Views/         # PHP templates
│   │   ├── layouts/   # main.php, admin.php, auth.php
│   │   ├── public/    # Public pages
│   │   ├── admin/     # Admin pages
│   │   └── emails/    # Email templates
│   ├── Services/      # Email, Webhook, Upload
│   ├── Helpers/       # Utility classes
│   └── Middleware/    # API auth middleware
├── config/            # Configuration files
├── core/              # Framework core (Router, DB, Session...)
├── database/          # SQL schema + seed data
├── lang/              # Translation files (7 languages)
├── public/            # Web root (CSS, JS, images, index.php)
├── storage/           # Logs, cache, uploads
└── install.php        # Web installer
```

## API Quick Start

```php
// Create a shipment from your store
$response = file_get_contents('https://yourtrackxa.com/api/v1/shipments', false,
    stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\nX-API-Key: YOUR_KEY\r\n",
        'content' => json_encode([
            'recipient_name'    => 'John Doe',
            'recipient_city'    => 'Paris',
            'recipient_address' => '10 Rue de la Paix',
            'order_number'      => 'ORD-12345',
            'weight'            => 1.5,
        ])
    ]])
);
$data = json_decode($response, true);
echo $data['tracking_number']; // TXA1A2B3C4D5E6
echo $data['tracking_url'];    // https://yourtrackxa.com/track/TXA1A2B3C4D5E6
```

## Default Admin Credentials

- **URL:** `/admin/login`
- **Email:** `admin@trackxa.com`
- **Password:** `Admin@123456`

> ⚠️ Change these immediately after installation!

## Security Checklist

- [x] CSRF protection on all forms
- [x] SQL injection protection (PDO prepared statements)
- [x] Password hashing (bcrypt cost 12)
- [x] Session security (httponly, samesite=lax)
- [x] Rate limiting on login and contact forms
- [x] API key authentication with rate limiting
- [x] Secure file upload validation (MIME type, size)
- [x] XSS protection (output escaping)
- [x] Admin activity logging
- [ ] Enable HTTPS on production
- [ ] Set strong `SECRET_KEY` in config
- [ ] Delete `install.php` after setup

## License

Proprietary – All rights reserved © 2025 TrackXa
