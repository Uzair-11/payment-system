<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM merchants WHERE id=$id");

echo "<script>alert('Merchant deleted');window.location='manage-merchants.php';</script>";
