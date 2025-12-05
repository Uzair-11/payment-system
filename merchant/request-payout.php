<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) {
    header("Location: merchant-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$merchant_id = $_SESSION['merchant_id'];
$business_name = $_SESSION['merchant_business_name'];

$title = "Request Payout";
$username = $business_name;
$role = "Merchant";
$logout = "merchant-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "merchant-dashboard.php"],
    ["label" => "Transactions", "link" => "merchant-transactions.php"],
    ["label" => "Payouts", "link" => "merchant-payouts.php"],
    ["label" => "API Keys", "link" => "merchant-api.php"],
    ["label" => "Settings", "link" => "merchant-settings.php"],
];

/* ---------------------------------------
    FETCH CURRENT BALANCE
---------------------------------------- */

$balance = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COALESCE(balance,0) FROM merchants WHERE id='$merchant_id'
"))[0];
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/request-payout.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <a href="merchant-payouts.php" class="back-btn">← Back</a>

    <div class="payout-card">

        <h2>Request Payout</h2>

        <div class="balance-box">
            Available Balance: <span>$<?= number_format($balance, 2) ?></span>
        </div>

        <form method="POST" action="merchant-payout-actions.php">

            <input type="hidden" name="action" value="request_payout">

            <label>Payout Amount</label>
            <input type="number" step="0.01" name="amount" 
                   placeholder="Enter amount"
                   max="<?= $balance ?>"
                   required>

            <label>Bank Account Number</label>
            <input type="text" name="account_number" placeholder="Optional">

            <label>Account Holder Name</label>
            <input type="text" name="account_name" placeholder="Optional">

            <label>IFSC / Routing Code</label>
            <input type="text" name="ifsc" placeholder="Optional">

            <button class="request-btn">Submit Request</button>
        </form>

    </div>

</div>

</div>
</div>
