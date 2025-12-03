<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_GET['id']);
mysqli_query($conn, "
    UPDATE merchants 
    SET verified=1 
    WHERE id=$id
");

echo "<script>alert('Merchant Verified');window.location='manage-merchants.php';</script>";
