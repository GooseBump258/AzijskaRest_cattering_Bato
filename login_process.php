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
        // --- ZMENA JE TU: Použi dva rôzne placeholdery ---
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :user_param OR email = :email_param");

        // --- A tu odovzdaj hodnoty pre oba ---
        $stmt->execute([
            'user_param' => $username_email,
            'email_param' => $username_email
        ]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['success_message'] = "Úspešne ste sa prihlásili!";
            header('Location: index.php');
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