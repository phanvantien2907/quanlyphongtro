<?php
session_start();
require_once '../config/connection.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id") or die("Lỗi truy vấn");
$user = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $current_password = $_POST['current_password'] ?? '';

    if (!$current_password) {
        $_SESSION['toast_error'] = "Vui lòng nhập mật khẩu hiện tại để xác nhận thay đổi";
        return;
    }

    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['toast_error'] = "Mật khẩu hiện tại không đúng!";
        return;
    }

    $fields = [];

    if ($name !== $user['name']) {
        $fields[] = "name='" . mysqli_real_escape_string($conn, $name) . "'";
    }

    if ($email !== $user['email']) {
        $fields[] = "email='" . mysqli_real_escape_string($conn, $email) . "'";
    }

    if ($new_password || $confirm_password) {
        if ($new_password === $confirm_password) {
            $fields[] = "password='" . password_hash($new_password, PASSWORD_BCRYPT) . "'";
        } else {
            $_SESSION['toast_error'] = "Mật khẩu mới không khớp!";
            return;
        }
    }

    if ($fields) {
        $sql = "UPDATE users SET " . implode(',', $fields) . ", updated_at=NOW() WHERE user_id=$user_id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['toast_success'] = "Cập nhật thông tin thành công!";
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION['toast_error'] = "Lỗi: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['toast_info'] = "Không có thông tin nào được thay đổi";
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
    <title>Tài khoản của tôi - Tìm phòng trọ thành phố Vinh</title>
</head>

<body class="bg-gray-50 min-h-screen">
    <?php include '../components/Header.php'; ?>

    <?php if (isset($_SESSION['toast_success'])): ?>
    <div class="toast toast-top toast-end z-50">
        <div class="alert alert-success shadow-lg">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= $_SESSION['toast_success'] ?></span>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['toast_success']); endif; ?>

    <?php if (isset($_SESSION['toast_error'])): ?>
    <div class="toast toast-top toast-end z-50">
        <div class="alert alert-error shadow-lg">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= $_SESSION['toast_error'] ?></span>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['toast_error']); endif; ?>

    <?php if (isset($_SESSION['toast_info'])): ?>
    <div class="toast toast-top toast-end z-50">
        <div class="alert alert-info shadow-lg">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= $_SESSION['toast_info'] ?></span>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['toast_info']); endif; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Tài Khoản Của Tôi</h1>
                <div class="h-1 w-24 bg-gradient-to-r from-primary to-secondary mx-auto mt-4 rounded-full"></div>
            </div>

            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                <!-- Cover Image & Profile Picture -->
                <div class="relative">
                    <div class="h-40 bg-gradient-to-r from-primary to-secondary"></div>
                    <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
                        <div class="avatar">
                            <div class="w-32 h-32 rounded-full ring ring-white ring-offset-base-100 ring-offset-2">
                                <?php if(!empty($user['images'])): ?>
                                    <img src="<?= $user['images'] ?>" alt="<?= $user['name'] ?>" />
                                <?php else: ?>
                                    <div class="bg-neutral-focus text-neutral-content rounded-full w-32">
                                        <span class="text-3xl"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="pt-20 px-6 pb-8 text-center">
                    <h2 class="text-2xl font-bold text-gray-800"><?= $user['name'] ?></h2>
                    <p class="text-gray-500 mt-1"><?= $user['email'] ?></p>

                    <div class="flex flex-wrap justify-center gap-2 mt-4">
                        <div class="badge badge-primary"><?= $user['role'] ?></div>
                        <?php if($user['email_verified_at']): ?>
                            <div class="badge badge-success">Đã xác minh</div>
                        <?php else: ?>
                            <div class="badge badge-error">Chưa xác minh</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">Cài Đặt Tài Khoản</h3>
                    <p class="text-gray-500 text-sm mt-1">Cập nhật thông tin cá nhân của bạn</p>
                </div>

                <form method="POST" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="form-control">
            <label class="label">
                <span class="label-text font-medium text-gray-700">Họ tên</span>
            </label>
            <input type="text" name="name" value="<?= $user['name'] ?>" class="input input-bordered rounded-xl" required />
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text font-medium text-gray-700">Email</span>
            </label>
            <input type="email" name="email" value="<?= $user['email'] ?>" class="input input-bordered rounded-xl" required />
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text font-medium text-gray-700">Xác minh email</span>
            </label>
            <input type="date" name="email_verified_at" placeholder="Nhập lại email để xác minh" class="input input-bordered rounded-xl" required />
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text font-medium text-gray-700">Mật khẩu hiện tại</span>
            </label>
            <input type="password" placeholder="Lần cuối cập nhật <?=date("d/m/Y", strtotime($user['updated_at'])) ?>" name="current_password" class="input input-bordered rounded-xl" required />
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text font-medium text-gray-700">Mật khẩu mới</span>
            </label>
            <input type="password" name="new_password" class="input input-bordered rounded-xl" />
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text font-medium text-gray-700">Xác nhận mật khẩu</span>
            </label>
            <input type="password" name="confirm_password" class="input input-bordered rounded-xl" />
        </div>
    </div>

    <div class="mt-6 text-right">
        <button type="submit" name="update" class="btn btn-primary rounded-xl">Cập nhật</button>
    </div>
</form>

            </div>
        </div>
    </div>

    <?php include '../components/Footer.php'; ?>
</body>

</html>
