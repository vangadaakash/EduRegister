<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>


<?php
require 'config.php';

// Basic required fields
$name  = trim($_POST['name']  ?? '');
$email = trim($_POST['email'] ?? '');
$course= trim($_POST['course']?? '');

if ($name === '' || $email === '' || $course === '') {
  flash('error', 'Please fill all required fields (Name, Email, Course).');
  header('Location: index.php'); exit;
}

// Collect optional fields
$phone          = trim($_POST['phone'] ?? '');
$dob            = $_POST['dob'] ?: null;
$gender         = $_POST['gender'] ?: null;
$address        = trim($_POST['address'] ?? '');
$department     = trim($_POST['department'] ?? '');
$roll_number    = trim($_POST['roll_number'] ?? '');
$admission_year = $_POST['admission_year'] ?: null;
$cgpa           = $_POST['cgpa'] !== '' ? $_POST['cgpa'] : null;

// Prepared statement to avoid SQL injection
$sql = "INSERT INTO students
  (name, email, phone, dob, gender, address, course, department, roll_number, admission_year, cgpa)
  VALUES (?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
  "sssssssssis",
  $name, $email, $phone, $dob, $gender, $address, $course, $department, $roll_number, $admission_year, $cgpa
);

if ($stmt->execute()) {
  flash('success', 'Student added successfully.');
  header('Location: view.php');
} else {
  // Common unique errors (email/roll unique)
  flash('error', 'Insert failed: ' . $conn->error);
  header('Location: index.php');
}
