<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$success = $error = "";

// Handle merchant creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
    $email         = mysqli_real_escape_string($conn, $_POST['email']);
    $api_key       = bin2hex(random_bytes(16)); // Auto-generate API key

    $insert = mysqli_query($conn, "
        INSERT INTO merchants (business_name, email, api_key, verified)
        VALUES ('$business_name', '$email', '$api_key', 0)
    ");

    if ($insert) {
        $success = "Merchant created successfully! API Key: $api_key";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/add-merchant.css">

<div class="dashboard">

<?php
$title = "Add Merchant";
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
        <h2>Add New Merchant</h2>
    </div>

    <?php if($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST">

            <label>Business Name</label>
            <input type="text" name="business_name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <button type="submit" class="btn-primary submit-btn">Create Merchant</button>

        </form>
    </div>

</div>
</div>
</div>
