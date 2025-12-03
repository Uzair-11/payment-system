<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) {
    header("Location: merchant-login.php");
    exit;
}

$title = "Merchant Dashboard";
$username = $_SESSION['merchant_business_name'];
$role = "Merchant";
$logout = "merchant-actions.php?action=logout";

$menu = [
    ["label" => "Transactions", "link" => "#"],
    ["label" => "Payouts", "link" => "#"],
    ["label" => "API Keys", "link" => "#"],
    ["label" => "Settings", "link" => "#"],
];
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

    <?php include "../components/sidebar.php"; ?>

    <div style="width:100%">
        <?php include "../components/topbar.php"; ?>

        <div class="stats-grid">
            <div class="card"><h3>Total Revenue</h3><p>$12,480</p></div>
            <div class="card"><h3>Today Sales</h3><p>$320</p></div>
            <div class="card"><h3>Payout Balance</h3><p>$980</p></div>
        </div>

        <div class="table-container">
            <h2>Recent Payments</h2>
            <table>
                <tr><th>ID</th><th>User</th><th>Amount</th><th>Status</th></tr>
                <tr><td>TX2020</td><td>John</td><td>$49</td><td>Success</td></tr>
                <tr><td>TX2021</td><td>Sarah</td><td>$19</td><td>Pending</td></tr>
            </table>
        </div>

    </div>
</div>
