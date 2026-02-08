<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    body { display: flex; min-height: 100vh; background-color: #f4f6f9; overflow-x: hidden; }
    
    /* --- SIDEBAR DESIGN --- */
    .sidebar { 
        width: 260px; 
        background-color: #343a40; 
        color: white; 
        display: flex; 
        flex-direction: column; 
        padding-top: 20px; 
        position: fixed; 
        top: 0; 
        left: 0; /* Default Desktop Position */
        height: 100%; 
        z-index: 1001; /* Sabse upar */
        transition: 0.3s ease-in-out; 
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .sidebar h2 { 
        text-align: center; margin-bottom: 30px; font-size: 22px; 
        color: #ffc107; border-bottom: 1px solid #4b545c; padding-bottom: 20px; 
    }
    
    .sidebar a { 
        text-decoration: none; color: #c2c7d0; padding: 15px 20px; 
        display: flex; align-items: center; transition: 0.3s; font-size: 16px; 
        border-left: 3px solid transparent;
    }
    
    .sidebar a i { margin-right: 15px; width: 25px; text-align: center; }
    
    .sidebar a:hover, .sidebar a.active { 
        background-color: #495057; color: white; border-left: 3px solid #007bff; 
    }
    
    .logout { margin-top: auto; background: #dc3545; }
    .logout:hover { background: #c82333 !important; border-left: 3px solid transparent !important; }

    /* --- MAIN CONTENT --- */
    .main-content { 
        flex: 1; margin-left: 260px; padding: 30px; 
        width: calc(100% - 260px); transition: 0.3s; 
    }

    /* --- FORM STYLES --- */
    .form-box { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 600px; margin: auto; }
    .form-box h3 { margin-bottom: 20px; color: #333; border-bottom: 2px solid #007bff; display: inline-block; padding-bottom: 5px;}
    input, select, textarea { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
    button.save-btn { background: #28a745; color: white; padding: 12px; border: none; cursor: pointer; width: 100%; font-size: 16px; border-radius: 4px; transition: 0.3s;}
    button.save-btn:hover { background: #218838; }

    /* --- MOBILE ELEMENTS --- */
    .mobile-toggle {
        display: none; position: fixed; top: 15px; left: 15px; z-index: 1000;
        background: #343a40; color: white; border: none; padding: 10px 15px;
        border-radius: 5px; cursor: pointer; font-size: 20px; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    /* Close Button inside Sidebar */
    .close-sidebar-btn {
        display: none; position: absolute; top: 10px; right: 15px;
        background: transparent; border: none; color: #adb5bd; font-size: 24px; cursor: pointer;
    }
    .close-sidebar-btn:hover { color: #fff; }

    /* Overlay (Black Background) */
    .sidebar-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); z-index: 999; backdrop-filter: blur(2px);
    }

    /* --- RESPONSIVE MEDIA QUERIES --- */
    @media (max-width: 991px) {
        .sidebar { left: -260px; } /* Hide Sidebar */
        .sidebar.active { left: 0; } /* Show Sidebar */
        
        .main-content { margin-left: 0; width: 100%; padding-top: 70px; }
        
        .mobile-toggle { display: block; }
        .close-sidebar-btn { display: block; }
        
        /* Overlay Logic */
        .sidebar-overlay.active { display: block; }
    }

    /* --- TOAST CSS --- */
    #toast-box {
        visibility: hidden; min-width: 300px; background-color: #d1e7dd; color: #0f5132; 
        border-left: 6px solid #198754; border-radius: 8px; padding: 15px 20px;
        position: fixed; z-index: 9999; right: 20px; top: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 15px; font-weight: 500;
        transform: translateX(120%); transition: transform 0.5s, visibility 0.5s;
    }
    #toast-box.error { background-color: #f8d7da; color: #721c24; border-left-color: #dc3545; }
    #toast-box.show { visibility: visible; transform: translateX(0); }
    .tick-circle { width: 30px; height: 30px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); flex-shrink: 0; }
    .tick-circle i { color: #198754; }
    #toast-box.error .tick-circle i { color: #dc3545; }
</style>

<div class="sidebar-overlay" onclick="closeSidebar()"></div>

<button class="mobile-toggle" onclick="openSidebar()">
    <i class="fas fa-bars"></i>
</button>

<div id="toast-box">
    <div class="tick-circle"><i id="toast-icon" class="fas fa-check"></i></div>
    <span id="toast-msg">Message</span>
</div>

<script>
    function openSidebar() {
        document.querySelector('.sidebar').classList.add('active');
        document.querySelector('.sidebar-overlay').classList.add('active');
    }

    function closeSidebar() {
        document.querySelector('.sidebar').classList.remove('active');
        document.querySelector('.sidebar-overlay').classList.remove('active');
    }

    function showToast(msg, type) {
        var toast = document.getElementById("toast-box");
        var msgSpan = document.getElementById("toast-msg");
        var icon = document.getElementById("toast-icon");
        
        msgSpan.innerText = msg;
        toast.className = ""; icon.className = "fas"; 
        
        if(type === 'error'){
            toast.classList.add("error"); icon.classList.add("fa-times");
        } else {
            icon.classList.add("fa-check");
        }
        setTimeout(function() { toast.classList.add("show"); }, 100);
        setTimeout(function() { toast.classList.remove("show"); }, 3000);
    }
</script>

<?php $page = basename($_SERVER['PHP_SELF']); ?>

<div class="sidebar">
    <button class="close-sidebar-btn" onclick="closeSidebar()">
        <i class="fas fa-times"></i>
    </button>

    <h2>CustomizeWorld</h2>
    
    <a href="index.php" class="<?php echo ($page == 'index.php') ? 'active' : ''; ?>">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    
    <a href="add_category.php" class="<?php echo ($page == 'add_category.php') ? 'active' : ''; ?>">
        <i class="fas fa-folder-plus"></i> Add Category
    </a>
    
    <a href="add_product.php" class="<?php echo ($page == 'add_product.php') ? 'active' : ''; ?>">
        <i class="fas fa-gift"></i> Add Product
    </a>

    <a href="manage_content.php" class="<?php echo ($page == 'manage_content.php') ? 'active' : ''; ?>">
        <i class="fas fa-tasks"></i> Manage Content
    </a>

    <a href="messages.php" class="<?php echo ($page == 'messages.php') ? 'active' : ''; ?>">
        <i class="fas fa-envelope"></i> Messages
    </a>
    
    <a href="../index.php" target="_blank">
        <i class="fas fa-globe"></i> View Website
    </a>
    
    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<?php
if(isset($success_msg)){ echo "<script> showToast('$success_msg', 'success'); </script>"; }
if(isset($warning_msg)){ echo "<script> showToast('$warning_msg', 'error'); </script>"; }
?>