<?php
session_start();
include("db.php");

if(!isset($_SESSION['id'])){
    header("Location: loginpage.html");
    exit();
}

$user_id = $_SESSION['id'];

$result=mysqli_query($conn,"
SELECT * FROM cart
WHERE user_id='$user_id'
");

$total=0;
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>My Cart</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<h2 class="mb-4">

🛒 My Shopping Cart

</h2>

<table class="table table-bordered">

<tr class="table-success">

<th>Product</th>

<th>Price</th>

<th>Quantity</th>

<th>Total</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result)){

$itemTotal=$row['price']*$row['quantity'];

$total+=$itemTotal;

?>

<tr>

<td><?php echo $row['product_name']; ?></td>

<td>RM <?php echo $row['price']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>RM <?php echo $itemTotal; ?></td>

</tr>

<?php

}

?>

<tr class="table-dark">

<td colspan="3">

<b>Grand Total</b>

</td>

<td>

<b>RM <?php echo $total; ?></b>

</td>

</tr>

</table>

<hr>

<h4>

Delivery Details

</h4>

<form action="confirm_order.php" method="POST">

<div class="mb-3">

<label>Name</label>

<input type="text" name="customer_name" class="form-control" required>

</div>

<div class="mb-3">

<label>Phone</label>

<input type="text" name="phone" class="form-control" required>

</div>

<div class="mb-3">

<label>Address</label>

<textarea name="address" class="form-control" required></textarea>

</div>

<button class="btn btn-success btn-lg">

Confirm Order

</button>

</form>

</div>

</body>

</html>
