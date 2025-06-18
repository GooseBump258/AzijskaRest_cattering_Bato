<?php
session_start();
require_once(__DIR__ . '/triedy/db_config.php'); // <--- Uisti sa, že toto existuje a správne nastavuje $pdo

// Kontrola, či je používateľ prihlásený
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Nemáte oprávnenie pre prístup k admin panelu.";
    header('Location: index.php');
    exit();
}

// Overenie roly z databázy
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    $_SESSION['error_message'] = "Nemáte oprávnenie pre prístup k admin panelu.";
    header('Location: index.php');
    exit();
}

// Ak sa kód dostane sem, používateľ je prihlásený a je admin
$admin_username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Victory</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/templatemo-style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding-top: 50px; }
        .admin-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
        }
        .admin-container h2 { color: #333; margin-bottom: 20px; }
        .admin-container p { color: #555; font-size: 1.1em; }
        .admin-container .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
        .admin-container .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <?php require_once 'parts/header.html'; // Includujeme hlavičku ?>

    <div class="admin-container">
        <h2>Vitajte v Admin Paneli, <?php echo $admin_username; ?>!</h2>
        <p>Tu budete môcť spravovať položky menu, recenzie a ďalší obsah.</p>
        <p>Táto stránka je prístupná len pre administrátorov.</p>
        <a href="manage_menu.php" class="btn">Spravovať menu</a>
        <a href="index.php" class="btn">Návrat na Domov</a>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
</body>
</html>