<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
require_once __DIR__ . '/triedy/db_config.php';
    $meno = $_POST['name'];
    $email = $_POST['email'];
    $telefon = $_POST['phone'];
    $sprava = $_POST['message'];
    $datum = date("Y-m-d H:i:s");

    $sql = "INSERT INTO kontakty (meno, email, telefon, sprava, datum) 
            VALUES ('$meno', '$email', '$telefon', '$sprava', '$datum')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Správa bola úspešne odoslaná.'); window.location.href='contact.php';</script>";
    } else {
        echo "Chyba: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    header("Location: kontakt.php");
    exit();
}
?>

