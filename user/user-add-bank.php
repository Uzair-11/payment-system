<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$title = "Add Bank Account";
$username = $user_name;
$role = "User";
$logout = "user-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "user-dashboard.php"],
    ["label" => "Transactions", "link" => "user-transactions.php"],
    ["label" => "Linked Banks", "link" => "user-linked-banks.php"],
    ["label" => "Pay", "link" => "user-pay.php"],
    ["label" => "Scan QR", "link" => "user-qr.php"],
    ["label" => "Recharge", "link" => "user-recharge.php"],
    ["label" => "Profile", "link" => "user-profile.php"],
    ["label" => "Settings", "link" => "user-settings.php"],
];

/* Fetch all available banks */
$bank_list = mysqli_query($conn, "SELECT * FROM banks WHERE active = 1 ORDER BY bank_name ASC");

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $bank_id = $_POST['bank_id'];
    $pin = $_POST['pin'];

    // Validate bank exists & PIN matches
    $bank = mysqli_query($conn, "
        SELECT * FROM banks 
        WHERE id='$bank_id' AND pin='$pin'
    ");

    if (mysqli_num_rows($bank) == 0) {
        echo "<script>alert('Invalid bank or PIN!');</script>";
    } else {

        // Check if already linked
        $already = mysqli_query($conn, "
            SELECT * FROM user_banks 
            WHERE user_id='$user_id' AND bank_id='$bank_id'
        ");

        if (mysqli_num_rows($already) > 0) {
            echo "<script>alert('This bank is already linked.');</script>";
        } else {

            mysqli_query($conn, "
                INSERT INTO user_banks(user_id, bank_id) 
                VALUES ('$user_id', '$bank_id')
            ");

            echo "<script>
                    alert('Bank linked successfully!');
                    window.location='user-linked-banks.php';
                </script>";
            exit;
        }
    }
}
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/user-add-bank.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">

<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>Add Bank Account</h2>
        <a href="user-linked-banks.php" class="btn-secondary">Back</a>
    </div>

    <div class="form-card">

        <form method="POST">

            <label>Select Bank</label>
            <select name="bank_id" required>
                <option value="">-- Choose Bank --</option>
                <?php while ($b = mysqli_fetch_assoc($bank_list)): ?>
                    <option value="<?= $b['id'] ?>">
                        <?= $b['bank_name'] ?> (<?= $b['bank_id'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Enter Bank PIN</label>
            <input type="password" name="pin" maxlength="6" required placeholder="Bank PIN">

            <button class="submit-btn">Add Bank</button>
        </form>

    </div>

</div>
</div>

</div>
