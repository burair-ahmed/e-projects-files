<?php
session_start();
include("db.php");

if(!isset($_SESSION['id'])){
    header("Location: loginpage.html");
    exit();
}

$user_id        = $_SESSION['id'];
$customer_name  = $_POST['customer_name'];
$phone          = $_POST['phone'];
$address        = $_POST['address'];
// Payment method sent from payment.php (cod or card); default to 'cod'
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cod';

$result = mysqli_query($conn,"SELECT * FROM cart WHERE user_id='$user_id'");

while($row = mysqli_fetch_assoc($result)){

    $product = $row['product_name'];
    $price   = $row['price'];
    $qty     = $row['quantity'];

    // Try inserting with payment_method column; fall back gracefully if column missing
    $insert = mysqli_query($conn,"
        INSERT INTO orders
        (user_id,customer_name,phone,address,product_name,price,quantity,status,payment_method)
        VALUES
        ('$user_id','$customer_name','$phone','$address','$product','$price','$qty','Pending','$payment_method')
    ");

    // If the payment_method column doesn't exist yet, insert without it
    if(!$insert){
        mysqli_query($conn,"
            INSERT INTO orders
            (user_id,customer_name,phone,address,product_name,price,quantity,status)
            VALUES
            ('$user_id','$customer_name','$phone','$address','$product','$price','$qty','Pending')
        ");
    }
}

mysqli_query($conn,"DELETE FROM cart WHERE user_id='$user_id'");

echo "<script>
alert('Order Confirmed Successfully!');
window.location='dashboard.php';
</script>";
?>
