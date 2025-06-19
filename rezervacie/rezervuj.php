<?php
session_start();
require_once(__DIR__ . '/../triedy/db_config.php');

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
        // Spočítame počet rezervácií na daný deň a hodinu
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM rezervacie WHERE den = ? AND hodina = ?");
        $stmt->execute([$den, $hodina]);
        $pocetRezervacii = $stmt->fetchColumn();

        if ($pocetRezervacii >= 3) {
            // Dni týždňa v poradí (ak máš iný formát, uprav)
            $dni = ['Pondelok', 'Utorok', 'Streda', 'Štvrtok', 'Piatok', 'Sobota', 'Nedeľa'];

            // Nájdeme index zvoleného dňa
            $indexDna = array_search($den, $dni);

            // Vypočítame ďalšie dostupné dni (napr. ďalších 7 dní po vybranom)
            $dostupneDni = [];
            for ($i = 1; $i <= 7; $i++) {
                $idx = ($indexDna + $i) % count($dni);
                $dostupneDni[] = $dni[$idx];
            }

            $dostupneDniStr = implode(", ", $dostupneDni);

            echo "<script>
                alert('Je nám to ľúto, ale na deň $den o $hodina už nie je možná žiadna rezervácia.\\n'
                    + 'Ďalšie dostupné dni na rezerváciu sú: $dostupneDniStr');
                window.history.back();
            </script>";
            exit();
        }

        // Ak je miesto, vložíme rezerváciu
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
            window.location.href = '../index.php';
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
