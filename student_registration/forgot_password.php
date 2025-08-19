<?php
require 'config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    // You can add email verification or security questions here
    // For demo, just show a message
    $message = "If this username exists, a password reset link will be sent (demo only).";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-container">
    <h2>Forgot Password</h2>
    <?php if($message): ?>
      <div class="flash success"><?= $message ?></div>
    <?php endif; ?>
    <form method="post">
      <label>Enter your username:</label>
      <input type="text" name="username" required>
      <button type="submit">Send Reset Link</button>
    </form>
    <div class="auth-links">
      <a href="login.php">Back to Login</a>
    </div>
  </div>
</body>
</html>