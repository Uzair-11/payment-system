<?php
// simple receipt viewer by tx_id GET param
session_start();
$conn = mysqli_connect("localhost","root","","payment_system");
$tx = null;
if (isset($_GET['tx'])) {
    $txid = mysqli_real_escape_string($conn, $_GET['tx']);
    $r = mysqli_query($conn, "SELECT * FROM transactions WHERE tx_id='$txid' LIMIT 1");
    if ($r && mysqli_num_rows($r)) $tx = mysqli_fetch_assoc($r);
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"/><title>Receipt</title></head>
<body style="font-family:Arial;background:#071428;color:#e6eef7;padding:30px">
<div style="max-width:800px;margin:0 auto;background:rgba(255,255,255,0.03);padding:20px;border-radius:10px">
    <?php if ($tx): ?>
      <h2>Receipt — <?php echo htmlspecialchars($tx['tx_id']); ?></h2>
      <pre style="color:#cdd3e0"><?php echo json_encode($tx, JSON_PRETTY_PRINT); ?></pre>
      <a href="checkout.php">Back to Checkout</a>
    <?php else: ?>
      <h2>No transaction found</h2>
      <a href="checkout.php">Start a new checkout</a>
    <?php endif; ?>
</div>
</body>
</html>
