<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

// Handle form submit
$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $insert = mysqli_query($conn, "
        INSERT INTO users (name, email, phone, password) 
        VALUES ('$name', '$email', '$phone', '$password')
    ");

    if ($insert) {
        $success = "User created successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/add-user.css">

<div class="dashboard">

<?php
$title = "Add User";
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

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>Add New User</h2>
    </div>

    <?php if($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST">

            <label>Name</label>
            <input type="text" name="name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Phone</label>
            <input type="text" name="phone" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn-primary submit-btn">Create User</button>

        </form>
    </div>

</div>
</div>
</div>
