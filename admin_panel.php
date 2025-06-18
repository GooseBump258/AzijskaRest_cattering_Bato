<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/triedy/db_config.php');

$menu_items = [];
try {
    $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category, name");
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $menu_items = [];
    $_SESSION['error_message'] = "Nastala chyba pri načítaní menu.";
}

$allowed_categories = ['Raňajky', 'Obed', 'Večera'];

$categorized_menu = [
    'Raňajky' => [],
    'Obed' => [],
    'Večera' => []
];

foreach ($menu_items as $item) {
    if (in_array($item['category'], $allowed_categories)) {
        $categorized_menu[$item['category']][] = $item;
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8" />
    <title>Menu</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; }
        h2 { margin-top: 40px; }
        .category { margin-bottom: 40px; }
        .food-item {
            background: white;
            border-radius: 6px;
            box-shadow: 0 0 8px #ccc;
            margin-bottom: 20px;
            overflow: hidden;
            max-width: 350px;
        }
        .food-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }
        .food-info {
            padding: 10px;
        }
        .food-info h3 {
            margin: 0 0 5px;
            font-size: 1.2em;
        }
        .food-info p {
            margin: 0 0 10px;
            color: #555;
            font-size: 0.9em;
            min-height: 40px;
        }
        .price {
            font-weight: bold;
            color: #d9534f;
            font-size: 1em;
        }
        .items-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
    </style>
</head>
<body>

<?php
if (isset($_SESSION['error_message'])) {
    echo '<p style="color:red;">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
    unset($_SESSION['error_message']);
}

foreach ($categorized_menu as $category => $items):
    if (empty($items)) continue;
?>
    <div class="category">
        <h2><?php echo htmlspecialchars($category); ?></h2>
        <div class="items-wrapper">
            <?php foreach ($items as $item): ?>
                <div class="food-item">
                    <img src="<?php echo htmlspecialchars($item['image_path'] ?: 'https://via.placeholder.com/350x250?text=No+Image'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />
                    <div class="food-info">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                        <div class="price"><?php echo number_format($item['price'], 2, ',', ' ') . ' €'; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

</body>
</html>
