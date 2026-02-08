<?php
session_start();
include '../database/db.php';

if (isset($_POST['login_btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database check
    $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Login Success -> Session Set karo
        $_SESSION['admin_name'] = $username;
        header("Location: index.php"); // Dashboard par bhejo
    } else {
        echo "<script>alert('Galat Username ya Password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - CustomizeWorld</title>
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background: #333; font-family: sans-serif; }
        .login-box { background: white; padding: 30px; border-radius: 8px; width: 300px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        h2 { margin-top: 0; color: #333; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login_btn">Login</button>
    </form>
</div>

</body>
</html>