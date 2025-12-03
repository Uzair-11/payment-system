<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_GET['id']);
$merchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM merchants WHERE id=$id"));

if (!$merchant) { die("Merchant not found"); }
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

<?php
$title = "Edit Merchant";
$username = $_SESSION['admin_username'];
$role = "Admin";
$logout = "admin-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "admin-dashboard.php"],
    ["label" => "Users", "link" => "manage-users.php"],
    ["label" => "Merchants", "link" => "manage-merchants.php"],
    ["label" => "Banks", "link" => "#"],
    ["label" => "Transactions", "link" => "#"],
];

include "../components/sidebar.php";
?>

<div style="width:100%">
    <?php include "../components/topbar.php"; ?>

    <div class="table-container" style="max-width:500px;margin:auto;">
        <h2>Edit Merchant</h2>

        <form method="POST" action="update-merchant.php">

            <input type="hidden" name="id" value="<?php echo $merchant['id']; ?>">

            <label class="muted">Business Name</label>
            <input name="business_name" value="<?php echo $merchant['business_name']; ?>" required>

            <label class="muted">Email</label>
            <input name="email" value="<?php echo $merchant['email']; ?>" required>

            <button class="btn" style="margin-top:12px;">Update Merchant</button>
        </form>
    </div>

</div>
</div>
