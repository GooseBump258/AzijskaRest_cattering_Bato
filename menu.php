<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/triedy/db_config.php');
require_once(__DIR__ . '/triedy/MenuManager.php');

$menuManager = new MenuManager($pdo);
$menu_items = $menuManager->getAllItems();
$categorized_menu = $menuManager->categorizeItems($menu_items);
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
            text-align: left;
            margin-bottom: 40px;
            font-size: 3.2em;
            color: #333;
            font-weight: 700;
            border-left: 6px solid #d9534f;
            padding-left: 15px;
        }
        .owl-carousel .item {
            padding: 10px;
        }
        .food-item {
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
            margin: 0 auto 20px;
            max-width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            transition: transform 0.2s ease-in-out;
        }
        .food-item:hover {
            transform: translateY(-5px);
        }
        .food-item img {
            width: 100%;
            max-height: 250px;
            object-fit: contain;
            background-color: #f9f9f9;
            border-bottom: 1px solid #eee;
        }
        .price-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background-color: #d9534f;
            color: white;
            font-weight: 700;
            font-size: 16px;
            padding: 5px 10px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            z-index: 10;
            user-select: none;
        }
        .food-item .text-content {
            padding: 20px 15px 30px;
            width: 100%;
            text-align: center;
        }
        .food-item .text-content h4 {
            font-size: 18px;
            margin: 0 0 10px;
            color: #222;
            font-weight: 700;
        }
        .food-item .text-content p {
            font-size: 13px;
            color: #555;
            line-height: 1.4;
            margin: 0;
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
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <h2><?php echo htmlspecialchars($category_name); ?> Menu</h2>
                    <div id="owl-<?php echo strtolower(str_replace(' ', '-', $category_name)); ?>" class="owl-carousel owl-theme">
                        <?php foreach ($items_in_category as $item): ?>
                            <div class="item">
                                <div class="food-item">
                                    <div class="price-badge">
                                        <?php echo htmlspecialchars(number_format($item['price'], 2, ',', ' ')) . ' €'; ?>
                                    </div>
                                    <?php if ($item['image_path']): ?>
                                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/400x200?text=Bez+obrázka" alt="Bez obrázka">
                                    <?php endif; ?>
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
                        1000:{items:3}
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
