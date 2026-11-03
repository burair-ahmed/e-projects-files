<?php
session_start();

if(!isset($_SESSION['id'])){
    header("Location: loginpage.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f5f5;
}

.navbar{
    background:#198754;
}

.navbar-brand,
.nav-link{
    color:white !important;
}

.card{
    border:none;
    border-radius:15px;
    box-shadow:0 0 15px rgba(0,0,0,0.1);
    transition:.3s;
}

.card:hover{
    transform:translateY(-5px);
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">
<div class="container">

<a class="navbar-brand" href="#">
Shine Utensils
</a>

<div>

<span class="text-white me-3">

Welcome,
<b><?php echo $_SESSION['name']; ?></b>

</span>

<a href="logout.php" class="btn btn-light">

Logout

</a>

</div>

</div>
</nav>

<div class="container mt-5">

<h2 class="mb-4">

User Dashboard

</h2>

<div class="row">

<div class="col-md-4">

<div class="card p-4 text-center">

<h3>👤</h3>

<h4><?php echo $_SESSION['name']; ?></h4>

<p>Logged in User</p>

</div>

</div>

<div class="col-md-4">

<div class="card p-4 text-center">

<h3>🛒</h3>

<h4>Products</h4>

<p>Browse Kitchen Products</p>

<a href="index.html" class="btn btn-success">

Go To Website

</a>

</div>

</div>

<div class="col-md-4">

<div class="card p-4 text-center">

<h3>📧</h3>

<h4>Email</h4>

<p><?php echo $_SESSION['email']; ?></p>

</div>

</div>

</div>

<div class="mt-5">

<div class="card p-4">

<h3>Welcome to Shine Utensils 🎉</h3>

<p>

You have successfully logged in.

From here you can visit the website, browse products, check offers and continue shopping.

</p>

<a href="index.html" class="btn btn-success">

Visit Home Page

</a>

</div>

</div>

</div>

</body>
</html>
