<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_POST['id']);
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

mysqli_query($conn, "
    UPDATE users
    SET name='$name', email='$email', phone='$phone'
    WHERE id=$id
");

echo "<script>alert('User updated successfully');window.location='manage-users.php';</script>";
