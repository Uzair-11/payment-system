<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$title = "User Dashboard";
$username = $user_name;
$role = "User";
$logout = "user-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "user-dashboard.php"],
    ["label" => "Transactions", "link" => "user-transactions.php"],
    ["label" => "Linked Banks", "link" => "user-linked-banks.php"],
    ["label" => "Pay", "link" => "user-pay.php"],
    ["label" => "Scan QR", "link" => "user-qr.php"],
    ["label" => "Recharge", "link" => "user-recharge.php"],
    ["label" => "Profile", "link" => "user-profile.php"],
    ["label" => "Settings", "link" => "user-settings.php"],
];

/* ---------------------------------------
   DASHBOARD STATS
---------------------------------------- */

// Total spent (all succeeded/settled)
$total_spent = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COALESCE(SUM(amount),0) FROM transactions 
    WHERE user_id = '$user_id' 
      AND status IN ('succeeded','settled')
"))[0];

// Today spent
$today_spent = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COALESCE(SUM(amount),0) FROM transactions 
    WHERE user_id = '$user_id' 
      AND DATE(created_at) = CURDATE()
      AND status IN ('succeeded','settled')
"))[0];

// Linked banks – assuming a junction table user_banks(user_id, bank_id)
$linked_banks = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COUNT(*) FROM user_banks WHERE user_id = '$user_id'
"))[0] ?? 0;

// Recent transactions
$recent = mysqli_query($conn, "
    SELECT t.*, 
           m.business_name AS merchant_name
    FROM transactions t
    LEFT JOIN merchants m ON m.id = t.merchant_id
    WHERE t.user_id = '$user_id'
    ORDER BY t.created_at DESC
    LIMIT 10
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/user-dashboard.css">

<div class="dashboard">

    <?php include "../components/sidebar.php"; ?>

    <div class="page-main">

        <?php include "../components/topbar.php"; ?>

        <div class="page-content">

            <!-- QUICK STATS LIKE GPAY HOME -->
            <div class="stats-grid">
                <div class="card">
                    <h3>Total Spent</h3>
                    <p class="stat-value">$<?= number_format($total_spent, 2) ?></p>
                </div>
                <div class="card">
                    <h3>Today’s Spend</h3>
                    <p class="stat-value">$<?= number_format($today_spent, 2) ?></p>
                </div>
                <div class="card">
                    <h3>Linked Banks</h3>
                    <p class="stat-value"><?= $linked_banks ?></p>
                </div>
            </div>

            <!-- RECENT TRANSACTIONS -->
            <div class="table-container">
                <h2>Recent Transactions</h2>

                <table>
                    <tr>
                        <th>Tx ID</th>
                        <th>Merchant</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>

                    <?php while ($t = mysqli_fetch_assoc($recent)): ?>
                    <tr>
                        <td><?= $t['tx_id'] ?></td>
                        <td><?= $t['merchant_name'] ?: 'N/A' ?></td>
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
