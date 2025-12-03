<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

<?php
$title = "Add New Bank";
$username = $_SESSION['admin_username'];
$role = "Admin";
$logout = "admin-actions.php?action=logout";
$menu = [
    ["label" => "Dashboard", "link" => "admin-dashboard.php"],
    ["label" => "Banks", "link" => "manage-banks.php"]
];
include "../components/sidebar.php";
?>

<div style="width:100%">
<?php include "../components/topbar.php"; ?>

<div class="table-container" style="max-width:500px;margin:auto;">
    <h2>Add Bank</h2>

    <form method="POST" action="update-bank.php">
        <input type="hidden" name="type" value="add">

        <label class="muted">Bank ID (Unique)</label>
        <input name="bank_id" required>

        <label class="muted">Bank Name</label>
        <input name="bank_name" required>

        <label class="muted">PIN</label>
        <input name="pin" type="password" maxlength="6" required>

        <button class="btn" style="margin-top:12px;">Add Bank</button>
    </form>
</div>

</div>
</div>
