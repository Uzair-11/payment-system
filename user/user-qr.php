<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$user_name = $_SESSION['user_name'];

$title = "Scan QR";
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
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/user-qr.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>
<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <h2>Scan QR Code</h2>

    <div class="qr-box">

        <div class="camera-view">
            <div class="focus-box"></div>
        </div>

        <button class="scan-btn" onclick="scanQR()">Scan Now</button>

        <p class="or">OR</p>

        <form action="user-pay.php" method="GET" class="manual-form">
            <input type="text" name="merchant_id" placeholder="Enter Merchant ID" required>
            <button class="manual-btn">Continue</button>
        </form>

    </div>

</div>
</div>
</div>

<script>
function scanQR() {
    // Simulate QR detection
    let fakeMerchant = "<?= rand(1000,9999) ?>";
    alert("QR Code Detected! Merchant ID: " + fakeMerchant);
    window.location = "user-pay.php?merchant_id=" + fakeMerchant;
}
</script>
