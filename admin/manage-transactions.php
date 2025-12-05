<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

// Filters
$status_filter = $_GET['status'] ?? 'all';

$where = "";
if ($status_filter !== "all") {
    $status = mysqli_real_escape_string($conn, $status_filter);
    $where = "WHERE status='$status'";
}

$tx = mysqli_query($conn, "
    SELECT t.*,
           (SELECT name FROM users WHERE id = t.user_id) AS user_name,
           (SELECT business_name FROM merchants WHERE id = t.merchant_id) AS merchant_name
    FROM transactions t
    $where
    ORDER BY t.created_at DESC
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/transactions.css">

<div class="dashboard">

<?php
$title = "Manage Transactions";
$username = $_SESSION['admin_username'];
$role = "Admin";
$logout = "admin-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "admin-dashboard.php"],
    ["label" => "Users", "link" => "manage-users.php"],
    ["label" => "Merchants", "link" => "manage-merchants.php"],
    ["label" => "Banks", "link" => "manage-banks.php"],
    ["label" => "Transactions", "link" => "manage-transactions.php"]
];

include "../components/sidebar.php";
?>

<div style="width:100%">
<?php include "../components/topbar.php"; ?>

<div class="table-container">
    <h2>All Transactions</h2>

    <!-- FILTER BUTTONS -->
    <div class="filter-btns">
        <a href="manage-transactions.php?status=all" 
        class="filter-all <?= $status_filter == 'all' ? 'filter-active' : '' ?>">All</a>

        <a href="manage-transactions.php?status=pending"
        class="filter-pending <?= $status_filter == 'pending' ? 'filter-active' : '' ?>">Pending</a>

        <a href="manage-transactions.php?status=succeeded"
        class="filter-succeeded <?= $status_filter == 'succeeded' ? 'filter-active' : '' ?>">Succeeded</a>

        <a href="manage-transactions.php?status=declined"
        class="filter-declined <?= $status_filter == 'declined' ? 'filter-active' : '' ?>">Declined</a>

        <a href="manage-transactions.php?status=settled"
        class="filter-settled <?= $status_filter == 'settled' ? 'filter-active' : '' ?>">Settled</a>
    </div>


    <table>
        <tr>
            <th>Tx ID</th>
            <th>User</th>
            <th>Merchant</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>

        <?php while($t = mysqli_fetch_assoc($tx)): ?>
        <tr>
            <td><?php echo $t['tx_id']; ?></td>
            <td><?php echo $t['user_name'] ?: 'N/A'; ?></td>
            <td><?php echo $t['merchant_name'] ?: 'N/A'; ?></td>
            <td>$<?php echo $t['amount']; ?></td>

            <td>
                <?php 
                $colors = [
                    "pending"=>"#f6c14b",
                    "succeeded"=>"#73fa79",
                    "declined"=>"#ff6b6b",
                    "settled"=>"#7c5cff"
                ];
                ?>
                <span class="status-<?php echo $t['status']; ?>">
                    <?php echo ucfirst($t['status']); ?>
                </span>

            </td>

            <td><?php echo $t['created_at']; ?></td>

            <td>
            <a href="view-transaction.php?tx=<?= $t['tx_id']; ?>" class="action-view">View</a>

            <?php if($t['status'] == "pending"): ?>
                | <a href="update-transaction.php?tx=<?= $t['tx_id']; ?>&action=approve" class="action-approve">Approve</a>
                | <a href="update-transaction.php?tx=<?= $t['tx_id']; ?>&action=decline" class="action-decline">Decline</a>
            <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>
</div>

</div>
</div>
