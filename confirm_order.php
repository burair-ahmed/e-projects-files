<?php
session_start();
include("db.php");

if(!isset($_SESSION['id'])){
    header("Location: loginpage.html");
    exit();
}

$user_id = $_SESSION['id'];

$customer_name = $_POST['customer_name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$result = mysqli_query($conn,"SELECT * FROM cart WHERE user_id='$user_id'");

while($row=mysqli_fetch_assoc($result)){

    $product = $row['product_name'];
    $price = $row['price'];
    $qty = $row['quantity'];

    mysqli_query($conn,"
    INSERT INTO orders
    (user_id,customer_name,phone,address,product_name,price,quantity,status)
    VALUES
    ('$user_id','$customer_name','$phone','$address','$product','$price','$qty','Pending')
    ");
}

mysqli_query($conn,"DELETE FROM cart WHERE user_id='$user_id'");

echo "<script>
alert('Order Confirmed Successfully!');
window.location='dashboard.php';
</script>";
?>
