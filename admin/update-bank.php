<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");
$type = $_POST['type'];

if ($type == "add") {
    $bank_id = $_POST['bank_id'];
    $bank_name = $_POST['bank_name'];
    $pin = $_POST['pin'];

    mysqli_query($conn, "
        INSERT INTO banks (bank_id, bank_name, pin, active)
        VALUES ('$bank_id', '$bank_name', '$pin', 1)
    ");

    echo "<script>alert('Bank added');window.location='manage-banks.php';</script>";
    exit;
}

if ($type == "edit") {
    $id = intval($_POST['id']);
    $bank_id = $_POST['bank_id'];
    $bank_name = $_POST['bank_name'];
    $pin = $_POST['pin'];

    if ($pin != "") {
        // New PIN set
        mysqli_query($conn, "
            UPDATE banks 
            SET bank_id='$bank_id', bank_name='$bank_name', pin='$pin'
            WHERE id=$id
        ");
    } else {
        // Keep old PIN
        mysqli_query($conn, "
            UPDATE banks 
            SET bank_id='$bank_id', bank_name='$bank_name'
            WHERE id=$id
        ");
    }

    echo "<script>alert('Bank updated');window.location='manage-banks.php';</script>";
    exit;
}
