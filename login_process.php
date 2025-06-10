<?php
// login_process.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_config.php'; // Načítanie konfiguračného súboru pre pripojenie k DB

// Skontroluj, či bol formulár odoslaný metódou POST a obsahuje potrebné dáta
if (isset($_POST['username_email']) && isset($_POST['password'])) {
    $username_email = trim($_POST['username_email']); // Používateľské meno alebo e-mail
    $password = $_POST['password'];

    try {
        // Vyhľadaj používateľa v databáze podľa používateľského mena ALEBO e-mailu
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username_email OR email = :username_email");
        $stmt->execute(['username_email' => $username_email]);
        $user = $stmt->fetch(); // Načítanie prvého nájdeného používateľa

        // Overenie, či používateľ existuje a či sa zhoduje heslo (pomocou password_verify)
        if ($user && password_verify($password, $user['password'])) {
            // Prihlásenie úspešné
            $_SESSION['user_id'] = $user['id'];       // Ulož ID používateľa do session
            $_SESSION['username'] = $user['username']; // Ulož meno používateľa do session
            $_SESSION['success_message'] = "Úspešne ste sa prihlásili!"; // Prípadná správa pre používateľa
            header('Location: index.php'); // Presmeruj na domovskú stránku
            exit();
        } else {
            // Nesprávne prihlasovacie údaje (používateľ neexistuje alebo heslo nesedí)
            $_SESSION['error_message'] = "Nesprávne používateľské meno/e-mail alebo heslo.";
            header('Location: index.php'); // Presmeruj späť na domovskú stránku (kde sa zobrazí chyba v modálnom okne)
            exit();
        }
    } catch (PDOException $e) {
        // Chyba pri práci s databázou
        $_SESSION['error_message'] = "Chyba databázy pri prihlasovaní: " . $e->getMessage();
        // V produkcii by si túto chybu zalogoval a používateľovi zobrazil všeobecnú správu
        header('Location: index.php');
        exit();
    }
} else {
    // Formulár nebol odoslaný správne (napr. chýbali údaje)
    $_SESSION['error_message'] = "Prosím, vyplňte prihlasovacie údaje.";
    header('Location: index.php');
    exit();
}
?>