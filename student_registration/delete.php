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

$stmt = $conn->prepare("DELETE FROM students WHERE id=?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
  flash('success','Student deleted.');
} else {
  flash('error','Delete failed: '.$conn->error);
}
header('Location: view.php');
