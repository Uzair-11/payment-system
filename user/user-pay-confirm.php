<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$tx_id = $_GET['tx_id'];

$tx = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT t.*, 
      (SELECT business_name FROM merchants WHERE id = t.merchant_id) AS merchant_name
    FROM transactions t 
    WHERE tx_id = '$tx_id'
"));

if (!$tx) {
    die("Invalid Transaction");
}

$user_name = $_SESSION['user_name'];

$title = "Payment Successful";
$username = $user_name;
$role = "User";
$logout = "user-actions.php?action=logout";

$menu = [];
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/user-pay-confirm.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">

<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="success-box">

        <div class="success-circle">
            <div class="checkmark"></div>
        </div>

        <h2>Payment Successful</h2>

        <p class="amount">₹<?= number_format($tx['amount'], 2) ?></p>

        <p class="merchant">Paid to <strong><?= $tx['merchant_name'] ?></strong></p>

        <div class="details-box">
            <p><strong>Transaction ID:</strong> <?= $tx['tx_id'] ?></p>
            <p><strong>Status:</strong> <?= ucfirst($tx['status']) ?></p>
            <p><strong>Date:</strong> <?= $tx['created_at'] ?></p>
        </div>

        <div class="btn-row">
            <a href="user-transactions.php" class="btn-grey">View History</a>
            <a href="user-dashboard.php" class="btn-primary">Go to Home</a>
        </div>

    </div>

</div>

</div>
</div>
