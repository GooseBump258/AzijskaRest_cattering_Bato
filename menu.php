<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Victory - Naše Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #fafafa;
            color: #333;
        }
        .menu-wrapper {
            display: flex;
            max-width: 1200px;
            margin: 40px auto;
            gap: 30px;
        }
        .menu-categories {
            flex: 0 0 180px;
            border-right: 2px solid #d9534f;
            padding-right: 20px;
        }
        .menu-categories h2 {
            font-size: 2.5em;
            color: #d9534f;
            margin-bottom: 20px;
            cursor: pointer;
            user-select: none;
        }
        .menu-categories h2.active {
            font-weight: 700;
            text-decoration: underline;
        }
        .menu-items {
            flex: 1;
        }
        .menu-item {
            display: flex;
            gap: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            overflow: hidden;
        }
        .menu-item img {
            width: 220px;
            height: 160px;
            object-fit: cover;
            flex-shrink: 0;
            border-right: 1px solid #eee;
        }
        .menu-item-content {
            padding: 15px 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .menu-item-content h3 {
            margin: 0 0 10px;
            font-size: 1.6em;
            color: #d9534f;
        }
        .menu-item-content .price {
            font-weight: 700;
            font-size: 1.3em;
            color: #444;
        }
        .menu-item-content p {
            margin-top: 8px;
            font-size: 0.95em;
            color: #666;
            line-height: 1.3;
        }
    </style>
</head>
<body>

<?php require_once 'parts/header.html'; ?>

<section class="page-heading" style="text-align:center; margin-top:40px;">
    <h1>Naše Menu</h1>
    <p>Objavte naše vynikajúce jedlá a nápoje pripravené s láskou a z čerstvých surovín.</p>
</section>

<div class="menu-wrapper">
    <nav class="menu-categories">
        <?php
        $first = true;
        foreach ($categorized_menu as $category_name => $items_in_category) {
            echo '<h2' . ($first ? ' class="active"' : '') . ' data-category="' . htmlspecialchars($category_name) . '">' . htmlspecialchars($category_name) . '</h2>';
            $first = false;
        }
        ?>
    </nav>

    <div class="menu-items">
        <?php
        $first = true;
        foreach ($categorized_menu as $category_name => $items_in_category) {
            echo '<div class="category-items" data-category="' . htmlspecialchars($category_name) . '"' . ($first ? '' : ' style="display:none;"') . '>';
            foreach ($items_in_category as $item) {
                $image = $item['image_path'] ? htmlspecialchars($item['image_path']) : 'https://via.placeholder.com/220x160?text=Bez+obrázka';
                $name = htmlspecialchars($item['name']);
                $price = number_format($item['price'], 2, ',', ' ') . ' €';
                $desc = htmlspecialchars($item['description']);
                echo '
                    <div class="menu-item">
                        <img src="' . $image . '" alt="' . $name . '">
                        <div class="menu-item-content">
                            <h3>' . $name . '</h3>
                            <div class="price">' . $price . '</div>
                            <p>' . $desc . '</p>
                        </div>
                    </div>
                ';
            }
            echo '</div>';
            $first = false;
        }
        ?>
    </div>
</div>

<?php require_once 'parts/footer.html'; ?>

<script>
    // Prepni zobrazenie kategórie po kliknuti na nadpis v menu
    document.querySelectorAll('.menu-categories h2').forEach(function(catHeading){
        catHeading.addEventListener('click', function(){
            // Odstran aktivny styl zo vsetkych
            document.querySelectorAll('.menu-categories h2').forEach(h => h.classList.remove('active'));
            this.classList.add('active');

            // Skry vsetky kategorie
            document.querySelectorAll('.category-items').forEach(div => div.style.display = 'none');

            // Ukaz tu spravnu
            var cat = this.getAttribute('data-category');
            document.querySelector('.category-items[data-category="' + cat + '"]').style.display = 'block';
        });
    });
</script>

</body>
</html>
