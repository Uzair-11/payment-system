<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM banks WHERE id=$id");

echo "<script>alert('Bank deleted');window.location='manage-banks.php';</script>";
