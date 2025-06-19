<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../triedy/db_config.php');
require_once(__DIR__ . '/../triedy/UserRegistration.php');

$registration = new UserRegistration($pdo);

if (isset($_POST['register_submit'])) {
    $result = $registration->register($_POST);

    if ($result) {
        $_SESSION['registration_success'] = "Registrácia bola úspešná! Teraz sa môžete prihlásiť.";
        header('Location: ../index.php');
        exit();
    } else {
        $_SESSION['registration_error'] = implode('<br>', $registration->getErrors());
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrácia</title>
    <link rel="stylesheet" href="path/to/your/bootstrap.min.css" />
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
        <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

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
