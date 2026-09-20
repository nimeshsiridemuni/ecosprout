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

1. Copy the project to `C:\xampp\htdocs\uni\ecosprout` (XAMPP) or
   `C:\wamp64\www\uni\ecosprout` (WAMP).
2. Start Apache and MySQL from XAMPP or WAMP.
3. Create/import the database using `database/ecosprout.sql`.
4. Import `database/sample-data.sql` once if sample records are required.
5. Copy `config/local.example.php` to `config/local.php`.
6. Enter the local MySQL username and password in `config/local.php`.
7. Open `http://localhost/uni/ecosprout/index.php`.

If the database was imported from an older copy of the project, run
`database/repair-existing.sql` once before testing checkout. It safely aligns
the checkout-related columns and payment method without deleting records.

## First administrator account

Register an ordinary account through the website. Then replace the email in
the following query and run it once in phpMyAdmin:

```sql
UPDATE users AS u
INNER JOIN roles AS r ON r.role_name = 'Administrator'
SET u.role_id = r.role_id
WHERE u.email = 'your-email@example.com';
```

Log out and sign in again. The account will then open the administrator
dashboard and can create Staff accounts.

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
session regeneration, output escaping, role checks and database transactions
for checkout and order cancellation. Card entry is a classroom simulation;
real card details must not be used.
