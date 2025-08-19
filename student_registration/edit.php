<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>


<?php
require 'config.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { flash('error','Invalid student id'); header('Location: view.php'); exit; }

$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
if (!$student) { flash('error','Student not found'); header('Location: view.php'); exit; }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Student</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <div class="card">
    <div class="header">
      <h1>Edit Student #<?= e($student['id']) ?></h1>
      <div class="nav">
        <a href="view.php">⬅️ Back</a>
      </div>
    </div>

    <?php if($m = flash('error')): ?>
      <div class="flash error"><?= e($m) ?></div>
    <?php endif; ?>

    <form action="update.php" method="post">
      <input type="hidden" name="id" value="<?= e($student['id']) ?>">

      <div class="grid">
        <div>
          <label>Full Name *</label>
          <input type="text" name="name" required value="<?= e($student['name']) ?>">
        </div>
        <div>
          <label>Email *</label>
          <input type="email" name="email" required value="<?= e($student['email']) ?>">
        </div>

        <div>
          <label>Phone</label>
          <input type="text" name="phone" value="<?= e($student['phone']) ?>">
        </div>
        <div>
          <label>Date of Birth</label>
          <input type="date" name="dob" value="<?= e($student['dob']) ?>">
        </div>

        <div>
          <label>Gender</label>
          <select name="gender">
            <option value="">Select…</option>
            <?php foreach(['Male','Female','Other'] as $g): ?>
              <option <?= $student['gender']===$g?'selected':'' ?>><?= e($g) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Course *</label>
          <input type="text" name="course" required value="<?= e($student['course']) ?>">
        </div>

        <div>
          <label>Department</label>
          <input type="text" name="department" value="<?= e($student['department']) ?>">
        </div>
        <div>
          <label>Roll Number</label>
          <input type="text" name="roll_number" value="<?= e($student['roll_number']) ?>">
        </div>

        <div>
          <label>Admission Year</label>
          <input type="number" name="admission_year" min="1990" max="2099" step="1" value="<?= e($student['admission_year']) ?>">
        </div>
        <div>
          <label>CGPA</label>
          <input type="number" name="cgpa" step="0.01" min="0" max="10" value="<?= e($student['cgpa']) ?>">
        </div>

        <div style="grid-column: 1 / -1;">
          <label>Address</label>
          <textarea name="address"><?= e($student['address']) ?></textarea>
        </div>
      </div>

      <div class="actions">
        <button class="btn btn-primary" type="submit">💾 Update</button>
        <a class="btn btn-secondary" href="view.php">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
