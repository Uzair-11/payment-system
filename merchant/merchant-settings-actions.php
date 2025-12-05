<?php
session_start();
$conn = mysqli_connect("localhost","root","","payment_system");

if (!isset($_SESSION['merchant_logged_in'])) { exit; }

$action = $_POST['action'] ?? null;
$merchant_id = $_SESSION['merchant_id'];

/* ---------------------------------------
   UPDATE PROFILE
---------------------------------------- */

if ($action === "update_profile") {

    $business_name = $_POST['business_name'];
    $email = $_POST['email'];
    $webhook = $_POST['webhook_url'] ?? null;

    mysqli_query($conn, "
        UPDATE merchants 
        SET business_name='$business_name',
            email='$email',
            webhook_url='$webhook'
        WHERE id='$merchant_id'
    ");

    $_SESSION['merchant_business_name'] = $business_name;

    header("Location: merchant-settings.php?updated=1");
    exit;
}

/* ---------------------------------------
   CHANGE PASSWORD
---------------------------------------- */

if ($action === "change_password") {

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Wrong: new passwords mismatch
    if ($new !== $confirm) {
        die("<script>alert('New passwords do not match!'); window.location='merchant-settings.php';</script>");
    }

    // Fetch old password
    $q = mysqli_query($conn, "SELECT password FROM merchants WHERE id='$merchant_id'");
    $row = mysqli_fetch_assoc($q);

    if (!password_verify($old, $row['password'])) {
        die("<script>alert('Incorrect current password'); window.location='merchant-settings.php';</script>");
    }

    // Update password
    $hashed = password_hash($new, PASSWORD_BCRYPT);

    mysqli_query($conn, "
        UPDATE merchants SET password='$hashed' WHERE id='$merchant_id'
    ");

    die("<script>alert('Password updated successfully!'); window.location='merchant-settings.php';</script>");
}

header("Location: merchant-settings.php");
exit;
