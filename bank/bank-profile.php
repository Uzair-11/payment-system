<?php
session_start();
if (!isset($_SESSION['bank_logged_in'])) {
    header("Location: bank-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$bank_id = $_SESSION['bank_id'];

// Fetch bank details
$bank = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM banks WHERE bank_id = '$bank_id'
"));

if (!$bank) {
    die("Bank not found.");
}

$title = "Bank Profile";
$username = $bank_id;
$role = "Bank";
$logout = "bank-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "bank-dashboard.php"],
    ["label" => "Approve Transactions", "link" => "bank-approvals.php"],
    ["label" => "Pending Settlements", "link" => "bank-settlements.php"],
    ["label" => "Bank Profile", "link" => "bank-profile.php"]
];
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/bank-profile.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>Bank Profile</h2>
    </div>

    <div class="form-card">

        <form method="POST" action="update-bank-profile.php">

            <input type="hidden" name="id" value="<?= $bank['id']; ?>">

            <label>Bank ID</label>
            <input type="text" name="bank_id" value="<?= $bank['bank_id']; ?>" required>

            <label>Bank Name</label>
            <input type="text" name="bank_name" value="<?= $bank['bank_name']; ?>" required>

            <label>Change PIN (optional)</label>
            <input type="password" name="pin" maxlength="6" placeholder="Leave blank to keep old PIN">

            <button class="btn-primary submit-btn" type="submit">Update Profile</button>
        </form>

    </div>

</div>
</div>
</div>
