<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>

<div class="container">
    <h2>User Login</h2>

    <form method="POST" action="user-actions.php">
        <input type="hidden" name="action" value="login">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

        <p>No account? <a href="user-register.php">Register</a></p>
    </form>
</div>

</body>
</html>
