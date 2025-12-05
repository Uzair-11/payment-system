<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) {
    header("Location: merchant-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$merchant_id = $_SESSION['merchant_id'];
$business_name = $_SESSION['merchant_business_name'];

$title = "Payouts";
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
    FETCH PAYOUT BALANCE
---------------------------------------- */

$payout_balance = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COALESCE(balance,0) FROM merchants WHERE id='$merchant_id'
"))[0];

/* ---------------------------------------
    FETCH PAYOUT HISTORY
---------------------------------------- */

$payouts = mysqli_query($conn, "
    SELECT * FROM payouts 
    WHERE merchant_id = '$merchant_id'
    ORDER BY created_at DESC
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/merchant-payouts.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">

<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>Payouts</h2>

        <div class="balance-box">
            Balance: <span>$<?= number_format($payout_balance, 2) ?></span>
        </div>

        <!-- OPTIONAL NEW PAYOUT BUTTON -->
        <a href="request-payout.php" class="payout-btn">Request Payout</a>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Payout ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Requested</th>
                <th>Completed</th>
            </tr>

            <?php while ($p = mysqli_fetch_assoc($payouts)): ?>
            <tr>
                <td><?= $p['payout_id'] ?></td>
                <td>$<?= $p['amount'] ?></td>

                <td>
                    <span class="status-<?= $p['status'] ?>">
                        <?= ucfirst($p['status']) ?>
                    </span>
                </td>

                <td><?= $p['created_at'] ?></td>
                <td><?= $p['completed_at'] ?: '-' ?></td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>

</div>
</div>
