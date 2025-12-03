<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Merchant Login</title>
    <link rel="stylesheet" href="merchant.css">
</head>
<body>

<div class="container">
    <h2>Merchant Login</h2>

    <form method="POST" action="merchant-actions.php">
        <input type="hidden" name="action" value="login">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

        <p>New merchant? <a href="merchant-register.php">Register</a></p>
    </form>
</div>

</body>
</html>
