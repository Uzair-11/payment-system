<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) {
    header("Location: merchant-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$merchant_id = $_SESSION['merchant_id'];

// Fetch merchant info
$q = mysqli_query($conn, "SELECT * FROM merchants WHERE id='$merchant_id'");
$merchant = mysqli_fetch_assoc($q);

$title = "Settings";
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
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/merchant-settings.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>Account Settings</h2>
    </div>

    <div class="settings-card">

        <form method="POST" action="merchant-settings-actions.php">

            <input type="hidden" name="action" value="update_profile">

            <label>Business Name</label>
            <input type="text" name="business_name" value="<?= $merchant['business_name'] ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= $merchant['email'] ?>" required>

            <label>Webhook URL (optional)</label>
            <input type="text" name="webhook_url" 
                   value="<?= $merchant['webhook_url'] ?? '' ?>" 
                   placeholder="https://example.com/webhook">

            <button class="save-btn">Save Changes</button>
        </form>
    </div>

    <h3 class="section-title">Change Password</h3>

    <div class="settings-card">

        <form method="POST" action="merchant-settings-actions.php">

            <input type="hidden" name="action" value="change_password">

            <label>Current Password</label>
            <input type="password" name="old_password" required>

            <label>New Password</label>
            <input type="password" name="new_password" minlength="6" required>

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" minlength="6" required>

            <button class="save-btn">Update Password</button>
        </form>

    </div>

</div>

</div>
</div>
