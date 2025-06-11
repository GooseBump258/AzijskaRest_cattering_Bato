<?php
// send_message.php

// Nastav tvoj e-mail, kam sa majú posielať správy
$to_email = "samo.bato6@gmail.com"; // ZMEŇ TOTO NA SVOJ E-MAIL!

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získaj a ošetruj vstupné dáta z formulára
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Predmet e-mailu
    $subject = "Nová správa z kontaktného formulára od: " . $name;

    // Telo e-mailu
    $email_body = "Meno: " . $name . "\n";
    $email_body .= "E-mail: " . $email . "\n";
    $email_body .= "Telefón: " . $phone . "\n\n";
    $email_body .= "Správa:\n" . $message;

    // Hlavičky e-mailu
    $headers = "From: " . $email . "\r\n"; // Odosielateľ je e-mail užívateľa
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n"; // Zabezpečí správne kódovanie

    // Skús odoslať e-mail
    if (mail($to_email, $subject, $email_body, $headers)) {
        // E-mail bol úspešne odoslaný
        // Môžeš sem pridať presmerovanie na stránku s potvrdením
        header("Location: contact.php?status=success");
        exit();
    } else {
        // Nastala chyba pri odosielaní e-mailu
        header("Location: contact.php?status=error");
        exit();
    }
} else {
    // Ak sa niekto pokúsi pristupovať k tomuto súboru priamo
    header("Location: contact.php"); // Presmeruj ich späť na kontaktnú stránku
    exit();
}
?>