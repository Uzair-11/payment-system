<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "payment_system");


// -------------------------------------------------------------
// DYNAMIC PAGE VARIABLES
// -------------------------------------------------------------
$title = "Admin Dashboard";
$username = $_SESSION['admin_username'];
$role = $_SESSION['admin_role'] ?? "Admin";
$logout = "admin-actions.php?action=logout";


// -------------------------------------------------------------
// DYNAMIC MENU (can easily be role-based later)
// -------------------------------------------------------------
$menu = [
    ["label" => "Dashboard", "link" => "admin-dashboard.php"],
    ["label" => "Users", "link" => "manage-users.php"],
    ["label" => "Merchants", "link" => "manage-merchants.php"],
    ["label" => "Banks", "link" => "manage-banks.php"],
    ["label" => "Transactions", "link" => "manage-transactions.php"],
];


// -------------------------------------------------------------
// DASHBOARD STATISTICS
// -------------------------------------------------------------
function countTable($conn, $table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) FROM `$table`");
    return mysqli_fetch_row($result)[0] ?? 0;
}

$total_users         = countTable($conn, "users");
$total_merchants     = countTable($conn, "merchants");
$total_banks         = countTable($conn, "banks");
$total_transactions  = countTable($conn, "transactions");


// -------------------------------------------------------------
// RECENT TRANSACTIONS (dynamic join)
// -------------------------------------------------------------
$recent = mysqli_query($conn, "
    SELECT 
        t.tx_id,
        t.amount,
        t.status,
        t.created_at,
        u.name AS user_name,
        m.business_name AS merchant_name
    FROM transactions t
    LEFT JOIN users u ON u.id = t.user_id
    LEFT JOIN merchants m ON m.id = t.merchant_id
    ORDER BY t.created_at DESC
    LIMIT 10
");

?>
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/dashboard.css">

<div class="dashboard">

    <!-- SIDEBAR -->
    <?php include "../components/sidebar.php"; ?>


    <div style="width:100%">

        <!-- TOPBAR -->
        <?php include "../components/topbar.php"; ?>


        <!-- ==============================
             DASHBOARD CONTENT
        ================================ -->
        <div class="stats-grid">

            <div class="card">
                <h3>Total Users</h3>
                <p class="stat-value"><?php echo $total_users; ?></p>
            </div>

            <div class="card">
                <h3>Total Merchants</h3>
                <p class="stat-value"><?php echo $total_merchants; ?></p>
            </div>

            <div class="card">
                <h3>Total Banks</h3>
                <p class="stat-value"><?php echo $total_banks; ?></p>
            </div>

            <div class="card">
                <h3>Total Transactions</h3>
                <p class="stat-value"><?php echo $total_transactions; ?></p>
            </div>

        </div>



        <!-- ==============================
             RECENT TRANSACTIONS TABLE
        ================================ -->
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

                <?php while ($tx = mysqli_fetch_assoc($recent)): ?>
                <tr>
                    <td><?php echo $tx['tx_id']; ?></td>
                    <td><?php echo $tx['user_name'] ?? 'N/A'; ?></td>
                    <td><?php echo $tx['merchant_name'] ?? 'N/A'; ?></td>
                    <td>$<?php echo $tx['amount']; ?></td>
                    <td>
                        <?php
                        $colors = [
                            'pending'   => '#f6c14b',
                            'succeeded' => '#73fa79',
                            'declined'  => '#ff6b6b',
                            'settled'   => '#7c5cff'
                        ];
                        $statusColor = $colors[$tx['status']] ?? '#ccc';
                        ?>

                        <span style="color:<?php echo $statusColor; ?>; font-weight:700;">
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
