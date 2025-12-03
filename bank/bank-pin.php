<?php
session_start();

if (!isset($_SESSION['bank_id'])) {
    header("Location: bank-login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enter PIN</title>
    <link rel="stylesheet" href="bank-login.css">
</head>
<body>

<div class="container">
    <h2>Enter PIN</h2>
    <p>Bank ID: <?php echo $_SESSION['bank_id']; ?></p>

    <form method="POST" action="bank-actions.php">
        <input type="hidden" name="action" value="verify_pin">

        <label>PIN</label>
        <input type="password" name="pin" maxlength="6" required placeholder="****">

        <button type="submit">Verify PIN</button>
    </form>
</div>

</body>
</html>
