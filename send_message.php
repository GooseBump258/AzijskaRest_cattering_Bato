<?php
require_once(__DIR__ . '/triedy/db_config.php'); 

$meno = $_POST['name'];
$email = $_POST['email'];
$telefon = $_POST['phone'];
$sprava = $_POST['message'];

$sql = "INSERT INTO kontakty (meno, email, telefon, sprava)
        VALUES ('$meno', '$email', '$telefon', '$sprava')";



?>
