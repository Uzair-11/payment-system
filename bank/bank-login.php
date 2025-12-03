<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bank Login - Step 1</title>
    <link rel="stylesheet" href="bank-login.css">
</head>
<body>

<div class="container">
    <h2>Bank Login</h2>
    <p>Enter your Bank ID to continue</p>

    <form method="POST" action="bank-actions.php">
        <input type="hidden" name="action" value="verify_bank_id">

        <label>Bank ID</label>
        <input type="text" name="bank_id" required placeholder="HDFC001">

        <button type="submit">Next</button>
    </form>
</div>

</body>
</html>
