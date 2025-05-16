<?php
session_start();
require_once '../../config/connection.php';
if(!isset($_SESSION['name'])) {
    header('Location: ../page/index.php');
    exit();
}
$id = (int)($_GET['id'] ?? 0);
$motel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM motel WHERE id = $id"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $area = $_POST['area'];
    $address = $_POST['address'];
    $latlng = $_POST['latlng'];
    $images = $_POST['images'];
    $utilities = $_POST['utilities'];
    $phone = $_POST['phone'];
    $approve = $_POST['approve'];
    $user_id = $_POST['user_id'];
    $district_id = $_POST['district_id'];

    $sql = mysqli_query($conn, "UPDATE motel SET 
        title='$title', description='$description', price=$price, area=$area, address='$address', latlng='$latlng', images='$images', utilities='$utilities', phone='$phone', approve=$approve, user_id=$user_id, district_id=$district_id
        WHERE id=$id") or die("Lỗi sửa phòng trọ");
        
    if ($sql) {
        $_SESSION['toast_success'] = "Sửa phòng trọ thành công!";
    } else {
        $_SESSION['toast_error'] = "Sửa phòng trọ thất bại! Lỗi: " . mysqli_error($conn);
    }
    
    header('Location: index.php');
    exit();
}

$users_query = mysqli_query($conn, "SELECT user_id, name FROM users WHERE is_active = 1");
$districts_query = mysqli_query($conn, "SELECT district_id, name FROM districts");
?>  
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa phòng trọ #<?=$motel['id'] ?> - Phòng trọ thành phố Vinh</title>
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
                <div class="max-w-3xl mx-auto card bg-white shadow-xl border rounded-2xl">
                    <div class="card-body px-8 py-8">
                        <div class="flex items-center justify-between mb-6 pb-3 border-b">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">Chỉnh sửa phòng trọ #<?= $motel['id'] ?></h2>
                            <a href="index.php" class="btn btn-sm btn-ghost text-gray-500 hover:text-gray-800">← Quay về Trang chủ</a>
                        </div>
                        <form method="POST" action="" class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Tên phòng trọ</span></label>
                                    <input type="text" name="title" value="<?= htmlspecialchars($motel['title']) ?>" placeholder="Nhập tiêu đề phòng trọ" class="input input-bordered rounded-xl w-full" required />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Chủ trọ</span></label>
                                    <select name="user_id" class="select select-bordered rounded-xl w-full" required>
                                        <option value="" disabled>-- Chọn chủ trọ --</option>
                                        <?php
                                        mysqli_data_seek($users_query, 0);
                                        while($user = mysqli_fetch_assoc($users_query)): ?>
                                            <option value="<?= $user['user_id']?>" <?=$user['user_id'] == $motel['user_id'] ? 'selected' : '' ?> ><?= htmlspecialchars($user['name']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Giá phòng</span></label>
                                    <div class="relative">
                                        <input type="number" name="price" value="<?= $motel['price'] ?>" placeholder="Nhập giá phòng" class="input input-bordered rounded-xl pl-12 w-full" min="0" required />
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">VNĐ</span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Diện tích (m²)</span></label>
                                    <div class="relative">
                                        <input type="number" name="area" value="<?= $motel['area'] ?>" min="0" placeholder="Nhập diện tích" class="input input-bordered rounded-xl pr-12 w-full" required />
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">m²</span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Số điện thoại</span></label>
                                    <input type="text" name="phone" value="<?= htmlspecialchars($motel['phone']) ?>" placeholder="Nhập số điện thoại" class="input input-bordered rounded-xl w-full" required />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Ảnh</span></label>
                                    <div class="flex items-center gap-2">
                                        <input id="imageInput" type="text" name="images" value="<?= htmlspecialchars($motel['images']) ?>" placeholder="Chọn file hình ảnh" class="input input-bordered rounded-xl w-[85%] py-2 px-3 text-sm" required />
                                        <button type="button" onclick="openFileManager()" class="bg-cyan-500 hover:bg-cyan-600 text-white text-sm px-4 py-2 rounded-xl whitespace-nowrap cursor-pointer shadow">
                                            Chọn ảnh
                                        </button>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Địa chỉ chi tiết</span></label>
                                    <input type="text" name="address" value="<?= htmlspecialchars($motel['address']) ?>" placeholder="Nhập địa chỉ" class="input input-bordered rounded-xl w-full" required />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Tọa độ</span></label>
                                    <input type="text" name="latlng" value="<?= htmlspecialchars($motel['latlng']) ?>" placeholder="Ví dụ: 10.7525, 106.6667" class="input input-bordered rounded-xl w-full" />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Quận/Huyện</span></label>
                                    <select name="district_id" class="select select-bordered rounded-xl w-full" required>
                                        <option value="" disabled>-- Chọn quận/huyện --</option>
                                        <?php
                                        mysqli_data_seek($districts_query, 0);
                                        while($district = mysqli_fetch_assoc($districts_query)): ?>
                                            <option value="<?= $district['district_id'] ?>" <?=$district['district_id'] == $motel['district_id'] ? 'selected' : ''  ?> ><?= htmlspecialchars($district['name']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Trạng thái</span></label>
                                    <select name="approve" class="select select-bordered rounded-xl w-full" required>
                                        <option value="1" <?=$motel['approve'] == 1 ? 'selected': '' ?>>Đã duyệt</option>
                                        <option value="0" <?=$motel['approve'] == 0 ? 'selected': '' ?>>Chờ duyệt</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Tiện ích</span></label>
                                    <input type="text" name="utilities" value="<?= htmlspecialchars($motel['utilities']) ?>" placeholder="Ví dụ: Wifi, Gác lửng, Máy lạnh" class="input input-bordered rounded-xl w-full" />
                                </div>
                                <div class="form-control">
                                    <label class="label"><span class="label-text font-medium text-gray-700">Mô tả</span></label>
                                    <textarea name="description" placeholder="Nhập mô tả chi tiết" rows="4" class="textarea textarea-bordered rounded-xl w-full resize-none"><?= htmlspecialchars($motel['description']) ?></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end mt-8">
                                <button type="submit" class="btn btn-primary px-10 py-3 rounded-xl shadow text-lg font-semibold bg-gradient-to-r from-cyan-500 to-emerald-500 border-0 hover:from-cyan-600 hover:to-emerald-600 transition-all duration-150">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
            <?php include '../components/Footer.php'; ?>
            <!-- Toast for JS -->
            <div id="dynamic-toast" class="toast toast-top toast-end z-50 hidden"></div>
        </div>
        <?php include '../components/Sidebar.php'; ?>
    </div>
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
      function selectFileFromManager(url) {
        window.parent.postMessage(url, '*');
      }
      // Tự động ẩn toast sau 2.5s
      document.querySelectorAll('.toast').forEach((el) => {
        setTimeout(() => { el.style.display = 'none'; }, 2500);
      });
    </script>
</body>
</html>