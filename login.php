<?php
session_start();
require 'db.php';
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = (new Database())->connect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($_POST['password'], $user['PasswordHash'])) {
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['username'] = $user['FullName'];
        $_SESSION['role'] = $user['Role'];
        header("Location: index.php");
        exit;
    }
    $error = "Invalid email or password.";
}
?>
<link rel="stylesheet" href="style.css">
<h2>Login</h2>
<p style="color:red"><?= $error ?></p>
<form method="POST">
  <input name="email" type="email" placeholder="Email" required>
  <input name="password" type="password" placeholder="Password" required>
  <button>Login</button>
</form>
<a href="register.php">Create account</a>