-- Import this file into the existing `test` database in phpMyAdmin.
-- It creates the table only. Then open seed_products.php once to import 56 products.

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    brand_name VARCHAR(100) NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    description VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    image_alt VARCHAR(150) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_catalogue_item (category_name, brand_name, product_name, image_path),
    KEY catalogue_grouping (category_name, brand_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
