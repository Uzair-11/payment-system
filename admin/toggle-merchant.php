<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_GET['id']);

$q = mysqli_query($conn, "SELECT verified FROM merchants WHERE id=$id");
$cur = mysqli_fetch_assoc($q)['verified'];

$new = $cur ? 0 : 1;

mysqli_query($conn, "
    UPDATE merchants 
    SET verified=$new 
    WHERE id=$id
");

echo "<script>alert('Merchant status updated');window.location='manage-merchants.php';</script>";
