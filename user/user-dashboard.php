<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$title = "User Dashboard";
$username = $_SESSION['user_name'];
$role = "User";
$logout = "user-actions.php?action=logout";

$menu = [
    ["label" => "My Payments", "link" => "#"],
    ["label" => "Saved Cards", "link" => "#"],
    ["label" => "Profile Settings", "link" => "#"],
];
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

    <?php include "../components/sidebar.php"; ?>

    <div style="width:100%">
        <?php include "../components/topbar.php"; ?>

        <div class="stats-grid">
            <div class="card"><h3>Total Spent</h3><p>$540</p></div>
            <div class="card"><h3>Transactions</h3><p>18</p></div>
            <div class="card"><h3>Saved Cards</h3><p>3</p></div>
        </div>

        <div class="table-container">
            <h2>Recent Purchases</h2>
            <table>
                <tr><th>ID</th><th>Merchant</th><th>Amount</th><th>Status</th></tr>
                <tr><td>TX3311</td><td>Atlas Store</td><td>$49</td><td>Success</td></tr>
                <tr><td>TX3312</td><td>Shopmax</td><td>$19</td><td>Refunded</td></tr>
            </table>
        </div>

    </div>
</div>
