# EcoSprout Nursery

EcoSprout is a monolithic PHP and MySQL web application for a plant nursery in
Kegalle, Sri Lanka. It supports customer purchases, gardening-service bookings,
workshop registrations, inquiries, staff operations and administration.

## Technology

- PHP 8.2+
- MySQL or MariaDB
- HTML5 and CSS3
- Vanilla JavaScript
- Apache through XAMPP

## Local installation

1. Copy the project to `C:\xampp\htdocs\uni\ecosprout`.
2. Start Apache and MySQL from XAMPP.
3. Create/import the database using `database/ecosprout.sql`.
4. Import `database/sample-data.sql` once if sample records are required.
5. Copy `config/local.example.php` to `config/local.php`.
6. Enter the local MySQL username and password in `config/local.php`.
7. Open `http://localhost/uni/ecosprout/index.php`.

`config/local.php` is intentionally ignored by Git because it contains local
database credentials.

## Application areas

- Public: plants, services, workshops, contact and registration
- Customer: cart, checkout, orders, bookings, workshops and inquiries
- Staff: inventory, orders, service bookings, services, workshops and inquiries
- Administrator: user accounts, roles, operational pages and reports

The `.html` files are the original frontend prototypes. The corresponding
`.php` pages are the functional database-connected application.

## Security controls

The project uses password hashing, PDO prepared statements, CSRF tokens,
session regeneration, output escaping, role checks and a database transaction
for checkout.
