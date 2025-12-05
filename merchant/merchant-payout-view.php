<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) {
    header("Location: merchant-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$merchant_id = $_SESSION['merchant_id'];

if (!isset($_GET['payout'])) {
    die("Invalid payout.");
}

$payout_id = mysqli_real_escape_string($conn, $_GET['payout']);

/* -----------------------------------------
   FETCH PAYOUT DETAILS
------------------------------------------ */
$p = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM payouts
    WHERE payout_id='$payout_id' 
    AND merchant_id='$merchant_id'
"));

// If payout not found or belongs to another merchant
if (!$p) {
    die("Payout not found or access denied.");
}

$title = "Payout Details";
$username = $_SESSION['merchant_business_name'];
$role = "Merchant";
$logout = "merchant-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "merchant-dashboard.php"],
    ["label" => "Transactions", "link" => "merchant-transactions.php"],
    ["label" => "Payouts", "link" => "merchant-payouts.php"],
    ["label" => "API Keys", "link" => "merchant-api.php"],
    ["label" => "Settings", "link" => "merchant-settings.php"],
];
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/merchant-payout-view.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <a href="merchant-payouts.php" class="back-btn">← Back</a>

    <div class="payout-card">

        <h2>Payout Details</h2>

        <div class="row">
            <span class="label">Payout ID:</span>
            <span class="value"><?= $p['payout_id'] ?></span>
        </div>

        <div class="row">
            <span class="label">Amount:</span>
            <span class="value">$<?= $p['amount'] ?></span>
        </div>

        <div class="row">
            <span class="label">Status:</span>
            <span class="value status-<?= $p['status'] ?>">
                <?= ucfirst($p['status']) ?>
            </span>
        </div>

        <div class="row">
            <span class="label">Requested At:</span>
            <span class="value"><?= $p['created_at'] ?></span>
        </div>

        <div class="row">
            <span class="label">Completed At:</span>
            <span class="value"><?= $p['completed_at'] ?: "-" ?></span>
        </div>

        <h3 class="section-title">Bank Details</h3>

        <div class="row">
            <span class="label">Account Number:</span>
            <span class="value"><?= $p['account_number'] ?: "N/A" ?></span>
        </div>

        <div class="row">
            <span class="label">Account Holder Name:</span>
            <span class="value"><?= $p['account_name'] ?: "N/A" ?></span>
        </div>

        <div class="row">
            <span class="label">IFSC / Routing Code:</span>
            <span class="value"><?= $p['ifsc'] ?: "N/A" ?></span>
        </div>

    </div>

</div>
</div>
</div>
