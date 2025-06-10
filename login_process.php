<?php
// login_process.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_config.php';

if (isset($_POST['username_email']) && isset($_POST['password'])) {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];

    try {
        // Zmenené: Vyberáme aj stĺpec is_admin
        $stmt = $pdo->prepare("SELECT id, username, password, is_admin FROM users WHERE username = :user_param OR email = :email_param");
        $stmt->execute([
            'user_param' => $username_email,
            'email_param' => $username_email
        ]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Prihlásenie úspešné
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin']; // Uložíme informáciu o adminovi do session
            $_SESSION['success_message'] = "Úspešne ste sa prihlásili!";

            // Presmerujeme admina na admin panel, iných na domovskú stránku
            if ($user['is_admin']) {
                header('Location: admin_panel.php'); // Admin panel
            } else {
                header('Location: index.php'); // Bežná domovská stránka
            }
            exit();
        } else {
            $_SESSION['error_message'] = "Nesprávne používateľské meno/e-mail alebo heslo.";
            header('Location: index.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Chyba databázy pri prihlasovaní: " . $e->getMessage();
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = "Prosím, vyplňte prihlasovacie údaje.";
    header('Location: index.php');
    exit();
}
?>