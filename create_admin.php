<?php
include("db.php");

$name = "Admin";
$email = "admin@gmail.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$role = "admin";

$check = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $check);

if (mysqli_num_rows($result) > 0) {
    echo "Admin already exists!";
} else {

    $sql = "INSERT INTO users(name,email,password,role)
            VALUES('$name','$email','$password','$role')";

    if(mysqli_query($conn,$sql)){
        echo "Admin Created Successfully!";
    }else{
        echo "Error: " . mysqli_error($conn);
    }
}
?>