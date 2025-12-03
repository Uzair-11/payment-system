<?php
session_start();

$conn = mysqli_connect("localhost","root","","payment_system");
if (!$conn) { die("Database Error"); }

// If admin already exists, block any new registration attempts
$exists = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM admins"))[0];
if ($exists > 0) {
    header("Location: admin-login.php?error=register-disabled");
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {
    echo "<script>alert('Passwords do not match');window.location='admin-register.php';</script>";
    exit;
}

// Ensure username not empty and no admin exists already
if ($username === "") {
    echo "<script>alert('Username cannot be empty');window.location='admin-register.php';</script>";
    exit;
}

// Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Insert first admin
mysqli_query($conn, "
    INSERT INTO admins (username, password, role)
    VALUES ('$username', '$hashed', 'superadmin')
");

// Redirect to login
echo "<script>
alert('Admin account created successfully! You can now log in.');
window.location='admin-login.php';
</script>";
exit;
