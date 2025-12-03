<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$txid = mysqli_real_escape_string($conn, $_GET['tx']);

$tx = mysqli_query($conn, "
    SELECT t.*,
           (SELECT name FROM users WHERE id=t.user_id) AS user_name,
           (SELECT business_name FROM merchants WHERE id=t.merchant_id) AS merchant_name
    FROM transactions t
    WHERE tx_id='$txid'
    LIMIT 1
");

$data = mysqli_fetch_assoc($tx);
if (!$data) die("Transaction not found");
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

<?php
$title = "Transaction Details";
$username = $_SESSION['admin_username'];
$role = "Admin";
$logout = "admin-actions.php?action=logout";
$menu = [
    ["label" => "Dashboard", "link" => "admin-dashboard.php"],
    ["label" => "Transactions", "link" => "manage-transactions.php"]
];
include "../components/sidebar.php";
?>

<div style="width:100%">
<?php include "../components/topbar.php"; ?>

<div class="table-container" style="max-width:650px;margin:auto;">
    <h2>Transaction: <?php echo $data['tx_id']; ?></h2>

    <pre style="color:#cdd3e8;background:rgba(255,255,255,0.03);padding:15px;border-radius:8px;">
ID: <?php echo $data['id']; ?>

User: <?php echo $data['user_name'] ?: 'N/A'; ?>

Merchant: <?php echo $data['merchant_name'] ?: 'N/A'; ?>

Amount: $<?php echo $data['amount']; ?>

Currency: <?php echo $data['currency']; ?>

Status: <?php echo ucfirst($data['status']); ?>

Card Brand: <?php echo $data['card_brand']; ?>

Last 4: <?php echo $data['card_last4']; ?>

Token: <?php echo $data['token']; ?>

Created At: <?php echo $data['created_at']; ?>
    </pre>

    <?php if($data['status'] == "pending"): ?>
    <a class="btn" href="update-transaction.php?tx=<?php echo $data['tx_id']; ?>&action=approve">
        Approve
    </a>
    <a class="btn" style="background:#ff4d6d;color:#fff;"
        href="update-transaction.php?tx=<?php echo $data['tx_id']; ?>&action=decline">
        Decline
    </a>
    <?php endif; ?>

</div>

</div>
</div>
