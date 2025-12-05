<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_GET['id']);
$bank = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM banks WHERE id=$id"));
if (!$bank) die("Bank not found");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/edit-bank.css">

<div class="dashboard">

<?php
$title = "Edit Bank";
$username = $_SESSION['admin_username'];
$role = "Admin";
$logout = "admin-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "admin-dashboard.php"],
    ["label" => "Users", "link" => "manage-users.php"],
    ["label" => "Merchants", "link" => "manage-merchants.php"],
    ["label" => "Banks", "link" => "manage-banks.php"],
    ["label" => "Transactions", "link" => "manage-transactions.php"]
];

include "../components/sidebar.php";
?>

<div class="page-main">

<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>Edit Bank</h2>
    </div>

    <div class="form-card">
        <form method="POST" action="update-bank.php">
            
            <input type="hidden" name="type" value="edit">
            <input type="hidden" name="id" value="<?php echo $bank['id']; ?>">

            <label>Bank ID</label>
            <input type="text" name="bank_id" value="<?php echo $bank['bank_id']; ?>" required>

            <label>Bank Name</label>
            <input type="text" name="bank_name" value="<?php echo $bank['bank_name']; ?>" required>

            <label>New PIN (optional)</label>
            <input type="password" name="pin" maxlength="6" placeholder="Leave blank to keep existing PIN">

            <button type="submit" class="btn-primary submit-btn">Update Bank</button>

        </form>
    </div>

</div>

</div>
</div>
