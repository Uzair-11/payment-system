<?php
session_start();
if (!isset($_SESSION['bank_logged_in'])) {
    header("Location: bank-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$bank_id = $_SESSION['bank_id'];

$title = "Pending Settlements";
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
   FETCH SETTLED TRANSACTIONS
------------------------------------------------------- */

$settled = mysqli_query($conn, "
    SELECT t.*,
        (SELECT business_name FROM merchants WHERE id = t.merchant_id) AS merchant_name,
        (SELECT name FROM users WHERE id = t.user_id) AS user_name
    FROM transactions t
    WHERE t.bank_id = '$bank_id'
    AND t.status = 'settled'
    ORDER BY t.created_at DESC
");

/* -------------------------------------------------------
   TOTAL SETTLED AMOUNT
------------------------------------------------------- */

$total_settled = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COALESCE(SUM(amount), 0)
    FROM transactions
    WHERE bank_id = '$bank_id'
    AND status = 'settled'
"))[0];
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/bank-settlements.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <!-- HEADER -->
    <div class="page-header">
        <h2>Settled Transactions</h2>
        <div class="total-box">
            Total Settled: <span>$<?= number_format($total_settled, 2) ?></span>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table>
            <tr>
                <th>Tx ID</th>
                <th>User</th>
                <th>Merchant</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

            <?php while ($tx = mysqli_fetch_assoc($settled)): ?>
            <tr>
                <td><?= $tx['tx_id'] ?></td>
                <td><?= $tx['user_name'] ?></td>
                <td><?= $tx['merchant_name'] ?></td>
                <td>$<?= $tx['amount'] ?></td>
                <td><span class="status-settled">Settled</span></td>
                <td><?= $tx['created_at'] ?></td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>
</div>

</div>
