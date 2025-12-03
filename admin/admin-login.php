<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="container">
    <h2>Admin Login</h2>

    <?php
    // Show message if admin registration is disabled
    if (isset($_GET['error']) && $_GET['error'] == "register-disabled") {
        echo "<p style='color:#ff6b6b;text-align:center;'>Admin registration is disabled.</p>";
    }

    // Show message if login failed
    if (isset($_GET['error']) && $_GET['error'] == "invalid") {
        echo "<p style='color:#ff6b6b;text-align:center;'>Invalid username or password.</p>";
    }

    // Message after logout
    if (isset($_GET['logout'])) {
        echo "<p style='color:#73fa79;text-align:center;'>Logged out successfully.</p>";
    }
    ?>

    <form method="POST" action="admin-actions.php">
        <input type="hidden" name="action" value="login">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div style="text-align:center;margin-top:15px;">
        <a href="admin-register.php" style="color:#7c5cff;text-decoration:none;">
            Create First Admin
        </a>
    </div>

</div>

</body>
</html>
