<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$user_id = $_SESSION['user_id'];

$merchant_id = $_POST['merchant_id'];
$amount = floatval($_POST['amount']);
$bank_id = $_POST['bank_id'];
$pin = $_POST['pin'];

/* 1. Validate merchant */
$merchant = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM merchants WHERE id = '$merchant_id'
"));

if (!$merchant) {
    die("<script>alert('Merchant not found.'); window.location='user-pay.php';</script>");
}

/* 2. Validate user owns the bank */
$bank = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM banks WHERE id = '$bank_id'
"));

if (!$bank) {
    die("<script>alert('Invalid bank.'); window.location='user-pay.php';</script>");
}

/* 3. Validate PIN */
if ($bank['pin'] !== $pin) {
    die("<script>alert('Incorrect PIN'); window.location='user-pay.php';</script>");
}

/* 4. Create transaction */
$tx_id = "TX" . rand(100000, 999999);

mysqli_query($conn, "
    INSERT INTO transactions (tx_id, user_id, merchant_id, bank_id, amount, status, created_at)
    VALUES ('$tx_id', '$user_id', '$merchant_id', '$bank_id', '$amount', 'pending', NOW())
");

echo "<script>
        alert('Payment request sent! Transaction: $tx_id');
        window.location='user-transactions.php';
      </script>";
exit;
?>
