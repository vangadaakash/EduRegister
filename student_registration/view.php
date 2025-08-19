<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require 'config.php';

// --- Search & Sorting ---
$q = trim($_GET['q'] ?? '');
$orderby = $_GET['sort'] ?? 'id';
$allowedSort = ['id','name','email','course','department','admission_year','cgpa','reg_date'];
if (!in_array($orderby, $allowedSort)) $orderby = 'id';

// --- Pagination ---
$limit = 10; // students per page
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// --- SQL ---
$where = "";
$params = [];
if ($q !== '') {
  $where = " WHERE name LIKE ? OR email LIKE ? OR roll_number LIKE ? OR course LIKE ? OR department LIKE ?";
  $like = "%$q%";
  $params = [$like,$like,$like,$like,$like];
}

$sql_count = "SELECT COUNT(*) AS total FROM students $where";
$stmt_count = $conn->prepare($sql_count);
if ($q !== '') $stmt_count->bind_param("sssss", ...$params);
$stmt_count->execute();
$total = $stmt_count->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Fetch data
$sql = "SELECT * FROM students $where ORDER BY $orderby DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if ($q !== '') {
  $stmt->bind_param("sssssii", $params[0],$params[1],$params[2],$params[3],$params[4], $limit, $offset);
} else {
  $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Students List</title>
  <link rel="stylesheet" href="style.css">
  <script>
    // Dark Mode Toggle
    function toggleDarkMode() {
      document.body.classList.toggle("dark");
      localStorage.setItem("darkmode", document.body.classList.contains("dark"));
    }
    window.onload = function() {
      if (localStorage.getItem("darkmode") === "true") {
        document.body.classList.add("dark");
      }
    }
  </script>
</head>
<body>
<div class="container">
<div class="admin-info">
  <span class="admin-icon">👤</span>
  <span>
    Logged in as <strong><?= e($_SESSION['username'] ?? 'Admin') ?></strong>
  </span>
  <a href="logout.php" title="Logout" class="logout-link" style="margin-left: auto; font-size: 1.3em; color: var(--danger); text-decoration: none;">
    🚪 Logout
  </a>
</div>
    <div class="header">
      <h1>Students</h1>
      <div class="nav">
        <a href="index.php" class="success">➕ Add Student</a>
        <button onclick="toggleDarkMode()" class="btn btn-dark">🌙 Dark Mode</button>
      </div>
    </div>

    <?php if($m = flash('success')): ?>
      <div class="flash success"><?= e($m) ?></div>
    <?php endif; ?>
    <?php if($m = flash('error')): ?>
      <div class="flash error"><?= e($m) ?></div>
    <?php endif; ?>

    <form class="searchbar" method="get">
      <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search name, email, roll, course, department">
      <select name="sort">
        <?php foreach($allowedSort as $c): ?>
          <option value="<?= e($c) ?>" <?= $orderby===$c?'selected':'' ?>>Sort by <?= e(ucwords(str_replace('_',' ',$c))) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary" type="submit">🔎 Search</button>
      <a class="btn btn-secondary" href="view.php">Reset</a>
    </form>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th>
            <th>DOB</th><th>Gender</th><th>Course</th><th>Department</th>
            <th>Roll</th><th>Year</th><th>CGPA</th><th>Registered</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($res->num_rows === 0): ?>
          <tr><td colspan="13">No records found.</td></tr>
        <?php else: while($row = $res->fetch_assoc()): ?>
          <tr>
            <td><?= e($row['id']) ?></td>
            <td><?= e($row['name']) ?></td>
            <td><?= e($row['email']) ?></td>
            <td><?= e($row['phone']) ?></td>
            <td><?= e($row['dob']) ?></td>
            <td><span class="badge"><?= e($row['gender']) ?></span></td>
            <td><?= e($row['course']) ?></td>
            <td><?= e($row['department']) ?></td>
            <td><?= e($row['roll_number']) ?></td>
            <td><?= e($row['admission_year']) ?></td>
            <td><?= e($row['cgpa']) ?></td>
            <td><?= e($row['reg_date']) ?></td>
            <td>
              <a class="btn btn-warning" href="edit.php?id=<?= e($row['id']) ?>">✏️ Edit</a>
              <a class="btn btn-danger" href="delete.php?id=<?= e($row['id']) ?>" onclick="return confirm('Delete this student?');">🗑️ Delete</a>
            </td>
          </tr>
        <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="?q=<?= e($q) ?>&sort=<?= e($orderby) ?>&page=<?= $page-1 ?>">&laquo; Prev</a>
      <?php endif; ?>
      Page <?= $page ?> of <?= $totalPages ?>
      <?php if ($page < $totalPages): ?>
        <a href="?q=<?= e($q) ?>&sort=<?= e($orderby) ?>&page=<?= $page+1 ?>">Next &raquo;</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<script>
function toggleDarkMode() {
  document.body.classList.toggle("dark");
  localStorage.setItem("darkmode", document.body.classList.contains("dark") ? "true" : "false");
}
window.addEventListener("DOMContentLoaded", () => {
  if(localStorage.getItem("darkmode") === "true") document.body.classList.add("dark");
});
</script>
</body>
</html>
