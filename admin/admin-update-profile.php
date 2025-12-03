<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");
if (!$conn) { die("DB connection failed"); }

$action = $_POST['action'] ?? '';

if ($action !== 'update_profile') {
    header("Location: admin-profile.php"); exit;
}

$admin_id = intval($_SESSION['admin_id']);
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$new_username = trim($_POST['username'] ?? '');

// fetch admin record
$res = mysqli_query($conn, "SELECT * FROM admins WHERE id = $admin_id LIMIT 1");
$admin = mysqli_fetch_assoc($res);
if (!$admin) { die("Admin not found"); }

// validate current password
$stored = $admin['password'];

// Prefer secure verification. But if the stored password is plain text (legacy), allow fallback:
// First try password_verify (works for hashed passwords)
$ok = false;
if (password_verify($current_password, $stored)) {
    $ok = true;
} else {
    // fallback: direct comparison (legacy plaintext). If matches, rehash and replace with hashed password.
    if ($current_password === $stored) {
        $ok = true;
        $rehash = password_hash($current_password, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE admins SET password = '".mysqli_real_escape_string($conn,$rehash)."' WHERE id = $admin_id");
    }
}

if (!$ok) {
    echo "<script>alert('Current password is incorrect'); window.location='admin-profile.php';</script>";
    exit;
}

// update username if changed
if ($new_username && $new_username !== $admin['username']) {
    // ensure unique username
    $check = mysqli_query($conn, "SELECT id FROM admins WHERE username = '".mysqli_real_escape_string($conn,$new_username)."' AND id != $admin_id LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username already taken'); window.location='admin-profile.php';</script>";
        exit;
    }
    mysqli_query($conn, "UPDATE admins SET username = '".mysqli_real_escape_string($conn,$new_username)."' WHERE id = $admin_id");
    $_SESSION['admin_username'] = $new_username;
}

// update password if provided
if ($new_password) {
    if ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirmation do not match'); window.location='admin-profile.php';</script>";
        exit;
    }
    if (strlen($new_password) < 8) {
        echo "<script>alert('New password must be at least 8 characters'); window.location='admin-profile.php';</script>";
        exit;
    }
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE admins SET password = '".mysqli_real_escape_string($conn,$hashed)."' WHERE id = $admin_id");
}

// Success
echo "<script>alert('Profile updated successfully'); window.location='admin-profile.php';</script>";
exit;
