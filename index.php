<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!--
Victory HTML CSS Template
https://templatemo.com/tm-507-victory
-->
        <title>Victory HTML CSS Template</title>
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
    </head>

<body>
    <!-- header -->
     <?php require_once 'parts/header.html' ?>
    <!-- header -->


    <section class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h4>Tu nájdete výborné jedlá</h4>
                    <h2>Čínska reštaurácia a cattering</h2>
                    <p>Usporiadavate oslavu, meeting alebo máte len chuť na výbornú čísnku kuchyňu, tu ste správne</p>
                    <div class="primary-button">
                        <a href="#" class="scroll-link" data-id="book-table">Objednajte si teraz</a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="cook-delecious">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-md-offset-1">
                    <div class="first-image">
                        <img src="img/cook_01.jpg" alt="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cook-content">
                        <h4>Varíme chutne a delikátne</h4>
                        <div class="contact-content">
                            <span>Môžte nám zavolať na</span>
                            <h6>+ 1234 567 8910</h6>
                        </div>
                        <span>or</span>
                        <div class="primary-white-button">
                            <a href="#" class="scroll-link" data-id="book-table">Order Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="second-image">
                        <img src="img/cook_02.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="services">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="service-item">
                        <a href="menu.html">
                        <img src="img/cook_breakfast.png" alt="Breakfast">
                        <h4>Raňajky</h4>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="service-item">
                        <a href="menu.html">
                        <img src="img/cook_lunch.png" alt="Lunch">
                        <h4>Obed</h4>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="service-item">
                        <a href="menu.html">
                        <img src="img/cook_dinner.png" alt="Dinner">
                        <h4>Večera</h4>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="service-item">
                        <a href="menu.html">
                        <img src="img/cook_dessert.png" alt="Desserts">
                        <h4>Dezerty</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section id="book-table">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Booknite si stôl</h2>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-2 col-sm-12">
                    <div class="left-image">
                        <img src="img/book_left_image.jpg" alt="">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="right-info">
                        <h4>Rezervácia</h4>
                        <form id="form-submit" action="" method="get">
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <select required name='day' onchange='this.form.()'>
                                            <option value="">Vyberte si deň</option>
                                            <option value="Monday">Pondelok</option>
                                            <option value="Tuesday">Utorok</option>
                                            <option value="Wednesday">Streda</option>
                                            <option value="Thursday">Štvrtok</option>
                                            <option value="Friday">Piatok</option>
                                            <option value="Saturday">Sobota</option>
                                            <option value="Sunday">Nedeľa</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <select required name='hour' onchange='this.form.()'>
                                            <option value="">Vyberte si hodinu</option>
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
                                            <option value="">Koľko ľudí?</option>
                                            <option value="1-Person">1 </option>
                                            <option value="2-Persons">2 </option>
                                            <option value="3-Persons">3 </option>
                                            <option value="4-Persons">4 </option>
                                            <option value="5-Persons">5 </option>
                                            <option value="6-Persons">6 </option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <button type="submit" id="form-submit" class="btn">Booknite si stôl</button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="get-app">
        <div class="container">
            <div class="row">
                <div class="heading">
                    <h2>Získajte aplikáciu na telefón</h2>
                </div>
                <div class="primary-white-button">
                    <a href="#">Inštalovať teraz</a>
                </div>
            </div>
        </div>
    </section>



    <section class="featured-food">
        <div class="container">
            <div class="row">
                <div class="heading">
                    <h2>Týždenné menu</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="food-item">
                        <h2>Raňajky</h2>
                        <img src="img/breakfast_item.jpg" alt="">
                        <div class="price">$4.50</div>
                        <div class="text-content">
                            <h4>Kale čipsy </h4>
                            <p>Dreamcatcher squid ennui cliche chicharros nes echo  small batch jean shorts hexagon street art knausgaard wolf...</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="food-item">
                        <h2>Obed</h2>
                        <img src="img/lunch_item.jpg" alt="">
                        <div class="price">$7.50</div>
                        <div class="text-content">
                            <h4>Taiyaki Gastro </h4>
                            <p>Dreamcatcher squid ennui cliche chicharros nes echo  small batch jean shorts hexagon street art knausgaard wolf...</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="food-item">
                        <h2>Dinner</h2>
                        <img src="img/dinner_item.jpg" alt="">
                        <div class="price">$12.50</div>
                        <div class="text-content">
                            <h4>Chobotnica</h4>
                            <p>Dreamcatcher squid ennui cliche chicharros nes echo  small batch jean shorts hexagon street art knausgaard wolf...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="our-blog">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Naše posty</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="blog-post">
                        <img src="img/blog_post_01.jpg" alt="">
                        <div class="date">11.2.2025</div>
                        <div class="right-content">
                            <h4>Stumptown Tofu Schlitz</h4>
                            <span>Branding / Admin</span>
                            <p>Skateboard iceland twee tofu shaman crucifix vice before they sold out corn hole occupy drinking vinegar chambra meggings kale chips hexagon...</p>
                            <div class="text-button">
                                <a href="#">Continue Reading</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="blog-post">
                        <img src="img/blog_post_02.jpg" alt="">
                        <div class="date">22.2.2025</div>
                        <div class="right-content">
                            <h4>Succulents Hashtag Folk</h4>
                            <span>Branding / Admin</span>
                            <p>Skateboard iceland twee tofu shaman crucifix vice before they sold out corn hole occupy drinking vinegar chambra meggings kale chips hexagon...</p>
                            <div class="text-button">
                                <a href="#">Pokračujte v čítaní</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="blog-post">
                        <img src="img/blog_post_03.jpg" alt="">
                        <div class="date">9.2.2025</div>
                        <div class="right-content">
                            <h4>Knaus Sriracha Pinterest</h4>
                            <span>Dessert / Chef</span>
                            <p>Skateboard iceland twee tofu shaman crucifix vice before they sold out corn hole occupy drinking vinegar chambra meggings kale chips hexagon...</p>
                            <div class="text-button">
                                <a href="#">Pokračujte v čítaní</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="blog-post">
                        <img src="img/blog_post_04.jpg" alt="">
                        <div class="date">8.3.2025</div>
                        <div class="right-content">
                            <h4>Crucifix Selvage Coat</h4>
                            <span>Plate / Chef</span>
                            <p>Skateboard iceland twee tofu shaman crucifix vice before they sold out corn hole occupy drinking vinegar chambra meggings kale chips hexagon...</p>
                            <div class="text-button">
                                <a href="#">Pokračujte v čítaní</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="sign-up">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Prihlásiť sa na odber</h2>
                    </div>
                </div>
            </div>
            <form id="contact" action="" method="get">
                <div class="row">
                    <div class="col-md-4 col-md-offset-3">
                        <fieldset>
                            <input name="email" type="text" class="form-control" id="email" placeholder="Enter your email here..." required="">
                        </fieldset>
                    </div>
                    <div class="col-md-2">
                        <fieldset>
                            <button type="submit" id="form-submit" class="btn">Poslať správu</button>
                        </fieldset>
                    </div>
                </div>
            </form>
        </div>
    </section>



    <!-- footer -->
    <?php require_once 'parts/footer.html' ?>
    <!-- footer -->

    
    
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