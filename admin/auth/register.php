<?php
session_start();
include_once '../../config/connection.php';

// check login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'manager';
    $is_active = 1;

    if($password != $confirm_password) {
        $_SESSION['toast_error'] = "Mật khẩu không trùng khớp!";
        header('Location: ..\auth\register.php');
        exit();
    }

    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'") or die("Lỗi truy vấn: " . mysqli_error($conn));
    $result_checkmail = mysqli_fetch_assoc($check_email);

    if ($check_email->num_rows > 0) {
        $_SESSION['toast_error'] = "Email <b>$email</b> đã tồn tại trong hệ thống!";
        header('Location: ..\auth\register.php');
        exit();
    }

    $hashed_pasword = password_hash("password", PASSWORD_BCRYPT);
    $query = mysqli_query($conn, "INSERT INTO users (name, password, email, role, is_active) VALUES ('$name', '$hashed_pasword', '$email', '$role',  1)") or die("Lỗi truy vấn: " . mysqli_error($conn));
    if ($query) {
        $_SESSION['toast_success'] = "Đăng ký tài khoản $email thành công!";
        header('Location: ..\auth\register.php');
        exit();
    } else {
        $_SESSION['toast_error'] = "Đăng ký tài khoản $email thất bại!";
        header('Location: ..\auth\register.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Đăng ký thành viên - Phòng trọ thành phố Vinh</title>
    <style>
        .bg-pattern {
            background-color: #4158D0;
            background-image: linear-gradient(43deg, #4158D0 0%, #C850C0 46%, #FFCC70 100%);
        }

        .login-card {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.85);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        .toast-anim-in {
            animation: fadeInUp 0.3s ease-out;
        }

        .toast-anim-out {
            animation: fadeOut 0.3s ease-in forwards;
        }
    </style>
</head>

<body class="min-h-screen bg-pattern flex items-center justify-center p-4 md:p-0">
    <?php include '../components/toast-message.php'; ?>
    <div class="w-full max-w-md relative">
        <!-- Logo and branding -->
        <div class="text-center mb-8">
            <div class="flex justify-center">
                <div class="bg-white p-3 rounded-full shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-white mt-4 drop-shadow-md">Phòng trọ Thành phố Vinh</h1>
            <p class="text-white text-opacity-90 mt-2 drop-shadow">Quản lý hệ thống</p>
        </div>

        <!-- Login Card -->
        <div class="card login-card w-full shadow-2xl">
            <div class="card-body p-8">
                <h2 class="card-title text-2xl font-bold text-center justify-center mb-4">Đăng ký</h2>
                <form action="../auth/register.php" method="POST">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Tên của bạn</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            <input type="text" name="name" placeholder="Nhập tên của bạn" class="input input-bordered w-full pl-10 rounded-3xl" required />
                        </div>
                    </div>

                     <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-medium">Email</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                            <input type="text" name="email" placeholder="Nhập email " class="input input-bordered w-full pl-10 rounded-3xl" required />
                        </div>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-medium">Mật khẩu</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                            <input type="password" name="password" placeholder="Nhập mật khẩu" class="input input-bordered w-full pl-10 rounded-3xl" required />
                        </div>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-medium">Xác nhận mật khẩu</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                            <input type="password" name="confirm_password" placeholder="Xác nhận lại mật khẩu " class="input input-bordered w-full pl-10 rounded-3xl" required />
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-2">
                        <label class="cursor-pointer label justify-start gap-2">
                            <input type="checkbox" name="remember" class="checkbox checkbox-sm checkbox-primary" />
                            <span class="label-text">Nhớ mật khẩu</span>
                        </label>
                        <a href="#" class="text-sm text-primary hover:underline">Quên mật khẩu?</a>
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" name="register" value="1" class="btn btn-primary rounded-3xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            Đăng ký
                        </button>
                    </div>
                </form>

                <div class="divider my-6">Hoặc</div>

                <div class="grid grid-cols-2 gap-3">
                    <button class="btn btn-outline hover:bg-blue-500 hover:border-blue-500">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#4285F4" d="M23.745 12.27c0-.79-.07-1.54-.19-2.27h-11.3v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82z" />
                            <path fill="#34A853" d="M12.255 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.16-4.07 1.16-3.13 0-5.78-2.11-6.73-4.96h-3.98v3.09c1.97 3.92 6.02 6.62 10.71 6.62z" />
                            <path fill="#FBBC05" d="M5.525 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29v-3.09h-3.98c-.81 1.62-1.27 3.45-1.27 5.38s.46 3.76 1.27 5.38l3.98-3.09z" />
                            <path fill="#EA4335" d="M12.255 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42c-2.08-1.94-4.8-3.13-8.02-3.13-4.69 0-8.74 2.7-10.71 6.62l3.98 3.09c.95-2.85 3.6-4.96 6.73-4.96z" />
                        </svg>
                        Google
                    </button>
                    <button class="btn btn-outline hover:bg-blue-600 hover:border-blue-600">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#1877F2">
                            <path d="M24,12.073c0,-5.8 -4.701,-10.501 -10.501,-10.501c-5.8,0 -10.499,4.701 -10.499,10.501c0,5.241 3.837,9.584 8.872,10.371v-7.337h-2.671v-3.034h2.671v-2.315c0,-2.637 1.571,-4.097 3.978,-4.097c1.151,0 2.356,0.206 2.356,0.206v2.594h-1.327c-1.309,0 -1.717,0.812 -1.717,1.644v1.968h2.922l-0.467,3.034h-2.455v7.337c5.035,-0.787 8.872,-5.13 8.872,-10.371" />
                        </svg>
                        Facebook
                    </button>
                </div>

                <p class="text-sm text-center mt-6 text-gray-600">
                    Đây là trang dành cho quản trị viên. <br>
                    Đã có tài khoản? <a href="../auth/login.php" class="text-primary font-medium hover:underline">Đăng nhập ngay</a><br>
                </p>
            </div>
        </div>

        <!-- Footer text -->
        <div class="text-center mt-6">
            <p class="text-white text-sm text-opacity-70">© 2024-2025 Phòng trọ thành phố Vinh. All rights reserved.</p>
        </div>
    </div>
</body>

</html>