<?php
// One-time importer: reads the original static T1.html and saves every product to MySQL.
require __DIR__ . '/db.php';

$html = file_get_contents(__DIR__ . '/T1.html');
if ($html === false) {
    exit('Cannot read T1.html. Keep the original file in this folder while importing.');
}

libxml_use_internal_errors(true);
$document = new DOMDocument();
$document->loadHTML('<?xml encoding="UTF-8">' . $html);
$xpath = new DOMXPath($document);

$pdo->exec(file_get_contents(__DIR__ . '/database.sql'));
$insert = $pdo->prepare(
    'INSERT INTO products (category_name, brand_name, product_name, description, image_path, image_alt)
     VALUES (:category, :brand, :name, :description, :image, :alt)
     ON DUPLICATE KEY UPDATE description = VALUES(description), image_alt = VALUES(image_alt)'
);

$count = 0;
foreach ($xpath->query('//details[contains(concat(" ", normalize-space(@class), " "), " category-box ")]') as $categoryBox) {
    $categoryNode = $xpath->query('./summary[contains(@class, "category-header")]', $categoryBox)->item(0);
    if (!$categoryNode) continue;
    $category = trim($categoryNode->textContent);

    foreach ($xpath->query('./div[contains(@class, "brand-group")]/details[contains(@class, "brand-box")]', $categoryBox) as $brandBox) {
        $brandNode = $xpath->query('./summary[contains(@class, "brand-header")]', $brandBox)->item(0);
        if (!$brandNode) continue;
        $brand = trim($brandNode->textContent);

        foreach ($xpath->query('./div[contains(@class, "product-grid")]/div', $brandBox) as $card) {
            $image = $xpath->query('./img', $card)->item(0);
            $name = $xpath->query('./h4', $card)->item(0);
            $description = $xpath->query('./p', $card)->item(0);
            if (!$image || !$name || !$description) continue;

            $insert->execute([
                'category' => $category,
                'brand' => $brand,
                'name' => trim($name->textContent),
                'description' => trim($description->textContent),
                'image' => $image->getAttribute('src'),
                'alt' => $image->getAttribute('alt'),
            ]);
            $count++;
        }
    }
}

echo '<h1>Import complete</h1><p>' . $count . ' product records were saved to the <code>products</code> table.</p><p><a href="T1.php">Open the SQL-powered product page</a></p>';
