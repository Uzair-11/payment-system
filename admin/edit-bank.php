<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { exit; }

$conn = mysqli_connect("localhost","root","","payment_system");

$id = intval($_GET['id']);
$bank = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM banks WHERE id=$id"));
if (!$bank) die("Bank not found");
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

<?php
$title = "Edit Bank";
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
    <h2>Edit Bank</h2>

    <form method="POST" action="update-bank.php">
        <input type="hidden" name="type" value="edit">
        <input type="hidden" name="id" value="<?php echo $bank['id']; ?>">

        <label class="muted">Bank ID</label>
        <input name="bank_id" value="<?php echo $bank['bank_id']; ?>" required>

        <label class="muted">Bank Name</label>
        <input name="bank_name" value="<?php echo $bank['bank_name']; ?>" required>

        <label class="muted">New PIN (optional)</label>
        <input name="pin" type="password" maxlength="6" placeholder="Leave blank to keep same PIN">

        <button class="btn" style="margin-top:12px;">Update Bank</button>
    </form>
</div>

</div>
</div>
