<?php
session_start();
include("db.php");

if(!isset($_SESSION['id'])){
    header("Location: loginpage.html");
    exit();
}

$user_id = $_SESSION['id'];

$product_name = $_POST['product_name'];
$price = $_POST['price'];

$check = mysqli_query($conn,"
SELECT * FROM cart
WHERE user_id='$user_id'
AND product_name='$product_name'
");

if(mysqli_num_rows($check)>0){

mysqli_query($conn,"
UPDATE cart
SET quantity = quantity + 1
WHERE user_id='$user_id'
AND product_name='$product_name'
");

}else{

mysqli_query($conn,"
INSERT INTO cart(user_id,product_name,price,quantity)
VALUES('$user_id','$product_name','$price',1)
");

}

header("Location: product1.php");
exit();
?>