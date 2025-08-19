
<?php
session_start();
// Set a flash message before destroying the session
$_SESSION['flash_success'] = '✅ Logout successful!';
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>