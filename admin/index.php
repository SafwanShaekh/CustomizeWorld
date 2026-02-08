<?php
session_start();
include '../database/db.php'; // DB connection

// Security Check
if (!isset($_SESSION['admin_name'])) {
    header("Location: login.php");
    exit();
}

// --- Stats Logic ---
// 1. Total Products
$prod_sql = "SELECT COUNT(*) as total FROM products";
$prod_res = mysqli_query($conn, $prod_sql);
$prod_row = mysqli_fetch_assoc($prod_res);
$total_products = $prod_row['total'];

// 2. Total Categories
$cat_sql = "SELECT COUNT(*) as total FROM categories";
$cat_res = mysqli_query($conn, $cat_sql);
$cat_row = mysqli_fetch_assoc($cat_res);
$total_categories = $cat_row['total'];

// 3. Total Messages (New Add kiya hai taake dashboard bhara hua lage)
$msg_sql = "SELECT COUNT(*) as total FROM messages";
$msg_res = mysqli_query($conn, $msg_sql);
$msg_row = mysqli_fetch_assoc($msg_res);
$total_msgs = $msg_row['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CustomizeWorld</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        
        body { display: flex; min-height: 100vh; background-color: #f4f6f9; overflow-x: hidden; }

        /* --- Main Content Area --- */
        .main-content {
            flex: 1;
            margin-left: 260px; /* Sidebar ki jagah */
            padding: 30px;
            width: calc(100% - 260px);
            transition: 0.3s ease-in-out;
        }

        .header {
            background: white;
            padding: 20px 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h3 { font-size: 20px; color: #333; margin: 0; }

        /* --- Stats Cards Grid --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); /* Responsive Grid */
            gap: 25px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 5px solid #007bff;
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }

        .stat-card.green { border-left-color: #28a745; }
        .stat-card.purple { border-left-color: #6f42c1; }
        .stat-card.orange { border-left-color: #fd7e14; }

        .stat-info h3 { font-size: 32px; margin: 0; color: #333; font-weight: 700; }
        .stat-info p { color: #666; font-size: 14px; margin-top: 5px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }

        .stat-icon { 
            font-size: 35px; color: #e9ecef; 
            background: #f8f9fa; padding: 15px; border-radius: 50%;
        }
        .stat-card:hover .stat-icon { color: #007bff; background: #e7f1ff; }
        .stat-card.green:hover .stat-icon { color: #28a745; background: #e6f8eb; }
        .stat-card.purple:hover .stat-icon { color: #6f42c1; background: #f2eefc; }
        .stat-card.orange:hover .stat-icon { color: #fd7e14; background: #fff0e6; }

        /* --- RESPONSIVE MEDIA QUERIES --- */
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0; /* Sidebar hat gaya */
                width: 100%;
                padding: 20px;
                padding-top: 80px; /* Top padding taake mobile header ke neeche na aaye */
            }
            
            .header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr; /* Mobile par ek ke baad ek card */
            }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        
        <div class="header">
            <h3>Welcome Back, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>! 👋</h3>
            <span style="color: #666; font-size: 14px; font-weight: 500;">
                <i class="far fa-calendar-alt"></i> <?php echo date('d M Y'); ?>
            </span>
        </div>

        <div class="stats-grid">
            
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?php echo $total_products; ?></h3>
                    <p>Total Products</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>

            <div class="stat-card green">
                <div class="stat-info">
                    <h3><?php echo $total_categories; ?></h3>
                    <p>Total Categories</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
            </div>

            <div class="stat-card orange">
                <div class="stat-info">
                    <h3><?php echo $total_msgs; ?></h3>
                    <p>New Messages</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>

            <div class="stat-card purple">
                <div class="stat-info">
                    <h3 style="font-size: 24px;">Active</h3>
                    <p>System Status</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-server"></i>
                </div>
            </div>

        </div>

    </div>

</body>
</html>