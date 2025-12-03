<?php
session_start();

if (!isset($_SESSION['bank_pin_verified'])) {
    header("Location: bank-login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enter OTP</title>
    <link rel="stylesheet" href="bank-login.css">
</head>
<body>

<div class="container">
    <h2>OTP Verification</h2>
    <p>We sent a 6-digit OTP to your registered mobile/email.</p>

    <form method="POST" action="bank-actions.php">
        <input type="hidden" name="action" value="verify_otp">

        <label>OTP</label>
        <input type="text" name="otp" maxlength="6" required placeholder="123456">

        <button type="submit">Verify OTP</button>
    </form>
</div>

</body>
</html>
