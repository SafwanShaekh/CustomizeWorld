<style>
    /* --- CUSTOM SCROLLBAR FOR DROPDOWN --- */
    .custom-scroll { scrollbar-width: thin; scrollbar-color: #E91E63 #f5f5f5; }
    .custom-scroll::-webkit-scrollbar { width: 8px; }
    .custom-scroll::-webkit-scrollbar-track { background: #f5f5f5; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb { background-color: #E91E63; border-radius: 10px; border: 2px solid #f5f5f5; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background-color: #C2185B; }
</style>

<?php
// Get Current Page Name (e.g., index.php, about-us.php)
$page = basename($_SERVER['PHP_SELF']);
?>

<header class="main-header-area">
    <div class="main-header header-transparent header-sticky">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-2 col-xl-2 col-md-6 col-6 col-custom">
                    <div class="header-logo d-flex align-items-center">
                        <a href="index.php">
                            <img class="img-full" src="assets/images/logo/logo.png" alt="Header Logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 d-none d-lg-flex justify-content-center col-custom">
                    <nav class="main-nav d-none d-lg-flex">
                        <ul class="nav">
                            <li>
                                <a class="<?php echo ($page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                                    <span class="menu-text"> Home</span>
                                </a>
                            </li>

                            <li>
                                <a class="<?php echo ($page == 'shop.php') ? 'active' : ''; ?>" href="#">
                                    <span class="menu-text"> All Categories</span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-submenu dropdown-hover custom-scroll" style="max-height: 240px; overflow-y: auto; min-width: 220px;">
                                    <?php
                                    if(isset($conn)){
                                        $cat_sql = "SELECT * FROM categories ORDER BY id DESC";
                                        $cat_res = mysqli_query($conn, $cat_sql);
                                        if(mysqli_num_rows($cat_res) > 0){
                                            while($cat = mysqli_fetch_assoc($cat_res)){
                                                ?>
                                                <li>
                                                    <a href="shop.php?category=<?php echo $cat['id']; ?>">
                                                        <?php echo $cat['cat_name']; ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            echo "<li><a href='#'>No Categories Yet</a></li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>

                            <li>
                                <a class="<?php echo ($page == 'about-us.php') ? 'active' : ''; ?>" href="about-us.php">
                                    <span class="menu-text"> About Us</span>
                                </a>
                            </li>

                            <li>
                                <a class="<?php echo ($page == 'contact-us.php') ? 'active' : ''; ?>" href="contact-us.php">
                                    <span class="menu-text">Contact Us</span>
                                </a>
                            </li>
                        </ul> 
                    </nav>
                </div>

                <div class="col-lg-2 col-md-6 col-6 col-custom">
                    <div class="header-right-area main-nav">
                        <ul class="nav">
                            <li class="mobile-menu-btn d-lg-none">
                                <a class="off-canvas-btn" href="#"><i class="fa fa-bars"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <aside class="off-canvas-wrapper" id="mobileMenu">
        <div class="off-canvas-overlay"></div>
        <div class="off-canvas-inner-content">
            <div class="btn-close-off-canvas"><i class="fa fa-times"></i></div>
            <div class="off-canvas-inner">
                <div class="mobile-navigation">
                <nav>
                    <ul class="mobile-menu">
                        <li><a href="index.php">Home</a></li>

                        <li class="menu-item-has-children"><a href="#">All Categories</a>
                            <ul class="dropdown">
                                <?php
                                if(isset($conn)){
                                    $m_cat_res = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
                                    if(mysqli_num_rows($m_cat_res) > 0){
                                        while($m_row = mysqli_fetch_assoc($m_cat_res)){
                                            echo "<li><a href='shop.php?category=".$m_row['id']."'>".$m_row['cat_name']."</a></li>";
                                        }
                                    } else {
                                        echo '<li><a href="#">No Categories Found</a></li>';
                                    }
                                }
                                ?>
                            </ul>
                        </li>

                        <li><a href="about-us.php">About Us</a></li>
                        <li><a href="contact-us.php">Contact</a></li>
                    </ul> 
                </nav>
                </div>
            </div>
        </div>
    </aside>
    </header>