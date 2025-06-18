<?php
// rezervacie.php
session_start();
require_once 'db_config.php';

// pripojenie k databáze
$conn = new mysqli($servername, $username, $password, $dbname);

// kontrola pripojenia
if ($conn->connect_error) {
    die("Chyba pripojenia: " . $conn->connect_error);
}

// ziskanie údajov z formulára
$den = $_POST['day'];
$hodina = $_POST['hour'];
$meno = $_POST['name'];
$telefon = $_POST['phone'];
$osoby = $_POST['persons'];

// SQL dotaz
$sql = "INSERT INTO rezervacie (den, hodina, meno, telefon, osoby)
        VALUES ('$den', '$hodina', '$meno', '$telefon', '$osoby')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Rezervácia bola úspešná!\\nMeno: $meno\\nTelefón: $telefon\\nDeň: $den\\nHodina: $hodina\\nPočet osôb: $osoby');
        window.location.href = 'index.php';
        </script>";
} else {
    echo "Chyba: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

