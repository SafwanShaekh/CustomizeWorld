<?php
session_start();
// --- SECURITY: Prevent Browser Back Button Issue ---
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['admin_name'])) { header("Location: login.php"); exit(); }
include '../database/db.php';

// --- CONFIGURATION ---
$limit = 10; 
$url_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($url_page < 1) $url_page = 1;

// --- TAB LOGIC ---
$active_tab = 'product'; // Default
if (isset($_GET['type']) && $_GET['type'] == 'category') {
    $active_tab = 'category';
}

// Page Vars
$prod_page = ($active_tab == 'product') ? $url_page : 1;
$cat_page  = ($active_tab == 'category') ? $url_page : 1;

$prod_offset = ($prod_page - 1) * $limit;
$cat_offset  = ($cat_page - 1) * $limit;

// --- 1. UPDATE LOGIC ---

// A. Tag Update
if(isset($_POST['save_tag'])){
    $p_id = $_POST['tag_prod_id'];
    $tag_val = mysqli_real_escape_string($conn, $_POST['tag_value']);
    $sql = "UPDATE products SET product_tag='$tag_val' WHERE id='$p_id'";
    if(mysqli_query($conn, $sql)){ $success_msg = "Tag Updated!"; }
}

// B. Category Update
if(isset($_POST['update_category'])){
    $id = $_POST['edit_cat_id'];
    $name = mysqli_real_escape_string($conn, $_POST['edit_cat_name']);
    $sql = "UPDATE categories SET cat_name='$name' WHERE id='$id'";
    if(mysqli_query($conn, $sql)){ $success_msg = "Category Updated!"; $active_tab = 'category'; }
}

// C. Product Update
if(isset($_POST['update_product'])){
    $id = $_POST['edit_prod_id'];
    $name = mysqli_real_escape_string($conn, $_POST['edit_prod_name']);
    $price = $_POST['edit_prod_price'];
    $cat_id = $_POST['edit_prod_cat'];
    $details = mysqli_real_escape_string($conn, $_POST['edit_prod_details']);
    
    $sql_part = "";
    if(!empty($_FILES['edit_prod_image']['name'])){
        $img_name = $_FILES['edit_prod_image']['name'];
        $tmp_name = $_FILES['edit_prod_image']['tmp_name'];
        $target = "../uploads/" . basename($img_name);
        if(move_uploaded_file($tmp_name, $target)){ $sql_part = ", image='$img_name'"; }
    }

    $sql = "UPDATE products SET name='$name', price='$price', category_id='$cat_id', details='$details' $sql_part WHERE id='$id'";
    if(mysqli_query($conn, $sql)){ $success_msg = "Product Updated!"; $active_tab = 'product'; }
}

// --- DELETE LOGIC ---
if (isset($_GET['delete_id']) && isset($_GET['type'])) {
    $id = $_GET['delete_id'];
    $type = $_GET['type'];
    if ($type == 'product') {
        $sql = "DELETE FROM products WHERE id='$id'";
    } else {
        $sql = "DELETE FROM categories WHERE id='$id'";
    }
    mysqli_query($conn, $sql);
    header("Location: manage_content.php?type=$type&page=$url_page");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Content</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; background-color: #f4f6f9; overflow-x: hidden; }
        
        /* Main Layout */
        .main-content { flex: 1; margin-left: 260px; padding: 30px; width: calc(100% - 260px); transition: 0.3s; }
        
        /* Toggle Switch */
        .toggle-container { display: flex; justify-content: center; margin-bottom: 30px; }
        .toggle-wrapper { background: #e9ecef; border-radius: 30px; padding: 5px; display: inline-flex; box-shadow: inset 0 2px 5px rgba(0,0,0,0.05); }
        .toggle-wrapper input { display: none; }
        .toggle-wrapper label { padding: 10px 30px; cursor: pointer; border-radius: 25px; color: #6c757d; font-weight: 600; transition: 0.3s; user-select: none; }
        .toggle-wrapper input:checked + label { background-color: #007bff; color: white; box-shadow: 0 4px 6px rgba(0,123,255,0.3); }

        /* Responsive Table */
        .table-responsive { 
            width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; 
            background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        }
        table { width: 100%; border-collapse: collapse; min-width: 800px; /* Min width forces scroll on mobile */ }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; white-space: nowrap; }
        th { background: #343a40; color: white; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; }
        
        /* Action Buttons */
        .action-btn { padding: 6px 10px; text-decoration: none; border-radius: 4px; color: white; margin-right: 5px; cursor:pointer; border:none; font-size: 13px; display: inline-block; }
        .edit-btn { background: #ffc107; color: #333; }
        .del-btn { background: #dc3545; }
        
        /* Pagination */
        .pagination { display: flex; justify-content: center; margin-top: 25px; gap: 5px; flex-wrap: wrap; }
        .page-link { padding: 8px 14px; border: 1px solid #dee2e6; color: #007bff; text-decoration: none; border-radius: 4px; background: white; font-weight: 500; font-size: 14px; }
        .page-link:hover { background-color: #e9ecef; }
        .page-link.active { background-color: #007bff; color: white; border-color: #007bff; }

        /* Tag Select */
        .tag-select { padding: 5px; border-radius: 4px; border: 1px solid #ddd; font-size: 13px; background: #fff; cursor: pointer; }

        /* Modal */
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center; backdrop-filter: blur(2px); }
        .modal-content { background-color: white; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: slideDown 0.3s; }
        @keyframes slideDown { from {transform: translateY(-50px); opacity: 0;} to {transform: translateY(0); opacity: 1;} }
        .close { float: right; font-size: 24px; cursor: pointer; }
        
        /* Form Elements */
        input, select, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .save-btn { width: 100%; background: #28a745; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        
        .content-section { display: none; animation: fadeIn 0.4s; }
        .content-section.active { display: block; }
        @keyframes fadeIn { from{opacity:0;} to{opacity:1;} }

        /* --- RESPONSIVE MEDIA QUERIES --- */
        @media (max-width: 991px) { 
            .main-content { margin-left: 0; width: 100%; padding: 20px; padding-top: 80px; } 
        }

        @media (max-width: 768px) {
            .toggle-wrapper label { padding: 8px 20px; font-size: 14px; }
            th, td { padding: 10px; font-size: 13px; }
            .action-btn { padding: 5px 8px; font-size: 12px; }
            h2 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <h2 style="text-align:center; margin-bottom: 20px; font-weight:700; color:#333;">Manage Content</h2>

        <div class="toggle-container">
            <div class="toggle-wrapper">
                <input type="radio" name="view_type" id="radio_prod" onclick="showTable('products')" <?php echo ($active_tab == 'product') ? 'checked' : ''; ?>>
                <label for="radio_prod">Products</label>
                <input type="radio" name="view_type" id="radio_cat" onclick="showTable('categories')" <?php echo ($active_tab == 'category') ? 'checked' : ''; ?>>
                <label for="radio_cat">Categories</label>
            </div>
        </div>

        <div id="products-table" class="content-section <?php echo ($active_tab == 'product') ? 'active' : ''; ?>">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Img</th><th>Name</th><th>Category</th><th>Price</th><th>Tag (Status)</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Count Total
                        $total_p_res = mysqli_query($conn, "SELECT COUNT(*) FROM products");
                        $total_p_rows = mysqli_fetch_array($total_p_res)[0];
                        $total_p_pages = ceil($total_p_rows / $limit);

                        $sql = "SELECT products.*, categories.cat_name FROM products 
                                LEFT JOIN categories ON products.category_id = categories.id 
                                ORDER BY products.id DESC LIMIT $limit OFFSET $prod_offset";
                        $res = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($res) > 0) {
                            while($row = mysqli_fetch_assoc($res)){
                                $img_path = "../uploads/".$row['image'];
                                $current_tag = isset($row['product_tag']) ? $row['product_tag'] : 'New';
                                echo "<tr>
                                        <td><img src='$img_path' width='40' height='40' style='border-radius:4px; object-fit:cover; border:1px solid #eee;'></td>
                                        <td><strong>".htmlspecialchars($row['name'])."</strong></td>
                                        <td>".htmlspecialchars($row['cat_name'])."</td>
                                        <td>Rs. ".number_format($row['price'])."</td>
                                        <td>
                                            <select class='tag-select' onchange='handleTagChange(this, ".$row['id'].")'>
                                                <option value='New' ".($current_tag=='New'?'selected':'').">New</option>
                                                <option value='Hot' ".($current_tag=='Hot'?'selected':'').">Hot</option>
                                                <option value='Sale' ".($current_tag=='Sale'?'selected':'').">Sale</option>
                                                <option value='Out of Stock' ".($current_tag=='Out of Stock'?'selected':'').">Out of Stock</option>
                                                <option value='custom' ".( !in_array($current_tag, ['New','Hot','Sale','Out of Stock']) ? 'selected' : '' ).">Custom...</option>
                                            </select>
                                            ".(!in_array($current_tag, ['New','Hot','Sale','Out of Stock']) ? "<br><small style='color:#007bff; font-weight:bold;'>$current_tag</small>" : "")."
                                        </td>
                                        <td>
                                            <button class='action-btn edit-btn' onclick='openProdModal(".json_encode($row).")'><i class='fas fa-edit'></i></button>
                                            <a href='manage_content.php?delete_id=".$row['id']."&type=product&page=$prod_page' class='action-btn del-btn' onclick='return confirm(\"Delete?\")'><i class='fas fa-trash'></i></a>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center; padding:30px;'>No Products Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if($total_p_pages > 1): ?>
            <div class="pagination">
                <?php if($prod_page > 1): ?>
                    <a href="manage_content.php?type=product&page=<?php echo $prod_page-1; ?>" class="page-link">&laquo;</a>
                <?php endif; ?>

                <?php
                $start_p = max(1, $prod_page - 2);
                $end_p = min($total_p_pages, $prod_page + 2);
                for($i = $start_p; $i <= $end_p; $i++): ?>
                    <a href="manage_content.php?type=product&page=<?php echo $i; ?>" class="page-link <?php echo ($prod_page==$i)?'active':''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if($prod_page < $total_p_pages): ?>
                    <a href="manage_content.php?type=product&page=<?php echo $prod_page+1; ?>" class="page-link">&raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div id="categories-table" class="content-section <?php echo ($active_tab == 'category') ? 'active' : ''; ?>">
            <div class="table-responsive">
                <table>
                    <thead><tr><th>ID</th><th>Category Name</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php
                        // Count Total
                        $total_c_res = mysqli_query($conn, "SELECT COUNT(*) FROM categories");
                        $total_c_rows = mysqli_fetch_array($total_c_res)[0];
                        $total_c_pages = ceil($total_c_rows / $limit);

                        $c_sql = "SELECT * FROM categories ORDER BY id DESC LIMIT $limit OFFSET $cat_offset";
                        $c_res = mysqli_query($conn, $c_sql);
                        
                        if(mysqli_num_rows($c_res) > 0){
                            while($c_row = mysqli_fetch_assoc($c_res)){
                                echo "<tr>
                                        <td>#".$c_row['id']."</td>
                                        <td><strong>".htmlspecialchars($c_row['cat_name'])."</strong></td>
                                        <td>
                                            <button class='action-btn edit-btn' onclick='openCatModal(\"".$c_row['id']."\", \"".htmlspecialchars($c_row['cat_name'])."\")'><i class='fas fa-edit'></i></button>
                                            <a href='manage_content.php?delete_id=".$c_row['id']."&type=category&page=$cat_page' class='action-btn del-btn' onclick='return confirm(\"Delete?\")'><i class='fas fa-trash'></i></a>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' style='text-align:center; padding:30px;'>No Categories Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if($total_c_pages > 1): ?>
            <div class="pagination">
                <?php if($cat_page > 1): ?>
                    <a href="manage_content.php?type=category&page=<?php echo $cat_page-1; ?>" class="page-link">&laquo;</a>
                <?php endif; ?>

                <?php
                $start_c = max(1, $cat_page - 2);
                $end_c = min($total_c_pages, $cat_page + 2);
                for($i = $start_c; $i <= $end_c; $i++): ?>
                    <a href="manage_content.php?type=category&page=<?php echo $i; ?>" class="page-link <?php echo ($cat_page==$i)?'active':''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if($cat_page < $total_c_pages): ?>
                    <a href="manage_content.php?type=category&page=<?php echo $cat_page+1; ?>" class="page-link">&raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <form id="tagForm" method="POST" style="display:none;">
        <input type="hidden" name="tag_prod_id" id="hidden_tag_id">
        <input type="hidden" name="tag_value" id="hidden_tag_val">
        <button type="submit" name="save_tag" id="btn_save_tag"></button>
    </form>

    <div id="customTagModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('customTagModal')">&times;</span>
            <h3>Set Custom Tag</h3>
            <label>Enter Tag Name</label>
            <input type="text" id="custom_tag_input">
            <button type="button" class="save-btn" onclick="submitCustomTag()">Apply Tag</button>
        </div>
    </div>

    <div id="catModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('catModal')">&times;</span>
            <h3>Edit Category</h3>
            <form method="POST">
                <input type="hidden" name="edit_cat_id" id="edit_cat_id">
                <input type="text" name="edit_cat_name" id="edit_cat_name" required>
                <button type="submit" name="update_category" class="save-btn">Update</button>
            </form>
        </div>
    </div>

    <div id="prodModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('prodModal')">&times;</span>
            <h3>Edit Product</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="edit_prod_id" id="edit_prod_id">
                <label>Name</label><input type="text" name="edit_prod_name" id="edit_prod_name" required>
                <label>Price</label><input type="number" name="edit_prod_price" id="edit_prod_price" required>
                <label>Category</label>
                <select name="edit_prod_cat" id="edit_prod_cat">
                    <?php
                    $cats = mysqli_query($conn, "SELECT * FROM categories");
                    while($c = mysqli_fetch_assoc($cats)){ echo "<option value='".$c['id']."'>".$c['cat_name']."</option>"; }
                    ?>
                </select>
                <label>Details</label><textarea name="edit_prod_details" id="edit_prod_details"></textarea>
                <label>Image</label><input type="file" name="edit_prod_image">
                <button type="submit" name="update_product" class="save-btn">Update</button>
            </form>
        </div>
    </div>

    <script>
        let currentProdIdForTag = null;
        
        function handleTagChange(selectElem, prodId) {
            if(selectElem.value === 'custom') {
                currentProdIdForTag = prodId;
                document.getElementById('custom_tag_input').value = '';
                document.getElementById('customTagModal').style.display = 'flex';
            } else {
                submitTagForm(prodId, selectElem.value);
            }
        }
        function submitCustomTag() {
            var val = document.getElementById('custom_tag_input').value;
            if(val.trim() !== "") submitTagForm(currentProdIdForTag, val);
        }
        function submitTagForm(id, value) {
            document.getElementById('hidden_tag_id').value = id;
            document.getElementById('hidden_tag_val').value = value;
            document.getElementById('btn_save_tag').click();
        }
        
        function showTable(type) {
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('type', type === 'products' ? 'product' : 'category');
            currentUrl.searchParams.set('page', 1); 
            window.history.pushState({}, '', currentUrl);

            document.getElementById('products-table').classList.toggle('active', type === 'products');
            document.getElementById('categories-table').classList.toggle('active', type === 'categories');
        }
        
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }
        
        function openCatModal(id, name) {
            document.getElementById('catModal').style.display = 'flex';
            document.getElementById('edit_cat_id').value = id;
            document.getElementById('edit_cat_name').value = name;
        }
        function openProdModal(data) {
            document.getElementById('prodModal').style.display = 'flex';
            document.getElementById('edit_prod_id').value = data.id;
            document.getElementById('edit_prod_name').value = data.name;
            document.getElementById('edit_prod_price').value = data.price;
            document.getElementById('edit_prod_details').value = data.details;
            document.getElementById('edit_prod_cat').value = data.category_id;
        }
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) event.target.style.display = "none";
        }
    </script>
</body>
</html>