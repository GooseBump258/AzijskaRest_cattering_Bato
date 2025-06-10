<?php
// menu.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_config.php';

$menu_items = [];
try {
    // Načítanie všetkých položiek menu z databázy
    $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category, name");
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC); // Použijeme FETCH_ASSOC pre ľahší prístup k stĺpcom
} catch (PDOException $e) {
    // V produkčnom prostredí by si tu mal logovať chybu namiesto jej zobrazovania
    // error_log("Chyba pri načítaní menu: " . $e->getMessage());
    // Môžeš zobraziť používateľovi priateľskú správu, alebo prázdne menu
    $menu_items = [];
    $_SESSION['error_message'] = "Nastala chyba pri načítaní menu. Prosím, skúste to neskôr.";
}

// Zoskupenie položiek podľa kategórie
// Pre prehľadnosť si vytvoríme pole, kde kľúčom bude kategória
$categorized_menu = [
    'Predjedlá' => [],
    'Hlavné jedlá' => [],
    'Dezerty' => [],
    'Nápoje' => []
    // Pridaj sem všetky kategórie, ktoré používaš v databáze a chceš ich zobraziť
];

foreach ($menu_items as $item) {
    if (isset($categorized_menu[$item['category']])) { // Kontrola, či kategória existuje v našom definovanom zozname
        $categorized_menu[$item['category']][] = $item;
    } else {
        // Ak existuje kategória, ktorú si nepreddefinoval, pridaj ju dynamicky
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
            /* Základné štýly pre lepšie zobrazenie položiek menu - prispôsob si podľa TEMPLATE */
            .menu-category-section {
                margin-top: 50px;
                padding-bottom: 30px;
            }
            .menu-category-section h2 {
                text-align: center;
                margin-bottom: 40px;
                font-size: 2.8em;
                color: #333;
            }
            .food-item {
                background-color: #fff;
                border: 1px solid #eee;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                overflow: hidden;
                margin-bottom: 20px; /* Priestor medzi kartami */
                transition: transform 0.2s ease-in-out;
            }
            .food-item:hover {
                transform: translateY(-5px);
            }
            .food-item img {
                width: 100%;
                height: 250px; /* Zväčšená výška obrázkov pre väčšie jedlá */
                object-fit: cover; /* Zabezpečí, že obrázok pokryje plochu bez deformácie */
                border-bottom: 1px solid #eee;
            }
            .food-item .price {
                position: absolute;
                top: 10px;
                right: 10px;
                background-color: #d9534f; /* Červená farba ako v pôvodnom template */
                color: white;
                padding: 5px 10px;
                border-radius: 5px;
                font-weight: bold;
                font-size: 1.1em;
                z-index: 10;
            }
            .food-item .text-content {
                padding: 15px;
            }
            .food-item .text-content h4 {
                font-size: 1.4em;
                margin-top: 0;
                margin-bottom: 8px;
                color: #333;
            }
            .food-item .text-content p {
                font-size: 0.9em;
                color: #666;
                line-height: 1.5;
            }
            /* Úpravy pre OWL Carousel šípky a bodky */
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
                background: #d9534f; /* Aktívna bodka */
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
    // Zobrazenie chybových a úspešných správ (napr. z admin panelu alebo ak databáza zlyhá)
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
        // Iterujeme cez zoskupené kategórie a generujeme sekcie s Owl Carouselom
        foreach ($categorized_menu as $category_name => $items_in_category):
            if (empty($items_in_category)) {
                continue; // Preskočíme prázdne kategórie
            }
        ?>
        <section class="menu-category-section <?php echo strtolower(str_replace(' ', '-', $category_name)); ?>-menu">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="menu-content">
                            <div class="row">
                                <div class="col-md-5 <?php echo (strtolower(str_replace(' ', '-', $category_name)) == 'lunch') ? '' : 'hidden-xs hidden-sm'; ?>">
                                    <div class="left-image">
                                        <?php
                                            $category_image = '';
                                            if (strtolower(str_replace(' ', '-', $category_name)) == 'predjedla') {
                                                $category_image = 'img/breakfast_menu.jpg';
                                            } elseif (strtolower(str_replace(' ', '-', $category_name)) == 'hlavne-jedla') {
                                                $category_image = 'img/lunch_menu.jpg';
                                            } elseif (strtolower(str_replace(' ', '-', $category_name)) == 'dezerty') {
                                                $category_image = 'img/dinner_menu.jpg';
                                            } else {
                                                $category_image = 'https://via.placeholder.com/400x300?text=' . urlencode($category_name);
                                            }
                                        ?>
                                        <img src="<?php echo $category_image; ?>" alt="<?php echo htmlspecialchars($category_name); ?>">
                                    </div>
                                </div>
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


    <section id="book-table">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Book Your Table Now</h2>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-2">
                    <div class="left-image">
                        <img src="img/book_left_image.jpg" alt="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="right-info">
                        <h4>Reservation</h4>
                        <form id="form-submit" action="" method="get">
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <select required name='day' onchange='this.form.()'>
                                            <option value="">Select day</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <select required name='hour' onchange='this.form.()'>
                                            <option value="">Select hour</option>
                                            <option value="10-00">10:00</option>
                                            <option value="12-00">12:00</option>
                                            <option value="14-00">14:00</option>
                                            <option value="16-00">16:00</option>
                                            <option value="18-00">18:00</option>
                                            <option value="20-00">20:00</option>
                                            <option value="22-00">22:00</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <input name="name" type="name" class="form-control" id="name" placeholder="Full name" required="">
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <input name="phone" type="phone" class="form-control" id="phone" placeholder="Phone number" required="">
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <select required class="person" name='persons' onchange='this.form.()'>
                                            <option value="">How many persons?</option>
                                            <option value="1-Person">1 Person</option>
                                            <option value="2-Persons">2 Persons</option>
                                            <option value="3-Persons">3 Persons</option>
                                            <option value="4-Persons">4 Persons</option>
                                            <option value="5-Persons">5 Persons</option>
                                            <option value="6-Persons">6 Persons</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <button type="submit" id="form-submit" class="btn">Book Table</button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
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

            // Inicializácia Owl Carousel pre každú kategóriu
            <?php foreach (array_keys($categorized_menu) as $category): ?>
                <?php $carousel_id = strtolower(str_replace(' ', '-', $category)); ?>
                <?php if (!empty($categorized_menu[$category])): // Inicializuj len ak sú položky v kategórii ?>
                    $('#owl-<?php echo $carousel_id; ?>').owlCarousel({
                        loop:true,
                        margin:30, /* Zväčšená medzera medzi položkami */
                        nav:true,
                        pagination: false,
                        dots: true,
                        responsive:{
                            0:{
                                items:1
                            },
                            600:{
                                items:2
                            },
                            1000:{
                                items:4 /* Zmenené na 4 položky pre väčšie obrazovky */
                            }
                        }
                    });
                <?php endif; ?>
            <?php endforeach; ?>
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