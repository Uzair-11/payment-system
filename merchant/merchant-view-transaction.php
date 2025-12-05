<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) {
    header("Location: merchant-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$merchant_id = $_SESSION['merchant_id'];

if (!isset($_GET['tx'])) {
    die("Invalid transaction");
}

$tx_id = mysqli_real_escape_string($conn, $_GET['tx']);

/* ----------------------------------------
   FETCH TRANSACTION DETAILS
----------------------------------------- */
$tx = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT t.*, 
           u.name AS user_name, 
           u.email AS user_email,
           m.business_name AS merchant_name,
           b.bank_name AS bank_name
    FROM transactions t
    LEFT JOIN users u ON u.id = t.user_id
    LEFT JOIN merchants m ON m.id = t.merchant_id
    LEFT JOIN banks b ON b.bank_id = t.bank_id
    WHERE t.tx_id = '$tx_id' AND t.merchant_id = '$merchant_id'
"));

if (!$tx) {
    die("Transaction not found or access denied.");
}

$title = "Transaction Details";
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
<link rel="stylesheet" href="css/merchant-view-transaction.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <a href="merchant-transactions.php" class="back-btn">← Back</a>

    <div class="tx-card">
        <h2>Transaction Details</h2>

        <div class="tx-row">
            <span class="label">Transaction ID:</span>
            <span class="value"><?= $tx['tx_id'] ?></span>
        </div>

        <div class="tx-row">
            <span class="label">User:</span>
            <span class="value"><?= $tx['user_name'] ?: "N/A" ?> (<?= $tx['user_email'] ?>)</span>
        </div>

        <div class="tx-row">
            <span class="label">Merchant:</span>
            <span class="value"><?= $tx['merchant_name'] ?></span>
        </div>

        <div class="tx-row">
            <span class="label">Amount:</span>
            <span class="value">$<?= $tx['amount'] ?></span>
        </div>

        <div class="tx-row">
            <span class="label">Status:</span>
            <span class="value status-<?= $tx['status'] ?>">
                <?= ucfirst($tx['status']) ?>
            </span>
        </div>

        <div class="tx-row">
            <span class="label">Bank:</span>
            <span class="value"><?= $tx['bank_name'] ?: "N/A" ?></span>
        </div>

        <div class="tx-row">
            <span class="label">Payment Method:</span>
            <span class="value"><?= $tx['payment_method'] ?: "N/A"; ?></span>
        </div>

        <div class="tx-row">
            <span class="label">Created At:</span>
            <span class="value"><?= $tx['created_at'] ?></span>
        </div>

    </div>

</div>
</div>
</div>
