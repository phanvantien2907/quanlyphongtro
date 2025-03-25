<?php
session_start();
include '../connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $is_active = 1;

   if($password != $confirm_password) {
    $_SESSION['error'] = "Mật khẩu không trùng khớp";
    header('Location: register.php');
    exit();
   }

    // check email exits
    $check_email = $conn->query("SELECT * FROM user WHERE email = '$email'");

    if ($check_email->num_rows > 0) {
        $_SESSION['error'] = "Email <b>$email</b> đã tồn tại trong hệ thống";
        header("Location: register.php");
        exit();
    }

    $hassed_password = password_hash("password", PASSWORD_BCRYPT);
    $query = "INSERT INTO user (name, username, password, email, is_active) VALUES ('$name', '$username', '$hassed_password', '$email', 1)";

    if($conn->query($query)) {
        $_SESSION['success'] = "Đăng ký tài khoản $email thành công";
        header("Location: register.php");
        exit();
    }

    else {
        $_SESSION['error'] = "Đăng ký tài khoản $email thất bại";
        header("Location: register.php");
        exit();
    }

    header("Location: register.php");
    exit();
}

 ?>
