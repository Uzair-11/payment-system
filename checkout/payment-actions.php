<?php
// payment-actions.php
header('Content-Type: application/json');
session_start();

// connect (adjust if you use password)
$conn = mysqli_connect("localhost","root","","payment_system");
if (!$conn) {
  echo json_encode(['success'=>false,'message'=>'DB connection failed']);
  exit;
}

// read JSON payload
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
$action = $data['action'] ?? '';

if ($action !== 'create_transaction') {
  echo json_encode(['success'=>false,'message'=>'Invalid action']);
  exit;
}

// validate required fields
$merchant_id = intval($data['merchant_id'] ?? 0);
$user_id = intval($data['user_id'] ?? 0);
$amount = floatval($data['amount'] ?? 0);
$currency = $data['currency'] ?? 'USD';
$token = $data['token'] ?? '';
$card_last4 = substr(preg_replace('/\\D/','', $data['card_last4'] ?? ''), -4);
$card_brand = $data['card_brand'] ?? 'Unknown';
$customer_name = mysqli_real_escape_string($conn, $data['customer_name'] ?? '');
$customer_email = mysqli_real_escape_string($conn, $data['customer_email'] ?? '');

if ($amount <= 0 || empty($token)) {
  echo json_encode(['success'=>false,'message'=>'Invalid amount or token']);
  exit;
}

// create tx id
function generate_txid(){
  return 'tx_' . bin2hex(random_bytes(6)) . time();
}
$tx_id = generate_txid();
$status = 'pending'; // initial

// insert transaction (prepared statement)
$stmt = mysqli_prepare($conn, "INSERT INTO transactions (tx_id, merchant_id, user_id, amount, currency, status, token, card_last4, card_brand, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$note = 'Mock transaction created';
mysqli_stmt_bind_param($stmt, 'siidssssss', $tx_id, $merchant_id, $user_id, $amount, $currency, $status, $token, $card_last4, $card_brand, $note);
$ok = mysqli_stmt_execute($stmt);

if (!$ok) {
  echo json_encode(['success'=>false,'message'=>'DB insert failed: '.mysqli_error($conn)]);
  exit;
}

// optionally queue for bank processing: choose bank (simple demo: first bank)
$bankQ = mysqli_query($conn, "SELECT bank_id FROM banks WHERE active=1 LIMIT 1");
if ($bankQ && mysqli_num_rows($bankQ)>0) {
  $b = mysqli_fetch_assoc($bankQ);
  $bank_id = $b['bank_id'];
  $q2 = mysqli_prepare($conn, "INSERT INTO bank_queue (tx_id, bank_id, status) VALUES (?, ?, 'queued')");
  mysqli_stmt_bind_param($q2,'ss',$tx_id,$bank_id);
  mysqli_stmt_execute($q2);
}

// fetch back transaction to return
$res = mysqli_query($conn, "SELECT * FROM transactions WHERE tx_id='".mysqli_real_escape_string($conn,$tx_id)."' LIMIT 1");
$tx = mysqli_fetch_assoc($res);

// For demo: simulate bank auto-approve after short delay (non-blocking: we won't sleep here).
// Instead we return 'pending' and the bank dashboard can approve later (or you can run a simple cron).

echo json_encode(['success'=>true,'transaction'=>$tx]);
exit;
