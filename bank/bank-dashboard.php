<?php
session_start();
if (!isset($_SESSION['bank_logged_in'])) {
    header("Location: bank-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$bank_id = $_SESSION['bank_id']; // bank_id from banks table
$title = "Bank Dashboard";
$username = $bank_id;
$role = "Bank";
$logout = "bank-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "bank-dashboard.php"],
    ["label" => "Approve Transactions", "link" => "bank-approvals.php"],
    ["label" => "Pending Settlements", "link" => "bank-settlements.php"],
    ["label" => "Bank Profile", "link" => "bank-profile.php"]
];

/* -------------------------------------------------------
    DASHBOARD DATA — FETCHING LIVE FROM DATABASE
------------------------------------------------------- */

// 1. PENDING APPROVALS (transactions for this bank)
$pending = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COUNT(*) FROM transactions 
    WHERE bank_id = '$bank_id' AND status = 'pending'
"))[0];

// 2. TODAY'S TRANSACTIONS
$todays = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COUNT(*) FROM transactions 
    WHERE bank_id = '$bank_id' AND DATE(created_at) = CURDATE()
"))[0];

// 3. TOTAL SETTLED AMOUNT
$settled = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COALESCE(SUM(amount),0) FROM transactions 
    WHERE bank_id = '$bank_id' AND status = 'settled'
"))[0];

// 4. LATEST 10 TRANSACTIONS FOR THIS BANK
$latest = mysqli_query($conn, "
    SELECT t.*, 
        (SELECT business_name FROM merchants WHERE id = t.merchant_id) AS merchant_name
    FROM transactions t
    WHERE bank_id = '$bank_id'
    ORDER BY t.created_at DESC
    LIMIT 10
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/bank-dashboard.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">

<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <!-- STATISTICS -->
    <div class="stats-grid">
        <div class="card"><h3>Pending Approvals</h3><p class="stat-value"><?= $pending ?></p></div>
        <div class="card"><h3>Today's Transactions</h3><p class="stat-value"><?= $todays ?></p></div>
        <div class="card"><h3>Total Settled</h3><p class="stat-value">$<?= number_format($settled, 2) ?></p></div>
    </div>

    <!-- LATEST TRANSACTIONS -->
    <div class="table-container">
        <h2>Latest Transactions</h2>

        <table>
            <tr>
                <th>Tx ID</th>
                <th>Merchant</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

            <?php while ($tx = mysqli_fetch_assoc($latest)): ?>
            <tr>
                <td><?= $tx['tx_id'] ?></td>
                <td><?= $tx['merchant_name'] ?: 'N/A' ?></td>
                <td>$<?= $tx['amount'] ?></td>

                <td>
                    <span class="status-<?= $tx['status'] ?>">
                        <?= ucfirst($tx['status']) ?>
                    </span>
                </td>

                <td><?= $tx['created_at'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>
</div>

</div>
