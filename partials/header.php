    <!-- preloader -->
    <div class="preloader">
        <div class="loader-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- preloader end -->


    <!-- header area -->
    <header class="header">

        <!-- header top -->
        <div class="header-top">
            <div class="container">
                <div class="header-top-wrapper">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6 col-xl-5">
                            <div class="header-top-left">
                                <ul class="header-top-list">
                                    <li><a href="mailto:tshumaila58@gmail.com"><i class="far fa-envelopes"></i>
                                            <span class="__cf_email__">tshumaila58@gmail.com</span></a></li>
                                    <li><a href="https://wa.me/923350391951"><i class="far fa-headset"></i> +92 335 0391951</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-7">
                            <div class="header-top-right">
                                <ul class="header-top-list">
                                    <li class="social">
                                        <div class="header-top-social">
                                            <span>Follow Us: </span>
                                            <a href="https://www.facebook.com/share/1BfUiUneiH/?mibextid=wwXIfr"><i class="fab fa-facebook"></i></a>
                                            <a href="https://www.instagram.com/customizeworld8"><i class="fab fa-instagram"></i></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- header top end -->

        <!-- navbar -->
        <div class="main-navigation">
            <nav class="navbar navbar-expand-lg">
                <div class="container position-relative">
                    <a class="navbar-brand" href="index.php">
                        <img src="assets/img/logo/logo.png" alt="logo" style = "width: 176px; height: 112px; margin-top: -10px; margin-bottom: -10px;">
                    </a>
                    <div class="mobile-menu-right">
                        <div class="mobile-menu-btn">
                            <a href="#" class="nav-right-link search-box-outer"><i class="far fa-search"></i></a>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                            aria-label="Toggle navigation">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                        aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <a href="index-2.html" class="offcanvas-brand" id="offcanvasNavbarLabel">
                                <img src="assets/img/logo/logo.png" alt="">
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <?php 
                                // Current page ka naam uthao (e.g. index.php, shop.php)
                                $current_page = basename($_SERVER['PHP_SELF']); 
                            ?>

                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-lg-5">

                                <li class="nav-item">
                                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>" href="shop.php">Shop</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?php echo ($current_page == 'about-us.php') ? 'active' : ''; ?>" href="about-us.php">About</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?php echo ($current_page == 'contact-us.php') ? 'active' : ''; ?>" href="contact-us.php">Contact</a>
                                </li>

                            </ul>
                            <!-- nav-right -->
                            <div class="nav-right">
                                <ul class="nav-right-list">
                                    <li>
                                        <a href="#" class="list-link search-box-outer">
                                            <i class="far fa-search"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- navbar end -->

    </header>
    <!-- header area end -->


    <!-- popup search -->
    <div class="search-popup">
        <button class="close-search"><span class="far fa-times"></span></button>

        <form action="shop.php" method="GET">
            <div class="form-group">
                <input type="search" name="search" class="form-control" placeholder="Search Here..." required>
                <button type="submit"><i class="far fa-search"></i></button>
            </div>
        </form>
    </div>
    <!-- popup search end -->