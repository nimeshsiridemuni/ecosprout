CREATE DATABASE IF NOT EXISTS ecosprout_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ecosprout_db;

CREATE TABLE roles (
    role_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(30) NOT NULL,

    CONSTRAINT uq_roles_name
        UNIQUE (role_name)
) ENGINE=InnoDB;

CREATE TABLE categories (
    category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(80) NOT NULL,
    description VARCHAR(255),

    CONSTRAINT uq_categories_name
        UNIQUE (category_name)
) ENGINE=InnoDB;

CREATE TABLE services (
    service_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    description TEXT,
    base_price DECIMAL(10, 2) NOT NULL,
    duration_minutes INT UNSIGNED,
    service_status ENUM(
        'Available',
        'Unavailable'
    ) NOT NULL DEFAULT 'Available',

    CONSTRAINT chk_services_price
        CHECK (base_price >= 0)
) ENGINE=InnoDB;

CREATE TABLE workshops (
    workshop_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    workshop_title VARCHAR(150) NOT NULL,
    description TEXT,
    workshop_date DATE NOT NULL,
    start_time TIME NOT NULL,
    location VARCHAR(200) NOT NULL,
    capacity INT UNSIGNED NOT NULL,
    registration_fee DECIMAL(10, 2) NOT NULL DEFAULT 0,
    workshop_status ENUM(
        'Scheduled',
        'Completed',
        'Cancelled'
    ) NOT NULL DEFAULT 'Scheduled',

    CONSTRAINT chk_workshops_capacity
        CHECK (capacity > 0),

    CONSTRAINT chk_workshops_fee
        CHECK (registration_fee >= 0)
) ENGINE=InnoDB;

CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id INT UNSIGNED NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    account_status ENUM(
        'Active',
        'Inactive',
        'Suspended'
    ) NOT NULL DEFAULT 'Active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uq_users_email
        UNIQUE (email),

    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id)
        REFERENCES roles(role_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE plants (
    plant_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    plant_name VARCHAR(100) NOT NULL,
    scientific_name VARCHAR(120),
    description TEXT,
    care_instructions TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT UNSIGNED NOT NULL DEFAULT 0,
    image_name VARCHAR(255),
    plant_status ENUM(
        'Active',
        'Inactive',
        'Out of Stock'
    ) NOT NULL DEFAULT 'Active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_plants_price
        CHECK (price >= 0),

    CONSTRAINT fk_plants_category
        FOREIGN KEY (category_id)
        REFERENCES categories(category_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE orders (
    order_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT UNSIGNED NOT NULL,
    order_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    delivery_address VARCHAR(255) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    delivery_fee DECIMAL(10, 2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status ENUM(
        'Pending',
        'Confirmed',
        'Processing',
        'Dispatched',
        'Completed',
        'Cancelled'
    ) NOT NULL DEFAULT 'Pending',

    CONSTRAINT chk_orders_subtotal
        CHECK (subtotal >= 0),

    CONSTRAINT chk_orders_delivery_fee
        CHECK (delivery_fee >= 0),

    CONSTRAINT chk_orders_total
        CHECK (total_amount >= 0),

    CONSTRAINT fk_orders_customer
        FOREIGN KEY (customer_id)
        REFERENCES users(user_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE service_bookings (
    booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT UNSIGNED NOT NULL,
    service_id INT UNSIGNED NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    service_address VARCHAR(255) NOT NULL,
    customer_notes TEXT,
    booking_status ENUM(
        'Pending',
        'Confirmed',
        'Completed',
        'Cancelled'
    ) NOT NULL DEFAULT 'Pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_service_bookings_customer
        FOREIGN KEY (customer_id)
        REFERENCES users(user_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_service_bookings_service
        FOREIGN KEY (service_id)
        REFERENCES services(service_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE workshop_registrations (
    registration_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    workshop_id INT UNSIGNED NOT NULL,
    customer_id INT UNSIGNED NOT NULL,
    registered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    registration_status ENUM(
        'Registered',
        'Attended',
        'Cancelled'
    ) NOT NULL DEFAULT 'Registered',

    CONSTRAINT uq_workshop_customer
        UNIQUE (workshop_id, customer_id),

    CONSTRAINT fk_registrations_workshop
        FOREIGN KEY (workshop_id)
        REFERENCES workshops(workshop_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_registrations_customer
        FOREIGN KEY (customer_id)
        REFERENCES users(user_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE inquiries (
    inquiry_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT UNSIGNED,
    assigned_staff_id INT UNSIGNED,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(150) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    staff_response TEXT,
    inquiry_status ENUM(
        'New',
        'In Progress',
        'Responded',
        'Closed'
    ) NOT NULL DEFAULT 'New',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    responded_at DATETIME,

    CONSTRAINT fk_inquiries_customer
        FOREIGN KEY (customer_id)
        REFERENCES users(user_id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_inquiries_staff
        FOREIGN KEY (assigned_staff_id)
        REFERENCES users(user_id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
    order_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    plant_id INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    line_total DECIMAL(10, 2) NOT NULL,

    CONSTRAINT uq_order_items_order_plant
        UNIQUE (order_id, plant_id),

    CONSTRAINT chk_order_items_quantity
        CHECK (quantity > 0),

    CONSTRAINT chk_order_items_price
        CHECK (unit_price >= 0),

    CONSTRAINT chk_order_items_total
        CHECK (line_total >= 0),

    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id)
        REFERENCES orders(order_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_order_items_plant
        FOREIGN KEY (plant_id)
        REFERENCES plants(plant_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE payments (
    payment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    payment_method ENUM(
        'Cash on Delivery',
        'Bank Transfer',
        'Card Payment'
    ) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_status ENUM(
        'Pending',
        'Paid',
        'Failed',
        'Refunded'
    ) NOT NULL DEFAULT 'Pending',
    transaction_reference VARCHAR(100),
    paid_at DATETIME,

    CONSTRAINT uq_payments_order
        UNIQUE (order_id),

    CONSTRAINT uq_payments_reference
        UNIQUE (transaction_reference),

    CONSTRAINT chk_payments_amount
        CHECK (amount >= 0),

    CONSTRAINT fk_payments_order
        FOREIGN KEY (order_id)
        REFERENCES orders(order_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_plants_category
    ON plants(category_id);

CREATE INDEX idx_plants_name
    ON plants(plant_name);

CREATE INDEX idx_orders_customer_date
    ON orders(customer_id, order_date);

CREATE INDEX idx_orders_status
    ON orders(order_status);

CREATE INDEX idx_bookings_customer_date
    ON service_bookings(customer_id, booking_date);

CREATE INDEX idx_workshops_date
    ON workshops(workshop_date);

CREATE INDEX idx_inquiries_status_date
    ON inquiries(inquiry_status, created_at);