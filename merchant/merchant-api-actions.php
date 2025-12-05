<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$action = $_POST['action'] ?? null;
$merchant_id = $_SESSION['merchant_id'];

if ($action === "regenerate_api") {

    // Generate new API key
    $new_key = bin2hex(random_bytes(16)); // secure

    mysqli_query($conn, "
        UPDATE merchants SET api_key='$new_key' WHERE id='$merchant_id'
    ");

    header("Location: merchant-api.php?regen=1");
    exit;
}

header("Location: merchant-api.php");
exit;
