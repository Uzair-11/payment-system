<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$title = "Linked Banks";
$username = $user_name;
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

/* Fetch linked banks */
$banks = mysqli_query($conn, "
    SELECT b.*, ub.id AS link_id
    FROM user_banks ub
    JOIN banks b ON b.id = ub.bank_id
    WHERE ub.user_id = '$user_id'
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/user-linked-banks.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>Linked Bank Accounts</h2>
        <a href="user-add-bank.php" class="btn-primary">+ Add Bank</a>
    </div>

    <div class="bank-list">

        <?php if (mysqli_num_rows($banks) == 0): ?>
            <p class="empty">No bank accounts linked.</p>
        <?php endif; ?>

        <?php while ($b = mysqli_fetch_assoc($banks)): ?>
        <div class="bank-card">

            <div class="bank-info">
                <h3><?= $b['bank_name'] ?></h3>
                <p>Bank ID: <?= $b['bank_id'] ?></p>
                <p>Linked: <?= $b['added_at'] ?></p>
            </div>

            <div class="bank-actions">
                <a href="user-unlink-bank.php?id=<?= $b['link_id'] ?>" 
                   class="unlink-btn"
                   onclick="return confirm('Unlink this bank?');">
                   Unlink
                </a>
            </div>

        </div>
        <?php endwhile; ?>

    </div>

</div>
</div>
</div>
