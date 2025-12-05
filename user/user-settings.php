<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$title = "Settings";
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

/* ---------------------------------------
   HANDLE SETTINGS UPDATE
---------------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $notif_email = isset($_POST['notif_email']) ? 1 : 0;
    $notif_sms = isset($_POST['notif_sms']) ? 1 : 0;
    $notif_push = isset($_POST['notif_push']) ? 1 : 0;
    $theme = $_POST['theme'];

    // Save to settings table
    mysqli_query($conn, "
        INSERT INTO user_settings (user_id, notif_email, notif_sms, notif_push, theme)
        VALUES ('$user_id', '$notif_email', '$notif_sms', '$notif_push', '$theme')
        ON DUPLICATE KEY UPDATE
        notif_email='$notif_email',
        notif_sms='$notif_sms',
        notif_push='$notif_push',
        theme='$theme'
    ");

    echo "<script>alert('Settings saved!'); window.location='user-settings.php';</script>";
    exit;
}

/* LOAD CURRENT SETTINGS */
$settings = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM user_settings WHERE user_id='$user_id'
")) ?? [
    "notif_email" => 1,
    "notif_sms" => 1,
    "notif_push" => 1,
    "theme" => "dark"
];
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/user-settings.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">

<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <h2>Settings</h2>

    <form method="POST" class="settings-card">

        <h3>Notifications</h3>

        <label class="switch-row">
            <span>Email Notifications</span>
            <input type="checkbox" name="notif_email" <?= $settings['notif_email'] ? 'checked' : '' ?>>
        </label>

        <label class="switch-row">
            <span>SMS Alerts</span>
            <input type="checkbox" name="notif_sms" <?= $settings['notif_sms'] ? 'checked' : '' ?>>
        </label>

        <label class="switch-row">
            <span>Push Notifications</span>
            <input type="checkbox" name="notif_push" <?= $settings['notif_push'] ? 'checked' : '' ?>>
        </label>

        <h3>Appearance</h3>

        <label class="switch-row">
            <span>Theme Mode</span>
            <select name="theme">
                <option value="dark" <?= $settings['theme']=="dark"?'selected':'' ?>>Dark</option>
                <option value="light" <?= $settings['theme']=="light"?'selected':'' ?>>Light</option>
            </select>
        </label>

        <button class="save-btn">Save Settings</button>

    </form>

</div>

</div>

</div>
