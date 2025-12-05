<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$user_id = $_SESSION['user_id'];

$user = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM users WHERE id = '$user_id'
"));

$title = "Profile";
$username = $user['name'];
$role = "User";
$logout = "user-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "user-dashboard.php"],
    ["label" => "Transactions", "link" => "user-transactions.php"],
    ["label" => "Linked Banks", "link" => "user-linked-banks.php"],
    ["label" => "Pay", "link" => "user-pay.php"],
    ["label" => "Scan QR", "link" => "user-qr.php"],
    ["label" => "Recharge", "link" => "user-recharge.php"],
    ["label" => "Profile", "link" => "user-profile.php"],
    ["label" => "Settings", "link" => "user-settings.php"],
];

/* ---------------------------------------
   UPDATE PROFILE LOGIC
---------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];

    // Update base details
    mysqli_query($conn, "
        UPDATE users SET 
            name='$name',
            email='$email',
            phone='$phone'
        WHERE id='$user_id'
    ");

    // Update password only if entered
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE id='$user_id'");
    }

    echo "<script>alert('Profile updated successfully!'); window.location='user-profile.php';</script>";
    exit;
}
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/user-profile.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">

<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <h2>My Profile</h2>

    <div class="profile-card">

        <form method="POST">

            <label>Full Name</label>
            <input type="text" name="name" value="<?= $user['name'] ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= $user['email'] ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?= $user['phone'] ?>" required>

            <label>New Password (optional)</label>
            <input type="password" name="password" placeholder="Leave blank to keep same password">

            <button class="submit-btn">Update Profile</button>
        </form>

    </div>

</div>

</div>

</div>
