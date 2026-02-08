<?php
include 'database/db.php';
?>
<!doctype html>
<html class="no-js" lang="en">


<!-- Mirrored from htmldemo.net/flosun/flosun/index-3.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 26 Jan 2026 16:57:19 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>FloSun - Flower Shop HTML5 Template</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">

    <!-- CSS
	============================================ -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="assets/css/vendor/font.awesome.min.css">
    <!-- Linear Icons CSS -->
    <link rel="stylesheet" href="assets/css/vendor/linearicons.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="assets/css/plugins/swiper-bundle.min.css">
    <!-- Animation CSS -->
    <link rel="stylesheet" href="assets/css/plugins/animate.min.css">
    <!-- Jquery ui CSS -->
    <link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="assets/css/plugins/nice-select.min.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup.css">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
       

        /* --- FLIP CARD CSS FOR SLIDER --- */
        .flip-card {
            background-color: transparent;
            width: 100%;
            height: 380px; /* Card Height */
            perspective: 1000px;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .product-slider .single-item:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 15px;
            overflow: hidden;
        }

        .flip-card-front { background-color: #fff; }
        .flip-card-front img { width: 100%; height: 100%; object-fit: cover; }

        .flip-card-back {
            background-color: white;
            color: #333;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            border: 2px solid #E91E63;
            box-shadow: 0 0 15px rgba(233, 30, 99, 0.6); /* Glow Effect */
        }

        .flip-category { font-size: 12px; text-transform: uppercase; color: #777; margin-bottom: 5px; letter-spacing: 1px; }
        .flip-title { font-size: 20px; font-weight: 700; margin-bottom: 10px; color: #333; }
        .flip-price { font-size: 22px; color: #E91E63; font-weight: bold; margin-bottom: 15px; }
        .flip-details { font-size: 13px; color: #666; margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

        .btn-flip-order {
            background-color: #25D366; color: white; padding: 10px 25px;
            border-radius: 50px; text-decoration: none; font-weight: 600;
            box-shadow: 0 4px 10px rgba(37, 211, 102, 0.4); transition: 0.3s;
        }
        .btn-flip-order:hover { background-color: #128C7E; color: white; }
    </style>
</head>

<body>

 

    <!-- Header Area Start Here -->
    <?php include "partials/header.php"?>
    <!-- Header Area End Here -->
    <!-- Slider/Intro Section Start -->
    <div class="intro11-slider-wrap section-2">
        <div class="intro11-slider swiper-container">
            <div class="swiper-wrapper">
                <div class="intro11-section swiper-slide slide-5 slide-bg-1 bg-position">
                    <!-- Intro Content Start -->
                    <div class="intro11-content-2 text-center">
                        <h1 class="different-title">Quality</h1>
                        <h2 class="title text-white">The Gift of Flowers</h2>
                        
                    </div>
                    <!-- Intro Content End -->
                </div>
                <div class="intro11-section swiper-slide slide-6 slide-bg-1 bg-position">
                    <!-- Intro Content Start -->
                    <div class="intro11-content-2 text-center">
                        <h1 class="different-title">Welcome</h1>
                    </div>
                    <!-- Intro Content End -->
                </div>
            </div>
            <!-- Slider Navigation -->
            <div class="home1-slider-prev swiper-button-prev main-slider-nav"><i class="lnr lnr-arrow-left"></i></div>
            <div class="home1-slider-next swiper-button-next main-slider-nav"><i class="lnr lnr-arrow-right"></i></div>
            <!-- Slider pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <!-- Slider/Intro Section End -->
    <!-- Shop Collection Start Here -->
    <div class="shop-collection-area gray-bg pt-no-text pb-no-text">
        <div class="container custom-area">
            <div class="row mb-30">
                <div class="col-md-6 col-custom">
                    <div class="collection-content">
                        <div class="section-title text-left">
                            <span class="section-title-1">Flowers for the</span>
                            <h3 class="section-title-3 pb-0">Birthday & Gifts</h3>
                        </div>
                        <div class="desc-content">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-custom">
                    <!--Single Banner Area Start-->
                    <div class="single-banner hover-style">
                        <div class="banner-img">
                            <a href="#">
                                <img src="assets/images/collection/1-1.jpg" alt="">
                                <div class="overlay-1"></div>
                            </a>
                        </div>
                    </div>
                    <!--Single Banner Area End-->
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-custom order-2 order-md-1">
                    <!--Single Banner Area Start-->
                    <div class="single-banner hover-style">
                        <div class="banner-img">
                            <a href="#">
                                <img src="assets/images/collection/1-2.jpg" alt="">
                                <div class="overlay-1"></div>
                            </a>
                        </div>
                    </div>
                    <!--Single Banner Area End-->
                </div>
                <div class="col-md-6 col-custom order-1 order-md-2">
                    <div class="collection-content">
                        <div class="section-title text-left">
                            <span class="section-title-1">Flowers for the</span>
                            <h3 class="section-title-3 pb-0">Wedding day</h3>
                        </div>
                        <div class="desc-content">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Collection End Here -->
    <!--Product Area Start-->
    <div class="product-area mt-text-3">
        <div class="container custom-area-2 overflow-hidden">
            <div class="row">
                <!--Section Title Start-->
                <div class="col-12 col-custom">
                    <div class="section-title text-center mb-30">
                        <span class="section-title-1">The Most Trendy</span>
                        <h3 class="section-title-3">Featured Products</h3>
                    </div>
                </div>
                <!--Section Title End-->
            </div>
            <div class="row product-row">
                <div class="col-12 col-custom">
                    <div class="product-slider swiper-container anime-element-multi">
                        <div class="swiper-wrapper">

                            <?php
                            // 1. JOIN QUERY (Category Name + Latest 10 Products)
                            $sql = "SELECT products.*, categories.cat_name 
                                    FROM products 
                                    LEFT JOIN categories ON products.category_id = categories.id 
                                    ORDER BY products.id DESC LIMIT 10";

                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {

                                    // Variables
                                    $phone = "923350391951"; /// whatsapp number teacher ka 
                                    $p_name = urlencode($row['name']);
                                    $price = $row['price'];
                                    $msg = "Salam! I want to order *$p_name* - Price: $price";
                                    $wa_link = "https://wa.me/$phone?text=$msg";
                                    $img_src = "uploads/" . $row['image'];
                                    $cat_name = isset($row['cat_name']) ? $row['cat_name'] : 'Product';
                            ?>

                            <div class="single-item swiper-slide mx-2">

                                <div class="flip-card">
                                    <div class="flip-card-inner">

                                        <div class="flip-card-front">
                                            <?php if(file_exists($img_src)): ?>
                                                <img src="<?php echo $img_src; ?>" alt="<?php echo $row['name']; ?>">
                                            <?php else: ?>
                                                <img src="assets/images/product/1.jpg" alt="Dummy">
                                            <?php endif; ?>
                                        </div>
                                            
                                        <div class="flip-card-back">
                                            <span class="flip-category"><?php echo $cat_name; ?></span>
                                            
                                            <h4 class="flip-title">
                                                <a href="#" style="color: inherit; text-decoration: none;"><?php echo $row['name']; ?></a>
                                            </h4>
                                            
                                            <p class="flip-details">
                                                <?php echo (strlen($row['details']) > 60) ? substr($row['details'], 0, 60) . '...' : $row['details']; ?>
                                            </p>
                                            
                                            <span class="flip-price">PKR <?php echo number_format($row['price']); ?></span>
                                            
                                            <a href="<?php echo $wa_link; ?>" target="_blank" class="btn-flip-order">
                                                <i class="fa fa-whatsapp"></i> Order Now
                                            </a>
                                        </div>
                                            
                                    </div>
                                </div>
                                </div>
                            <?php 
                                } // While loop end
                            } else {
                                echo "<p class='text-center' style='width:100%; padding:20px;'>No featured products found.</p>";
                            }
                            ?>

                        </div>
                        <div class="swiper-pagination default-pagination mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Product Area End-->
    <!-- Banner Area Start Here -->
    <div class="banner-area mt-text-3">
        <div class="container custom-area">
            <div class="row">
                <div class="col-md-6 col-custom">
                    <!--Single Banner Area Start-->
                    <div class="single-banner hover-style mb-30">
                        <div class="banner-img">
                            <a href="#">
                                <img src="assets/images/banner/1-1.jpg" alt="">
                                <div class="overlay-1"></div>
                            </a>
                        </div>
                    </div>
                    <!--Single Banner Area End-->
                </div>
                <div class="col-md-6 col-custom">
                    <!--Single Banner Area Start-->
                    <div class="single-banner hover-style mb-30">
                        <div class="banner-img">
                            <a href="#">
                                <img src="assets/images/banner/1-3.jpg" alt="">
                                <div class="overlay-1"></div>
                            </a>
                        </div>
                    </div>
                    <!--Single Banner Area End-->
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Area End Here -->
    <!-- Testimonial Area Start Here -->
    
    <!-- Testimonial Area End Here -->
    <!-- Newsletter Area Start Here -->
    
    <!-- Newsletter Area End Here -->
    <!-- Blog Area Start Here -->
    <div class="blog-post-area mt-text-3">
        <div class="container custom-area">
            <div class="row">
                <!--Section Title Start-->
                <div class="col-12">
                    <div class="section-title text-center mb-30">
                        <span class="section-title-1">From The Blogs</span>
                        <h3 class="section-title-3">Our Latest Posts</h3>
                    </div>
                </div>
                <!--Section Title End-->
            </div>
            <div class="row">
                <div class="col-12 col-md-4 col-lg-4 col-custom mb-30">
                    <div class="blog-lst">
                        <div class="single-blog">
                            <div class="blog-image">
                                <a class="d-block" href="#">
                                    <img src="assets/images/blog/blog1.jpg" alt="Blog Image" class="w-100">
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-text">
                                    <h4><a href="#">Standard blog post one</a></h4>
                                    <div class="blog-post-info">
                                        <span><a href="#">By admin</a></span>
                                        <span>December 18, 2022</span>
                                    </div>
                                    <p>Lorem ipsu dolor sit amet cons elits cumque adipisicing, sed do incid eiusmod tempor ut labore et dolore eveniet .</p>
                                    <a href="#" class="readmore">Read More <i class="fa fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4 col-custom mb-30">
                    <div class="blog-lst">
                        <div class="single-blog">
                            <div class="blog-image">
                                <a class="d-block" href="#">
                                    <img src="assets/images/blog/blog3.jpg" alt="Blog Image" class="w-100">
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-text">
                                    <h4><a href="#">Standard blog post two</a></h4>
                                    <div class="blog-post-info">
                                        <span><a href="#">By admin</a></span>
                                        <span>December 18, 2022</span>
                                    </div>
                                    <p>Lorem ipsu dolor sit amet cons elits cumque adipisicing, sed do incid eiusmod tempor ut labore et dolore eveniet .</p>
                                    <a href="#" class="readmore">Read More <i class="fa fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4 col-custom mb-30">
                    <div class="blog-lst">
                        <div class="single-blog">
                            <div class="blog-image">
                                <a class="d-block" href="#">
                                    <img src="assets/images/blog/blog2.jpg" alt="Blog Image" class="w-100">
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-text">
                                    <h4><a href="#">Standard blog post three</a></h4>
                                    <div class="blog-post-info">
                                        <span><a href="#">By admin</a></span>
                                        <span>December 18, 2022</span>
                                    </div>
                                    <p>Lorem ipsu dolor sit amet cons elits cumque adipisicing, sed do incid eiusmod tempor ut labore et dolore eveniet .</p>
                                    <a href="#" class="readmore">Read More <i class="fa fa-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--Footer Area Start-->
    <?php include 'partials/footer.php'; ?>
    <!--Footer Area End-->

    <!-- Modal -->
    <div class="modal flosun-modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close close-button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="close-icon" aria-hidden="true">x</span>
                </button>
                <div class="modal-body">
                    <div class="container-fluid custom-area">
                        <div class="row">
                            <div class="col-md-6 col-custom">
                                <div class="modal-product-img">
                                    <a class="w-100" href="#">
                                        <img class="w-100" src="assets/images/product/large-size/1.jpg" alt="Product">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-custom">
                                <div class="modal-product">
                                    <div class="product-content">
                                        <div class="product-title">
                                            <h4 class="title">Product dummy name</h4>
                                        </div>
                                        <div class="price-box">
                                            <span class="regular-price ">$80.00</span>
                                            <span class="old-price"><del>$90.00</del></span>
                                        </div>
                                        <div class="product-rating">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <span>1 Review</span>
                                        </div>
                                        <p class="desc-content">we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame bel...</p>
                                        <form class="d-flex flex-column w-100" action="#">
                                            <div class="form-group">
                                                <select class="form-control nice-select w-100">
                                                    <option>S</option>
                                                    <option>M</option>
                                                    <option>L</option>
                                                    <option>XL</option>
                                                    <option>XXL</option>
                                                </select>
                                            </div>
                                        </form>
                                        <div class="quantity-with-btn">
                                            <div class="quantity">
                                                <div class="cart-plus-minus">
                                                    <input class="cart-plus-minus-box" value="0" type="text">
                                                    <div class="dec qtybutton">-</div>
                                                    <div class="inc qtybutton">+</div>
                                                    <div class="dec qtybutton"><i class="fa fa-minus"></i></div>
                                                    <div class="inc qtybutton"><i class="fa fa-plus"></i></div>
                                                </div>
                                            </div>
                                            <div class="add-to_btn">
                                                <a class="btn product-cart button-icon flosun-button dark-btn" href="cart.html">Add to cart</a>
                                                <a class="btn flosun-button secondary-btn rounded-0" href="wishlist.html">Add to wishlist</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Start -->
    <a class="scroll-to-top" href="#">
        <i class="lnr lnr-arrow-up"></i>
    </a>
    <!-- Scroll to Top End -->

    <!-- JS
============================================ -->


    <!-- jQuery JS -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- jQuery Migrate JS -->
    <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <!-- Modernizer JS -->
    <script src="assets/js/vendor/modernizr-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>


    <!-- Swiper Slider JS -->
    <script src="assets/js/plugins/swiper-bundle.min.js"></script>
    <!-- nice select JS -->
    <script src="assets/js/plugins/nice-select.min.js"></script>
    <!-- Ajaxchimpt js -->
    <script src="assets/js/plugins/jquery.ajaxchimp.min.js"></script>
    <!-- Jquery Ui js -->
    <script src="assets/js/plugins/jquery-ui.min.js"></script>
    <!-- Jquery Countdown js -->
    <script src="assets/js/plugins/jquery.countdown.min.js"></script>
    <!-- jquery magnific popup js -->
    <script src="assets/js/plugins/jquery.magnific-popup.min.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <script>
        $(window).on('load', function() {
            // Thora delay taake theme ki apni JS pehle load ho jaye
            setTimeout(function() {
                var sliderElement = document.querySelector('.product-slider');
                                                
                // Check karte hain ke swiper initialize ho chuka hai ya nahi
                if (sliderElement && sliderElement.swiper) {
                    // Autoplay ki settings add kar rahe hain
                    sliderElement.swiper.params.autoplay = {
                        delay: 2000, // 1000 milliseconds = 1 second
                        disableOnInteraction: false // Agar user swipe kare tab bhi autoplay na ruke
                    };
                    // Autoplay start kardo
                    sliderElement.swiper.autoplay.start();
                }
            }, 500);
        });
    </script>


</body>


<!-- Mirrored from htmldemo.net/flosun/flosun/index-3.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 26 Jan 2026 16:57:21 GMT -->
</html>