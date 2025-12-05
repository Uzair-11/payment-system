<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$title = "Send Payment";
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

/* Fetch user's linked banks */
$banks = mysqli_query($conn, "
    SELECT b.*, ub.id AS link_id
    FROM user_banks ub
    JOIN banks b ON b.id = ub.bank_id
    WHERE ub.user_id = '$user_id'
");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/user-pay.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">

<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <h2>Send Payment</h2>

    <div class="form-card">

        <form method="POST" action="user-pay-process.php">

            <label>Merchant ID</label>
            <input type="text" name="merchant_id" required placeholder="Example: M12345">

            <label>Amount</label>
            <input type="number" name="amount" required min="1" placeholder="Enter amount">

            <label>Select Bank</label>
            <select name="bank_id" required>
                <option value="">-- Select Bank --</option>
                <?php while ($b = mysqli_fetch_assoc($banks)): ?>
                    <option value="<?= $b['id'] ?>">
                        <?= $b['bank_name'] ?> (<?= $b['bank_id'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Enter PIN</label>
            <input type="password" name="pin" maxlength="6" required placeholder="Bank PIN">

            <button class="submit-btn">Send Payment</button>
        </form>

    </div>

</div>

</div>

</div>
