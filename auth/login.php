<?php
session_start();
include_once '../config/connection.php';

if (!isset($_SESSION['login_failed'])) {
    $_SESSION['login_failed'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $is_active = 1;

    if ($_SESSION['login_failed'] > 3) {
        if ($_POST['g-recaptcha-response']) {
            $_SESSION['toast_error'] = "Vui lòng xác minh CAPTCHA!";
            header('Location: login.php');
            exit();
        }
        $capcha = $_POST['g-recaptcha-response'];
        $secret = "6Le8Vz0rAAAAAN9VDD9pN-H7XYUuLzghUBgAcbvW";
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$capcha");
        $response = json_decode($response, true);
        if (!$response['success']) {
            $_SESSION['toast_error'] = "CAPTCHA không hợp lệ!";
            header('Location: login.php');
            exit();
        }
    }

    // query sql
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND is_active = $is_active") or die("Lỗi truy vấn");
    $result = mysqli_fetch_assoc($sql);


    if ($result['is_active'] == 0) {
        $_SESSION['error'] = "Tài khoản <b>$email</b> chưa được kích hoạt";
        header('Location: login.php');
        exit();
    }

    if (password_verify($password, $result['password'])) {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['name'] = $result['name'];
        $_SESSION['login_failed'] = 0;
        header('Location: ..\page\index.php');
        exit();
    } else {
        $_SESSION['login_failed']++;
        $_SESSION['toast_error'] = "Mật khẩu <b>$email</b> không chính xác";
        header('Location: login.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Login</title>
</head>

<body>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div id="toast-container" class="toast toast-top toast-end z-50">
        <?php if (isset($_SESSION['toast_error'])): ?>
            <div class="alert alert-error shadow-lg toast-anim-in" id="toast-message">
                <div>
                    <div><?= $_SESSION['toast_error']; ?></div>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('toast-message');
                    toast.classList.remove('toast-anim-in');
                    toast.classList.add('toast-anim-out');
                    setTimeout(() => {
                        toast.style.display = 'none';
                    }, 300);
                }, 4000);
            </script>
            <?php unset($_SESSION['toast_error']); ?>
        <?php endif; ?>
        </div>
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign in to your account</h2>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="login.php" method="POST">
                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">Email</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="username" autocomplete="email" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                        <div class="text-sm">
                            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                </div>
                <?php if ($_SESSION['login_failed'] > 3): ?>
                    <div class="mt-2">
                        <div class="g-recaptcha" data-sitekey="6Le8Vz0rAAAAAN9VDD9pN-H7XYUuLzghUBgAcbvW"></div>
                    </div>
                <?php endif; ?>
                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm/6 text-gray-500">
                Not a member?
                <a href="../auth/register.php" class="font-semibold text-indigo-600 hover:text-indigo-500">Start a 14 day free trial</a>
            </p>
        </div>
    </div>
</body>

</html>