<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'triedy/db_config.php';

    $meno = $_POST['name'];
    $email = $_POST['email'];
    $telefon = $_POST['phone'];
    $sprava = $_POST['message'];
    $datum = date("Y-m-d H:i:s");

    try {
        $sql = "INSERT INTO kontakty (meno, email, telefon, sprava, datum) 
                VALUES (:meno, :email, :telefon, :sprava, :datum)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':meno' => $meno,
            ':email' => $email,
            ':telefon' => $telefon,
            ':sprava' => $sprava,
            ':datum' => $datum
        ]);

        echo "<script>alert('Správa bola úspešne odoslaná.'); window.location.href='contact.php';</script>";
    } catch (PDOException $e) {
        echo "Chyba pri ukladaní správy: " . $e->getMessage();
    }
} else {
    header("Location: contact.php");
    exit();
}
?>

