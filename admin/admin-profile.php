<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

// load admin record
$admin_id = intval($_SESSION['admin_id']);
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admins WHERE id = $admin_id"));

if (!$admin) {
    echo "Admin account not found."; exit;
}

$title = "Admin Profile";
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

?>
<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div style="width:100%">
    <?php include "../components/topbar.php"; ?>

    <div class="table-container" style="max-width:640px;margin:auto;">
        <h2>Admin Profile</h2>

        <div style="background:rgba(255,255,255,0.02);padding:18px;border-radius:10px;">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['username']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($admin['role']); ?></p>
            <p><small class="muted">Change your username or password below.</small></p>
        </div>

        <hr style="border:none;border-top:1px solid rgba(255,255,255,0.03);margin:18px 0" />

        <form method="POST" action="admin-update-profile.php" style="max-width:520px;margin:auto;">
            <input type="hidden" name="action" value="update_profile">
            <label class="muted">New Username</label>
            <input name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>

            <label class="muted">Current Password</label>
            <input type="password" name="current_password" placeholder="Enter current password" required>

            <label class="muted">New Password</label>
            <input type="password" name="new_password" placeholder="New password (min 8 chars)" minlength="8">

            <label class="muted">Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password">

            <div style="display:flex;gap:10px;margin-top:12px;">
                <button class="btn" type="submit">Save Changes</button>
                <a href="admin-dashboard.php" class="btn" style="background:rgba(255,255,255,0.04);color:#fff;text-decoration:none;padding:10px;border-radius:8px;">Cancel</a>
            </div>
            <p style="margin-top:10px;" class="muted">If you only change username, leave new password fields blank.</p>
        </form>
    </div>
</div>
</div>
