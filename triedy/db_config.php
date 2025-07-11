<?php


$host = 'localhost'; 
$db   = 'restauracia_projekt'; 
$user = 'root';       
$pass = '';      
$charset = 'utf8mb4';          

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Voliteľné nastavenia PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => true,                  
];

try {
    
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    
    die("Chyba pripojenia k databáze: " . $e->getMessage());
}
?>

