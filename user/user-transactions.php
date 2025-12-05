<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$user_id = $_SESSION['user_id'];

$status_filter = $_GET['status'] ?? 'all';
$where = "";
if ($status_filter !== 'all') {
    $status = mysqli_real_escape_string($conn, $status_filter);
    $where = "AND t.status='$status'";
}

$title = "My Transactions";
$username = $_SESSION['user_name'];
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

$transactions = mysqli_query($conn, "
    SELECT t.*, 
           m.business_name AS merchant_name
    FROM transactions t
    LEFT JOIN merchants m ON m.id = t.merchant_id
    WHERE t.user_id = '$user_id'
    $where
    ORDER BY t.created_at DESC
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/user-transactions.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>My Transactions</h2>
    </div>

    <!-- Filters like in GPay: All, Pending, Completed, Failed -->
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
                <th>Merchant</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

            <?php while ($t = mysqli_fetch_assoc($transactions)): ?>
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
