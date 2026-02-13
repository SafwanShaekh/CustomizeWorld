<?php 
include "database/db.php"; 

// --- 1. DETERMINE VIEW & LIMIT (Moved to Top) ---
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'grid';

// Dynamic Limit based on View
if ($view_mode == 'list') {
    $limit = 5;  // List View mein 5 items
} else {
    $limit = 12; // Grid View mein 12 items
}

// --- 2. PAGINATION CONFIG ---
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- 3. FILTERS LOGIC ---
$whereClauses = [];
$whereClauses[] = "1=1"; 

// A. Search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $whereClauses[] = "name LIKE '%$search%'";
}

// B. Category
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $cat_id = (int)$_GET['category'];
    $whereClauses[] = "category_id = '$cat_id'";
}

// C. Price
$min_price_default = 0;
$max_price_default = 10000;
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : $min_price_default;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : $max_price_default;

if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
    $whereClauses[] = "price BETWEEN $min_price AND $max_price";
}

// D. Tags
$selected_tags = [];
if (isset($_GET['tags']) && !empty($_GET['tags'])) {
    if(is_string($_GET['tags'])) {
        $selected_tags = explode(',', $_GET['tags']);
    } elseif(is_array($_GET['tags'])){
        $selected_tags = $_GET['tags'];
    }

    if(!empty($selected_tags)){
        $safe_tags = array_map(function($t) use ($conn) { 
            return "'" . mysqli_real_escape_string($conn, $t) . "'"; 
        }, $selected_tags);
        $tags_str = implode(',', $safe_tags);
        $whereClauses[] = "product_tag IN ($tags_str)";
    }
}

// --- 4. SORTING LOGIC ---
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : '1';
$orderBy = "ORDER BY id DESC";

switch($sort_option){
    case '1': $orderBy = "ORDER BY id DESC"; break;
    case '5': $orderBy = "ORDER BY id DESC"; break;
    case '2': $orderBy = "ORDER BY RAND()"; break; 
    case '3': $orderBy = "ORDER BY price ASC"; break;
    case '4': $orderBy = "ORDER BY price DESC"; break;
}

// --- 5. FINAL QUERY & COUNTS ---
$whereSQL = " WHERE " . implode(' AND ', $whereClauses);

// Count Total Results
$count_sql = "SELECT COUNT(*) as total FROM products $whereSQL";
$count_res = mysqli_query($conn, $count_sql);
$total_rows = mysqli_fetch_assoc($count_res)['total'];

// Calculate Total Pages (Dynamic Limit use ho raha hai)
$total_pages = ceil($total_rows / $limit);

// Range Display (Showing 1-5 or 1-12)
$start = ($total_rows > 0) ? $offset + 1 : 0;
$end = min($offset + $limit, $total_rows);
?>

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from live.themewild.com/gifoy/shop-grid.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 10 Feb 2026 16:13:14 GMT -->
<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- title -->
    <title>Customize World</title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/logo/favicon.png">

    <!-- css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all-fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/nice-select.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Custom Pink Scrollbar for Categories */
        .shop-category-list.scroll-active {
            max-height: 320px; /* Approx 7-8 items ki height */
            overflow-y: auto;
            padding-right: 5px; /* Scrollbar ke liye thori jagah */
        }

        /* Scrollbar Design */
        .shop-category-list.scroll-active::-webkit-scrollbar {
            width: 3.5px;
        }
        .shop-category-list.scroll-active::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .shop-category-list.scroll-active::-webkit-scrollbar-thumb {
            background-color: var(--theme-color, #ff2c55); /* Theme Pink Color */
            border-radius: 10px;
        }
        .shop-category-list.scroll-active::-webkit-scrollbar-thumb:hover {
            background-color: #d62243;
        }

        /* --- Custom List View Design --- */
    .custom-list-card {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #f4f4f4;
        border-radius: 20px;
        padding: 15px;
        margin-bottom: 25px;
        transition: 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }
    
    .custom-list-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    /* Image Section */
    .custom-list-img-box {
        flex: 0 0 300px; /* Fixed width for image box */
        max-width: 300px;
        background: #fff0f5; /* Light Pink Background like screenshot */
        border-radius: 15px;
        padding: 20px;
        position: relative;
        text-align: center;
        overflow: hidden; /* For hover effect */
    }

    .custom-list-img-box img {
        height: 220px;
        width: 100%;
        object-fit: contain; /* Product poora nazar aye */
        mix-blend-mode: multiply; /* Agar white bg image ho to blend hojaye */
    }

    /* Eye Button Hover Effect */
    .hover-eye-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        background: #fff;
        width: 45px;
        height: 45px;
        line-height: 45px;
        text-align: center;
        border-radius: 50%;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: var(--theme-color);
        opacity: 0;
        transition: 0.4s ease;
        z-index: 2;
    }
    
    .custom-list-img-box:hover .hover-eye-btn {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }

    /* Content Section */
    .custom-list-content {
        flex: 1;
        padding-left: 40px;
        position: relative;
    }

    .custom-list-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 10px;
        text-decoration: none;
        display: block;
    }

    .custom-list-desc {
        color: #777;
        font-size: 15px;
        line-height: 1.7;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3; /* Limit to 3 lines */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .custom-list-price {
        font-size: 24px;
        font-weight: 700;
        color: #ff3366; /* Red/Pinkish Price */
    }

    /* Action Button (Bottom Right) */
    .custom-list-action-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #8b5cf6; /* Purple color like screenshot */
        color: #fff;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        border-radius: 50%;
        font-size: 18px;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(139, 92, 246, 0.4);
    }

    .custom-list-action-btn:hover {
        background: #7c3aed;
        color: #fff;
        transform: scale(1.1);
    }

    /* Tag Styling */
    .list-tag {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #fff;
        z-index: 1;
    }
    .list-tag.new { background: #00d25b; }
    .list-tag.hot { background: #ffaa17; }
    .list-tag.discount { background: #ff3366; }
    .list-tag.oos { background: #666; }

    /* Responsive */
    @media (max-width: 768px) {
        .custom-list-card { flex-direction: column; align-items: flex-start; }
        .custom-list-img-box { flex: 0 0 100%; max-width: 100%; width: 100%; margin-bottom: 20px; }
        .custom-list-content { padding-left: 0; width: 100%; }
        .custom-list-action-btn { position: relative; float: right; margin-top: 10px; }
    }
    </style>
</head>

<body>

    <?php include "partials/header.php"?>


    <main class="main">

        <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Shop</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="index.php"><i class="far fa-home"></i> Home</a></li>
                        <li class="active">Shop</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- shop-area -->
        <div class="shop-area py-90">
            <div class="container">
                <div class="row">
                    
                    <div class="col-lg-3">
                        <div class="shop-sidebar">
                            
                            <div class="shop-widget">
                                <div class="shop-search-form">
                                    <h4 class="shop-widget-title">Search</h4>
                                    <form onsubmit="event.preventDefault(); applyFilters();">
                                        <div class="form-group">
                                            <input type="text" id="searchInput" class="form-control" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                            <button type="submit"><i class="far fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Category</h4>
                                <ul class="shop-category-list scroll-active">
                                    <li><a href="shop.php" class="<?php echo !isset($_GET['category']) ? 'active-cat' : ''; ?>">All Categories</a></li>
                                    <?php
                                    $cat_sql = "SELECT c.id, c.cat_name, COUNT(p.id) as count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id";
                                    $cat_res = mysqli_query($conn, $cat_sql);
                                    while($cat = mysqli_fetch_assoc($cat_res)){
                                        $isActive = (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'style="color:var(--theme-color); font-weight:bold;"' : '';
                                        echo "<li><a href='javascript:void(0);' onclick='setCategory(".$cat['id'].")' $isActive>".$cat['cat_name']."<span>(".$cat['count'].")</span></a></li>";
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Price Range</h4>
                                <div class="price-range-box">
                                    <div class="price-range-input">
                                        <input type="text" id="price-amount" readonly>
                                    </div>
                                    <div class="price-range"></div>
                                    <input type="hidden" id="min_price_val" value="<?php echo $min_price; ?>">
                                    <input type="hidden" id="max_price_val" value="<?php echo $max_price; ?>">
                                </div>
                            </div>

                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Tags</h4>
                                <ul class="shop-checkbox-list scroll-active">
                                    <?php
                                    $tag_sql = "SELECT DISTINCT product_tag FROM products WHERE product_tag != ''";
                                    $tag_res = mysqli_query($conn, $tag_sql);
                                    $t_count = 0;
                                    while($t_row = mysqli_fetch_assoc($tag_res)){
                                        $t_count++;
                                        $tagName = $t_row['product_tag'];
                                        $checked = in_array($tagName, $selected_tags) ? 'checked' : '';
                                        echo "<li>
                                                <div class='form-check'>
                                                    <input class='form-check-input filter-tag' type='checkbox' value='$tagName' id='tag$t_count' $checked onchange='applyFilters()'>
                                                    <label class='form-check-label' for='tag$t_count'>$tagName</label>
                                                </div>
                                              </li>";
                                    }
                                    ?>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="col-md-12">
                            <div class="shop-sort">
                                <div class="shop-sort-box">
                                    <div class="shop-sorty-label">Sort By:</div>
                                    <select class="select" id="sortSelect" onchange="applyFilters()">
                                        <option value="1" <?php echo ($sort_option=='1')?'selected':''; ?>>Default Sorting</option>
                                        <option value="5" <?php echo ($sort_option=='5')?'selected':''; ?>>Latest Items</option>
                                        <option value="2" <?php echo ($sort_option=='2')?'selected':''; ?>>Best Seller</option>
                                        <option value="3" <?php echo ($sort_option=='3')?'selected':''; ?>>Price - Low To High</option>
                                        <option value="4" <?php echo ($sort_option=='4')?'selected':''; ?>>Price - High To Low</option>
                                    </select>
                                    <div class="shop-sort-show">Showing <?php echo "$start-$end of $total_rows"; ?> Results</div>
                                </div>
                                <div class="shop-sort-gl">
                                    <a href="javascript:void(0);" onclick="setView('grid')" class="shop-sort-grid <?php echo $view_mode=='grid'?'active':''; ?>"><i class="far fa-grid-round-2"></i></a>
                                    <a href="javascript:void(0);" onclick="setView('list')" class="shop-sort-list <?php echo $view_mode=='list'?'active':''; ?>"><i class="far fa-list-ul"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="shop-item-wrap">
                            <div class="row g-4">
                                <?php
                                $final_sql = "SELECT products.*, categories.cat_name 
                                              FROM products 
                                              LEFT JOIN categories ON products.category_id = categories.id 
                                              $whereSQL $orderBy LIMIT $limit OFFSET $offset";

                                $final_res = mysqli_query($conn, $final_sql);

                                if(mysqli_num_rows($final_res) > 0){
                                    while($row = mysqli_fetch_assoc($final_res)){

                                        // --- Variables ---
                                        $p_name = htmlspecialchars($row['name']);
                                        $price = number_format($row['price']);
                                        $img_src = "uploads/" . $row['image'];
                                        $db_tag = isset($row['product_tag']) ? $row['product_tag'] : 'New';
                                        $cat_name = isset($row['cat_name']) ? $row['cat_name'] : 'Item';

                                        // Badge Logic
                                        $badge = 'new';
                                        $t_low = strtolower($db_tag);
                                        if($t_low == 'hot') $badge = 'hot';
                                        elseif($t_low == 'sale') $badge = 'discount';
                                        elseif($t_low == 'out of stock') $badge = 'oos';

                                        // WhatsApp Link
                                        $wa_msg = urlencode("Salam! I want to order: $p_name - Price: $price");
                                        $wa_link = "https://wa.me/923350391951?text=$wa_msg";

                                        // --- CONDITION: GRID VIEW VS LIST VIEW ---
                                        if ($view_mode == 'grid') {
                                            // === OLD GRID DESIGN ===
                                            ?>
                                            <div class="col-md-6 col-lg-3">
                                                <div class="product-item">
                                                    <div class="product-img">
                                                        <span class="type <?php echo $badge; ?>"><?php echo $db_tag; ?></span>
                                                        <a href="#"><img src="<?php echo file_exists($img_src)?$img_src:'assets/img/product/01.png'; ?>" alt="" style="height:250px; object-fit:cover;"></a>
                                                        <div class="product-action-wrap">
                                                            <div class="product-action">
                                                                <a href="javascript:void(0);" onclick='openQuickView(<?php echo json_encode($row); ?>, "<?php echo $cat_name; ?>")' data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="product-info">
                                                            <h3 class="product-title"><a href="#"><?php echo $p_name; ?></a></h3>
                                                            <div class="product-price"><span>PKR <?php echo $price; ?></span></div>
                                                        </div>
                                                        <a href="<?php echo $wa_link; ?>" target="_blank" class="product-cart-btn"><i class="fab fa-whatsapp"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        } else {
                                            // === NEW LIST VIEW DESIGN (SCREENSHOT MATCH) ===
                                            ?>
                                            <div class="col-lg-12">
                                                <div class="custom-list-card">

                                                    <div class="custom-list-img-box">
                                                        <span class="list-tag <?php echo $badge; ?>"><?php echo $db_tag; ?></span>
                                                        <img src="<?php echo file_exists($img_src)?$img_src:'assets/img/product/01.png'; ?>" alt="<?php echo $p_name; ?>">

                                                        <a href="javascript:void(0);" 
                                                           class="hover-eye-btn"
                                                           onclick='openQuickView(<?php echo json_encode($row); ?>, "<?php echo $cat_name; ?>")' 
                                                           data-bs-toggle="modal" data-bs-target="#quickview">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                    </div>

                                                    <div class="custom-list-content">
                                                        <a href="#" class="custom-list-title"><?php echo $p_name; ?></a>

                                                        <p class="custom-list-desc">
                                                            <?php echo !empty($row['details']) ? substr($row['details'], 0, 200).'...' : 'No description available for this premium product.'; ?>
                                                        </p>

                                                        <div class="d-flex justify-content-between align-items-end mt-4">
                                                            <div class="custom-list-price">PKR <?php echo $price; ?></div>

                                                            <a href="<?php echo $wa_link; ?>" target="_blank" class="custom-list-action-btn">
                                                                <i class="fab fa-whatsapp"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                } else {
                                    echo "<div class='col-12 text-center py-5'><h3>No Products Found!</h3><a href='shop.php' class='theme-btn'>Clear Filters</a></div>";
                                }
                                ?>
                            </div>
                        </div>

                        <?php if($total_pages > 1): ?>
                        <div class="pagination-area mt-50">
                            <div aria-label="Page navigation example">
                                <ul class="pagination">
                                                
                                    <?php if($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript:void(0);" onclick="setPage(<?php echo $page - 1; ?>)" aria-label="Previous">
                                            <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php
                                    // Start aur End calculate karo (Current se 1 peeche, Current, Current se 1 aage)
                                    $start_loop = max(1, $page - 1);
                                    $end_loop = min($total_pages, $page + 1);
                                    
                                    // Loop sirf inhi numbers par chalega
                                    for($i = $start_loop; $i <= $end_loop; $i++): 
                                    ?>
                                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                            <a class="page-link" href="javascript:void(0);" onclick="setPage(<?php echo $i; ?>)"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript:void(0);" onclick="setPage(<?php echo $page + 1; ?>)" aria-label="Next">
                                            <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- shop-area end -->

    </main>


    <?php include "partials/footer.php"?>


    <!-- scroll-top -->
    <a href="#" id="scroll-top"><i class="far fa-arrow-up-from-arc"></i></a>
    <!-- scroll-top end -->


    <!-- modal quick shop-->
    <div class="modal quickview fade" id="quickview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="quickview" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="far fa-xmark"></i>
                </button>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <img id="qv_image" src="" alt="Product Image" style="width: 100%; height: 450px; object-fit: cover; border-radius: 10px;">
                        </div>
                                    
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="quickview-content">
                                    
                                <h4 id="qv_title" class="quickview-title">Product Name</h4>
                                    
                                <div class="quickview-price">
                                    <h5><span id="qv_price">PKR 0</span></h5>
                                </div>
                                    
                                <ul class="quickview-list">
                                    <li>Category: <span id="qv_category" style="font-weight:600; color:#333;">-</span></li>
                                    <li>Tag: <span id="qv_tag" class="badge" style="background-color:var(--theme-color); color:white; padding:5px 10px; border-radius:5px;">-</span></li>
                                </ul>
                                    
                                <div class="quickview-cart" style="margin-bottom: 20px;">
                                    <a id="qv_wa_link" href="#" target="_blank" class="theme-btn" style="width: 100%; text-align: center;">
                                        <i class="fab fa-whatsapp"></i> Order on WhatsApp
                                    </a>
                                </div>

                                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 20px;">
                                    <h6 style="font-size: 14px; font-weight: 700; margin-bottom: 8px; color: #333;">Description:</h6>
                                    <div id="qv_details" class="custom-scroll" 
                                         style="max-height: 100px; overflow-y: auto; font-size: 14px; line-height: 1.6; color: #666; padding-right: 5px;">
                                        </div>
                                </div>
                                    
                                <div class="quickview-social" style="border-top: 1px solid #eee; padding-top: 15px;">
                                    <span>Share:</span>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal quick shop end -->


    <!-- js -->
    <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/jquery.appear.min.js"></script>
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/counter-up.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <script src="assets/js/countdown.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/main.js"></script>

<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"version":"2024.11.0","token":"1190e059c5bc497bafd35e121aae37b1","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
<script>
        $(document).ready(function() {
            // Price Slider Init
            var minP = parseInt($("#min_price_val").val());
            var maxP = parseInt($("#max_price_val").val());
            
            $(".price-range").slider({
                range: true, min: 0, max: 20000, values: [minP, maxP],
                slide: function(event, ui) { $("#price-amount").val("PKR " + ui.values[0] + " - PKR " + ui.values[1]); },
                stop: function(event, ui) { applyFilters(); }
            });
            $("#price-amount").val("PKR " + minP + " - PKR " + maxP);
        });

        // Master Filter Function
        function applyFilters() {
            var url = new URL(window.location.href);
            var params = new URLSearchParams(url.search);

            // 1. Search
            var search = $("#searchInput").val();
            if(search) params.set('search', search); else params.delete('search');

            // 2. Sort
            var sort = $("#sortSelect").val();
            params.set('sort', sort);

            // 3. Price
            var min = $(".price-range").slider("values", 0);
            var max = $(".price-range").slider("values", 1);
            params.set('min_price', min);
            params.set('max_price', max);

            // 4. Tags
            params.delete('tags[]'); // Clear old tags
            $('.filter-tag:checked').each(function() {
                params.append('tags[]', $(this).val());
            });

            // Page reset to 1 on filter change
            params.set('page', 1);

            window.location.href = "shop.php?" + params.toString();
        }

        // Helpers for Links
        function setCategory(id) {
            var url = new URL(window.location.href);
            url.searchParams.set('category', id);
            url.searchParams.set('page', 1); // Reset page
            window.location.href = url.toString();
        }

        function setPage(p) {
            var url = new URL(window.location.href);
            url.searchParams.set('page', p);
            window.location.href = url.toString();
        }

        function setView(v) {
            var url = new URL(window.location.href);
            url.searchParams.set('view', v);
            url.searchParams.set('page', 1); // View change karte waqt page 1 par le jao
            window.location.href = url.toString();
        }
        // --- Quick View Modal Function ---
        function openQuickView(product, categoryName) {
            // 1. Basic Data
            document.getElementById('qv_title').innerText = product.name;
            document.getElementById('qv_price').innerText = "PKR " + new Intl.NumberFormat().format(product.price);
            document.getElementById('qv_category').innerText = categoryName;
                                    
            // 2. Dynamic Tag
            var tagText = product.product_tag ? product.product_tag : 'New';
            document.getElementById('qv_tag').innerText = tagText;
                                    
            // 3. Description
            var desc = product.details ? product.details : "No description available.";
            document.getElementById('qv_details').innerHTML = desc;
                                    
            // 4. Image Path
            var imgPath = "uploads/" + product.image;
            document.getElementById('qv_image').src = imgPath;
                                    
            // 5. WhatsApp Link
            var phone = "923350391951"; // Apna number check karlena
            var msg = encodeURIComponent("Salam! I want to order from Shop: " + product.name + " - Price: " + product.price);
            document.getElementById('qv_wa_link').href = "https://wa.me/" + phone + "?text=" + msg;
        }
    </script>
</body>


<!-- Mirrored from live.themewild.com/gifoy/shop-grid.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 10 Feb 2026 16:13:16 GMT -->
</html>