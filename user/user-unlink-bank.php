<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$user_id = $_SESSION['user_id'];

// Validate ID
$link_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($link_id <= 0) {
    echo "<script>alert('Invalid request'); window.location='user-linked-banks.php';</script>";
    exit;
}

// Check if the bank link belongs to this user
$check = mysqli_query($conn, "
    SELECT * FROM user_banks 
    WHERE id = '$link_id' AND user_id = '$user_id'
");

if (mysqli_num_rows($check) == 0) {
    echo "<script>alert('Unauthorized action!'); window.location='user-linked-banks.php';</script>";
    exit;
}

// Delete the linked bank
mysqli_query($conn, "
    DELETE FROM user_banks 
    WHERE id = '$link_id' AND user_id = '$user_id'
");

echo "<script>
        alert('Bank unlinked successfully!');
        window.location='user-linked-banks.php';
      </script>";
exit;
?>
