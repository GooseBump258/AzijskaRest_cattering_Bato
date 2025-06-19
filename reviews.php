<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/triedy/db_config.php');
require_once(__DIR__ . '/triedy/addreview.php');

$is_logged_in = isset($_SESSION['user_id']);
$current_username = $is_logged_in ? htmlspecialchars($_SESSION['username']) : '';

$reviewsClass = new Reviews($pdo);
$reviews = $reviewsClass->fetchAll();

if (isset($_POST['submit_review'])) {
    if (!$is_logged_in) {
        $_SESSION['error_message'] = "Pre pridanie recenzie sa musíte prihlásiť.";
        header('Location: reviews.php');
        exit();
    }

    $addReview = new AddReview($pdo);
    $addReview->setUserId($_SESSION['user_id']);
    $addReview->setReviewData($_POST['rating'], $_POST['review_text']);

    if ($addReview->save()) {
        $_SESSION['success_message'] = "Vaša recenzia bola úspešne pridaná!";
        header('Location: reviews.php');
        exit();
    } else {
        $_SESSION['error_message'] = implode('<br>', $addReview->getErrors());
        header('Location: reviews.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Victory - Recenzie</title>
    <!-- tvoj CSS a JS odkazy z pôvodného kódu -->
</head>
<body>
    <?php require_once 'parts/header.html'; ?>

    <section class="page-heading">
        <div class="container">
            <h1>Recenzie zákazníkov</h1>
            <p>Prečítajte si, čo si o nás myslia naši zákazníci, alebo pridajte vlastnú recenziu.</p>
        </div>
    </section>

    <section class="reviews-section">
        <div class="container">
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<p class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</p>';
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo '<p class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
                unset($_SESSION['error_message']);
            }
            ?>

            <?php if ($is_logged_in): ?>
                <div class="add-review-form">
                    <h3>Pridať vašu recenziu</h3>
                    <form action="reviews.php" method="POST">
                        <label for="rating">Vaše hodnotenie:</label>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 hviezdičiek">★</label>
                            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 hviezdičky">★</label>
                            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 hviezdičky">★</label>
                            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 hviezdičky">★</label>
                            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 hviezdička">★</label>
                        </div>
                        <label for="review_text">Vaša recenzia:</label>
                        <textarea id="review_text" name="review_text" rows="6" placeholder="Napíšte svoju recenziu tu..." required></textarea>
                        <button type="submit" name="submit_review">Odoslať recenziu</button>
                    </form>
                </div>
            <?php else: ?>
                <p class="message error">Pre pridanie recenzie sa musíte <a href="#" id="login-button-for-review">prihlásiť</a> alebo <a href="prihlasovanie/register.php">zaregistrovať</a>.</p>
            <?php endif; ?>

            <h3>Existujúce recenzie</h3>
            <?php if (empty($reviews)): ?>
                <p>Zatiaľ nemáme žiadne recenzie. Buďte prvý, kto pridá recenziu!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="reviewer-info">
                            <h4><?php echo htmlspecialchars($review['username']); ?></h4>
                            <div class="rating">
                                <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                    ★
                                <?php endfor; ?>
                                <?php for ($i = 0; $i < (5 - $review['rating']); $i++): ?>
                                    ☆
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="review-text"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                        <p class="review-date">Odoslané: <?php echo date('d.m.Y H:i', strtotime($review['created_at'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once 'parts/footer.html'; ?>

    <!-- tvoj JS z pôvodného kódu -->

</body>
</html>
