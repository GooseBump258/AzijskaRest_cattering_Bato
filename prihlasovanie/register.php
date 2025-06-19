<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../triedy/db_config.php');

if (isset($_POST['register_submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

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

    // Pokračuj len ak nie sú chyby
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $email]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors[] = "Používateľské meno alebo e-mail už existuje. Vyberte si iné.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password])) {
                    $_SESSION['registration_success'] = "Registrácia bola úspešná! Teraz sa môžete prihlásiť.";
                    header('Location: ../index.php');
                    exit();
                } else {
                    $errors[] = "Chyba pri ukladaní používateľa do databázy.";
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Chyba databázy pri registrácii.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['registration_error'] = implode('<br>', $errors);
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
            box-sizing: border-box;
        }
        .register-form button {
            background-color: #5cb85c;
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
    if (isset($_SESSION['registration_error'])) {
        echo '<p class="error-message">' . htmlspecialchars($_SESSION['registration_error']) . '</p>';
        unset($_SESSION['registration_error']);
    }
    if (isset($_SESSION['registration_success'])) {
        echo '<p class="success-message">' . htmlspecialchars($_SESSION['registration_success']) . '</p>';
        unset($_SESSION['registration_success']);
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

    <p class="login-link">Už máte účet? <a href="../index.php" id="login-link">Prihláste sa tu</a>.</p>
</div>

</body>
</html>
