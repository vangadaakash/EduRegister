
<?php
require 'config.php';
// session_start(); // Remove or comment this line if session_start() is already in config.php
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Get total students
$total = $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'] ?? 0;

// Get recent students (last 5)
$stmt = $conn->prepare("SELECT id, name, email, course, reg_date FROM students ORDER BY reg_date DESC LIMIT 5");
$stmt->execute();
$recent = $stmt->get_result();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <div class="card">
    <div class="header">
      <h1>Admin Dashboard</h1>
      <div class="nav">
        <a href="view.php" class="primary">📄 View Students</a>
        <a href="index.php" class="success">➕ Add Student</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>

    <?php if($m = flash('success')): ?>
      <div class="flash success"><?= e($m) ?></div>
    <?php endif; ?>
    <?php if($m = flash('error')): ?>
      <div class="flash error"><?= e($m) ?></div>
    <?php endif; ?>

    <h2>Welcome, <?= e($_SESSION['username'] ?? 'Admin') ?>!</h2>
    <div style="margin: 18px 0;">
      <strong>Total Students:</strong> <?= e($total) ?>
    </div>

    <h2>Recent Registrations</h2>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Registered</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($recent->num_rows === 0): ?>
          <tr><td colspan="5">No recent students.</td></tr>
        <?php else: while($row = $recent->fetch_assoc()): ?>
          <tr>
            <td><?= e($row['id']) ?></td>
            <td><?= e($row['name']) ?></td>
            <td><?= e($row['email']) ?></td>
            <td><?= e($row['course']) ?></td>
            <td><?= e($row['reg_date']) ?></td>
          </tr>
        <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>