<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

// Fetch all users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

// Page variables
$title = "Manage Users";
$username = $_SESSION['admin_username'];
$role = $_SESSION['admin_role'] ?? "Admin";
$logout = "admin-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "admin-dashboard.php"],
    ["label" => "Users", "link" => "manage-users.php"],
    ["label" => "Merchants", "link" => "manage-merchants.php"],
    ["label" => "Banks", "link" => "manage-banks.php"],
    ["label" => "Transactions", "link" => "manage-transactions.php"],
];
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/users.css">

<div class="dashboard">

    <?php include "../components/sidebar.php"; ?>

    <div class="page-main">

        <?php include "../components/topbar.php"; ?>

        <div class="page-content">

            <div class="page-header">
                <h2>All Users</h2>
                <a href="add-user.php" class="btn-primary">+ Add User</a>
            </div>

            <div class="table-wrapper">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php while($u = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo $u['name']; ?></td>
                            <td><?php echo $u['email']; ?></td>
                            <td><?php echo $u['phone']; ?></td>
                            <td><?php echo $u['created_at']; ?></td>

                            <td class="actions">
                                <a href="edit-user.php?id=<?php echo $u['id']; ?>" class="action-edit">Edit</a>
                                <a href="delete-user.php?id=<?php echo $u['id']; ?>" 
                                   class="action-delete"
                                   onclick="return confirm('Delete this user?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>
