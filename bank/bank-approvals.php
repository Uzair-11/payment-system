<?php
session_start();
if (!isset($_SESSION['bank_logged_in'])) {
    header("Location: bank-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$bank_id = $_SESSION['bank_id'];

$title = "Approve Transactions";
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
    FETCH PENDING TRANSACTIONS FOR THIS BANK
------------------------------------------------------- */

$pending = mysqli_query($conn, "
    SELECT t.*,
           (SELECT business_name FROM merchants WHERE id = t.merchant_id) AS merchant_name,
           (SELECT name FROM users WHERE id = t.user_id) AS user_name
    FROM transactions t
    WHERE t.bank_id = '$bank_id'
    AND t.status = 'pending'
    ORDER BY t.created_at DESC
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/bank-approvals.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>Pending Approvals</h2>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Tx ID</th>
                <th>User</th>
                <th>Merchant</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>

            <?php while ($tx = mysqli_fetch_assoc($pending)): ?>
            <tr>
                <td><?= $tx['tx_id'] ?></td>
                <td><?= $tx['user_name'] ?: 'N/A' ?></td>
                <td><?= $tx['merchant_name'] ?: 'N/A' ?></td>
                <td>$<?= $tx['amount'] ?></td>
                <td><?= $tx['created_at'] ?></td>

                <td class="actions">
                    <a href="update-status.php?tx=<?= $tx['tx_id']; ?>&action=approve"
                       class="approve-btn">Approve</a>

                    <a href="update-status.php?tx=<?= $tx['tx_id']; ?>&action=decline"
                       class="decline-btn"
                       onclick="return confirm('Decline this transaction?');">
                       Decline
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>
</div>
</div>
