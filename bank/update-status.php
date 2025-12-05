<?php
session_start();
if (!isset($_SESSION['bank_logged_in'])) { exit; }

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$tx = $_GET['tx'];
$action = $_GET['action'];

if ($action === "approve") {
    mysqli_query($conn, "UPDATE transactions SET status='approved' WHERE tx_id='$tx'");
}

if ($action === "decline") {
    mysqli_query($conn, "UPDATE transactions SET status='declined' WHERE tx_id='$tx'");
}

header("Location: bank-approvals.php");
exit;
