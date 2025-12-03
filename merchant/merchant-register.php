<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Merchant Registration</title>
    <link rel="stylesheet" href="merchant.css">
</head>
<body>

<div class="container">
    <h2>Merchant Registration</h2>

    <form method="POST" action="merchant-actions.php">
        <input type="hidden" name="action" value="register">

        <label>Business Name</label>
        <input type="text" name="business_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Register Merchant</button>

        <p>Already registered? <a href="merchant-login.php">Login</a></p>
    </form>
</div>

</body>
</html>
