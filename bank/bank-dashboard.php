<?php
session_start();
if (!isset($_SESSION['bank_logged_in'])) {
    header("Location: bank-login.php");
    exit;
}

$title = "Bank Dashboard";
$username = $_SESSION['bank_id'];
$role = "Bank";
$logout = "bank-actions.php?action=logout";

$menu = [
    ["label" => "Approve Transactions", "link" => "#"],
    ["label" => "Pending Settlements", "link" => "#"],
    ["label" => "Bank Profile", "link" => "#"]
];
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

    <?php include "../components/sidebar.php"; ?>

    <div style="width:100%">
        <?php include "../components/topbar.php"; ?>

        <div class="stats-grid">
            <div class="card"><h3>Pending Approvals</h3><p>12</p></div>
            <div class="card"><h3>Today's Transactions</h3><p>248</p></div>
            <div class="card"><h3>Total Settled</h3><p>$82,400</p></div>
        </div>

        <div class="table-container">
            <h2>Latest Transactions</h2>
            <table>
                <tr><th>ID</th><th>Merchant</th><th>Amount</th><th>Status</th></tr>
                <tr><td>TX1021</td><td>Atlas Store</td><td>$49.00</td><td>Pending</td></tr>
                <tr><td>TX1022</td><td>ShopMax</td><td>$19.00</td><td>Approved</td></tr>
            </table>
        </div>

    </div>
</div>
