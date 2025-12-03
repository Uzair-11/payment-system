<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

// get user id
$id = intval($_GET['id']);
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$id"));

if (!$user) {
    die("User not found");
}
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

<?php
$title = "Edit User";
$username = $_SESSION['admin_username'];
$role = "Admin";
$logout = "admin-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "admin-dashboard.php"],
    ["label" => "Users", "link" => "manage-users.php"],
    ["label" => "Merchants", "link" => "#"],
    ["label" => "Banks", "link" => "#"],
    ["label" => "Transactions", "link" => "#"],
];

include "../components/sidebar.php";
?>

<div style="width:100%">
    <?php include "../components/topbar.php"; ?>

    <div class="table-container" style="max-width:500px;margin:auto;">
        <h2>Edit User</h2>

        <form method="POST" action="update-user.php">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <label style="color:#fff;">Name</label>
            <input style="width:100%;padding:10px;border-radius:8px;" 
                   name="name" value="<?php echo $user['name']; ?>" required>

            <label style="color:#fff;">Email</label>
            <input style="width:100%;padding:10px;border-radius:8px;" 
                   name="email" value="<?php echo $user['email']; ?>" required>

            <label style="color:#fff;">Phone</label>
            <input style="width:100%;padding:10px;border-radius:8px;" 
                   name="phone" value="<?php echo $user['phone']; ?>" required>

            <button style="margin-top:12px;background:#7c5cff;border:none;padding:12px 20px;border-radius:10px;color:#fff;font-weight:600;">
                Update User
            </button>
        </form>
    </div>

</div>

</div>
