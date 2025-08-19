<?php
require 'config.php';

$id     = intval($_POST['id'] ?? 0);
$name   = trim($_POST['name'] ?? '');
$email  = trim($_POST['email'] ?? '');
$course = trim($_POST['course'] ?? '');

if ($id <= 0 || $name === '' || $email === '' || $course === '') {
    flash('error', 'Invalid data. Make sure required fields are filled.');
    header('Location: edit.php?id=' . $id);
    exit;
}

$phone          = trim($_POST['phone'] ?? '');
$dob            = $_POST['dob'] ?: null;
$gender         = $_POST['gender'] ?: null;
$address        = trim($_POST['address'] ?? '');
$department     = trim($_POST['department'] ?? '');
$roll_number    = trim($_POST['roll_number'] ?? '');
$admission_year = $_POST['admission_year'] !== '' ? intval($_POST['admission_year']) : null;
$cgpa           = $_POST['cgpa'] !== '' ? floatval($_POST['cgpa']) : null;

$sql = "UPDATE students SET
    name=?, email=?, phone=?, dob=?, gender=?, address=?, course=?, department=?, roll_number=?, admission_year=?, cgpa=?
    WHERE id=?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    flash('error', 'Prepare failed: ' . $conn->error);
    header('Location: edit.php?id=' . $id);
    exit;
}

// types: 9 strings + 1 integer + 1 double + 1 integer → sssssssss d i i
$stmt->bind_param(
    "ssssssssssdi",
    $name,
    $email,
    $phone,
    $dob,
    $gender,
    $address,
    $course,
    $department,
    $roll_number,
    $admission_year,  // integer
    $cgpa,            // double
    $id               // integer
);

if ($stmt->execute()) {
    flash('success', 'Student updated successfully.');
    header('Location: view.php');
} else {
    flash('error', 'Update failed: ' . $stmt->error);
    header('Location: edit.php?id=' . $id);
}

$stmt->close();
$conn->close();
