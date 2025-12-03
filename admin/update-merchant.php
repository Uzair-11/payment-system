<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_POST['id']);
$name = $_POST['business_name'];
$email = $_POST['email'];

mysqli_query($conn, "
    UPDATE merchants 
    SET business_name='$name', email='$email' 
    WHERE id=$id
");

echo "<script>alert('Merchant updated'); window.location='manage-merchants.php';</script>";
