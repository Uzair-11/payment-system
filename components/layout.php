<?php
if (!isset($title)) $title = "Untitled";
if (!isset($menu)) $menu = [];
if (!isset($role)) $role = $_SESSION['admin_role'] ?? "Admin";
if (!isset($username)) $username = $_SESSION['admin_username'] ?? "Unknown";
$logout = "admin-actions.php?action=logout";
?>

<div class="dashboard">
    <?php include "sidebar.php"; ?>

    <div style="width:100%">
        <?php include "topbar.php"; ?>

        <div class="page-content">
            <?php echo $content; ?>
        </div>
    </div>
</div>
