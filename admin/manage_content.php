<?php
session_start();
if (!isset($_SESSION['admin_name'])) { header("Location: login.php"); exit(); }
include '../database/db.php';

// --- CONFIGURATION ---
$limit = 10; 
$url_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($url_page < 1) $url_page = 1;

// --- TAB LOGIC ---
$active_tab = 'product';
if (isset($_GET['type']) && $_GET['type'] == 'category') {
    $active_tab = 'category';
}

$prod_page = ($active_tab == 'product') ? $url_page : 1;
$cat_page  = ($active_tab == 'category') ? $url_page : 1;
$prod_offset = ($prod_page - 1) * $limit;
$cat_offset  = ($cat_page - 1) * $limit;

// --- 1. UPDATE LOGIC (Handle Form Submits) ---

// A. Update Category
if(isset($_POST['update_category'])){
    $id = $_POST['edit_cat_id'];
    $name = mysqli_real_escape_string($conn, $_POST['edit_cat_name']);
    
    $sql = "UPDATE categories SET cat_name='$name' WHERE id='$id'";
    if(mysqli_query($conn, $sql)){
        $success_msg = "Category Updated Successfully!";
        $active_tab = 'category'; // Stay on category tab
    }
}

// B. Update Product
if(isset($_POST['update_product'])){
    $id = $_POST['edit_prod_id'];
    $name = mysqli_real_escape_string($conn, $_POST['edit_prod_name']);
    $price = $_POST['edit_prod_price'];
    $cat_id = $_POST['edit_prod_cat'];
    $details = mysqli_real_escape_string($conn, $_POST['edit_prod_details']);
    
    // Image Logic
    $sql_part = "";
    if(!empty($_FILES['edit_prod_image']['name'])){
        $img_name = $_FILES['edit_prod_image']['name'];
        $tmp_name = $_FILES['edit_prod_image']['tmp_name'];
        $target = "../uploads/" . basename($img_name);
        
        if(move_uploaded_file($tmp_name, $target)){
            $sql_part = ", image='$img_name'"; // Agar new image hai to update query me add karo
        }
    }

    $sql = "UPDATE products SET name='$name', price='$price', category_id='$cat_id', details='$details' $sql_part WHERE id='$id'";
    
    if(mysqli_query($conn, $sql)){
        $success_msg = "Product Updated Successfully!";
        $active_tab = 'product';
    }
}

// --- DELETE LOGIC ---
if (isset($_GET['delete_id']) && isset($_GET['type'])) {
    $id = $_GET['delete_id'];
    $type = $_GET['type'];
    $return_page = isset($_GET['page']) ? $_GET['page'] : 1;

    if ($type == 'product') {
        $img_q = mysqli_query($conn, "SELECT image FROM products WHERE id='$id'");
        $row = mysqli_fetch_assoc($img_q);
        if ($row && !empty($row['image'])) {
            $file_path = "../uploads/" . $row['image'];
            if (file_exists($file_path)) unlink($file_path);
        }
        $sql = "DELETE FROM products WHERE id='$id'";
        $warning_msg = "Product Deleted!";
    } else {
        $sql = "DELETE FROM categories WHERE id='$id'";
        $warning_msg = "Category Deleted!";
    }
    mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Content - CustomizeWorld</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        
        body { display: flex; min-height: 100vh; background-color: #f4f6f9; overflow-x: hidden; }

        /* --- Main Content --- */
        .main-content {
            flex: 1; margin-left: 260px; padding: 30px; 
            width: calc(100% - 260px); transition: 0.3s;
        }

        /* --- Toggle Buttons --- */
        .toggle-container { display: flex; justify-content: center; margin-bottom: 30px; }
        .toggle-wrapper { background: #e9ecef; border-radius: 30px; padding: 5px; display: inline-flex; box-shadow: inset 0 2px 5px rgba(0,0,0,0.1); flex-wrap: wrap; justify-content: center; }
        .toggle-wrapper input[type="radio"] { display: none; }
        .toggle-wrapper label { padding: 10px 30px; cursor: pointer; border-radius: 25px; font-weight: 600; color: #6c757d; transition: 0.3s; white-space: nowrap; }
        .toggle-wrapper input[type="radio"]:checked + label { background-color: #007bff; color: white; box-shadow: 0 4px 6px rgba(0,123,255,0.3); }
        
        /* --- Responsive Table --- */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); background: white; }
        
        table { width: 100%; border-collapse: collapse; min-width: 600px; /* Table ko chota hone se roka taake horizontal scroll aye */ }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
        th { background: #343a40; color: white; text-transform: uppercase; font-size: 14px; white-space: nowrap; }
        tr:hover { background-color: #f8f9fa; }
        
        .action-btn { padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 13px; margin-right: 5px; color: white; border: none; cursor: pointer; display: inline-block;}
        .edit-btn { background: #ffc107; color: #333; }
        .del-btn { background: #dc3545; }
        
        .content-section { display: none; animation: fadeIn 0.5s; }
        .content-section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* --- Pagination --- */
        .pagination { display: flex; justify-content: center; margin-top: 25px; gap: 8px; flex-wrap: wrap; }
        .pagination a { padding: 8px 14px; border: 1px solid #ddd; color: #6c757d; text-decoration: none; border-radius: 6px; transition: 0.3s; background: white; font-weight: 500; font-size: 14px; }
        .pagination a.active { background-color: #007bff; color: white; border-color: #007bff; }
        .pagination a:hover:not(.active) { background-color: #e9ecef; color: #333; }

        /* --- MODAL STYLES --- */
        .modal {
            display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center; backdrop-filter: blur(2px);
        }
        .modal-content {
            background-color: white; padding: 25px; border-radius: 8px; width: 90%; max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: slideDown 0.3s; position: relative;
            max-height: 90vh; overflow-y: auto; /* Scrollable if modal is too tall */
        }
        @keyframes slideDown { from {transform: translateY(-50px); opacity: 0;} to {transform: translateY(0); opacity: 1;} }
        
        .modal h3 { margin-top: 0; border-bottom: 2px solid #007bff; padding-bottom: 10px; display: inline-block; }
        .close { position: absolute; right: 20px; top: 15px; font-size: 28px; font-weight: bold; cursor: pointer; color: #aaa; }
        .close:hover { color: black; }
        
        .modal input, .modal select, .modal textarea { width: 100%; padding: 10px; margin: 8px 0 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .modal label { font-weight: 600; color: #555; font-size: 14px; }
        .save-btn { width: 100%; background: #28a745; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: 600; transition:0.3s; }
        .save-btn:hover { background: #218838; }

        /* --- RESPONSIVE QUERIES --- */
        @media (max-width: 991px) {
            .main-content { margin-left: 0; width: 100%; padding: 20px; padding-top: 80px; }
            .toggle-wrapper { flex-direction: column; width: 100%; border-radius: 10px; }
            .toggle-wrapper label { text-align: center; width: 100%; border-radius: 8px; margin-bottom: 5px; }
            .modal-content { width: 95%; padding: 20px; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        
        <h2 style="text-align:center; margin-bottom: 20px; color: #333;">Manage Content</h2>

        <div class="toggle-container">
            <div class="toggle-wrapper">
                <input type="radio" name="view_type" id="radio_prod" onclick="showTable('products')" <?php echo ($active_tab == 'product') ? 'checked' : ''; ?>>
                <label for="radio_prod"><i class="fas fa-box"></i> Products List</label>

                <input type="radio" name="view_type" id="radio_cat" onclick="showTable('categories')" <?php echo ($active_tab == 'category') ? 'checked' : ''; ?>>
                <label for="radio_cat"><i class="fas fa-tags"></i> Categories List</label>
            </div>
        </div>

        <div id="products-table" class="content-section <?php echo ($active_tab == 'product') ? 'active' : ''; ?>">
            <div class="table-responsive">
                <table>
                    <thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php
                        $total_p_res = mysqli_query($conn, "SELECT COUNT(*) FROM products");
                        $total_rows = mysqli_fetch_array($total_p_res)[0];
                        $total_p_pages = ceil($total_rows / $limit);

                        $sql = "SELECT products.*, categories.cat_name FROM products 
                                LEFT JOIN categories ON products.category_id = categories.id 
                                ORDER BY products.id DESC LIMIT $limit OFFSET $prod_offset";
                        $res = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($res) > 0) {
                            while($row = mysqli_fetch_assoc($res)){
                                echo "<tr>";
                                echo "<td>#".$row['id']."</td>";
                                $img_path = "../uploads/".$row['image'];
                                echo "<td>".(file_exists($img_path) ? "<img src='$img_path' width='50' height='50' style='border-radius:4px; object-fit:cover;'>" : "<span style='color:red;font-size:12px'>No Img</span>")."</td>";
                                echo "<td><strong>".htmlspecialchars($row['name'])."</strong></td>";
                                echo "<td><span style='background:#e9ecef; padding:4px 8px; border-radius:4px; font-size:12px;'>".htmlspecialchars($row['cat_name'])."</span></td>";
                                echo "<td>Rs. ".number_format($row['price'])."</td>";
                                echo "<td>
                                        <button class='action-btn edit-btn' onclick='openProdModal(".json_encode($row).")'><i class='fas fa-edit'></i></button>
                                        <a href='manage_content.php?delete_id=".$row['id']."&type=product&page=$url_page' class='action-btn del-btn' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center; padding: 40px;'>No Products Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($total_p_pages > 1): ?>
            <div class="pagination">
                <?php if($prod_page > 1): ?>
                    <a href="?page=<?php echo $prod_page-1; ?>&type=product" class="arrow-btn">&laquo;</a>
                <?php endif; ?>
                <?php 
                $start = max(1, $prod_page - 1); $end = min($total_p_pages, $prod_page + 1);
                for($i = $start; $i <= $end; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&type=product" class="<?php echo ($prod_page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if($prod_page < $total_p_pages): ?>
                    <a href="?page=<?php echo $prod_page+1; ?>&type=product" class="arrow-btn">&raquo;</a>
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
                        $total_c_res = mysqli_query($conn, "SELECT COUNT(*) FROM categories");
                        $total_c_rows = mysqli_fetch_array($total_c_res)[0];
                        $total_c_pages = ceil($total_c_rows / $limit);

                        $c_sql = "SELECT * FROM categories ORDER BY id DESC LIMIT $limit OFFSET $cat_offset";
                        $c_res = mysqli_query($conn, $c_sql);
                        
                        if (mysqli_num_rows($c_res) > 0) {
                            while($c_row = mysqli_fetch_assoc($c_res)){
                                echo "<tr>";
                                echo "<td>#".$c_row['id']."</td>";
                                echo "<td><strong>".htmlspecialchars($c_row['cat_name'])."</strong></td>";
                                echo "<td>
                                        <button class='action-btn edit-btn' onclick='openCatModal(\"".$c_row['id']."\", \"".htmlspecialchars($c_row['cat_name'])."\")'><i class='fas fa-edit'></i></button>
                                        <a href='manage_content.php?delete_id=".$c_row['id']."&type=category&page=$url_page' class='action-btn del-btn' onclick='return confirm(\"Delete?\")'><i class='fas fa-trash'></i></a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' style='text-align:center; padding: 40px;'>No Categories Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

             <?php if($total_c_pages > 1): ?>
            <div class="pagination">
                <?php if($cat_page > 1): ?>
                    <a href="?page=<?php echo $cat_page-1; ?>&type=category" class="arrow-btn">&laquo;</a>
                <?php endif; ?>
                <?php 
                $start = max(1, $cat_page - 1); $end = min($total_c_pages, $cat_page + 1);
                for($j = $start; $j <= $end; $j++): ?>
                    <a href="?page=<?php echo $j; ?>&type=category" class="<?php echo ($cat_page == $j) ? 'active' : ''; ?>"><?php echo $j; ?></a>
                <?php endfor; ?>
                <?php if($cat_page < $total_c_pages): ?>
                    <a href="?page=<?php echo $cat_page+1; ?>&type=category" class="arrow-btn">&raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <div id="catModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('catModal')">&times;</span>
            <h3>Edit Category</h3>
            <form method="POST">
                <input type="hidden" name="edit_cat_id" id="edit_cat_id">
                <label>Category Name</label>
                <input type="text" name="edit_cat_name" id="edit_cat_name" required>
                <button type="submit" name="update_category" class="save-btn">Update Category</button>
            </form>
        </div>
    </div>

    <div id="prodModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('prodModal')">&times;</span>
            <h3>Edit Product</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="edit_prod_id" id="edit_prod_id">
                
                <label>Category</label>
                <select name="edit_prod_cat" id="edit_prod_cat" required>
                    <?php
                    $cat_d_res = mysqli_query($conn, "SELECT * FROM categories");
                    while($cd = mysqli_fetch_assoc($cat_d_res)){
                        echo "<option value='".$cd['id']."'>".$cd['cat_name']."</option>";
                    }
                    ?>
                </select>

                <label>Product Name</label>
                <input type="text" name="edit_prod_name" id="edit_prod_name" required>

                <label>Price (PKR)</label>
                <input type="number" name="edit_prod_price" id="edit_prod_price" required>

                <label>Details</label>
                <textarea name="edit_prod_details" id="edit_prod_details" rows="3"></textarea>
                
                <label>Update Image (Optional)</label>
                <input type="file" name="edit_prod_image">
                <p style="font-size:12px; color:gray; margin-top:-5px;">Leave empty to keep current image.</p>

                <button type="submit" name="update_product" class="save-btn">Update Product</button>
            </form>
        </div>
    </div>

    <script>
        function showTable(type) {
            var prodTable = document.getElementById('products-table');
            var catTable = document.getElementById('categories-table');
            if (type === 'products') {
                prodTable.classList.add('active'); catTable.classList.remove('active');
            } else {
                catTable.classList.add('active'); prodTable.classList.remove('active');
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        function openCatModal(id, name) {
            document.getElementById('catModal').style.display = "flex";
            document.getElementById('edit_cat_id').value = id;
            document.getElementById('edit_cat_name').value = name;
        }

        function openProdModal(data) {
            document.getElementById('prodModal').style.display = "flex";
            document.getElementById('edit_prod_id').value = data.id;
            document.getElementById('edit_prod_name').value = data.name;
            document.getElementById('edit_prod_price').value = data.price;
            document.getElementById('edit_prod_details').value = data.details;
            document.getElementById('edit_prod_cat').value = data.category_id;
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }
    </script>
</body>
</html>