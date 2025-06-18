<?php
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datum_rezervacie = $_POST['datum_rezervacie'];
    $hodina = $_POST['hour'];
    $meno = trim($_POST['name']);
    $telefon = trim($_POST['phone']);
    $osoby = $_POST['persons'];

    // Jednoduchá základná validácia
    if (!$datum_rezervacie || !$hodina || !$meno || !$telefon || !$osoby) {
        $_SESSION['error_message'] = "Prosím vyplňte všetky povinné polia.";
        header('Location: rezervacny_formular.php');
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO rezervacie (datum_rezervacie, hodina, meno, telefon, osoby) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$datum_rezervacie, $hodina, $meno, $telefon, $osoby]);

        $_SESSION['success_message'] = "Rezervácia bola úspešne vytvorená.";
        header('Location: rezervacny_formular.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Chyba pri uložení rezervácie: " . $e->getMessage();
        header('Location: rezervacny_formular.php');
        exit();
    }
} else {
    header('Location: rezervacny_formular.php');
    exit();
}
