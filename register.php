<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check passwords
    if ($password != $confirm_password) {
        echo "<script>
        alert('Passwords do not match!');
        window.location='signup.html';
        </script>";
        exit();
    }

    // Check email already exists
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {

        echo "<script>
        alert('Email already exists!');
        window.location='signup.html';
        </script>";

    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(name,email,password)
                VALUES('$name','$email','$hashedPassword')";

        if (mysqli_query($conn, $sql)) {

            echo "<script>
            alert('Registration Successful');
            window.location='loginpage.html';
            </script>";

        } else {

            echo "Error: " . mysqli_error($conn);

        }

    }

}
?>