<?php
session_start();
// --- SECURITY: Prevent Browser Back Button Issue ---
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['admin_name'])) { header("Location: login.php"); exit(); }
include '../database/db.php'; // Path check karlena (admin folder structure ke hisaab se)

// --- CONFIGURATION ---
$limit = 10; // Messages per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $p_no = isset($_GET['page']) ? $_GET['page'] : 1;
    
    $sql = "DELETE FROM messages WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        // Delete ke baad redirect karo taake refresh par dobara query na chale
        header("Location: messages.php?page=$p_no&msg=deleted");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Messages - CustomizeWorld</title>
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
        }

        /* --- Table Styles --- */
        .table-responsive {
            width: 100%;
            overflow-x: auto; /* Enable horizontal scrolling on mobile */
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            background: white;
        }

        table { width: 100%; border-collapse: collapse; min-width: 800px; /* Min width to force scroll on small screens */ }
        
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: top; }
        th { background: #343a40; color: white; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; white-space: nowrap; }
        tr:hover { background-color: #f8f9fa; }
        
        /* Buttons */
        .action-btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px; color: white; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; }
        .del-btn { background: #dc3545; transition: 0.3s; }
        .del-btn:hover { background: #c82333; }
        
        /* Content Styling */
        .msg-content { color: #555; font-size: 14px; line-height: 1.6; max-width: 400px; }
        .sender-info { font-weight: 700; color: #333; display: block; margin-bottom: 2px; }
        .sender-email { font-size: 13px; color: #007bff; text-decoration: none; }
        .msg-date { font-size: 11px; color: #999; display: block; margin-top: 5px; font-style: italic; }

        /* --- Pagination Styles --- */
        .pagination { display: flex; justify-content: center; margin-top: 30px; gap: 5px; flex-wrap: wrap; }
        .page-link { 
            padding: 8px 14px; 
            border: 1px solid #dee2e6; 
            color: #007bff; 
            text-decoration: none; 
            border-radius: 4px; 
            background: white; 
            font-weight: 500; 
            font-size: 14px;
            transition: 0.2s;
        }
        .page-link:hover { background-color: #e9ecef; color: #0056b3; }
        .page-link.active { background-color: #007bff; color: white; border-color: #007bff; box-shadow: 0 2px 5px rgba(0,123,255,0.3); }
        .page-link.disabled { color: #ccc; pointer-events: none; background: #fff; border-color: #eee; }

        /* Success Alert */
        .alert-box {
            background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px;
            margin-bottom: 20px; border: 1px solid #c3e6cb; text-align: center;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn { from { opacity:0; transform: translateY(-10px); } to { opacity:1; transform: translateY(0); } }

        /* --- RESPONSIVE MEDIA QUERIES --- */
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0; 
                width: 100%;
                padding: 20px;
                padding-top: 90px; /* Header Space */
            }
            th, td { padding: 10px; font-size: 13px; }
            .msg-content { min-width: 200px; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        
        <h2 style="text-align:center; margin-bottom: 30px; color: #333; font-weight: 700;">
            <i class="fas fa-envelope-open-text" style="color:#007bff;"></i> Customer Messages
        </h2>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert-box">
                <i class="fas fa-check-circle"></i> Message deleted successfully!
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="25%">Sender Details</th>
                        <th width="20%">Subject</th>
                        <th width="40%">Message</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 1. Count Total Messages for Pagination
                    $count_sql = "SELECT COUNT(*) FROM messages";
                    $count_res = mysqli_query($conn, $count_sql);
                    $total_rows = mysqli_fetch_array($count_res)[0];
                    $total_pages = ceil($total_rows / $limit);

                    // 2. Fetch Limited Messages
                    $sql = "SELECT * FROM messages ORDER BY id DESC LIMIT $limit OFFSET $offset";
                    $res = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)){
                            $date = date('d M Y, h:i A', strtotime($row['created_at'])); // Ensure 'created_at' column exists
                            ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td>
                                    <span class="sender-info"><?php echo htmlspecialchars($row['name']); ?></span>
                                    <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="sender-email">
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    </a>
                                    <span class="msg-date"><i class="far fa-clock"></i> <?php echo $date; ?></span>
                                </td>
                                <td><strong><?php echo htmlspecialchars($row['subject']); ?></strong></td>
                                <td>
                                    <div class="msg-content">
                                        <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="messages.php?delete_id=<?php echo $row['id']; ?>&page=<?php echo $page; ?>" 
                                       class="action-btn del-btn" 
                                       onclick="return confirm('Are you sure you want to delete this message?')">
                                       <i class="fas fa-trash-alt"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr>
                                <td colspan='5' style='text-align:center; padding: 60px 20px; color: #adb5bd;'>
                                    <i class='fas fa-inbox' style='font-size: 50px; margin-bottom: 15px; opacity: 0.5;'></i>
                                    <p style='font-size: 18px; margin: 0;'>No Messages Found</p>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php if($total_pages > 1): ?>
        <div class="pagination">
            
            <?php if($page > 1): ?>
                <a href="?page=<?php echo $page-1; ?>" class="page-link">&laquo; Prev</a>
            <?php else: ?>
                <span class="page-link disabled">&laquo; Prev</span>
            <?php endif; ?>

            <?php 
            $start_loop = max(1, $page - 2);
            $end_loop = min($total_pages, $page + 2);

            for($i = $start_loop; $i <= $end_loop; $i++): 
            ?>
                <a href="?page=<?php echo $i; ?>" class="page-link <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if($page < $total_pages): ?>
                <a href="?page=<?php echo $page+1; ?>" class="page-link">Next &raquo;</a>
            <?php else: ?>
                <span class="page-link disabled">Next &raquo;</span>
            <?php endif; ?>

        </div>
        <?php endif; ?>

    </div>

</body>
</html>