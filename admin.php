<?php
include("auth.php");
include("db.php");

if($_SESSION['role']!="admin"){
    header("Location:dashboard.php");
    exit();
}

// Dashboard Cards
$totalUsers = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users"));
$totalOrders = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM orders"));

$pendingOrders = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM orders
WHERE status='Pending'
"));

$completedOrders = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM orders
WHERE status='Completed'
"));

$revenue = mysqli_query($conn,"
SELECT SUM(price*quantity) AS total FROM orders
WHERE status='Completed'
");

$rev = mysqli_fetch_assoc($revenue);
$totalRevenue = $rev['total'];

$users = mysqli_query($conn,"SELECT * FROM users");

$orders = mysqli_query($conn,"
SELECT orders.*,users.name,users.email
FROM orders
INNER JOIN users
ON orders.user_id=users.id
ORDER BY orders.order_date DESC
");
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f6f9;
}

.sidebar{
height:100vh;
background:#198754;
padding:20px;
color:white;
}

.sidebar h3{
text-align:center;
}

.sidebar a{
display:block;
padding:12px;
margin-top:10px;
background:rgba(255,255,255,.1);
color:white;
text-decoration:none;
border-radius:8px;
transition:.3s;
}

.sidebar a:hover{
background:white;
color:#198754;
}

.card{
border:none;
box-shadow:0 0 15px rgba(0,0,0,.15);
border-radius:12px;
}

table{
background:white;
}

</style>

</head>

<body>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 sidebar">

<h3>Admin Panel</h3>

<hr>

<a href="admin.php">Dashboard</a>

<a href="#">Products</a>

<a href="#">Orders</a>

<a href="#">Feedback</a>

<a href="#">Contact</a>

<a href="logout.php">Logout</a>

</div>

<div class="col-md-10">

<div class="container mt-4">

<h2>
Welcome,
<?php echo $_SESSION['name']; ?>
</h2>

<div class="row mt-4">

<div class="col-md-3">

<div class="card p-4 bg-success text-white">

<h3><?php echo $totalUsers; ?></h3>

<h5>Total Users</h5>

</div>

</div>

<div class="col-md-3">

<div class="card p-4 bg-primary text-white">

<h3><?php echo $totalOrders; ?></h3>

<h5>Total Orders</h5>

</div>

</div>

<div class="col-md-3">

<div class="card p-4 bg-warning">

<h3><?php echo $pendingOrders; ?></h3>

<h5>Pending Orders</h5>

</div>

</div>

<div class="col-md-3">

<div class="card p-4 bg-dark text-white">

<h3>RM <?php echo $totalRevenue==""?0:$totalRevenue; ?></h3>

<h5>Total Revenue</h5>

</div>

</div>

</div>

<h3 class="mt-5">
Registered Users
</h3>

<table class="table table-bordered table-striped">

<tr class="table-success">

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Role</th>

<th>Delete</th>

</tr>

<?php
while($row=mysqli_fetch_assoc($users)){
?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo ucfirst($row['role']); ?></td>

<td>

<a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>
<h3 class="mt-5">Customer Orders</h3>

<table class="table table-bordered table-striped">

<tr class="table-dark">

<th>Order ID</th>
<th>Customer</th>
<th>Email</th>
<th>Product</th>
<th>Price</th>
<th>Qty</th>
<th>Total</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>

</tr>

<?php
while($order=mysqli_fetch_assoc($orders)){
?>

<tr>

<td><?php echo $order['id']; ?></td>

<td><?php echo $order['name']; ?></td>

<td><?php echo $order['email']; ?></td>

<td><?php echo $order['product_name']; ?></td>

<td>RM <?php echo $order['price']; ?></td>

<td><?php echo $order['quantity']; ?></td>

<td>
RM <?php echo $order['price'] * $order['quantity']; ?>
</td>

<td>

<?php

if($order['status']=="Pending"){

echo "<span class='badge bg-warning'>Pending</span>";

}else{

echo "<span class='badge bg-success'>Completed</span>";

}

?>

</td>

<td><?php echo $order['order_date']; ?></td>

<td>

<?php
if($order['status']=="Pending"){
?>

<a href="update_order.php?id=<?php echo $order['id']; ?>"
class="btn btn-success btn-sm">
Complete
</a>

<?php
}
?>

<a href="delete_order.php?id=<?php echo $order['id']; ?>"
class="btn btn-danger btn-sm">
Delete
</a>

</td>

</tr>

<?php
}
?>

</table>

</div>

</div>

</div>

</div>

</body>

</html>