<?php
session_start();
// --- SECURITY: Prevent Browser Back Button Issue ---
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['admin_name'])) { header("Location: login.php"); exit(); }
include '../database/db.php';

if (isset($_POST['add_cat'])) {
    $c_name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    
    if(!empty($c_name)){
        // 1. Check duplicate
        $check_sql = "SELECT * FROM categories WHERE cat_name = '$c_name'";
        $check_res = mysqli_query($conn, $check_sql);
        
        if(mysqli_num_rows($check_res) > 0){
            $warning_msg = "This Category Already Exists!";
        } else {
            // 2. Insert
            $sql = "INSERT INTO categories (cat_name) VALUES ('$c_name')";
            if (mysqli_query($conn, $sql)) {
                $success_msg = "Category Added Successfully!";
            } else {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - CustomizeWorld</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        
        body { display: flex; min-height: 100vh; background-color: #f4f6f9; overflow-x: hidden; }

        /* --- Main Content Area --- */
        .main-content {
            flex: 1;
            margin-left: 260px; /* Desktop spacing for sidebar */
            padding: 30px;
            width: calc(100% - 260px);
            transition: 0.3s ease-in-out;
            display: flex;
            align-items: center; /* Vertically center form */
            justify-content: center;
            flex-direction: column;
        }

        /* --- Form Box Design --- */
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 500px; /* Form ki maximum chowdai */
            border-top: 4px solid #007bff;
        }

        .form-box h3 {
            margin-bottom: 25px;
            color: #333;
            font-size: 22px;
            text-align: center;
            font-weight: 600;
        }

        .form-box input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            transition: 0.3s;
        }
        .form-box input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
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
            }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        
        <div class="form-box">
            <h3><i class="fas fa-folder-plus" style="color: #007bff;"></i> Add New Category</h3>
            
            <form method="POST">
                <label style="font-weight: 600; color: #555; margin-bottom: 8px; display: block;">Category Name</label>
                <input type="text" name="cat_name" placeholder="Ex: Valentine's Day Gifts" required>
                
                <button type="submit" name="add_cat" class="save-btn">
                    <i class="fas fa-save"></i> Save Category
                </button>
            </form>

            </div>

    </div>

</body>
</html>