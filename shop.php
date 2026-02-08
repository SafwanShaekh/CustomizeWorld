<?php
include 'database/db.php';

// --- CONFIGURATION ---
$limit = 12; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- FILTER VARIABLES ---
$cat_id = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : '';

// Check karo ke kya koi EXTRA filter laga hai? (Category ke ilawa)
$is_filtered = !empty($search) || !empty($min_price) || !empty($max_price);

$page_title = "All Products"; 

// --- BUILD QUERY ---
$where_conditions = [];

if (!empty($cat_id)) {
    $cat_sql = "SELECT cat_name FROM categories WHERE id = '$cat_id'";
    $cat_res = mysqli_query($conn, $cat_sql);
    if ($cat_row = mysqli_fetch_assoc($cat_res)) {
        $page_title = $cat_row['cat_name'];
    }
    $where_conditions[] = "category_id = '$cat_id'";
}

if (!empty($search)) { $where_conditions[] = "name LIKE '%$search%'"; }
if (!empty($min_price)) { $where_conditions[] = "price >= $min_price"; }
if (!empty($max_price)) { $where_conditions[] = "price <= $max_price"; }

$where_clause = "";
if (count($where_conditions) > 0) {
    $where_clause = "WHERE " . implode(' AND ', $where_conditions);
}

// --- QUERIES ---
$count_sql = "SELECT COUNT(*) FROM products $where_clause";
$count_res = mysqli_query($conn, $count_sql);
$total_rows = mysqli_fetch_array($count_res)[0];
$total_pages = ceil($total_rows / $limit);

// JOIN lagaya hai taake category ka naam bhi mile
$prod_sql = "SELECT products.*, categories.cat_name 
             FROM products 
             LEFT JOIN categories ON products.category_id = categories.id 
             $where_clause 
             ORDER BY products.id DESC LIMIT $limit OFFSET $offset";
$prod_res = mysqli_query($conn, $prod_sql);
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $page_title; ?> - FloSun</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font.awesome.min.css">
    <link rel="stylesheet" href="assets/css/vendor/linearicons.min.css">
    <link rel="stylesheet" href="assets/css/plugins/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .pagination-area { margin-top: 50px; display: flex; justify-content: center; }
        .page-link-custom {
            display: inline-block; padding: 10px 18px; margin: 0 5px; background: #fff; 
            border: 1px solid #ddd; color: #333; border-radius: 5px; transition: 0.3s;
            font-weight: 600; text-decoration: none;
        }
        .page-link-custom.active, .page-link-custom:hover {
            background-color: #E91E63; color: white; border-color: #E91E63;
        }
        .breadcrumb-area {
            background-image: url('assets/images/bg/breadcrumb.jpg'); 
            background-size: cover; background-position: center center; opacity: 0.7;
            min-height: 276px; display: flex; align-items: center; justify-content: center;
            padding-top: 80px; 
        }
        .breadcrumb-title {
            color: #030202; font-size: 48px; font-weight: 700;
            font-family: 'Great Vibes', cursive; position: relative !important; margin: 0;
            text-shadow: 2px 2px 4px rgba(255,255,255,0.5);
        }
        .filter-box {
            background: #fff; padding: 25px; border-radius: 8px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 40px; border: 1px solid #eee;
        }
        .filter-box label { font-weight: 600; margin-bottom: 5px; font-size: 14px; color: #555; }
        .filter-box input { border-radius: 4px; border: 1px solid #ddd; height: 45px; }

        /* --- 3D FLIP CARD DESIGN --- */
        .flip-card {
            background-color: transparent;
            width: 100%;
            height: 380px; /* Card ki height */
            perspective: 1000px; /* 3D effect ke liye zaroori */
            margin-bottom: 30px;
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

        /* Hover karne par ghoomega */
        .flip-card:hover .flip-card-inner {
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

        /* --- FRONT SIDE (Sirf Image) --- */
        .flip-card-front {
            background-color: #fff;
        }
        .flip-card-front img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Image stretch nahi hogi */
        }

        /* --- BACK SIDE (Details + Glow) --- */
        .flip-card-back {
            background-color: white; /* Background color */
            color: #333;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            border: 2px solid #E91E63; /* Pink Border */

            /* GLOW EFFECT */
            box-shadow: 0 0 15px rgba(233, 30, 99, 0.6); 
        }

        /* Details Styling */
        .flip-category {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #777;
            margin-bottom: 5px;
        }
        .flip-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }
        .flip-price {
            font-size: 22px;
            color: #E91E63;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .flip-details {
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Sirf 3 lines show karega details ki */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* WhatsApp Button */
        .btn-flip-order {
            background-color: #25D366;
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(37, 211, 102, 0.4);
            transition: 0.3s;
        }
        .btn-flip-order:hover {
            background-color: #128C7E;
            color: white;
            transform: scale(1.05);
        }

        /* --- PRODUCT MODAL DESIGN --- */
        .product-modal-overlay {
            display: none; /* Hidden by default */
            position: fixed; z-index: 9999;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.7); /* Dark background */
            align-items: center; justify-content: center;
            backdrop-filter: blur(5px); /* Background Blur effect */
        }

        .product-modal-box {
            background: #fff;
            width: 90%; max-width: 800px;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn { from {transform: scale(0.8); opacity: 0;} to {transform: scale(1); opacity: 1;} }

        /* Left Side: Image */
        .pm-img-col {
            width: 50%;
            background: #f9f9f9;
            display: flex; align-items: center; justify-content: center;
        }
        .pm-img-col img {
            width: 100%; height: 100%; object-fit: cover;
            max-height: 500px;
        }

        /* Right Side: Details */
        .pm-info-col {
            width: 50%; padding: 30px;
            display: flex; flex-direction: column;
        }

        .pm-close {
            position: absolute; right: 20px; top: 15px;
            font-size: 28px; cursor: pointer; color: #aaa; z-index: 10;
        }
        .pm-close:hover { color: #E91E63; }

        .pm-cat { color: #888; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .pm-title { font-size: 26px; font-weight: 700; color: #333; margin-bottom: 10px; }
        .pm-price { font-size: 24px; color: #E91E63; font-weight: bold; margin-bottom: 20px; }

        /* --- CUSTOM SCROLLBAR FOR DESCRIPTION --- */
        .pm-desc-box {
            max-height: 200px; /* Is se zyada hua to scroll ayega */
            overflow-y: auto;
            margin-bottom: 25px;
            font-size: 15px; color: #555; line-height: 1.6;
            padding-right: 10px; /* Scrollbar ke liye jagah */
        }

        /* Wahi same Scrollbar Design */
        .pm-desc-box::-webkit-scrollbar { width: 5px; }
        .pm-desc-box::-webkit-scrollbar-track { background: #f1f1f1; }
        .pm-desc-box::-webkit-scrollbar-thumb { background: #E91E63; border-radius: 5px; }
        .pm-desc-box::-webkit-scrollbar-thumb:hover { background: #c2185b; }

        /* Responsive: Mobile par stack ho jaye */
        @media (max-width: 768px) {
            .product-modal-box { flex-direction: column; height: 90vh; overflow-y: auto; }
            .pm-img-col, .pm-info-col { width: 100%; }
            .pm-img-col { height: 250px; }
        }
    </style>
</head>

<body>

    <?php include "partials/header.php"?>

    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap text-center">
                        <h1 class="breadcrumb-title position-relative"><?php echo $page_title; ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-area section-padding">
        <div class="container">
            
            <div class="row mt-5">
                <div class="col-12">
                    <form method="GET" action="shop.php" class="filter-box">
                        <div class="row align-items-end">
                            <?php if(!empty($cat_id)): ?>
                                <input type="hidden" name="category" value="<?php echo $cat_id; ?>">
                            <?php endif; ?>

                            <div class="col-md-4 col-sm-12 mb-3 mb-md-0">
                                <label><i class="fa fa-search"></i> Search Product</label>
                                <input type="text" name="search" class="form-control" placeholder="Type Name..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                <label>Min Price</label>
                                <input type="number" name="min_price" class="form-control" placeholder="0" value="<?php echo $min_price; ?>">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                <label>Max Price</label>
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="<?php echo $max_price; ?>">
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <button type="submit" class="btn flosun-button secondary-btn rounded-0 w-100" style="height: 45px; line-height: 45px; padding: 0;">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <?php
                if (mysqli_num_rows($prod_res) > 0) {
                    while ($row = mysqli_fetch_assoc($prod_res)) {
                    $img_src = "uploads/" . $row['image'];
                    $phone = "923350391951"; /// whatsapp number teacher ka 
                    $p_name = urlencode($row['name']);
                    $price = $row['price'];
                    $wa_link = "https://wa.me/$phone?text=Salam! I want to order *$p_name* - Price: $price";
                    $cat_name = isset($row['cat_name']) ? $row['cat_name'] : 'Product';
                ?>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="flip-card" 
                         onclick="openProductModal(this)"
                         data-name="<?php echo htmlspecialchars($row['name']); ?>"
                         data-price="<?php echo number_format($row['price']); ?>"
                         data-cat="<?php echo htmlspecialchars($cat_name); ?>"
                         data-desc="<?php echo htmlspecialchars($row['details']); ?>"
                         data-img="<?php echo file_exists($img_src) ? $img_src : 'assets/images/product/1.jpg'; ?>"
                         data-walink="<?php echo $wa_link; ?>"
                         style="cursor: pointer;">

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
                                <h3 class="flip-title"><?php echo $row['name']; ?></h3>
                                
                                <p class="flip-details">
                                    <?php echo (strlen($row['details']) > 80) ? substr($row['details'], 0, 80) . '...' : $row['details']; ?>
                                    <br><span style="color:#E91E63; font-size:12px; font-weight:bold;">(Click for more)</span>
                                </p>
                                
                                <span class="flip-price">PKR <?php echo number_format($row['price']); ?></span>
                                
                                <a href="<?php echo $wa_link; ?>" target="_blank" class="btn-flip-order" onclick="event.stopPropagation();">
                                    <i class="fa fa-whatsapp"></i> Order Now
                                </a>
                            </div>
                                
                        </div>
                    </div>
                </div>
                                
                <?php 
                }
                } else {
                    // --- LOGIC FIX: Check karo ke filter laga hai ya category khali hai ---
                    
                    if ($is_filtered) {
                        // CASE 1: Filter laga hua hai, par kuch nahi mila (Reset dikhao)
                        $reset_link = "shop.php";
                        if(!empty($cat_id)){ $reset_link .= "?category=" . $cat_id; }

                        echo "<div class='col-12 text-center' style='padding: 100px 0;'>
                                <i class='fa fa-search' style='font-size: 50px; color: #ccc; margin-bottom: 20px;'></i>
                                <h3>No Products Found!</h3>
                                <p>Try changing your filters or search keywords.</p>
                                <a href='$reset_link' class='btn flosun-button secondary-btn rounded-0'>Reset Filters</a>
                              </div>";
                    } else {
                        // CASE 2: Category hi khali hai (Browse Other Categories dikhao)
                        echo "<div class='col-12 text-center' style='padding: 100px 0;'>
                                <i class='fa fa-box-open' style='font-size: 50px; color: #ccc; margin-bottom: 20px;'></i>
                                <h3>No Products Yet!</h3>
                                <p>We haven't added any products in this category yet. Stay tuned!</p>
                                <a href='index.php' class='btn flosun-button secondary-btn rounded-0'>Browse Other Categories</a>
                              </div>";
                    }
                }
                ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="row mb-5">
                <div class="col-12">
                    <div class="pagination-area">
                        <?php
                        $url_params = "";
                        if(!empty($cat_id)) $url_params .= "&category=$cat_id";
                        if(!empty($search)) $url_params .= "&search=$search";
                        if(!empty($min_price)) $url_params .= "&min_price=$min_price";
                        if(!empty($max_price)) $url_params .= "&max_price=$max_price";

                        if ($page > 1) echo '<a href="?page='.($page-1).$url_params.'" class="page-link-custom">&laquo; Prev</a>';
                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active = ($page == $i) ? 'active' : '';
                            echo '<a href="?page='.$i.$url_params.'" class="page-link-custom '.$active.'">'.$i.'</a>';
                        }
                        if ($page < $total_pages) echo '<a href="?page='.($page+1).$url_params.'" class="page-link-custom">Next &raquo;</a>';
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="productModal" class="product-modal-overlay">
        <div class="product-modal-box">
            <span class="pm-close" onclick="closeProductModal()">&times;</span>
                
            <div class="pm-img-col">
                <img id="modalImg" src="" alt="Product">
            </div>
                
            <div class="pm-info-col">
                <span id="modalCat" class="pm-cat">Category</span>
                <h2 id="modalTitle" class="pm-title">Product Name</h2>
                <div id="modalPrice" class="pm-price">PKR 0</div>
                
                <div class="pm-desc-box custom-scroll" id="modalDesc">
                    Product description goes here...
                </div>
                
                <a id="modalWaBtn" href="#" target="_blank" class="btn flosun-button secondary-btn rounded-0" style="background: #25D366; border: none; text-align:center;">
                    <i class="fa fa-whatsapp"></i> Order on WhatsApp
                </a>
            </div>
        </div>
    </div>

    <script>
        // Open Modal Function
        function openProductModal(element) {
            // Data attributes se value uthao (jo hum Step 3 me set karenge)
            var name = element.getAttribute('data-name');
            var price = element.getAttribute('data-price');
            var cat = element.getAttribute('data-cat');
            var desc = element.getAttribute('data-desc');
            var img = element.getAttribute('data-img');
            var link = element.getAttribute('data-walink');

            // Modal ke elements me value daalo
            document.getElementById('modalTitle').innerText = name;
            document.getElementById('modalPrice').innerText = "PKR " + price;
            document.getElementById('modalCat').innerText = cat;
            document.getElementById('modalDesc').innerText = desc; // HTML tags hatane ke liye innerText use kiya
            document.getElementById('modalImg').src = img;
            document.getElementById('modalWaBtn').href = link;

            // Modal dikhao
            document.getElementById('productModal').style.display = "flex";
        }

        // Close Modal Function
        function closeProductModal() {
            document.getElementById('productModal').style.display = "none";
        }

        // Window click se close karna
        window.onclick = function(event) {
            var modal = document.getElementById('productModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <?php include 'partials/footer.php'; ?>


    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>