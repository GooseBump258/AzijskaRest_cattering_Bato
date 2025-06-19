<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kontakty"; // zmeň podľa názvu tvojej DB

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Nepodarilo sa pripojiť: " . $conn->connect_error);
}

$meno = $_POST['name'];
$email = $_POST['email'];
$telefon = $_POST['phone'];
$sprava = $_POST['message'];

$sql = "INSERT INTO kontakty (meno, email, telefon, sprava)
        VALUES ('$meno', '$email', '$telefon', '$sprava')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Správa bola úspešne odoslaná!');window.location.href='contact.php';</script>";
} else {
    echo "Chyba: " . $conn->error;
}

$conn->close();
?>
