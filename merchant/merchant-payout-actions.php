<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$action = $_POST['action'] ?? null;
$merchant_id = $_SESSION['merchant_id'];

/* ---------------------------------------
    REQUEST PAYOUT
---------------------------------------- */

if ($action === "request_payout") {

    $amount = floatval($_POST['amount']);
    $acc_no = $_POST['account_number'] ?? null;
    $acc_name = $_POST['account_name'] ?? null;
    $ifsc = $_POST['ifsc'] ?? null;

    // Fetch balance
    $bal = mysqli_fetch_row(mysqli_query($conn, "
        SELECT balance FROM merchants WHERE id='$merchant_id'
    "))[0];

    if ($amount <= 0 || $amount > $bal) {
        die("<script>alert('Invalid amount.'); window.location='request-payout.php';</script>");
    }

    // Create payout record
    $payout_id = "PO".time().rand(100,999);

    mysqli_query($conn, "
        INSERT INTO payouts (payout_id, merchant_id, amount, status, account_number, account_name, ifsc)
        VALUES ('$payout_id', '$merchant_id', '$amount', 'pending', '$acc_no', '$acc_name', '$ifsc')
    ");

    // Deduct balance
    mysqli_query($conn, "
        UPDATE merchants SET balance = balance - $amount WHERE id='$merchant_id'
    ");

    echo "<script>alert('Payout request submitted successfully!'); window.location='merchant-payouts.php';</script>";
    exit;
}

header("Location: merchant-payouts.php");
exit;
