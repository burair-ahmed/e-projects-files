<?php
session_start();
include("db.php");

if(!isset($_SESSION['id'])){
    header("Location: loginpage.html");
    exit();
}

$user_id        = $_SESSION['id'];
$customer_name  = isset($_POST['customer_name'])  ? mysqli_real_escape_string($conn, $_POST['customer_name'])  : '';
$phone          = isset($_POST['phone'])          ? mysqli_real_escape_string($conn, $_POST['phone'])          : '';
$address        = isset($_POST['address'])        ? mysqli_real_escape_string($conn, $_POST['address'])        : '';
$payment_method = isset($_POST['payment_method']) ? mysqli_real_escape_string($conn, $_POST['payment_method']) : 'cod';

// Guard: must have come from cart form
if(empty($customer_name)){
    header("Location: cart.php");
    exit();
}

// Fetch cart
$result = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");

$order_ok = true;
if($result){
    while($row = mysqli_fetch_assoc($result)){
        $product = mysqli_real_escape_string($conn, $row['product_name']);
        $price   = (float)$row['price'];
        $qty     = (int)$row['quantity'];

        // Try with payment_method column first
        $ok = mysqli_query($conn,
            "INSERT INTO orders
             (user_id, customer_name, phone, address, product_name, price, quantity, status, payment_method)
             VALUES
             ('$user_id','$customer_name','$phone','$address','$product','$price','$qty','Pending','$payment_method')"
        );

        // Fall back without payment_method column if column doesn't exist
        if(!$ok){
            $ok = mysqli_query($conn,
                "INSERT INTO orders
                 (user_id, customer_name, phone, address, product_name, price, quantity, status)
                 VALUES
                 ('$user_id','$customer_name','$phone','$address','$product','$price','$qty','Pending')"
            );
        }

        if(!$ok) $order_ok = false;
    }

    // Clear cart only if orders were placed
    if($order_ok){
        mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order Confirmed – Shine Utensils</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .confirm-box {
      max-width: 560px;
      margin: 5rem auto;
      text-align: center;
      padding: 2.5rem 2rem;
      border: 1px solid #dee2e6;
      border-radius: 10px;
      box-shadow: 0 4px 18px rgba(0,0,0,0.08);
      background: #fff;
    }
    .confirm-box .icon-wrap {
      font-size: 3.8rem;
      color: #198754;
      margin-bottom: 1rem;
    }
    .confirm-box h3 {
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }
    .confirm-box p  { color: #6c757d; font-size: 0.95rem; }
    .confirm-box .badge-method {
      display: inline-block;
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 20px;
      padding: 0.25rem 0.9rem;
      font-size: 0.82rem;
      margin-top: 0.4rem;
      color: #495057;
    }
    .redirect-bar {
      height: 4px;
      background: #dee2e6;
      border-radius: 2px;
      margin: 1.5rem 0 0.5rem;
      overflow: hidden;
    }
    .redirect-bar span {
      display: block;
      height: 100%;
      background: #198754;
      animation: shrink 3s linear forwards;
      width: 100%;
    }
    @keyframes shrink { from { width:100%; } to { width:0%; } }
    .redirect-note { font-size: 0.8rem; color: #adb5bd; }

    /* Error state */
    .confirm-box.error .icon-wrap { color: #dc3545; }
    .confirm-box.error h3 { color: #dc3545; }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark custom-navbar sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.html">
        <img src="images/logo.jpg" alt="Logo" class="logo-img me-2" />
        Shine Utensils
      </a>
    </div>
  </nav>

  <div class="container">
    <?php if($order_ok): ?>
    <!-- ── SUCCESS ── -->
    <div class="confirm-box">
      <div class="icon-wrap"><i class="fa fa-circle-check"></i></div>
      <h3>Order Placed Successfully!</h3>
      <p>Thank you, <strong><?php echo htmlspecialchars($customer_name); ?></strong>!<br>
         Your order has been confirmed and will be delivered to:</p>
      <p><em><?php echo htmlspecialchars($address); ?></em></p>

      <?php
        $method_label = ($payment_method === 'card') ? '💳 Online Card Payment' : '💵 Cash on Delivery';
      ?>
      <div class="badge-method"><?php echo $method_label; ?></div>

      <div class="redirect-bar"><span></span></div>
      <p class="redirect-note">Redirecting to your dashboard in 3 seconds…</p>

      <div class="mt-3">
        <a href="dashboard.php" class="btn btn-success btn-sm">Go to Dashboard now</a>
        <a href="index.html" class="btn btn-outline-secondary btn-sm ms-2">Continue Shopping</a>
      </div>
    </div>
    <script>
      setTimeout(function(){ window.location = 'dashboard.php'; }, 3000);
    </script>

    <?php else: ?>
    <!-- ── ERROR ── -->
    <div class="confirm-box error">
      <div class="icon-wrap"><i class="fa fa-circle-exclamation"></i></div>
      <h3>Something Went Wrong</h3>
      <p>We couldn't process your order at this time. Please try again or contact support.</p>
      <div class="mt-3">
        <a href="cart.php" class="btn btn-danger btn-sm">Back to Cart</a>
        <a href="index.html" class="btn btn-outline-secondary btn-sm ms-2">Go Home</a>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
