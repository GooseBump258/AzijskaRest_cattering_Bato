<?php
// add_review.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/triedy/db_config.php'); // Pripojenie k databáze

// Skontroluj, či je používateľ prihlásený
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Pre pridanie recenzie sa musíte prihlásiť.";
    header('Location: reviews.php'); // Presmeruj späť na stránku recenzií
    exit();
}

// Spracovanie odoslaného formulára
if (isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review_text = trim($_POST['review_text']);

    $errors = [];

    // Validácia hodnotenia
    if (!filter_var($rating, FILTER_VALIDATE_INT) || $rating < 1 || $rating > 5) {
        $errors[] = "Hodnotenie musí byť číslo od 1 do 5.";
    }

    // Validácia textu recenzie (nepovinné, ale dobré na obmedzenie dĺžky)
    if (empty($review_text)) {
        $errors[] = "Text recenzie nemôže byť prázdny.";
    } elseif (strlen($review_text) > 1000) { // Príklad: obmedzenie na 1000 znakov
        $errors[] = "Recenzia je príliš dlhá (max. 1000 znakov).";
    }

    if (empty($errors)) {
        try {
            // Vloženie recenzie do databázy
            $stmt = $pdo->prepare("INSERT INTO reviews (user_id, rating, review_text) VALUES (:user_id, :rating, :review_text)");
            if ($stmt->execute(['user_id' => $user_id, 'rating' => $rating, 'review_text' => $review_text])) {
                $_SESSION['success_message'] = "Vaša recenzia bola úspešne pridaná!";
                header('Location: reviews.php'); // Presmeruj späť na stránku recenzií
                exit();
            } else {
                $errors[] = "Chyba pri ukladaní recenzie do databázy.";
            }
        } catch (PDOException $e) {
            $errors[] = "Chyba databázy: " . $e->getMessage();
            // V produkcii: error_log($e->getMessage());
        }
    }

    // Ak sú chyby, ulož ich do session a presmeruj späť
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode('<br>', $errors);
        header('Location: reviews.php');
        exit();
    }
} else {
    // Ak nebol formulár odoslaný správne
    $_SESSION['error_message'] = "Neplatný pokus o pridanie recenzie.";
    header('Location: reviews.php');
    exit();
}
?>