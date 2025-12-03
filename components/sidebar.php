<div class="sidebar">
    <h2><?php echo $role; ?> Panel</h2>

    <?php foreach ($menu as $item): ?>
        <a href="<?php echo $item['link']; ?>"><?php echo $item['label']; ?></a>
    <?php endforeach; ?>

    <a class="logout-btn" href="<?php echo $logout; ?>">Logout</a>
</div>
