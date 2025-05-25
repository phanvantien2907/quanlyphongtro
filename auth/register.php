<?php
session_start();
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'user';
    $is_active = 1;

    if ($password != $confirm_password) {
        $_SESSION['error'] = "Mật khẩu không trùng khớp";
        header('Location: register.php');
        exit();
    }

    // check email exits
    $check_email = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if ($check_email->num_rows > 0) {
        $_SESSION['error'] = "Email <b>$email</b> đã tồn tại trong hệ thống";
        header("Location: register.php");
        exit();
    }

    $hassed_password = password_hash("password", PASSWORD_BCRYPT);
    $query = "INSERT INTO users (name, password, email, role,  is_active) VALUES ('$name', '$hassed_password', '$email', '$role', 1)";

    if ($conn->query($query)) {
        $_SESSION['toast_success'] = "Đăng ký tài khoản $email thành công";
        header("Location: register.php");
        exit();
    } else {
        $_SESSION['toast_error'] = "Đăng ký tài khoản $email thất bại";
        header("Location: register.php");
        exit();
    }

    header("Location: register.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Register</title>
</head>

<body>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <title>Đăng ký tài khoản</title>
    </head>

    <body>
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <?php include '../admin/components/toast-message.php'; ?>
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
                <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign up to your account</h2>
            </div>
            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" action="register.php" method="POST">
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="name" class="block text-sm/6 font-medium text-gray-900">Name</label>
                        </div>
                        <div class="mt-2">
                            <input type="name" name="name" id="name" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                        </div>
                        <div class="mt-2">
                            <input type="password" name="password" id="password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="confirm_password" class="block text-sm/6 font-medium text-gray-900">Confirm Password</label>
                        </div>
                        <div class="mt-2">
                            <input type="password" name="confirm_password" id="confirm_password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="email" class="block text-sm/6 font-medium text-gray-900">Email</label>
                        </div>
                        <div class="mt-2">
                            <input type="email" name="email" id="email" autocomplete="email" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign up</button>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm/6 text-gray-500">
                    a member?
                    <a href="../auth/login.php" class="font-semibold text-indigo-600 hover:text-indigo-500">Login now</a>
                </p>
            </div>
        </div>
    </body>

    </html>
</body>

</html>