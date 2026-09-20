USE ecosprout_db;

-- Run this once when upgrading an existing EcoSprout database.
-- It does not delete tables, orders or customer data.

DROP PROCEDURE IF EXISTS repair_ecosprout_checkout;

DELIMITER $$

CREATE PROCEDURE repair_ecosprout_checkout()
BEGIN
    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'orders'
          AND column_name = 'customer_name'
    ) THEN
        ALTER TABLE orders
            MODIFY customer_name VARCHAR(100) NULL;
    END IF;

    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = DATABASE()
          AND table_name = 'orders'
          AND column_name = 'customer_phone'
    ) THEN
        ALTER TABLE orders
            MODIFY customer_phone VARCHAR(20) NULL;
    END IF;

    ALTER TABLE payments
        MODIFY payment_method ENUM(
            'Cash on Delivery',
            'Bank Transfer',
            'Card Simulation',
            'Card Payment'
        ) NOT NULL;
END$$

DELIMITER ;

CALL repair_ecosprout_checkout();
DROP PROCEDURE repair_ecosprout_checkout;
