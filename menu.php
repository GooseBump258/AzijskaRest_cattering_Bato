<?php
// menu.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/triedy/db_config.php');

$menu_items = [];
try {
    // Načítanie všetkých položiek menu z databázy
    $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category, name");
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC); // Použijeme FETCH_ASSOC pre ľahší prístup k stĺpcom
} catch (PDOException $e) {
    $menu_items = [];
    $_SESSION['error_message'] = "Nastala chyba pri načítaní menu. Prosím, skúste to neskôr.";
}

// Zoskupenie položiek podľa kategórie
$categorized_menu = [
    'Predjedlá' => [],
    'Hlavné jedlá' => [],
    'Dezerty' => [],
    'Nápoje' => []
];

foreach ($menu_items as $item) {
    if (isset($categorized_menu[$item['category']])) {
        $categorized_menu[$item['category']][] = $item;
    } else {
        $categorized_menu[$item['category']][] = $item;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Victory - Naše Menu</title>
        <meta name="description" content="">
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
            .menu-category-section {
                margin-top: 50px;
                padding-bottom: 30px;
            }
            .menu-category-section h2 {
                text-align: left; /* zarovnanie naľavo */
                margin-bottom: 40px;
                font-size: 3.2em; /* väčší nadpis */
                color: #333;
                font-weight: 700;
                border-left: 6px solid #d9534f; /* farebný pruh vľavo pre lepší efekt */
                padding-left: 15px;
            }
            .food-item {
    width: 250px;
    height: 280px;
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    margin: 0 auto 30px auto;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.food-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.food-item img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    background-color: #f0f0f0;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    flex-shrink: 0;
}

.food-item .price {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: #d9534f;
    color: white;
    padding: 6px 12px;
    border-radius: 30px;
    font-weight: 700;
    font-size: 16px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    z-index: 10;
}

.food-item .text-content {
    padding: 15px 20px;
    flex-grow: 1;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.food-item .text-content h4 {
    font-size: 18px;
    margin: 0 0 10px 0;
    color: #222;
    font-weight: 700;
    letter-spacing: 0.02em;
    text-align: center;
}

.food-item .text-content p {
    font-size: 13px;
    color: #555;
    line-height: 1.4;
    margin: 0;
    text-align: center;
}

/* Aby cena bola vo vrchnej časti nad obrázkom */
.food-item .price {
    position: absolute;
    top: 10px;
    right: 10px;
}

            .food-item .text-content {
                padding: 50px;
            }
            .food-item .text-content h4 {
                font-size: 20px;
                margin-top: 0;
                margin-bottom: 8px;
                color: #333;
            }
            .food-item .text-content p {
                font-size: 0.9em;
                color: #666;
                line-height: 1.5;
            }
            .owl-nav {
                margin-top: 20px;
                text-align: center;
            }
            .owl-nav button {
                background: #f7f7f7;
                color: #555;
                border: 1px solid #ddd;
                padding: 8px 15px;
                margin: 0 5px;
                border-radius: 4px;
                transition: all 0.3s ease;
            }
            .owl-nav button:hover {
                background: #eee;
                color: #333;
            }
            .owl-dots {
                text-align: center;
                margin-top: 15px;
            }
            .owl-dots button.owl-dot {
                width: 12px;
                height: 12px;
                background: #ccc;
                border-radius: 50%;
                display: inline-block;
                margin: 0 5px;
                transition: all 0.3s ease;
            }
            .owl-dots button.owl-dot.active {
                background: #d9534f;
            }
        </style>
    </head>

<body>
    <?php require_once 'parts/header.html' ?>
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Naše Menu</h1>
                    <p>Objavte naše vynikajúce jedlá a nápoje pripravené s láskou a z čerstvých surovín.</p>
                </div>
            </div>
        </div>
    </section>

    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="container"><p class="message error">' . htmlspecialchars($_SESSION['error_message']) . '</p></div>';
        unset($_SESSION['error_message']);
    }
    if (isset($_SESSION['success_message'])) {
        echo '<div class="container"><p class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</p></div>';
        unset($_SESSION['success_message']);
    }
    ?>

    <?php if (empty($menu_items)): ?>
        <div class="container text-center" style="padding: 50px 0;">
            <p>Momentálne nemáme žiadne položky v menu. Skúste nás navštíviť neskôr!</p>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1): ?>
                <p><a href="manage_menu.php" class="btn btn-primary" style="margin-top: 20px;">Pridať položky do menu</a></p>
            <?php endif; ?>
        </div>
    <?php else: ?>

        <?php
        foreach ($categorized_menu as $category_name => $items_in_category):
            if (empty($items_in_category)) {
                continue;
            }
        ?>
        <section class="menu-category-section <?php echo strtolower(str_replace(' ', '-', $category_name)); ?>-menu">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="menu-content">
                            <div class="row">
                                
                                <div class="col-md-7 <?php echo (strtolower(str_replace(' ', '-', $category_name)) == 'lunch') ? 'col-md-offset-5' : ''; ?>">
                                    <h2><?php echo htmlspecialchars($category_name); ?> Menu</h2>
                                    <div id="owl-<?php echo strtolower(str_replace(' ', '-', $category_name)); ?>" class="owl-carousel owl-theme">
                                        <?php foreach ($items_in_category as $item): ?>
                                            <div class="item col-md-12">
                                                <div class="food-item">
                                                    <?php if ($item['image_path']): ?>
                                                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                    <?php else: ?>
                                                        <img src="https://via.placeholder.com/400x200?text=Bez+obrázka" alt="Bez obrázka">
                                                    <?php endif; ?>
                                                    <div class="price"><?php echo htmlspecialchars(number_format($item['price'], 2, ',', ' ')) . ' €'; ?></div>
                                                    <div class="text-content">
                                                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                                        <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endforeach; ?>

    <?php endif; ?>

    <?php require_once 'rezervacie/rezervacny_formular_original.php'; ?>

    <?php require_once 'parts/footer.html' ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

    <script src="js/vendor/bootstrap.min.js"></script>

    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.scroll-link').on('click', function(event){
                event.preventDefault();
                var sectionID = $(this).attr("data-id");
                scrollToID('#' + sectionID, 750);
            });
            $('.scroll-top').on('click', function(event) {
                event.preventDefault();
                $('html, body').animate({scrollTop:0}, 'slow');
            });
            $('#nav-toggle').on('click', function (event) {
                event.preventDefault();
                $('#main-nav').toggleClass("open");
            });

            <?php foreach (array_keys($categorized_menu) as $category): ?>
                <?php $carousel_id = strtolower(str_replace(' ', '-', $category)); ?>
                <?php if (!empty($categorized_menu[$category])): ?>
                    $('#owl-<?php echo $carousel_id; ?>').owlCarousel({
                        loop:true,
                        margin:30,
                        nav:true,
                        pagination: false,
                        dots: true,
                        responsive:{
                            0:{items:1},
                            600:{items:2},
                            1000:{items:4}
                        }
                    });
                <?php endif; ?>
            <?php endforeach; ?>
        });

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
            console = { log: function() { } };
        }
    </script>
</body>
</html>
