<?php
// reviews.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_config.php'; // Pripojenie k databáze

// Skontroluj, či je používateľ prihlásený
$is_logged_in = isset($_SESSION['user_id']);
$current_username = $is_logged_in ? htmlspecialchars($_SESSION['username']) : '';

// Načítanie recenzií z databázy
$reviews = [];
try {
    // Získaj recenzie spolu s používateľským menom autora
    $stmt = $pdo->query("SELECT r.review_text, r.rating, r.created_at, u.username 
                          FROM reviews r JOIN users u ON r.user_id = u.id 
                          ORDER BY r.created_at DESC");
    $reviews = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Nepodarilo sa načítať recenzie: " . $e->getMessage();
    // V produkcii: error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Victory - Recenzie</title>
        <meta name="description" content="Recenzie od našich zákazníkov.">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/fontAwesome.css">
        <link rel="stylesheet" href="css/hero-slider.css">
        <link rel="stylesheet" href="css/owl-carousel.css">
        <link rel="stylesheet" href="css/templatemo-style.css">

        <link href="https://fonts.googleapis.com/css?family=Spectral:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">

        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>

        <style>
            /* Základné štýly z minulej odpovede pre správy */
            .message {
                padding: 12px;
                margin-bottom: 20px;
                border-radius: 5px;
                text-align: center;
                font-size: 1em;
                line-height: 1.4;
            }
            .message.success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .message.error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }

            /* Nové štýly pre sekciu recenzií */
            .reviews-section {
                padding: 60px 0;
            }
            .add-review-form {
                background-color: #f9f9f9;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.08);
                margin-bottom: 40px;
            }
            .add-review-form h3 {
                margin-top: 0;
                margin-bottom: 25px;
                color: #333;
                text-align: center;
            }
            .add-review-form label {
                display: block;
                margin-bottom: 8px;
                font-weight: bold;
                color: #555;
            }
            .add-review-form select,
            .add-review-form textarea {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-sizing: border-box;
                font-size: 16px;
            }
            .add-review-form button {
                background-color: #007bff; /* Modré tlačidlo */
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                width: 100%;
                font-size: 18px;
                transition: background-color 0.3s ease;
            }
            .add-review-form button:hover {
                background-color: #0056b3;
            }

            .review-item {
                background-color: #fff;
                padding: 25px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                margin-bottom: 30px;
            }
            .review-item .reviewer-info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }
            .review-item .reviewer-info h4 {
                margin: 0;
                color: #333;
                font-size: 1.3em;
            }
            .review-item .rating {
                color: #ffc107; /* Zlatá farba pre hviezdy */
                font-size: 1.2em;
            }
            .review-item .review-text {
                color: #666;
                line-height: 1.6;
                margin-bottom: 15px;
            }
            .review-item .review-date {
                font-size: 0.85em;
                color: #999;
                text-align: right;
            }
            .star-rating {
                direction: rtl; /* Pre zarovnanie hviezd sprava doľava */
                display: inline-block;
                unicode-bidi: bidi-override; /* Pre správne zobrazenie v starších prehliadačoch */
            }
            .star-rating input[type="radio"] {
                display: none; /* Skrytie predvolených rádio buttonov */
            }
            .star-rating label {
                font-size: 2em; /* Veľkosť hviezdy */
                color: #ccc; /* Predvolená farba hviezdy */
                cursor: pointer;
                padding: 0 5px;
            }
            .star-rating input[type="radio"]:checked ~ label,
            .star-rating label:hover,
            .star-rating label:hover ~ label {
                color: #ffc107; /* Farba hviezdy po zaškrtnutí alebo prejdení myšou */
            }
        </style>
    </head>

<body>
    <?php require_once 'parts/header.html' ?>
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Recenzie zákazníkov</h1>
                    <p>Prečítajte si, čo si o nás myslia naši zákazníci, alebo pridajte vlastnú recenziu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="reviews-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <?php
                    // Zobrazenie úspešných/chybových správ
                    if (isset($_SESSION['success_message'])) {
                        echo '<p class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</p>';
                        unset($_SESSION['success_message']);
                    }
                    if (isset($_SESSION['error_message'])) {
                        echo '<p class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
                        unset($_SESSION['error_message']);
                    }
                    ?>

                    <?php if ($is_logged_in): // Zobraz formulár len pre prihlásených používateľov ?>
                        <div class="add-review-form">
                            <h3>Pridať vašu recenziu</h3>
                            <form action="add_review.php" method="POST">
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
                        <p class="message error">Pre pridanie recenzie sa musíte <a href="#" id="login-button-for-review">prihlásiť</a> alebo <a href="register.php">zaregistrovať</a>.</p>
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
            </div>
        </div>
    </section>

    <?php require_once 'parts/footer.html' ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

    <script src="js/vendor/bootstrap.min.js"></script>

    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        // navigation click actions
        $('.scroll-link').on('click', function(event){
            event.preventDefault();
            var sectionID = $(this).attr("data-id");
            scrollToID('#' + sectionID, 750);
        });
        // scroll to top action
        $('.scroll-top').on('click', function(event) {
            event.preventDefault();
            $('html, body').animate({scrollTop:0}, 'slow');
        });
        // mobile nav toggle
        $('#nav-toggle').on('click', function (event) {
            event.preventDefault();
            $('#main-nav').toggleClass("open");
        });

        // Prihlásenie pre recenzie - ak používateľ nie je prihlásený a klikne na odkaz
        $('#login-button-for-review').on('click', function(e) {
            e.preventDefault();
            // Spusti kód, ktorý otvára prihlasovacie modálne okno
            // Predpokladáme, že máš v parts/header.html definované id "login-modal"
            $('#login-modal').css('display', 'block');
        });
    });

    // scroll function
    function scrollToID(id, speed){
        var offSet = 0;
        var targetOffset = $(id).offset().top - offSet;
        var mainNav = $('#main-nav');
        $('html,body').animate({scrollTop:targetOffset}, speed);
        if (mainNav.hasClass("open")) {
            mainNav.css("height", "1px").removeClass("in").addClass("collapse");
            mainNav.removeClass("open");
        }
    }
    if (typeof console === "undefined") {
        console = {
            log: function() { }
        };
    }
    </script>
</body>
</html>