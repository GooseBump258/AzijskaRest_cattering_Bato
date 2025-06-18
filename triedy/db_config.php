<?php


// Nastavenia databázy
$host = 'localhost'; 
$db   = 'restauracia_projekt'; 
$user = 'root';       
$pass = '';      
$charset = 'utf8mb4';          

// Data Source Name (DSN) pre PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Voliteľné nastavenia PDO uprava
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Zapne výnimky pre chyby, čo je lepšie pre ladenie
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Predvolený spôsob načítania dát (ako asociatívne pole)
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Zabraňuje emulácii prepared statements (lepšie zabezpečenie)
];

try {
    // Vytvorenie novej PDO inštancie (pripojenie k databáze)
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Ak nastane chyba pri pripojení, zastav skript a vypíš chybovú správu
    // V produkčnom prostredí by si mal namiesto 'die()' logovať chybu a zobraziť používateľovi všeobecnú správu
    die("Chyba pripojenia k databáze: " . $e->getMessage());
}
?>

