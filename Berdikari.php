<?php
require __DIR__ . '/db.php';

try {
    $products = $pdo->query(
        'SELECT category_name, brand_name, product_name, description, image_path, image_alt
         FROM products
         ORDER BY category_name, brand_name, id'
    )->fetchAll();
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

$catalogue = [];
foreach ($products as $product) {
    $catalogue[$product['category_name']][$product['brand_name']][] = $product;
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
