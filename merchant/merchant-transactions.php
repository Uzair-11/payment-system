<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) {
    header("Location: merchant-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$merchant_id = $_SESSION['merchant_id'];

// Filter
$status_filter = $_GET['status'] ?? 'all';

$where = "";
if ($status_filter !== "all") {
    $status = mysqli_real_escape_string($conn, $status_filter);
    $where = "AND t.status='$status'";
}

$title = "Transactions";
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

/* ---------------------------------------
   FETCH TRANSACTIONS
---------------------------------------- */

$transactions = mysqli_query($conn, "
    SELECT t.*, 
           (SELECT name FROM users WHERE id=t.user_id) AS user_name
    FROM transactions t
    WHERE t.merchant_id = '$merchant_id'
    $where
    ORDER BY t.created_at DESC
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/merchant-transactions.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>Transactions</h2>
    </div>

    <!-- FILTER BUTTONS -->
    <div class="filter-btns">
        <a href="?status=all" class="<?= $status_filter=='all'?'active':'' ?>">All</a>
        <a href="?status=pending" class="<?= $status_filter=='pending'?'active':'' ?>">Pending</a>
        <a href="?status=succeeded" class="<?= $status_filter=='succeeded'?'active':'' ?>">Succeeded</a>
        <a href="?status=declined" class="<?= $status_filter=='declined'?'active':'' ?>">Declined</a>
        <a href="?status=settled" class="<?= $status_filter=='settled'?'active':'' ?>">Settled</a>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Tx ID</th>
                <th>User</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

            <?php while ($tx = mysqli_fetch_assoc($transactions)): ?>
            <tr>
                <td><?= $tx['tx_id'] ?></td>
                <td><?= $tx['user_name'] ?: "N/A" ?></td>
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
