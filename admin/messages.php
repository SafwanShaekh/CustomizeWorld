<?php
session_start();
if (!isset($_SESSION['admin_name'])) { header("Location: login.php"); exit(); }
include '../database/db.php';

// --- CONFIGURATION ---
$limit = 10; // Messages per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM messages WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        $warning_msg = "Message Deleted Successfully!";
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
            overflow-x: auto; /* Enable horizontal scrolling */
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            background: white;
        }

        table { width: 100%; border-collapse: collapse; min-width: 700px; /* Table minimum width */ }
        
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: top; }
        th { background: #343a40; color: white; text-transform: uppercase; font-size: 14px; white-space: nowrap; }
        tr:hover { background-color: #f8f9fa; }
        
        .action-btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; color: white; border: none; cursor: pointer; display: inline-block; }
        .del-btn { background: #dc3545; transition: 0.3s; }
        .del-btn:hover { background: #c82333; }
        
        .msg-content { color: #555; font-size: 14px; line-height: 1.5; word-wrap: break-word; }
        .sender-info { font-weight: bold; color: #333; display: block; margin-bottom: 2px; }
        .sender-email { font-size: 12px; color: #888; }
        .msg-date { font-size: 11px; color: #999; display: block; margin-top: 5px; }

        /* --- Pagination Styles --- */
        .pagination { display: flex; justify-content: center; margin-top: 25px; gap: 8px; flex-wrap: wrap; }
        .pagination a { padding: 8px 16px; border: 1px solid #ddd; color: #6c757d; text-decoration: none; border-radius: 6px; transition: 0.3s; background: white; font-weight: 500; font-size: 14px; }
        .pagination a.active { background-color: #007bff; color: white; border-color: #007bff; box-shadow: 0 4px 6px rgba(0,123,255,0.2); }
        .pagination a:hover:not(.active) { background-color: #e9ecef; color: #333; }
        .pagination .arrow-btn { font-weight: bold; background: #f8f9fa; }

        /* --- RESPONSIVE MEDIA QUERIES --- */
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0; /* Sidebar hat gaya */
                width: 100%;
                padding: 20px;
                padding-top: 90px; /* Mobile Header ke liye jagah */
            }
            
            /* Table ke liye padding kam karo */
            th, td { padding: 12px; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        
        <h2 style="text-align:center; margin-bottom: 30px; color: #333;">
            <i class="fas fa-envelope-open-text" style="color:#007bff;"></i> Customer Messages
        </h2>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">Sender</th>
                        <th width="20%">Subject</th>
                        <th width="45%">Message</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Count Total Messages
                    $count_sql = "SELECT COUNT(*) FROM messages";
                    $count_res = mysqli_query($conn, $count_sql);
                    $total_rows = mysqli_fetch_array($count_res)[0];
                    $total_pages = ceil($total_rows / $limit);

                    // Fetch Messages
                    $sql = "SELECT * FROM messages ORDER BY id DESC LIMIT $limit OFFSET $offset";
                    $res = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)){
                            // Date Formatting (e.g., 12 Feb 2026)
                            $date = date('d M Y, h:i A', strtotime($row['created_at']));
                            ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td>
                                    <span class="sender-info"><?php echo htmlspecialchars($row['name']); ?></span>
                                    <span class="sender-email"><?php echo htmlspecialchars($row['email']); ?></span>
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
                                       <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr>
                                <td colspan='5' style='text-align:center; padding: 50px; color: #adb5bd;'>
                                    <i class='fas fa-inbox' style='font-size: 50px; margin-bottom: 15px; opacity: 0.5;'></i>
                                    <p style='font-size: 18px; margin: 0;'>No Messages Yet</p>
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
                <a href="?page=<?php echo $page-1; ?>" class="arrow-btn">&laquo;</a>
            <?php endif; ?>

            <?php 
            $start = max(1, $page - 1);
            $end = min($total_pages, $page + 1);
            for($i = $start; $i <= $end; $i++): 
            ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if($page < $total_pages): ?>
                <a href="?page=<?php echo $page+1; ?>" class="arrow-btn">&raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>

</body>
</html>