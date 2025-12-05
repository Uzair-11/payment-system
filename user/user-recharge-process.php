<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$user_id = $_SESSION['user_id'];

$mobile = $_POST['mobile'];
$operator = $_POST['operator'];
$amount = floatval($_POST['amount']);
$bank_id = $_POST['bank_id'];
$pin = $_POST['pin'];

/* Validate bank */
$bank = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM banks WHERE id='$bank_id'
"));

if (!$bank) {
    die("<script>alert('Invalid bank'); window.location='user-recharge.php';</script>");
}

/* Validate pin */
if ($bank['pin'] !== $pin) {
    die("<script>alert('Incorrect PIN'); window.location='user-recharge.php';</script>");
}

/* Recharge merchant (system merchant) */
$merchant_id = 9999;

/* Create transaction */
$tx_id = "TX" . rand(100000, 999999);

mysqli_query($conn, "
    INSERT INTO transactions (tx_id, user_id, merchant_id, bank_id, amount, status, created_at)
    VALUES ('$tx_id', '$user_id', '$merchant_id', '$bank_id', '$amount', 'pending', NOW())
");

echo "<script>
        alert('Recharge request created! Transaction: $tx_id');
        window.location='user-transactions.php';
      </script>";
exit;
?>
