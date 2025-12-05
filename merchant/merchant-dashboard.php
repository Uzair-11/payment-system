<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) {
    header("Location: merchant-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$merchant_id = $_SESSION['merchant_id'];
$business_name = $_SESSION['merchant_business_name'];

$title = "Merchant Dashboard";
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

/* -------------------------------------------
    FETCH DASHBOARD METRICS
-------------------------------------------- */

// Total revenue
$total_revenue = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COALESCE(SUM(amount),0) FROM transactions 
    WHERE merchant_id='$merchant_id' AND status='succeeded'
"))[0];

// Today sales
$today_sales = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COALESCE(SUM(amount),0) FROM transactions 
    WHERE merchant_id='$merchant_id' AND DATE(created_at)=CURDATE()
"))[0];

// Payout Balance (assume payouts table exists)
$payout_balance = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COALESCE(balance,0) FROM merchants WHERE id='$merchant_id'
"))[0];

// Recent 10 payments
$recent_payments = mysqli_query($conn, "
    SELECT t.*, u.name AS user_name
    FROM transactions t
    LEFT JOIN users u ON u.id = t.user_id
    WHERE merchant_id = '$merchant_id'
    ORDER BY created_at DESC
    LIMIT 10
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/merchant-dashboard.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">

<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <!-- DASHBOARD CARDS -->
    <div class="stats-grid">

        <div class="card">
            <h3>Total Revenue</h3>
            <p class="stat-value">$<?= number_format($total_revenue, 2) ?></p>
        </div>

        <div class="card">
            <h3>Today's Sales</h3>
            <p class="stat-value">$<?= number_format($today_sales, 2) ?></p>
        </div>

        <div class="card">
            <h3>Payout Balance</h3>
            <p class="stat-value">$<?= number_format($payout_balance, 2) ?></p>
        </div>

    </div>

    <!-- RECENT TRANSACTIONS -->
    <div class="table-container">
        <h2>Recent Payments</h2>

        <table>
            <tr>
                <th>Tx ID</th>
                <th>User</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

            <?php while ($t = mysqli_fetch_assoc($recent_payments)): ?>
            <tr>
                <td><?= $t['tx_id'] ?></td>
                <td><?= $t['user_name'] ?: "N/A" ?></td>
                <td>$<?= $t['amount'] ?></td>

                <td>
                    <span class="status-<?= $t['status'] ?>">
                        <?= ucfirst($t['status']) ?>
                    </span>
                </td>

                <td><?= $t['created_at'] ?></td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>

</div>
</div>
