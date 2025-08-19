
<?php
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Username already exists. Please choose another.";
    } else {
        $stmt->close();

        // Hash password and insert new admin
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $passwordHash);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $message = "Error: " . $conn->error;
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Register Admin</h2>
        <?php if($message) echo "<div class='flash error'>$message</div>"; ?>
        
<form method="POST">
    <label for="username">New Username</label>
    <input type="text" id="username" name="username" placeholder="New Username" required>
    <label for="password">New Password</label>
    <input type="password" id="password" name="password" placeholder="New Password" required autocomplete="off">
    <button type="submit">Register</button>
</form>
        <div class="register-links">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>