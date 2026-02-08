<?php
session_start();
if (!isset($_SESSION['admin_name'])) { header("Location: login.php"); exit(); }

include '../database/db.php';

if (isset($_POST['upload_btn'])) {
    // --- FIX: mysqli_real_escape_string lagaya hai ---
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price']; 
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $cat_id = $_POST['category_id'];
    
    $image_name = $_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];
    
    // Image name ko safe karna
    $safe_image_name = basename($image_name); 
    $target_folder = "../uploads/" . $safe_image_name;

    $sql = "INSERT INTO products (name, price, image, details, category_id) 
            VALUES ('$name', '$price', '$safe_image_name', '$details', '$cat_id')";

    if (move_uploaded_file($temp_name, $target_folder)) {
        if(mysqli_query($conn, $sql)){
            $success_msg = "Product Uploaded Successfully!";
        } else {
            echo "<script>alert('DB Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Image Upload Failed!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - CustomizeWorld</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        
        body { display: flex; min-height: 100vh; background-color: #f4f6f9; overflow-x: hidden; }

        /* --- Main Content Area --- */
        .main-content {
            flex: 1;
            margin-left: 260px; /* Sidebar space */
            padding: 30px;
            width: calc(100% - 260px);
            transition: 0.3s ease-in-out;
            display: flex;
            justify-content: center; /* Horizontally center */
            align-items: flex-start; /* Top se start ho */
        }

        /* --- Form Box Design --- */
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 600px; /* Thora chora form */
            border-top: 4px solid #007bff;
            margin-top: 20px;
        }

        .form-box h3 {
            margin-bottom: 25px;
            color: #333;
            font-size: 22px;
            text-align: center;
            font-weight: 600;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        /* Form Labels & Inputs */
        label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            transition: 0.3s;
            background: #f9f9f9;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
            background: #fff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        }

        /* File Input Styling */
        input[type="file"] {
            padding: 10px;
            background: #fff;
        }

        .save-btn {
            background: #28a745;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            font-weight: 600;
            transition: 0.3s;
            margin-top: 10px;
        }
        .save-btn:hover { background: #218838; transform: translateY(-2px); }

        /* --- RESPONSIVE MEDIA QUERIES --- */
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0; /* Sidebar hat gaya */
                width: 100%;
                padding: 20px;
                padding-top: 90px; /* Mobile Header ke liye jagah */
            }
            
            .form-box {
                padding: 20px;
                max-width: 100%; /* Mobile par full width */
            }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="form-box">
            <h3><i class="fas fa-box-open" style="color: #007bff;"></i> Add New Product</h3>
            
            <form method="POST" enctype="multipart/form-data">
                
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php
                    if(isset($conn)){
                        $cats = mysqli_query($conn, "SELECT * FROM categories");
                        while($c = mysqli_fetch_assoc($cats)){
                            echo "<option value='".$c['id']."'>".$c['cat_name']."</option>";
                        }
                    }
                    ?>
                </select>

                <label>Product Name</label>
                <input type="text" name="name" placeholder="Ex: Customized Wallet" required>

                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label>Price (PKR)</label>
                        <input type="number" name="price" placeholder="Ex: 1500" required>
                    </div>
                </div>

                <label>Description / Details</label>
                <textarea name="details" rows="4" placeholder="Enter product features, size, material etc..." required></textarea>

                <label>Product Image</label>
                <input type="file" name="image" required accept="image/*">

                <button type="submit" name="upload_btn" class="save-btn">
                    <i class="fas fa-cloud-upload-alt"></i> Upload Product
                </button>
            </form>
        </div>
    </div>

</body>
</html>