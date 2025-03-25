<?php
// khai báo session
session_start();
// kết nối đến csdl
include 'conn.php';

// check login
if($_SERVER['REQUEST_METHOD'] == 'POST') {
   $username = $_POST['username'];
    $password = $_POST['password'];
    $is_active = 1;

    // query sql
   $sql = "select * from user where username = '$username' ";
   $re = $conn->query($sql);

   // check user exits
   if($re->num_rows > 0) {
        $user = $re->fetch_assoc();

        if($user['is_active'] == 0) {
            $_SESSION['error'] = "Tài khoản $username này chưa được kích hoạt";
            header('Location: login.php');
            exit();
        }

        elseif($password == $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            header('Location: index.php');
            exit();
        }
        else {
            $_SESSION['error'] = 'Mật khẩu không chính xác';
            header('Location: login.php');
            exit();
        }

}
}


?>