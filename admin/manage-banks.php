<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

$banks = mysqli_query($conn, "SELECT * FROM banks ORDER BY id DESC");
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/banks.css">

<div class="dashboard">

<?php
$title = "Manage Banks";
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

<div style="width:100%">
    <?php include "../components/topbar.php"; ?>

    <div class="table-container">
        <h2>All Banks</h2>

        <a href="add-bank.php" class="add-bank-btn">+ Add New Bank</a>

        <br><br>

        <table>
            <tr>
                <th>ID</th>
                <th>Bank ID</th>
                <th>Bank Name</th>
                <th>PIN</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php while($b = mysqli_fetch_assoc($banks)): ?>
            <tr>
                <td><?php echo $b['id']; ?></td>
                <td><?php echo $b['bank_id']; ?></td>
                <td><?php echo $b['bank_name']; ?></td>
                <td><?php echo $b['pin']; ?></td>

                <td>
                    <?php if($b['active']): ?>
                        <span style="color:#73fa79;font-weight:700;">Active</span>
                    <?php else: ?>
                        <span style="color:#ff6b6b;font-weight:700;">Inactive</span>
                    <?php endif; ?>
                </td>

                <td>
                    <a href="edit-bank.php?id=<?= $b['id']; ?>" class="action-edit">Edit</a>
                    <a href="toggle-bank.php?id=<?= $b['id']; ?>" class="action-toggle">
                        <?= $b['active'] ? 'Deactivate' : 'Activate'; ?>
                    </a>
                    <a href="delete-bank.php?id=<?= $b['id']; ?>" 
                    class="action-delete"
                    onclick="return confirm('Delete bank? This cannot be undone!');">
                    Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>

</div>
