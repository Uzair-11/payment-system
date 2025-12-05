<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

// Fetch merchants
$merchants = mysqli_query($conn, "SELECT * FROM merchants ORDER BY id DESC");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/merchants.css">

<div class="dashboard">

<?php
$title = "Manage Merchants";
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

<div style="width:100%">
    <?php include "../components/topbar.php"; ?>

    <div class="table-container">
        <h2>All Merchants</h2>
        <a href="add-merchant.php" class="add-btn">+ Add Merchant</a>
        <br><br>

        <table>
            <tr>
                <th>ID</th>
                <th>Business Name</th>
                <th>Email</th>
                <th>API Key</th>
                <th>Verified</th>
                <th>Actions</th>
            </tr>

            <?php while($m = mysqli_fetch_assoc($merchants)): ?>
            <tr>
                <td><?php echo $m['id']; ?></td>
                <td><?php echo $m['business_name']; ?></td>
                <td><?php echo $m['email']; ?></td>
                <td><?php echo $m['api_key']; ?></td>

                <td>
                    <?php if($m['verified']): ?>
                      <span style="color:#73fa79;font-weight:700;">Verified</span>
                    <?php else: ?>
                      <span style="color:#f6c14b;font-weight:700;">Pending</span>
                    <?php endif; ?>
                </td>

                <td>
                    <a href="edit-merchant.php?id=<?php echo $m['id']; ?>" class="action-edit">Edit</a>
                    <a href="delete-merchant.php?id=<?php echo $m['id']; ?>" class="action-delete">Delete</a>

                    <?php if(!$m['verified']): ?>
                        <a href="verify-merchant.php?id=<?php echo $m['id']; ?>" class="verify">Verify</a>
                    <?php else: ?>
                        <a href="toggle-merchant.php?id=<?php echo $m['id']; ?>" class="suspend">Suspend</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>
</div>
</div>
