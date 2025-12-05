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

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/edit-merchant.css">

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
    ["label" => "Banks", "link" => "manage-banks.php"],
    ["label" => "Transactions", "link" => "manage-transactions.php"],
];

include "../components/sidebar.php";
?>

<div class="page-main">
    <?php include "../components/topbar.php"; ?>

    <div class="page-content">

        <div class="page-header">
            <h2>Edit Merchant</h2>
        </div>

        <div class="form-card">
            <form method="POST" action="update-merchant.php">

                <input type="hidden" name="id" value="<?php echo $merchant['id']; ?>">

                <label>Business Name</label>
                <input type="text" name="business_name" value="<?php echo $merchant['business_name']; ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo $merchant['email']; ?>" required>

                <button class="btn-primary submit-btn" type="submit">Update Merchant</button>
            </form>
        </div>

    </div>
</div>

</div>
