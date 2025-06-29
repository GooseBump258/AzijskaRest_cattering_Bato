<?php
session_start();
require_once(__DIR__ . '/../triedy/db_config.php');

// Kontrola, či je používateľ prihlásený a má rolu 'reception'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'reception') {
    $_SESSION['error_message'] = 'Nemáte oprávnenie pre prístup k tejto stránke. Prihláste sa ako recepcia.';
    header('Location: login.php');
    exit();
}

$rezervacie = [];
$error_message = '';

// Spracovanie požiadavky na vymazanie rezervácie
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM rezervacie WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: rezervacie.php");
        exit();
    } catch (PDOException $e) {
        $error_message = 'Chyba pri mazaní rezervácie: ' . $e->getMessage();
    }
}

try {
    $stmt = $pdo->query("SELECT id, den, hodina, meno, osoby, telefon, datum FROM rezervacie ORDER BY datum DESC, hodina DESC");
    $rezervacie = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = 'Chyba pri načítaní rezervácií: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Správa Rezervácií - Recepcia</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/fontAwesome.css">
    <link rel="stylesheet" href="css/templatemo-style.css">
    <style>
        .page-heading {
            background-color: #f7f7f7;
            padding: 50px 0;
            text-align: center;
            margin-bottom: 30px;
        }
        .page-heading h1 {
            color:rgb(0, 0, 0);
            font-size: 3em;
            margin-bottom: 10px;
        }
        .page-heading p {
            color:rgb(0, 0, 0);
            font-size: 1.1em;
        }
        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow-x: auto;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .message.error {
            color: red;
            background-color: #ffe0e0;
            border: 1px solid red;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn-logout {
            background-color: #d9534f;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .btn-logout:hover {
            background-color: #c9302c;
            color: white;
            text-decoration: none;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
        }
        .btn-delete:hover {
            background-color: #c82333;
            color: white;
        }
    </style>
</head>
<body>

    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Správa Rezervácií</h1>
                    <p>Vitajte, <?php echo htmlspecialchars($_SESSION['username']); ?>  prehľad všetkých prijatých rezervácií nižšie.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <?php if ($error_message): ?>
            <p class="message error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <?php if (empty($rezervacie)): ?>
            <div class="text-center" style="padding: 30px 0;">
                <p>Momentálne nemáme žiadne rezervácie v systéme.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Deň</th>
                            <th>Čas</th>
                            <th>Meno</th>
                            <th>Tel. číslo</th>
                            <th>Počet osôb</th>
                            <th>Vytvorené</th>
                            <th>Akcia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rezervacie as $rez): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rez['id']); ?></td>
                                <td><?php echo htmlspecialchars($rez['den']); ?></td>
                                <td><?php echo htmlspecialchars($rez['hodina']); ?></td>
                                <td><?php echo htmlspecialchars($rez['meno']); ?></td>
                                <td><?php echo htmlspecialchars($rez['telefon']); ?></td>
                                <td><?php echo htmlspecialchars($rez['osoby']); ?></td>
                                <td><?php echo htmlspecialchars($rez['datum']); ?></td>
                                <td>
                                    <a href="rezervacie.php?delete=<?php echo $rez['id']; ?>" class="btn-delete" onclick="return confirm('Naozaj chcete zmazať túto rezerváciu?');">Zmazať</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="text-center" style="margin-bottom: 50px;">
            <a href="../prihlasovanie/logout.php" class="btn btn-logout">Odhlásiť sa z recepcie</a>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
