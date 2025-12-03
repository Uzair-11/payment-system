<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$txid = mysqli_real_escape_string($conn, $_GET['tx']);
$action = $_GET['action'];

if ($action == "approve") {
    mysqli_query($conn, "
        UPDATE transactions
        SET status='succeeded'
        WHERE tx_id='$txid'
        LIMIT 1
    ");

    mysqli_query($conn, "
        UPDATE bank_queue
        SET status='approved'
        WHERE tx_id='$txid'
    ");

    echo "<script>alert('Transaction approved');window.location='manage-transactions.php';</script>";
    exit;
}

if ($action == "decline") {
    mysqli_query($conn, "
        UPDATE transactions
        SET status='declined'
        WHERE tx_id='$txid'
        LIMIT 1
    ");

    mysqli_query($conn, "
        UPDATE bank_queue
        SET status='rejected'
        WHERE tx_id='$txid'
    ");

    echo "<script>alert('Transaction declined');window.location='manage-transactions.php';</script>";
    exit;
}

echo "<script>alert('Invalid action');window.location='manage-transactions.php';</script>";
