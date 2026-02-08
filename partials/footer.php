<style>
    /* --- FOOTER CSS FIXES --- */
    
    /* 1. Logo Styling & Alignment (Desktop) */
    .footer-logo {
        margin-top: -25px; /* Desktop par logo ko upar khincha */
        margin-bottom: 15px; 
    }
    
    .footer-logo img {
        width: 160px;
        max-width: 100%;    
        height: auto;
        display: block;     
    }

    /* 2. Text Spacing */
    .single-footer-widget .desc-content {
        margin-top: -32px;
        line-height: 1.6;
        color: #ddd; 
    }

    /* 3. Social Icons Alignment */
    .social-links {
        margin-top: -16px;
    }

    /* --- MOBILE RESPONSIVE (Max Width: 767px) --- */
    @media (max-width: 767px) {
        
        /* Logo wapis normal flow me aur LEFT aligned */
        .footer-logo {
            margin-top: 0;
            text-align: left; /* Left Align */
            margin-bottom: 15px;
        }
        
        .footer-logo img {
            margin: 0; /* Margin remove kiya taake left rahe */
        }

        /* Content spacing reset aur LEFT aligned */
        .single-footer-widget .desc-content {
            margin-top: 0; /* Negative margin khatam */
        }

        .social-links {
            margin-top: 15px; /* Thora gap diya */
        }

        /* Saara text LEFT align */
        .single-footer-widget {
            text-align: left; /* Left Align */
            margin-bottom: 40px; /* Sections ke darmiyan gap */
        }

        /* Social icons LEFT align */
        .social-links ul {
            justify-content: flex-start; /* Start (Left) se shuru hon */
        }
    }
</style>

<footer class="footer-area">
    <div class="footer-widget-area">
        <div class="container container-default custom-area">
            <div class="row">
                
                <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-custom">
                    <div class="single-footer-widget m-0">
                        <div class="footer-logo">
                            <a href="index.php">
                                <img src="assets/images/logo/logo-footer.png" alt="Logo Image">
                            </a>
                        </div>
                        <p class="desc-content">Lorem Khaled Ipsum is a major key to success. To be successful you’ve got to work hard you’ve got to make it.</p>
                        
                        <div class="social-links">
                            <ul class="d-flex">
                                <li>
                                    <a class="rounded-circle" href="https://www.facebook.com/share/1BfUiUneiH/?mibextid=wwXIfr" title="Facebook">
                                        <i class="fa fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="rounded-circle" href="https://www.instagram.com/customizeworld8?igsh=dWJpZGIwZmJzeGI1" title="Instagram">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-2 col-custom">
                    <div class="single-footer-widget">
                        <h2 class="widget-title">Information</h2>
                        <ul class="widget-list">
                            <li><a href="#">Our Company</a></li>
                            <li><a href="contact-us.php">Contact Us</a></li>
                            <li><a href="#">Our Services</a></li>
                            <li><a href="#">Why We?</a></li>
                            <li><a href="#">Careers</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-2 col-custom">
                    <div class="single-footer-widget">
                        <h2 class="widget-title">Quicklink</h2>
                        <ul class="widget-list">
                            <li><a href="about-us.php">About</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="shop.php">Shop</a></li>
                            <li><a href="#">Cart</a></li>
                            <li><a href="contact-us.php">Contact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-2 col-custom">
                    <div class="single-footer-widget">
                        <h2 class="widget-title">Support</h2>
                        <ul class="widget-list">
                            <li><a href="contact-us.php">Online Support</a></li>
                            <li><a href="contact-us.php">Shipping Policy</a></li>
                            <li><a href="contact-us.php">Return Policy</a></li>
                            <li><a href="contact-us.php">Privacy Policy</a></li>
                            <li><a href="contact-us.php">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-custom">
                    <div class="single-footer-widget">
                        <h2 class="widget-title">See Information</h2>
                        <div class="widget-body">
                            <address>123, ABC, Road ##, Main City, Your address goes here.<br>Phone / WhatsApp : 03350391951<br>Email : tshumaila58@gmail.com</address>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <div class="footer-copyright-area">
        <div class="container custom-area">
            <div class="row">
                <div class="col-12 text-center col-custom">
                    <div class="copyright-content">
                        <p>Copyright © 2026 | Built by <a href="https://safwanverse.com/" title="Safwan">Safwan</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>