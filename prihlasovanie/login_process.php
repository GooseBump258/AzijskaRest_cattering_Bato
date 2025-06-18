<?php
// login_process.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../triedy/db_config.php');

if (isset($_POST['username_email']) && isset($_POST['password'])) {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];

    try {
        // Zmenené: Teraz vyberáme stĺpec 'role' namiesto 'is_admin'
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = :user_param OR email = :email_param");
        $stmt->execute([
            'user_param' => $username_email,
            'email_param' => $username_email
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Používame FETCH_ASSOC pre lepšiu čitateľnosť

        if ($user && password_verify($password, $user['password'])) {
            // Prihlásenie úspešné
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role']; // Uložíme rolu používateľa do session
            $_SESSION['success_message'] = "Úspešne ste sa prihlásili!";

            // Presmerovanie na základe roly
            if ($user['role'] === 'admin') {
                header('Location: ../admin_panel.php'); // Admin panel
            } elseif ($user['role'] === 'reception') {
                header('Location: ../rezervacie.php'); // Stránka pre recepciu
            } else {
                header('Location: ../index.php'); // Bežná domovská stránka pre ostatných používateľov
            }
            exit();
        } else {
            $_SESSION['error_message'] = "Nesprávne používateľské meno/e-mail alebo heslo.";
            header('Location: ../index.php'); // Vráti používateľa na prihlasovaciu stránku (alebo index.php)
            exit();
        }
    } catch (PDOException $e) {
        // V produkčnom prostredí by si mal logovať chybu namiesto jej zobrazovania.
        // error_log("Login DB error: " . $e->getMessage());
        $_SESSION['error_message'] = "Nastala chyba pri prihlasovaní. Prosím, skúste to neskôr.";
        header('Location: ../index.php'); // Vráti používateľa na prihlasovaciu stránku (alebo index.php)
        exit();
    }
} else {
    $_SESSION['error_message'] = "Prosím, vyplňte prihlasovacie údaje.";
    header('Location: ../index.php'); // Vráti používateľa na prihlasovaciu stránku (alebo index.php)
    exit();
}
?>