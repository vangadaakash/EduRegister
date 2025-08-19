
<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare("SELECT id, password_hash FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hash);

    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['admin_id'] = $id;
        $_SESSION['username'] = $username;

        flash('success', '✅ Login successful! Welcome back.');
        header("Location: view.php");
        exit;
    } else {
        flash('error', '❌ Invalid username or password!');
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-container">
    <h2>Admin Login</h2>
     <!-- Show logout success message -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="flash success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <!-- Show flash messages -->
    <?php if ($msg = flash('success')): ?>
        <div class="flash success"><?= e($msg) ?></div>
    <?php endif; ?>

    <?php if ($msg = flash('error')): ?>
        <div class="flash error"><?= e($msg) ?></div>
    <?php endif; ?>

<form method="post">
  <label>Username:</label>
  <input type="text" name="username" required>
  
  <label>Password:</label>
  <input type="password" name="password" required>
  
  <button type="submit">Login</button>
</form>

<div class="auth-links" style="margin-top: 16px; text-align: center;">
  <p>
    <a href="forgot_password.php">Forgot Password?</a>
  </p>
  <p>
    Don't have an account? <a href="register.php">Sign up</a>.<br>
    Already have an account? Please <strong>login</strong>.
  </p>
</div>
  </div>
</body>
</html>