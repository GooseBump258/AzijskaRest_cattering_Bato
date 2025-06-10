<?php
// logout.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Uvoľní všetky premenné relácie
session_unset();

// Zničí reláciu
session_destroy();

// Presmeruje používateľa na domovskú stránku
header('Location: index.php');
exit();
?>