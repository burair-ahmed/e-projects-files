<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $login_type = $_POST['login_type'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // ===== ADMIN LOGIN =====
            if ($login_type == "admin") {

                if ($user['role'] == "admin") {

                    header("Location: admin.php");
                    exit();

                } else {

                    echo "<script>
                    alert('You are not an Admin!');
                    window.location='loginpage.html';
                    </script>";
                    exit();

                }

            }

            // ===== USER LOGIN =====
            else {

                if ($user['role'] == "user") {

                    header("Location: dashboard.php");
                    exit();

                } else {

                    echo "<script>
                    alert('Please login from Admin section.');
                    window.location='loginpage.html';
                    </script>";
                    exit();

                }

            }

        } else {

            echo "<script>
            alert('Incorrect Password!');
            window.location='loginpage.html';
            </script>";

        }

    } else {

        echo "<script>
        alert('Email not found!');
        window.location='loginpage.html';
        </script>";

    }

}

$conn->close();
?>