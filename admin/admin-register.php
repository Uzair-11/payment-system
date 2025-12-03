<?php
session_start();
$conn = mysqli_connect("localhost","root","","payment_system");

// Check if any admin already exists
$exists = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM admins"))[0];

// If an admin already exists, block public access
if ($exists > 0) {
    header("Location: admin-login.php?error=register-disabled");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Setup - First Admin</title>
    <link rel="stylesheet" href="../components/dashboard-styles.css">
</head>
<body>

<div style="display:flex;justify-content:center;align-items:center;height:100vh;">
    <div style="
        width:420px;
        background:rgba(255,255,255,0.05);
        padding:30px;
        border-radius:12px;
        backdrop-filter:blur(12px);
        border:1px solid rgba(255,255,255,0.07);
        color:white;
    ">

        <h2 style="text-align:center;color:#b8a4ff;">Create First Admin</h2>
        <p style="text-align:center;">No admin found. Please create the first admin account.</p>

        <form method="POST" action="admin-register-action.php" style="margin-top:20px;">
            
            <label class="muted">Username</label>
            <input name="username" required>

            <label class="muted">Password</label>
            <input name="password" type="password" minlength="8" required>

            <label class="muted">Confirm Password</label>
            <input name="confirm_password" type="password" minlength="8" required>

            <button class="btn" style="margin-top:14px;width:100%;">Create Admin</button>
        </form>

    </div>
</div>

</body>
</html>
