<?php
// minimal session user/merchant simulation for demo
session_start();
// optionally set demo user/merchant if not logged in
if (!isset($_SESSION['user_id'])) {
    // demo user id 1 (exists from sample SQL) - optional
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'John Doe';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Checkout — AtlasPay (Mock)</title>
<link rel="stylesheet" href="checkout.css">
</head>
<body>
<div class="wrap">
  <div class="panel">
    <h1>AtlasPay — Mock Checkout</h1>
    <p class="muted">Demo checkout. No real payments. Card tokenization is simulated client-side.</p>

    <div class="product">
      <div class="thumb">AP</div>
      <div>
        <div class="muted">Atlas Project — Pro License</div>
        <div style="font-weight:700">$49.00</div>
      </div>
    </div>

    <form id="checkout-form" autocomplete="off">
      <label>Full name</label>
      <input id="name" type="text" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required>

      <label>Email</label>
      <input id="email" type="email" value="" required>

      <label>Card number</label>
      <input id="cardnumber" type="text" inputmode="numeric" placeholder="4242 4242 4242 4242" maxlength="19" required>

      <div class="row">
        <div class="small">
          <label>Expiry (MM/YY)</label>
          <input id="exp" type="text" maxlength="5" placeholder="08/29" required>
        </div>
        <div class="small">
          <label>CVC</label>
          <input id="cvc" type="text" maxlength="4" placeholder="123" required>
        </div>
      </div>

      <input id="amount" type="hidden" value="49.00">
      <input id="merchant_id" type="hidden" value="1"> <!-- demo merchant id -->

      <div id="msg" class="muted" style="margin-top:10px"></div>

      <button id="pay" class="btn" type="submit">Pay $49.00</button>
    </form>
  </div>

  <aside class="panel" id="side">
    <div id="receipt" hidden>
      <h3>Receipt</h3>
      <pre id="receipt-body" style="color:#c8d3e8"></pre>
      <a id="download" class="btn" href="#">Download JSON</a>
    </div>
    <div id="processing" hidden class="muted">Processing transaction…</div>
  </aside>
</div>

<script>
// UTILITIES
function el(id){return document.getElementById(id)}
function luhnCheck(cc){
  const digits = cc.replace(/\D/g,'').split('').reverse().map(n=>parseInt(n,10));
  let sum=0;
  for(let i=0;i<digits.length;i++){
    let d = digits[i];
    if(i%2===1){d*=2; if(d>9) d-=9}
    sum+=d;
  }
  return sum%10===0;
}
function formatCard(v){return v.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim();}
function guessBrand(number){
  const n = number+'';
  if(/^4/.test(n)) return 'Visa';
  if(/^5[1-5]/.test(n)) return 'Mastercard';
  if(/^3[47]/.test(n)) return 'American Express';
  if(/^6/.test(n)) return 'Discover';
  return 'Unknown';
}
// Fake tokenization
function createFakeToken(cardNumber){
  return 'tok_' + btoa(cardNumber.slice(-8) + ':' + Date.now()).replace(/=+$/,'').slice(0,24);
}

// FORM HANDLING
const form = el('checkout-form');
const msg = el('msg');
const receiptEl = el('receipt');
const receiptBody = el('receipt-body');
const processing = el('processing');
const downloadBtn = el('download');

document.getElementById('cardnumber').addEventListener('input', e=>{
  e.target.value = formatCard(e.target.value);
});

form.addEventListener('submit', async (e)=>{
  e.preventDefault();
  msg.textContent = '';
  receiptEl.hidden = true;
  processing.hidden = true;

  const name = el('name').value.trim();
  const email = el('email').value.trim();
  const cardnum = el('cardnumber').value.replace(/\s+/g,'');
  const exp = el('exp').value.trim();
  const cvc = el('cvc').value.trim();
  const amount = el('amount').value;
  const merchant_id = el('merchant_id').value;

  if(!name){ msg.textContent='Enter name'; return; }
  if(!/.+@.+\..+/.test(email)){ msg.textContent='Enter valid email'; return; }
  if(!/^\d{12,19}$/.test(cardnum)){ msg.textContent='Enter valid card number'; return; }
  if(!luhnCheck(cardnum)){ msg.textContent='Card failed Luhn check'; return; }
  if(!/^\d{2}\/\d{2}$/.test(exp)){ msg.textContent='Expiry must be MM/YY'; return; }
  if(!/^\d{3,4}$/.test(cvc)){ msg.textContent='Enter valid CVC'; return; }

  // simulate tokenization
  msg.textContent = 'Tokenizing card (mock)…';
  const token = createFakeToken(cardnum);
  const last4 = cardnum.slice(-4);
  const brand = guessBrand(cardnum);

  // send to server (AJAX)
  processing.hidden = false;
  msg.textContent = 'Submitting transaction to gateway (mock)…';

  try {
    const res = await fetch('payment-actions.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({
        action: 'create_transaction',
        merchant_id: merchant_id,
        user_id: <?php echo intval($_SESSION['user_id'] ?? 0); ?>,
        amount: amount,
        currency: 'USD',
        token: token,
        card_last4: last4,
        card_brand: brand,
        customer_name: name,
        customer_email: email
      })
    });

    const data = await res.json();
    processing.hidden = true;

    if(data.success){
      msg.textContent = 'Payment recorded (mock). Transaction status: ' + data.transaction.status;
      receiptEl.hidden = false;
      receiptBody.textContent = JSON.stringify(data.transaction, null, 2);
      // download
      const blob = new Blob([JSON.stringify(data.transaction, null, 2)], {type:'application/json'});
      const url = URL.createObjectURL(blob);
      downloadBtn.href = url;
      downloadBtn.download = 'receipt-'+data.transaction.tx_id+'.json';
    } else {
      msg.textContent = 'Error: ' + (data.message || 'Unknown error');
    }
  } catch (err) {
    processing.hidden = true;
    msg.textContent = 'Network error: ' + err.message;
  }
});
</script>
</body>
</html>
