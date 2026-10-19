<?php
require 'db.php';
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = (new Database())->connect();
    $check = $conn->prepare("SELECT UserID FROM users WHERE Email = ?");
    $check->execute([$_POST['email']]);
    if ($check->fetch()) {
        $msg = "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (FullName, Email, PasswordHash, Role) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['fullname'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['role']
        ]);
        $msg = "Registered. <a href='login.php'>Login</a>";
    }
}
?>
<link rel="stylesheet" href="style.css">
<h2>Register</h2>
<?= $msg ?>
<form method="POST">
  <input name="fullname" placeholder="Full name" required>
  <input name="email" type="email" placeholder="Email" required>
  <input name="password" type="password" placeholder="Password" required>
  <select name="role">
    <option value="student">Student</option>
    <option value="faculty">Faculty</option>
  </select>
  <button>Register</button>
</form>
<a href="login.php">Back to login</a>