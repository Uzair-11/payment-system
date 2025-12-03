<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_GET['id']);
$q = mysqli_fetch_assoc(mysqli_query($conn, "SELECT active FROM banks WHERE id=$id"));
$new = $q['active'] ? 0 : 1;

mysqli_query($conn, "UPDATE banks SET active=$new WHERE id=$id");

echo "<script>alert('Bank status updated');window.location='manage-banks.php';</script>";
