<?php require 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Student Registration</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <div class="card">
    <div class="header">
      <h1>Student Registration</h1>
      <div class="nav">
        <a href="view.php" class="primary">📄 View Students</a>
      </div>
    </div>

    <?php if($m = flash('success')): ?>
      <div class="flash success"><?= e($m) ?></div>
    <?php endif; ?>
    <?php if($m = flash('error')): ?>
      <div class="flash error"><?= e($m) ?></div>
    <?php endif; ?>

    <form action="insert.php" method="post">
      <div class="grid">
        <div>
          <label>Full Name *</label>
          <input type="text" name="name" required placeholder="e.g., Rahul Sharma">
        </div>
        <div>
          <label>Email *</label>
          <input type="email" name="email" required placeholder="e.g., rahul@example.com">
        </div>

        <div>
          <label>Phone</label>
          <input type="text" name="phone" placeholder="e.g., 9876543210">
        </div>
        <div>
          <label>Date of Birth</label>
          <input type="date" name="dob">
        </div>

        <div>
          <label>Gender</label>
          <select name="gender">
            <option value="">Select…</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </div>
        <div>
          <label>Course *</label>
          <input type="text" name="course" required placeholder="e.g., B.Tech CSE">
        </div>

        <div>
          <label>Department</label>
          <input type="text" name="department" placeholder="e.g., Computer Science">
        </div>
        <div>
          <label>Roll Number</label>
          <input type="text" name="roll_number" placeholder="e.g., CSE2025A01">
        </div>

        <div>
          <label>Admission Year</label>
          <input type="number" name="admission_year" min="1990" max="2099" step="1" placeholder="e.g., 2023">
        </div>
        <div>
          <label>CGPA</label>
          <input type="number" name="cgpa" step="0.01" min="0" max="10" placeholder="e.g., 8.25">
        </div>

        <div style="grid-column: 1 / -1;">
          <label>Address</label>
          <textarea name="address" placeholder="House no, street, city, state"></textarea>
        </div>
      </div>

      <div class="actions">
        <button class="btn btn-primary" type="submit">💾 Save Student</button>
        <a class="btn btn-secondary" href="view.php">🔍 Go to List</a>
      </div>
      <p class="small">Fields marked * are required.</p>
    </form>
  </div>
</div>
</body>
</html>
