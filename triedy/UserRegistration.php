<?php

class UserRegistration {
    private $pdo;
    private $errors = [];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($data) {
        $username = trim($data['username']);
        $email = trim($data['email']);
        $password = $data['password'];
        $confirm_password = $data['confirm_password'];

        $this->errors = [];

        if (empty($username)) {
            $this->errors[] = "Používateľské meno je povinné.";
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $this->errors[] = "Používateľské meno musí mať 3 až 50 znakov.";
        }

        if (empty($email)) {
            $this->errors[] = "E-mail je povinný.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Zadajte platný e-mailový formát.";
        }

        if (empty($password)) {
            $this->errors[] = "Heslo je povinné.";
        } elseif (strlen($password) < 8) {
            $this->errors[] = "Heslo musí mať aspoň 8 znakov.";
        } elseif ($password !== $confirm_password) {
            $this->errors[] = "Heslá sa nezhodujú.";
        }

        if (!empty($this->errors)) {
            return false;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $email]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $this->errors[] = "Používateľské meno alebo e-mail už existuje. Vyberte si iné.";
                return false;
            }

            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $success = $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password
            ]);

            if (!$success) {
                $this->errors[] = "Chyba pri ukladaní používateľa do databázy.";
                return false;
            }

            return true;

        } catch (PDOException $e) {
            $this->errors[] = "Chyba databázy pri registrácii.";
            return false;
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}
