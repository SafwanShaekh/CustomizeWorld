<?php
session_start();
include '../database/db.php'; // Path check karlena

$error_msg = "";

if (isset($_POST['login_btn'])) {
    
    // 1. Inputs Sanitize karo (SQL Injection Prevention)
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Password ko escape karne ki zaroorat nahi hoti verification me

    // 2. Query: Sirf Username se data mangwao (Password match mat karo SQL me)
    $sql = "SELECT * FROM admins WHERE username = ?";
    
    // Prepared Statement (Best Security)
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // 3. Password Verify (Hash check)
        // password_verify(user_input, database_hash)
        if (password_verify($password, $row['password'])) {
            
            // Login Success
            $_SESSION['admin_name'] = $row['username'];
            $_SESSION['admin_id'] = $row['id'];
            
            header("Location: index.php"); // Dashboard par bhejo
            exit();
            
        } else {
            $error_msg = "Invalid Password!";
        }
    } else {
        $error_msg = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Secure</title>
    <style>
        body { display: flex; height: 100vh; justify-content: center; align-items: center; background: #f4f6f9; font-family: sans-serif; }
        .login-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #0056b3; }
        .error { color: red; margin-bottom: 10px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Admin Login</h2>
        
        <?php if($error_msg): ?>
            <div class="error"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login_btn">Login Securely</button>
        </form>
    </div>

</body>
</html>