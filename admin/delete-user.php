<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM users WHERE id=$id");

echo "<script>alert('User deleted');window.location='manage-users.php';</script>";
