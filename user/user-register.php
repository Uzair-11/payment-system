<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>User Register</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>

<div class="container">
    <h2>Create User Account</h2>

    <form method="POST" action="user-actions.php">
        <input type="hidden" name="action" value="register">

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone</label>
        <input type="text" name="phone" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>

        <p>Already have an account? <a href="user-login.php">Login</a></p>
    </form>
</div>

</body>
</html>
