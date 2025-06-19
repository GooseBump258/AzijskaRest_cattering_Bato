<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/triedy/db_config.php');
require_once(__DIR__ . '/triedy/add_review.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Pre pridanie recenzie sa musíte prihlásiť.";
    header('Location: reviews.php');
    exit();
}

if (isset($_POST['submit_review'])) {
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
} else {
    $_SESSION['error_message'] = "Neplatný pokus o pridanie recenzie.";
    header('Location: reviews.php');
    exit();
}
