<?php
session_start();
include("db.php");

if(!isset($_SESSION['id'])){
    header("Location: loginpage.html");
    exit();
}

$user_id = $_SESSION['id'];

// Get order details passed from cart.php form
$customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
$phone         = isset($_POST['phone'])         ? $_POST['phone']         : '';
$address       = isset($_POST['address'])       ? $_POST['address']       : '';

// If nothing was posted, redirect back to cart
if(empty($customer_name)){
    header("Location: cart.php");
    exit();
}

// Fetch cart items to show order summary
$result = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
$total  = 0;
$items  = [];
while($row = mysqli_fetch_assoc($result)){
    $row['item_total'] = $row['price'] * $row['quantity'];
    $total += $row['item_total'];
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment – Shine Utensils</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Site CSS -->
  <link rel="stylesheet" href="style.css" />

  <style>
    /* ── Payment page local styles ── */
    .payment-wrapper {
      max-width: 820px;
      margin: 2.5rem auto;
      padding: 0 1rem 3rem;
    }

    /* Step breadcrumb */
    .step-trail {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.85rem;
      color: #6c757d;
      margin-bottom: 1.5rem;
    }
    .step-trail .step { display: flex; align-items: center; gap: 0.35rem; }
    .step-trail .step.done  { color: #198754; }
    .step-trail .step.active{ color: var(--secondary-color); font-weight: 600; }
    .step-trail .sep { color: #adb5bd; }

    /* Order summary box */
    .order-summary {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 6px;
      padding: 1.25rem 1.5rem;
      margin-bottom: 1.5rem;
    }
    .order-summary h5 {
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 1rem;
      border-bottom: 2px solid var(--accent-color);
      padding-bottom: 0.4rem;
    }
    .order-summary table td, .order-summary table th {
      padding: 0.4rem 0.6rem;
      font-size: 0.9rem;
    }
    .total-row td { font-weight: 700; border-top: 2px solid #dee2e6 !important; }

    /* Payment method card */
    .method-card {
      border: 2px solid #dee2e6;
      border-radius: 8px;
      padding: 1rem 1.25rem;
      cursor: pointer;
      transition: border-color 0.25s, box-shadow 0.25s;
      margin-bottom: 1rem;
    }
    .method-card:hover      { border-color: var(--primary-color); }
    .method-card.selected   {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 3px rgba(231,76,60,.15);
    }
    .method-card input[type="radio"] { accent-color: var(--secondary-color); }
    .method-card label { cursor: pointer; font-weight: 600; font-size: 1rem; }
    .method-card .method-desc { font-size: 0.82rem; color: #6c757d; margin: 0.25rem 0 0 1.6rem; }

    /* Card detail fields */
    #card-fields {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 6px;
      padding: 1.25rem 1.5rem;
      margin-top: -0.25rem;
      margin-bottom: 1rem;
      display: none;         /* shown via JS */
    }
    #card-fields .form-label { font-size: 0.88rem; font-weight: 500; }
    .card-brand-icons { font-size: 1.5rem; gap: 0.5rem; }
    .card-brand-icons i { color: #6c757d; }

    /* COD note */
    #cod-note {
      background: #fff3cd;
      border: 1px solid #ffc107;
      border-radius: 6px;
      padding: 0.85rem 1.25rem;
      font-size: 0.88rem;
      margin-top: -0.25rem;
      margin-bottom: 1rem;
      display: none;
    }

    /* Confirm button */
    .btn-pay {
      background-color: var(--secondary-color);
      color: #fff;
      border: none;
      padding: 0.75rem 2rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: background-color 0.25s, transform 0.2s;
      border-radius: 4px;
    }
    .btn-pay:hover {
      background-color: var(--accent-color);
      color: #fff;
      transform: translateY(-2px);
    }
    .btn-pay:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    /* Success screen */
    #success-screen {
      display: none;
      text-align: center;
      padding: 3rem 1rem;
    }
    #success-screen .success-icon { font-size: 4rem; color: #198754; margin-bottom: 1rem; }
    #success-screen h3 { color: var(--primary-color); font-weight: 700; }
    #success-screen p  { color: #6c757d; }

    /* Mini spinner */
    .spinner-sm {
      display: inline-block;
      width: 1rem; height: 1rem;
      border: 2px solid rgba(255,255,255,.4);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin .6s linear infinite;
      vertical-align: middle;
      margin-left: 0.4rem;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── MOCK card input decoration ── */
    .card-num-wrap { position: relative; }
    .card-num-wrap .card-chip { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 1.1rem; color: #adb5bd; }
  </style>
</head>

<body>

  <!-- ===== Navbar ===== -->
  <nav class="navbar navbar-expand-lg navbar-dark custom-navbar sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.html">
        <img src="images/logo.jpg" alt="Logo" class="logo-img me-2" />
        Shine Utensils
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="product1.php">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.html">Contact US</a></li>
          <li class="nav-item"><a class="nav-link" href="aboutus.html">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="cart.php">🛒 Cart</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ===== Payment Page ===== -->
  <div class="payment-wrapper">

    <!-- Step trail -->
    <div class="step-trail">
      <span class="step done"><i class="fa fa-check-circle"></i> Cart</span>
      <span class="sep">›</span>
      <span class="step done"><i class="fa fa-check-circle"></i> Delivery Details</span>
      <span class="sep">›</span>
      <span class="step active"><i class="fa fa-credit-card"></i> Payment</span>
    </div>

    <div id="payment-form-area">

      <!-- Order Summary -->
      <div class="order-summary">
        <h5><i class="fa fa-receipt me-1"></i> Order Summary</h5>
        <table class="table table-sm mb-0">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th class="text-center">Qty</th>
              <th class="text-end">Price</th>
              <th class="text-end">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($items as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['product_name']); ?></td>
              <td class="text-center"><?php echo $item['quantity']; ?></td>
              <td class="text-end">RM <?php echo number_format($item['price'], 2); ?></td>
              <td class="text-end">RM <?php echo number_format($item['item_total'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="total-row">
              <td colspan="3" class="text-end">Grand Total</td>
              <td class="text-end">RM <?php echo number_format($total, 2); ?></td>
            </tr>
          </tfoot>
        </table>

        <!-- Delivery info summary -->
        <div class="mt-3 pt-2 border-top" style="font-size:0.84rem; color:#555;">
          <strong>Delivering to:</strong>
          <?php echo htmlspecialchars($customer_name); ?> &nbsp;|&nbsp;
          <?php echo htmlspecialchars($phone); ?> &nbsp;|&nbsp;
          <?php echo htmlspecialchars($address); ?>
        </div>
      </div>

      <!-- ── Choose Payment Method ── -->
      <h5 class="fw-bold mb-3" style="color:var(--primary-color);">
        <i class="fa fa-wallet me-1"></i> Select Payment Method
      </h5>

      <!-- COD Option -->
      <div class="method-card" id="card-cod" onclick="selectMethod('cod')">
        <div class="d-flex align-items-center gap-2">
          <input type="radio" name="pay_method" id="pay-cod" value="cod" />
          <label for="pay-cod">
            <i class="fa fa-money-bill-wave me-1" style="color:#198754;"></i>
            Cash on Delivery (COD)
          </label>
        </div>
        <p class="method-desc">Pay with cash when your order arrives at your door.</p>
      </div>

      <!-- COD note (shown when COD selected) -->
      <div id="cod-note">
        <i class="fa fa-circle-info me-1"></i>
        <strong>Note:</strong> Please keep the exact amount of
        <strong>RM <?php echo number_format($total, 2); ?></strong> ready at the time of delivery.
        Our delivery agent will not carry change.
      </div>

      <!-- Online Card Option -->
      <div class="method-card" id="card-online" onclick="selectMethod('card')">
        <div class="d-flex align-items-center gap-2">
          <input type="radio" name="pay_method" id="pay-card" value="card" />
          <label for="pay-card">
            <i class="fa fa-credit-card me-1" style="color:#0d6efd;"></i>
            Online Card Payment
          </label>
        </div>
        <p class="method-desc">Pay securely using Visa, Mastercard, or any debit/credit card.</p>
      </div>

      <!-- Card Detail Fields (shown when card selected) -->
      <div id="card-fields">
        <div class="d-flex align-items-center gap-2 card-brand-icons mb-3">
          <i class="fab fa-cc-visa"></i>
          <i class="fab fa-cc-mastercard"></i>
          <i class="fab fa-cc-amex"></i>
          <span style="font-size:0.78rem; color:#adb5bd; margin-left:4px;">Mockup – any values accepted</span>
        </div>

        <div class="mb-3">
          <label class="form-label">Cardholder Name</label>
          <input type="text" id="card-name" class="form-control"
                 placeholder="e.g. Ali Hassan" autocomplete="off" />
        </div>

        <div class="mb-3">
          <label class="form-label">Card Number</label>
          <div class="card-num-wrap">
            <input type="text" id="card-number" class="form-control"
                   placeholder="1234 5678 9012 3456" maxlength="19"
                   oninput="formatCardNum(this)" autocomplete="off" />
            <span class="card-chip"><i class="fa fa-credit-card"></i></span>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-6">
            <label class="form-label">Expiry Date</label>
            <input type="text" id="card-expiry" class="form-control"
                   placeholder="MM / YY" maxlength="7"
                   oninput="formatExpiry(this)" autocomplete="off" />
          </div>
          <div class="col-6">
            <label class="form-label">CVV</label>
            <input type="text" id="card-cvv" class="form-control"
                   placeholder="123" maxlength="4"
                   oninput="this.value=this.value.replace(/\D/g,'')" autocomplete="off" />
          </div>
        </div>
      </div>

      <!-- Hidden form that actually submits to confirm_order.php -->
      <form id="final-form" action="confirm_order.php" method="POST">
        <input type="hidden" name="customer_name"   value="<?php echo htmlspecialchars($customer_name); ?>" />
        <input type="hidden" name="phone"            value="<?php echo htmlspecialchars($phone); ?>" />
        <input type="hidden" name="address"          value="<?php echo htmlspecialchars($address); ?>" />
        <input type="hidden" name="payment_method"  id="hidden-method" value="" />

        <div class="mt-3">
          <button type="button" class="btn btn-pay" id="pay-btn" onclick="processPayment()">
            <i class="fa fa-lock me-1"></i> Confirm & Pay
            <span style="margin-left:4px;">RM <?php echo number_format($total, 2); ?></span>
          </button>
          <a href="cart.php" class="btn btn-link ms-3 text-muted" style="font-size:0.88rem;">
            ← Back to Cart
          </a>
        </div>
      </form>

    </div><!-- /payment-form-area -->

    <!-- ===== Success Screen ===== -->
    <div id="success-screen">
      <div class="success-icon"><i class="fa fa-circle-check"></i></div>
      <h3>Order Placed Successfully!</h3>
      <p id="success-subtext"></p>
      <p class="mt-3" style="font-size:0.9rem; color:#888;">Redirecting to your dashboard…</p>
    </div>

  </div><!-- /payment-wrapper -->

  <!-- Footer -->
  <footer class="footer">
    <div class="container text-center">
      <div class="social-icons mb-3">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
      </div>
      <p>© 2025 Shine Utensils. All rights reserved.</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    let chosenMethod = null;

    // ── Select payment method ──────────────────────────────
    function selectMethod(method) {
      chosenMethod = method;

      // Update radio buttons
      document.getElementById('pay-cod').checked  = (method === 'cod');
      document.getElementById('pay-card').checked = (method === 'card');

      // Highlight selected card border
      document.getElementById('card-cod').classList.toggle('selected',    method === 'cod');
      document.getElementById('card-online').classList.toggle('selected', method === 'card');

      // Show/hide extra sections
      document.getElementById('cod-note').style.display   = (method === 'cod')  ? 'block' : 'none';
      document.getElementById('card-fields').style.display = (method === 'card') ? 'block' : 'none';
    }

    // ── Format card number with spaces ────────────────────
    function formatCardNum(input) {
      let v = input.value.replace(/\D/g, '').substring(0, 16);
      input.value = v.replace(/(.{4})/g, '$1 ').trim();
    }

    // ── Format expiry MM / YY ──────────────────────────────
    function formatExpiry(input) {
      let v = input.value.replace(/\D/g, '').substring(0, 4);
      if (v.length >= 3) {
        input.value = v.substring(0, 2) + ' / ' + v.substring(2);
      } else {
        input.value = v;
      }
    }

    // ── Validate card fields (basic, mockup) ──────────────
    function validateCard() {
      const name   = document.getElementById('card-name').value.trim();
      const number = document.getElementById('card-number').value.replace(/\s/g, '');
      const expiry = document.getElementById('card-expiry').value.trim();
      const cvv    = document.getElementById('card-cvv').value.trim();

      if (!name) { alert('Please enter the cardholder name.'); return false; }
      if (number.length < 12) { alert('Please enter a valid card number (at least 12 digits).'); return false; }
      if (expiry.length < 4)  { alert('Please enter a valid expiry date (MM/YY).'); return false; }
      if (cvv.length < 3)     { alert('Please enter a valid CVV (3–4 digits).'); return false; }
      return true;
    }

    // ── Process payment ───────────────────────────────────
    function processPayment() {
      if (!chosenMethod) {
        alert('Please select a payment method to continue.');
        return;
      }

      if (chosenMethod === 'card' && !validateCard()) return;

      // Set hidden field
      document.getElementById('hidden-method').value = chosenMethod;

      // Disable button & show spinner
      const btn = document.getElementById('pay-btn');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-sm"></span> Processing…';

      if (chosenMethod === 'card') {
        // Simulate card processing delay (mockup)
        setTimeout(() => showSuccess('card'), 2000);
      } else {
        // COD — submit instantly after short delay
        setTimeout(() => showSuccess('cod'), 800);
      }
    }

    // ── Show success screen then submit form ──────────────
    function showSuccess(method) {
      document.getElementById('payment-form-area').style.display = 'none';

      const successScreen = document.getElementById('success-screen');
      successScreen.style.display = 'block';

      const sub = document.getElementById('success-subtext');
      if (method === 'card') {
        sub.textContent = 'Your card payment has been processed. Thank you for your purchase!';
      } else {
        sub.textContent = 'Cash on Delivery selected. Please have the payment ready at your door.';
      }

      // Submit the actual form after showing success for 1.8s
      setTimeout(() => {
        document.getElementById('final-form').submit();
      }, 1800);
    }
  </script>

</body>
</html>

