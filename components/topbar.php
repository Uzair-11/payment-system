<?php
// topbar.php expects:
// $title, $username

$title    = htmlspecialchars($title ?? "Untitled Page");
$username = htmlspecialchars($username ?? "Unknown");
?>

<div class="topbar">
    <h2><?php echo $title; ?></h2>

    <div>
        Logged in as:
        <strong><?php echo $username; ?></strong>
    </div>
</div>
