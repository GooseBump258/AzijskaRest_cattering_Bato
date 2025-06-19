<?php
session_start();
require_once(__DIR__ . '/triedy/db_config.php'); // <--- Uisti sa, že toto existuje a správne nastavuje $pdo

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Nemáte oprávnenie pre prístup k admin panelu.";
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    $_SESSION['error_message'] = "Nemáte oprávnenie pre prístup k admin panelu.";
    header('Location: index.php');
    exit();
}

$success_message = '';
$error_message = '';
$edit_item = null; 

if (isset($_POST['submit_item'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']); // Zabezpečenie, že cena je číslo
    $category = trim($_POST['category']);
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;

    if (empty($name) || empty($description) || empty($price) || empty($category)) {
        $error_message = "Všetky polia okrem obrázka sú povinné!";
    } elseif ($price <= 0) {
        $error_message = "Cena musí byť kladné číslo.";
    } else {
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/"; 
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $uploadOk = 1;

            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
            } else {
                $error_message = "Súbor nie je obrázok.";
                $uploadOk = 0;
            }

            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $error_message = "Prepáčte, povolené sú len JPG, JPEG, PNG & GIF súbory.";
                $uploadOk = 0;
            }

            if ($_FILES["image"]["size"] > 500000) { // Max 500KB
                $error_message = "Prepáčte, váš súbor je príliš veľký.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                } else {
                    $error_message = "Nastala chyba pri nahrávaní obrázka.";
                }
            }
        }

        try {
            if ($item_id > 0) {
                $sql = "UPDATE menu_items SET name = :name, description = :description, price = :price, category = :category";
                if ($image_path) {
                    $sql .= ", image_path = :image_path";
                }
                $sql .= " WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $params = ['name' => $name, 'description' => $description, 'price' => $price, 'category' => $category, 'id' => $item_id];
                if ($image_path) {
                    $params['image_path'] = $image_path;
                }
                $stmt->execute($params);
                $success_message = "Položka menu bola úspešne aktualizovaná.";
            } else {
                
                $stmt = $pdo->prepare("INSERT INTO menu_items (name, description, price, category, image_path) VALUES (:name, :description, :price, :category, :image_path)");
                $stmt->execute([
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'category' => $category,
                    'image_path' => $image_path
                ]);
                $success_message = "Nová položka menu bola úspešne pridaná.";
            }
        } catch (PDOException $e) {
            $error_message = "Chyba databázy: " . $e->getMessage();
            
        }
    }
}

// Zmazanie položky
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = :id");
        $stmt->execute(['id' => $item_id]);
        $success_message = "Položka menu bola úspešne zmazaná.";
    } catch (PDOException $e) {
        $error_message = "Chyba pri mazaní položky: " . $e->getMessage();
    }
    header('Location: manage_menu.php'); 
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = :id");
        $stmt->execute(['id' => $item_id]);
        $edit_item = $stmt->fetch();
        if (!$edit_item) {
            $error_message = "Položka na úpravu nebola nájdená.";
        }
    } catch (PDOException $e) {
        $error_message = "Chyba pri načítaní položky na úpravu: " . $e->getMessage();
    }
}

$menu_items = [];
try {
    $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category, name");
    $menu_items = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Chyba pri načítaní položiek menu: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Správa Menu - Admin Panel</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/templatemo-style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding-top: 50px; }
        .admin-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 50px auto;
        }
        .admin-container h2 { text-align: center; color: #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group input[type="file"] {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .btn-primary { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 1.1em; transition: background-color 0.3s ease; }
        .btn-primary:hover { background-color: #0056b3; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        table, th, td { border: 1px solid #eee; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .action-buttons a { margin-right: 10px; text-decoration: none; color: #007bff; }
        .action-buttons a.delete { color: red; }
        .action-buttons a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <?php require_once 'parts/header.html'; ?>

    <div class="admin-container">
        <h2>Správa položiek menu</h2>

        <?php if ($success_message): ?>
            <p class="message success"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <p class="message error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <h3><?php echo ($edit_item ? 'Upraviť' : 'Pridať novú'); ?> položku menu</h3>
        <form action="manage_menu.php" method="POST" enctype="multipart/form-data">
            <?php if ($edit_item): ?>
                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($edit_item['id']); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Názov položky:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($edit_item['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Popis:</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($edit_item['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Cena (€):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($edit_item['price'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Kategória:</label>
                <select id="category" name="category" required>
                    <option value="">Vyberte kategóriu</option>
                    <option value="Predjedlá" <?php echo (($edit_item['category'] ?? '') == 'Predjedlá') ? 'selected' : ''; ?>>Predjedlá</option>
                    <option value="Hlavné jedlá" <?php echo (($edit_item['category'] ?? '') == 'Hlavné jedlá') ? 'selected' : ''; ?>>Hlavné jedlá</option>
                    <option value="Dezerty" <?php echo (($edit_item['category'] ?? '') == 'Dezerty') ? 'selected' : ''; ?>>Dezerty</option>
                    <option value="Nápoje" <?php echo (($edit_item['category'] ?? '') == 'Nápoje') ? 'selected' : ''; ?>>Nápoje</option>
                    </select>
            </div>
            <div class="form-group">
                <label for="image">Obrázok položky:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if ($edit_item && $edit_item['image_path']): ?>
                    <p>Aktuálny obrázok: <img src="<?php echo htmlspecialchars($edit_item['image_path']); ?>" alt="Obrázok" style="width: 100px; height: auto;"></p>
                <?php endif; ?>
            </div>
            <button type="submit" name="submit_item" class="btn-primary"><?php echo ($edit_item ? 'Uložiť zmeny' : 'Pridať položku'); ?></button>
        </form>

        <hr>

        <h3>Existujúce položky menu</h3>
        <?php if (empty($menu_items)): ?>
            <p>Zatiaľ žiadne položky v menu.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Názov</th>
                        <th>Kategória</th>
                        <th>Cena</th>
                        <th>Popis</th>
                        <th>Obrázok</th>
                        <th>Akcie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menu_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['price'], 2, ',', ' ')) . ' €'; ?></td>
                            <td><?php echo nl2br(htmlspecialchars($item['description'])); ?></td>
                            <td>
                                <?php if ($item['image_path']): ?>
                                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 50px; height: auto;">
                                <?php else: ?>
                                    Bez obrázka
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <a href="manage_menu.php?action=edit&id=<?php echo $item['id']; ?>">Upraviť</a>
                                <a href="manage_menu.php?action=delete&id=<?php echo $item['id']; ?>" class="delete" onclick="return confirm('Naozaj chcete zmazať túto položku?');">Zmazať</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <p style="margin-top: 30px;"><a href="admin_panel.php">Naspäť do Admin Panela</a></p>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
</body>
</html>