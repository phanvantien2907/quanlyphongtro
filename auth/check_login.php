<?php
// khai báo session
session_start();
// kết nối đến csdl
include_once '../connection.php';

// check login
if($_SERVER['REQUEST_METHOD'] == 'POST') {
   $username = $_POST['username'];
    $password = $_POST['password'];
    $is_active = 1;

    // query sql
   $sql = "select * from user where username = '$username' ";
   $re = $conn->query($sql);

   
   if($re->num_rows == 0) {
        $_SESSION['error'] = 'Tài khoản không tồn tại';
        header('Location: login.php');
        exit();
}

    $user = $re->fetch_assoc();

    if($user['is_active'] == 0) {
        $_SESSION['error'] = "Tài khoản <b>$username</b> chưa được kích hoạt";
        header('Location: login.php');
        exit();
    }

    if(password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        header('Location: /casestudy/home/index.php');
        exit();
    }

    else {
        $_SESSION['error'] = "Mật khẩu <b>$username</b> không chính xác";
        header('Location: login.php');
        exit();
    }
}


?>