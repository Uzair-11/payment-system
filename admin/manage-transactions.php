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

<link rel="stylesheet" href="../components/dashboard-styles.css">

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
    <div style="margin-bottom:15px;">
        <a href="manage-transactions.php?status=all" style="color:white;margin-right:10px;">All</a>
        <a href="manage-transactions.php?status=pending" style="color:#f6c14b;margin-right:10px;">Pending</a>
        <a href="manage-transactions.php?status=succeeded" style="color:#73fa79;margin-right:10px;">Succeeded</a>
        <a href="manage-transactions.php?status=declined" style="color:#ff6b6b;margin-right:10px;">Declined</a>
        <a href="manage-transactions.php?status=settled" style="color:#7c5cff;">Settled</a>
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
                <span style="color:<?php echo $colors[$t['status']]; ?>;font-weight:700;">
                    <?php echo ucfirst($t['status']); ?>
                </span>
            </td>

            <td><?php echo $t['created_at']; ?></td>

            <td>
                <a href="view-transaction.php?tx=<?php echo $t['tx_id']; ?>" 
                   style="color:#7c5cff;font-weight:600;">View</a>

                <?php if($t['status'] == "pending"): ?>
                  | <a href="update-transaction.php?tx=<?php echo $t['tx_id']; ?>&action=approve" 
                       style="color:#73fa79;font-weight:600;">Approve</a>
                  | <a href="update-transaction.php?tx=<?php echo $t['tx_id']; ?>&action=decline" 
                       style="color:#ff6b6b;font-weight:600;">Decline</a>
                <?php endif; ?>

            </td>
        </tr>
        <?php endwhile; ?>

    </table>
</div>

</div>
</div>
