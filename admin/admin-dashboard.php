<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

// Stats
$total_users = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
$total_merchants = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM merchants"))[0];
$total_banks = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM banks"))[0];
$total_transactions = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM transactions"))[0];

// Recent Transactions
$recent = mysqli_query($conn, "
    SELECT t.*, 
           (SELECT business_name FROM merchants WHERE id = t.merchant_id) AS merchant_name,
           (SELECT name FROM users WHERE id = t.user_id) AS user_name
    FROM transactions t 
    ORDER BY t.created_at DESC 
    LIMIT 10
");
?>

<link rel="stylesheet" href="../components/dashboard-styles.css">

<div class="dashboard">

    <?php
    $title = "Admin Dashboard";
    $username = $_SESSION['admin_username'];
    $role = "Admin";
    $logout = "admin-actions.php?action=logout";

    $menu = [
        ["label" => "Dashboard", "link" => "admin-dashboard.php"],
        ["label" => "Users", "link" => "#"],
        ["label" => "Merchants", "link" => "#"],
        ["label" => "Banks", "link" => "#"],
        ["label" => "Transactions", "link" => "#"],
    ];

    include "../components/sidebar.php";
    ?>

    <div style="width:100%">
        
        <?php include "../components/topbar.php"; ?>

        <!-- Stats Section -->
        <div class="stats-grid">
            <div class="card">
                <h3>Total Users</h3>
                <p style="font-size:26px;margin-top:10px;"><?php echo $total_users; ?></p>
            </div>

            <div class="card">
                <h3>Total Merchants</h3>
                <p style="font-size:26px;margin-top:10px;"><?php echo $total_merchants; ?></p>
            </div>

            <div class="card">
                <h3>Total Banks</h3>
                <p style="font-size:26px;margin-top:10px;"><?php echo $total_banks; ?></p>
            </div>

            <div class="card">
                <h3>Total Transactions</h3>
                <p style="font-size:26px;margin-top:10px;"><?php echo $total_transactions; ?></p>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="table-container">
            <h2>Recent Transactions</h2>
            <table>
                <tr>
                    <th>Tx ID</th>
                    <th>User</th>
                    <th>Merchant</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>

                <?php while($tx = mysqli_fetch_assoc($recent)): ?>
                <tr>
                    <td><?php echo $tx['tx_id']; ?></td>
                    <td><?php echo $tx['user_name'] ?: 'N/A'; ?></td>
                    <td><?php echo $tx['merchant_name'] ?: 'N/A'; ?></td>
                    <td>$<?php echo $tx['amount']; ?></td>
                    <td>
                        <?php 
                        $color = [
                            'pending' => '#f6c14b',
                            'succeeded' => '#73fa79',
                            'declined' => '#ff6b6b',
                            'settled' => '#7c5cff'
                        ][$tx['status']] ?? '#ccc';
                        ?>
                        <span style="color:<?php echo $color; ?>;font-weight:700;">
                            <?php echo ucfirst($tx['status']); ?>
                        </span>
                    </td>
                    <td><?php echo $tx['created_at']; ?></td>
                </tr>
                <?php endwhile; ?>

            </table>
        </div>

    </div>
</div>
