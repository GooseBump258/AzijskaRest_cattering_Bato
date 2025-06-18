<?php
session_start();
require_once 'triedy/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $den = isset($_POST['day']) ? $_POST['day'] : '';
    $hodina = isset($_POST['hour']) ? $_POST['hour'] : '';
    $meno = isset($_POST['name']) ? $_POST['name'] : '';
    $telefon = isset($_POST['phone']) ? $_POST['phone'] : '';
    $osoby = isset($_POST['persons']) ? $_POST['persons'] : '';

    if (empty($den) || empty($hodina) || empty($meno) || empty($telefon) || empty($osoby)) {
        echo "<script>alert('Prosím, vyplňte všetky polia.'); window.history.back();</script>";
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO rezervacie (den, hodina, meno, telefon, osoby) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$den, $hodina, $meno, $telefon, $osoby]);

        $den = htmlspecialchars($den);
        $hodina = htmlspecialchars($hodina);
        $meno = htmlspecialchars($meno);
        $telefon = htmlspecialchars($telefon);
        $osoby = htmlspecialchars($osoby);

        $message = "Rezervácia úspešne odoslaná!\n"
                 . "Deň: $den\n"
                 . "Hodina: $hodina\n"
                 . "Meno: $meno\n"
                 . "Telefón: $telefon\n"
                 . "Počet osôb: $osoby";

        echo "<script>
            alert(`$message`);
            window.location.href = 'index.php';  // presmerovanie späť na formulár
        </script>";
        exit();

    } catch (PDOException $e) {
        $error = htmlspecialchars($e->getMessage());
        echo "<script>alert('Chyba pri ukladaní rezervácie: $error'); window.history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('Neplatný prístup.'); window.location.href = 'index.php';</script>";
    exit();
}
?>
