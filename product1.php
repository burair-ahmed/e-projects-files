<?php
session_start();
include("db.php");
$user_id = $_SESSION['id'];

$cart = mysqli_query($conn,"
SELECT * FROM cart
WHERE user_id='$user_id'
");

$cartCount = mysqli_num_rows($cart);
// if(!isset($_SESSION['id'])){
//     header("Location: loginpage.html");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Shine Utensils - Products</title>

<link rel="stylesheet" href="productstyle.css">
<link rel="stylesheet" href="style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar sticky-top">

<div class="container">

<a class="navbar-brand d-flex align-items-center" href="#">

<img src="images/logo.jpg" class="logo-img me-2">

Shine Utensils

</a>

<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse justify-content-end" id="navbarNav">

<ul class="navbar-nav">

<li class="nav-item">
<a class="nav-link" href="dashboard.php">Home</a>
</li>

<li class="nav-item">
<a class="nav-link active" href="product1.php">Products</a>
</li>
<!-- 
<li class="nav-item">
<a class="nav-link" href="offerf.html">Offers</a>
</li>

<li class="nav-item">
<a class="nav-link" href="features.html">Features</a>
</li> -->



<li class="nav-item">
<a class="nav-link" href="contact.html">Contact</a>
</li>

<li class="nav-item">
<a class="nav-link" href="aboutus.html">About</a>
</li>

<li class="nav-item">
<a class="nav-link" href="feedback.html">Feedback</a>
</li>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle"

href="#"

id="cartDropdown"

role="button"

data-bs-toggle="dropdown">

<i class="fas fa-shopping-cart"></i>

Cart


<span class="badge bg-danger">
<?php echo $cartCount; ?>
</span>
</a>

<ul class="dropdown-menu dropdown-menu-end" style="width:320px">

<li class="dropdown-header">

Cart Items

</li>

<li><hr></li>

<li>

<div class="px-3">

<?php

$total = 0;

if(mysqli_num_rows($cart)>0){

mysqli_data_seek($cart,0);

while($row=mysqli_fetch_assoc($cart)){

echo "<p>
<b>".$row['product_name']."</b><br>
RM ".$row['price']." × ".$row['quantity']."
</p>";

$total += $row['price'] * $row['quantity'];

}

echo "<hr>";
echo "<h6>Total : RM ".$total."</h6>";

}
else{

echo "No Items";

}

?>

</div>


</li>

<li><hr></li>

<li class="px-3">

<a href="cart.php" class="btn btn-success w-100"><i class="fas fa-check-circle me-1"></i> Confirm Order / Checkout</a>


</li>

</ul>

</li>

<li class="nav-item"><a class="nav-link" href="loginpage.html">login page</a></li>

<li class="nav-item">

<a class="nav-link" href="logout.php">

Logout

</a>

</li>

</ul>

</div>

</div>

</nav>

<div class="headline-container mt-3">

<marquee>

Welcome To Shine Utensils | Best Kitchen Products | Best Malaysian Prices

</marquee>

</div>

<section class="featured-today mt-4">

<h2 class="text-center">

MOST PURCHASED ITEM

</h2>

<div class="featured-card">

<img src="images/product3.jpg">

<div class="featured-info">

<h3>

Non-Stick Magic Pan

</h3>

<p>

Cook Like A Pro

</p>

<span>

RM 799

</span>

</div>

</div>

</section>

<div class="container py-5">

<div class="row g-4">
    <!-- Product 1 -->

<div class="col-md-3">
<div class="card product-card h-100">

<img src="images/product1.jpg" class="card-img-top">

<div class="card-body">

<h5 class="card-title">Stainless Steel Kadhai</h5>

<p>Material : Stainless Steel</p>

<p class="fw-bold">
RM 999
<span class="text-danger text-decoration-line-through ms-2">
RM 1299
</span>
</p>

<form action="add_to_cart.php" method="POST">

<input type="hidden" name="product_name" value="Stainless Steel Kadhai">

<input type="hidden" name="price" value="999">

<button type="submit" class="btn btn-primary w-100">
Add To Cart
</button>

</form>
</div>
</div>
</div>


<!-- Product 2 -->

<div class="col-md-3">
<div class="card product-card h-100">

<img src="images/product2.jpg" class="card-img-top">

<div class="card-body">

<h5 class="card-title">Copper Bottom Pan</h5>

<p>Material : Copper</p>

<p class="fw-bold">
RM 849
<span class="text-danger text-decoration-line-through ms-2">
RM 999
</span>
</p>

<form action="add_to_cart.php" method="POST">

<input type="hidden" name="product_name" value="Copper Bottom Pan">

<input type="hidden" name="price" value="849">

<button type="submit" class="btn btn-primary w-100">
Add To Cart
</button>

</form>
</div>
</div>
</div>


<!-- Product 3 -->

<div class="col-md-3">
<div class="card product-card h-100">

<img src="images/product3.jpg" class="card-img-top">

<div class="card-body">

<h5 class="card-title">Non-stick Fry Pan</h5>

<p>Material : Non-stick</p>

<p class="fw-bold">
RM 699
<span class="text-danger text-decoration-line-through ms-2">
RM 899
</span>
</p>

<form action="add_to_cart.php" method="POST">

<input type="hidden" name="product_name" value="Non-stick Fry Pan">

<input type="hidden" name="price" value="699">

<button type="submit" class="btn btn-primary w-100">
Add To Cart
</button>

</form>

</div>
</div>
</div>


<!-- Product 4 -->

<div class="col-md-3">
<div class="card product-card h-100">

<img src="images/product4.jpg" class="card-img-top">

<div class="card-body">

<h5 class="card-title">Aluminium Pressure Cooker</h5>

<p>Material : Aluminium</p>

<p class="fw-bold">
RM 1199
<span class="text-danger text-decoration-line-through ms-2">
RM 1499
</span>
</p>

<form action="add_to_cart.php" method="POST">

<input type="hidden" name="product_name" value="Aluminium Pressure Cooker">

<input type="hidden" name="price" value="1199">

<button type="submit" class="btn btn-primary w-100">
Add To Cart
</button>

</form>

</div>
</div>
</div>
<!-- Product 5 -->

<div class="col-md-3">
<div class="card product-card h-100">

<img src="images/product39.jpg" class="card-img-top">

<div class="card-body">

<h5 class="card-title">Cast Iron Tawa</h5>

<p>Material : Cast Iron</p>

<p class="fw-bold">
RM 799
<span class="text-danger text-decoration-line-through ms-2">
RM 999
</span>
</p>

<form action="add_to_cart.php" method="POST">

<input type="hidden" name="product_name" value="Cast Iron Tawa">

<input type="hidden" name="price" value="799">

<button type="submit" class="btn btn-primary w-100">
Add To Cart
</button>

</form>
</div>

</div>
</div>

<!-- Product 6 -->

<div class="col-md-3">
<div class="card product-card h-100">

<img src="images/pic3.jpg" class="card-img-top">

<div class="card-body">

<h5 class="card-title">Mini Saucepan</h5>

<p>Material : Stainless Steel</p>

<p class="fw-bold">
RM 449
<span class="text-danger text-decoration-line-through ms-2">
RM 599
</span>
</p>
<form action="add_to_cart.php" method="POST">

<input type="hidden" name="product_name" value="Mini Saucepan">

<input type="hidden" name="price" value="449">

<button type="submit" class="btn btn-primary w-100">
Add To Cart
</button>

</form>

</div>

</div>
</div>

<!-- Product 7 -->

<div class="col-md-3">
<div class="card product-card h-100">

<img src="images/product7.jpg" class="card-img-top">

<div class="card-body">

<h5 class="card-title">Handi Set</h5>

<p>Material : Copper</p>

<p class="fw-bold">
RM 1599
<span class="text-danger text-decoration-line-through ms-2">
RM 1899
</span>
</p>

<form action="add_to_cart.php" method="POST">

<input type="hidden" name="product_name" value="Handi Set">

<input type="hidden" name="price" value="1599">

<button type="submit" class="btn btn-primary w-100">
Add To Cart
</button>

</form>
</div>

</div>
</div>

<!-- Product 8 -->

<div class="col-md-3">
<div class="card product-card h-100">

<img src="images/product40.jpg" class="card-img-top">

<div class="card-body">

<h5 class="card-title">Deep Fryer</h5>

<p>Material : Non-stick</p>

<p class="fw-bold">
RM 999
<span class="text-danger text-decoration-line-through ms-2">
RM 1249
</span>
</p>

<form action="add_to_cart.php" method="POST">

<input type="hidden" name="product_name" value="Deep Fryer">

<input type="hidden" name="price" value="999">

<button type="submit" class="btn btn-primary w-100">
Add To Cart
</button>

</form>

</div>

</div>
</div>

</div>
</div>

<!-- Image Slider -->

<div id="utensilSlider" class="carousel slide mb-5" data-bs-ride="carousel">

<div class="carousel-inner">

<div class="carousel-item active">
<img src="images/slideshow1.jpg" class="d-block w-100">
</div>

<div class="carousel-item">
<img src="images/slideshow2.jpg" class="d-block w-100">
</div>

<div class="carousel-item">
<img src="images/slideshow3.jpg" class="d-block w-100">
</div>

</div>

<button class="carousel-control-prev" type="button" data-bs-target="#utensilSlider" data-bs-slide="prev">
<span class="carousel-control-prev-icon"></span>
</button>

<button class="carousel-control-next" type="button" data-bs-target="#utensilSlider" data-bs-slide="next">
<span class="carousel-control-next-icon"></span>
</button>

</div>

<!-- Footer -->

<footer class="footer">

<div class="container text-center">

<div class="social-icons mb-3">

<a href="#"><i class="fab fa-facebook-f"></i></a>

<a href="#"><i class="fab fa-instagram"></i></a>

<a href="#"><i class="fab fa-twitter"></i></a>

</div>

<p>© 2025 Shine Utensils. All Rights Reserved.</p>

</div>

</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
