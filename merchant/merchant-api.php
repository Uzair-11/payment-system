<?php
session_start();
if (!isset($_SESSION['merchant_logged_in'])) {
    header("Location: merchant-login.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","payment_system");

// Merchant info
$merchant_id = $_SESSION['merchant_id'];
$business_name = $_SESSION['merchant_business_name'];

// Fetch API Key
$q = mysqli_query($conn, "SELECT api_key FROM merchants WHERE id='$merchant_id'");
$api = mysqli_fetch_assoc($q);
$api_key = $api['api_key'];

$title = "API Keys";
$username = $business_name;
$role = "Merchant";
$logout = "merchant-actions.php?action=logout";

$menu = [
    ["label" => "Dashboard", "link" => "merchant-dashboard.php"],
    ["label" => "Transactions", "link" => "merchant-transactions.php"],
    ["label" => "Payouts", "link" => "merchant-payouts.php"],
    ["label" => "API Keys", "link" => "merchant-api.php"],
    ["label" => "Settings", "link" => "merchant-settings.php"],
];
?>

<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/merchant-api.css">

<div class="dashboard">

<?php include "../components/sidebar.php"; ?>

<div class="page-main">
<?php include "../components/topbar.php"; ?>

<div class="page-content">

    <div class="page-header">
        <h2>API Keys</h2>
    </div>

    <div class="api-card">

        <label>Current API Key</label>

        <div class="api-row">
            <input type="text" id="apiKey" value="<?= $api_key ?>" readonly>
            <button class="copy-btn" onclick="copyAPI()">Copy</button>
        </div>

        <form method="POST" action="merchant-api-actions.php">
            <input type="hidden" name="action" value="regenerate_api">

            <button class="regen-btn">Regenerate API Key</button>
        </form>

        <h3 class="section-title">Base API Endpoint</h3>
        <div class="endpoint-box">
            <?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] ?>/payment-system/api/
        </div>

        <h3 class="section-title">Usage Example (cURL)</h3>

        <pre class="code-block">
curl -X POST \
  -H "API-KEY: <?= $api_key ?>" \
  -d "amount=199&user_id=5" \
  <?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] ?>/payment-system/api/create-payment.php
        </pre>

    </div>

</div>
</div>
</div>

<script>
function copyAPI() {
    let input = document.getElementById("apiKey");
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value);
    alert("API Key Copied!");
}
</script>
