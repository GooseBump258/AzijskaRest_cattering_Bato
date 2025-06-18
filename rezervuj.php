<?php
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Získanie dát z POST formulára
    $datum = isset($_POST['date']) ? $_POST['date'] : '';
    $hodina = isset($_POST['hour']) ? $_POST['hour'] : '';
    $meno = isset($_POST['name']) ? $_POST['name'] : '';
    $telefon = isset($_POST['phone']) ? $_POST['phone'] : '';
    $osoby = isset($_POST['persons']) ? $_POST['persons'] : '';

    // Validácia
    if (empty($datum) || empty($hodina) || empty($meno) || empty($telefon) || empty($osoby)) {
        $_SESSION['error_message'] = 'Prosím, vyplňte všetky polia.';
        header('Location: index.php');
        exit();
    }

    try {
        // Pridanie rezervácie do databázy
        $stmt = $pdo->prepare("INSERT INTO rezervacie (datum, hodina, meno, telefon, osoby) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$datum, $hodina, $meno, $telefon, $osoby]);

        $_SESSION['success_message'] = 'Rezervácia bola úspešne odoslaná!';
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Chyba pri ukladaní rezervácie: ' . $e->getMessage();
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = 'Neplatný prístup.';
    header('Location: index.php');
    exit();
}
