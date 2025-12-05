<?php
// sidebar.php expects:
// $role, $menu, $logout

$role = htmlspecialchars($role ?? "Admin");
$logout = htmlspecialchars($logout ?? "#");
?>

<div class="sidebar">
    <h2><?php echo $role; ?> Panel</h2>

    <?php if (!empty($menu)): ?>
        <?php foreach ($menu as $item): ?>
            <a href="<?php echo htmlspecialchars($item['link']); ?>">
                <?php echo htmlspecialchars($item['label']); ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>

    <a class="logout-btn" href="<?php echo $logout; ?>">Logout</a>
</div>
