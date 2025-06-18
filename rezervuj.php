<?php
session_start();
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $den = $_POST['day'];
    $datum_rezervacie = $_POST['datum_rezervacie'];
    $hodina = $_POST['hour'];
    $meno = $_POST['name'];
    $telefon = $_POST['phone'];
    $osoby = $_POST['persons'];
    $vytvorene = date("Y-m-d H:i:s"); // pre stĺpec "datum"

    try {
        $stmt = $pdo->prepare("INSERT INTO rezervacie (den, datum_rezervacie, hodina, meno, telefon, osoby, datum) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$den, $datum_rezervacie, $hodina, $meno, $telefon, $osoby, $vytvorene]);

        $_SESSION['success_message'] = "Rezervácia bola úspešne vytvorená.";
        header('Location: dakujeme.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Chyba pri ukladaní rezervácie: " . $e->getMessage();
        header('Location: chyba.php');
        exit();
    }
}
?>
