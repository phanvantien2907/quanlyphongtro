<?php
session_start();
require_once '../../config/connection.php';
if(!isset($_SESSION['name'])) {
    header('Location: ../page/index.php');
    exit();
}
$id = (int)($_GET['id'] ?? 0);
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $id"));

if (!$user) {
    $_SESSION['toast_error'] = "Không tìm thấy tài khoản với ID: $id";
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $images = $_POST['images'];
    $updated_at = date('Y-m-d H:i:s');
    $is_active = $_POST['is_active'];
    
    $sql = "UPDATE users SET 
    name='$name',
    email='$email',
    role='$role',
    images='$images',
    updated_at='$updated_at',
    is_active='$is_active'";
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql .= ", password='$password'";
    }
    
    $sql .= " WHERE user_id=$id";
    
    $result = mysqli_query($conn, $sql) or die("Lỗi cập nhật tài khoản: " . mysqli_error($conn));

    if ($result) {
        $_SESSION['toast_success'] = "Cập nhật tài khoản thành công!";
    } else {
        $_SESSION['toast_error'] = "Cập nhật tài khoản thất bại! Lỗi: " . mysqli_error($conn);
    }
    
    header('Location: index.php');
    exit();
}

?>  
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật tài khoản #<?=$user['user_id'] ?> - Phòng trọ thành phố Vinh</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script src="../../js/main.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="drawer lg:drawer-open">
        <input id="main-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col min-h-screen">
            <!-- Header -->
            <?php include '../components/Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="max-w-4xl mx-auto card bg-white shadow-xl border rounded-2xl">
                    <div class="card-body px-8 py-8">
                        <div class="flex items-center justify-between mb-6 pb-3 border-b">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">Cập nhật tài khoản #<?= $user['user_id'] ?></h2>
                            <a href="index.php" class="btn btn-sm btn-ghost text-gray-500 hover:text-gray-800">← Quay về Trang chủ</a>
                        </div>
                        <form method="POST" action="" class="space-y-8">
                            <!-- Grid Inputs -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tên -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Tên</span></label>
                                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" placeholder="Nhập tên" class="input input-bordered rounded-xl w-full" required />
                                </div>

                                <!-- Email -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Email</span></label>
                                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Nhập email" class="input input-bordered rounded-xl w-full" required />
                                </div>

                                <!-- Mật khẩu -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Mật khẩu</span></label>
                                    <input type="password" name="password" placeholder="Lần cuối cập nhật <?=date("d/m/Y", strtotime($user['updated_at'])) ?> " class="input input-bordered rounded-xl w-full" />
                                </div>

                                <!-- Quyền tài khoản -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Quyền tài khoản</span></label>
                                    <select name="role" class="select select-bordered rounded-xl w-full" required>
                                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                        <option value="manager" <?= $user['role'] == 'manager' ? 'selected' : '' ?>>Manager</option>
                                    </select>
                                </div>

                                <!-- Trạng thái -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Trạng thái</span></label>
                                    <select name="is_active" class="select select-bordered rounded-xl w-full" required>
                                        <option value="1" <?= $user['is_active'] == 1 ? 'selected' : '' ?>>Đang hoạt động</option>
                                        <option value="0" <?= $user['is_active'] == 0 ? 'selected' : '' ?>>Ngừng hoạt động</option>
                                    </select>
                                </div>

                                <!-- Hình ảnh -->
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Ảnh</span></label>
                                    <div class="flex items-center gap-4">
                                        <input id="imageInput" type="text" name="images" value="<?= htmlspecialchars($user['images']) ?>" placeholder="Chọn file hình ảnh" class="input input-bordered rounded-xl w-full" required />
                                        <button type="button" onclick="openFileManager()" class="bg-cyan-500 hover:bg-cyan-600 text-white text-sm px-4 py-2 rounded-xl whitespace-nowrap">
                                            Chọn ảnh
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="flex justify-end mt-8">
                                <button type="submit" class="btn btn-primary px-10 py-3 rounded-xl shadow text-lg font-semibold bg-gradient-to-r from-cyan-500 to-emerald-500 border-0 hover:from-cyan-600 hover:to-emerald-600 transition-all duration-150">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
            <?php include '../components/Footer.php'; ?>
        </div>
        <?php include '../components/Sidebar.php'; ?>
    </div>

    <!-- File Manager Modal -->
    <dialog id="fileManagerModal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl p-0 h-[80vh] rounded-2xl overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                <h3 class="font-bold text-lg">Quản lý tập tin</h3>
                <button type="button" onclick="closeFileManager()" class="btn btn-sm btn-circle btn-ghost">✕</button>
            </div>
            <div class="h-[calc(80vh-60px)]">
                <iframe src="http://localhost/casestudy/admin/uploads/tinyfilemanager.php?p=admin%2Fuploads%2Fimage" frameborder="0" class="w-full h-full"></iframe>
            </div>
        </div>
    </dialog>

    <script>
        function openFileManager() {
            document.getElementById('fileManagerModal').showModal();
        }

        function closeFileManager() {
            document.getElementById('fileManagerModal').close();
        }

        window.addEventListener('message', function (e) {
            if (e.data && typeof e.data === 'string' && e.data.includes('/casestudy/admin/uploads/image/')) {
                document.getElementById('imageInput').value = e.data;
                closeFileManager();
            }
        });
    </script>
</body>
</html>