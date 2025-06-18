<?php
// register.php
// Spustenie PHP session musí byť na začiatku každého súboru, pred akýmkoľvek HTML výstupom
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Načítanie konfiguračného súboru pre pripojenie k databáze
require_once 'triedy/db_config.php';

// Spracovanie formulára po jeho odoslaní
if (isset($_POST['register_submit'])) {
    // Získanie a orezanie (trim) vstupných údajov z formulára
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = []; // Pole na ukladanie prípadných chybových správ

    // --- Validácia vstupu (server-side) ---
    if (empty($username)) {
        $errors[] = "Používateľské meno je povinné.";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = "Používateľské meno musí mať 3 až 50 znakov.";
    }

    if (empty($email)) {
        $errors[] = "E-mail je povinný.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Zadajte platný e-mailový formát.";
    }

    if (empty($password)) {
        $errors[] = "Heslo je povinné.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Heslo musí mať aspoň 8 znakov.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Heslá sa nezhodujú.";
    }

    // --- Ak nie sú žiadne validačné chyby, pokračuj s databázovými operáciami ---
    if (empty($errors)) {
        try {
            // Skontroluj, či používateľské meno alebo e-mail už existujú v databáze
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $email]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Používateľské meno alebo e-mail už existuje. Vyberte si iné.";
            } else {
                // Hašovanie hesla pre bezpečné uloženie do databázy
                // PASSWORD_BCRYPT je odporúčaný algoritmus pre hašovanie hesiel
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Vloženie nového používateľa do databázy pomocou prepared statement (pre bezpečnosť)
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password])) {
                    // Úspešná registrácia
                    $_SESSION['registration_success'] = "Registrácia bola úspešná! Teraz sa môžete prihlásiť.";
                    header('Location: index.php'); // Presmeruj na domovskú stránku (alebo priamo na prihlasovacie okno)
                    exit(); // Dôležité pre ukončenie skriptu po presmerovaní
                } else {
                    $errors[] = "Chyba pri ukladaní používateľa do databázy.";
                }
            }
        } catch (PDOException $e) {
            // Zachytenie databázových chýb (napr. problém s pripojením)
            $errors[] = "Chyba databázy pri registrácii: " . $e->getMessage();
            // V produkčnom prostredí by si mal túto chybu zalogovať a zobraziť používateľovi všeobecnejšiu správu
        }
    }

    // Ak sa vyskytli chyby (validačné alebo databázové), ulož ich do session
    if (!empty($errors)) {
        $_SESSION['registration_error'] = implode('<br>', $errors); // Spoj všetky chyby do jedného reťazca
        // V tomto prípade nesmerujeme, aby sa chybové správy zobrazili na rovnakej stránke
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrácia</title>
    <link rel="stylesheet" href="path/to/your/bootstrap.min.css">
    <style>
        /* Základné štýly pre registračný formulár */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .register-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .register-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .register-form input[type="text"],
        .register-form input[type="email"],
        .register-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Pre správne zobrazenie paddingu a borderu */
        }
        .register-form button {
            background-color: #5cb85c; /* Zelená farba pre registráciu */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .register-form button:hover {
            background-color: #4cae4c;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
        .success-message {
            color: green;
            margin-bottom: 15px;
            text-align: center;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2>Registrácia</h2>

        <?php
        // Zobrazenie chybovej správy, ak existuje
        if (isset($_SESSION['registration_error'])) {
            echo '<p class="error-message">' . htmlspecialchars($_SESSION['registration_error']) . '</p>';
            unset($_SESSION['registration_error']); // Vymazať správu po zobrazení
        }
        ?>

        <form class="register-form" action="register.php" method="POST">
            <label for="username">Používateľské meno:</label>
            <input type="text" id="username" name="username" required value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">

            <label for="password">Heslo:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Potvrdenie hesla:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit" name="register_submit">Registrovať sa</button>
        </form>

        <p class="login-link">Už máte účet? <a href="index.php" id="login-link">Prihláste sa tu</a>.</p>
    </div>

</body>
</html>