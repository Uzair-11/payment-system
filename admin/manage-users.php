<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

// Fetch all users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

<?php
$title = "Manage Users";
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

    <div class="table-container">
        <h2>All Users</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>

            <?php while($u = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?php echo $u['id']; ?></td>
                <td><?php echo $u['name']; ?></td>
                <td><?php echo $u['email']; ?></td>
                <td><?php echo $u['phone']; ?></td>
                <td><?php echo $u['created_at']; ?></td>

                <td>
                    <a href="edit-user.php?id=<?php echo $u['id']; ?>" 
                       style="color:#7c5cff;font-weight:600;">Edit</a> | 
                    <a href="delete-user.php?id=<?php echo $u['id']; ?>" 
                       style="color:#ff5b5b;font-weight:600;"
                       onclick="return confirm('Delete this user?');">Delete</a>
                </td>

            </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>

</div>
