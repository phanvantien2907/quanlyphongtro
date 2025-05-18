<?php
session_start();
require_once '../config/connection.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['toast_error'] = "Bạn cần đăng nhập để đăng tin!";
    header('Location: ../auth/login.php');
    exit();
}

$current_user_id = (int)$_SESSION['user_id'];
$current_user_role = $_SESSION['role'] ?? 'user'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
    $area = isset($_POST['area']) ? (float)$_POST['area'] : 0;
    $address = mysqli_real_escape_string($conn, trim($_POST['address'] ?? ''));
    $latlng = mysqli_real_escape_string($conn, trim($_POST['latlng'] ?? ''));
    $images = mysqli_real_escape_string($conn, trim($_POST['images'] ?? ''));
    $utilities = mysqli_real_escape_string($conn, trim($_POST['utilities'] ?? ''));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));

    $post_user_id = ($current_user_role === 'admin' && isset($_POST['user_id'])) ? (int)$_POST['user_id'] : $current_user_id;

    $district_id = isset($_POST['district_id']) ? (int)$_POST['district_id'] : 0;

    $approve = ($current_user_role === 'admin' && isset($_POST['approve'])) ? 1 : 0;

    $count_view = 0;
    $created_at = date('Y-m-d H:i:s');

    if (empty($errors)) {
        $sql = "INSERT INTO motel 
                (title, description, price, area, count_view, address, latlng, images, utilities, created_at, phone, approve, user_id, district_id) 
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssddissssssiii",
                $title,
                $description,
                $price,
                $area,
                $count_view,
                $address,
                $latlng,
                $images,
                $utilities,
                $created_at,
                $phone,
                $approve,
                $post_user_id,
                $district_id
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['toast_success'] = "Thêm phòng trọ thành công! Tin của bạn sẽ sớm được duyệt.";
                if ($approve === 1) {
                    $_SESSION['toast_success'] = "Thêm và duyệt phòng trọ thành công!";
                }
                header("Location: index.php"); 
                exit();
            } else {
                $_SESSION['toast_error'] = "Thêm phòng trọ thất bại! Lỗi: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['toast_error'] = "Lỗi chuẩn bị truy vấn: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['toast_error'] = implode("<br>", $errors);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$users_query_result = mysqli_query($conn, "SELECT user_id, name FROM users WHERE role='landlord' OR role='admin' ORDER BY name ASC");
$districts_query_result = mysqli_query($conn, "SELECT district_id, name FROM districts ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="vi" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <title>Đăng tin phòng trọ mới - Tìm phòng trọ thành phố Vinh</title>

</head>

<body class="bg-gray-100 min-h-screen">
    <?php include '../components/Header.php'; ?>
    <?php include '../admin/components/toast-message.php';
    ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white p-6 md:p-10 rounded-2xl shadow-xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-indigo-600">Đăng tin phòng trọ mới</h1>
                <p class="text-gray-600 mt-2">Chia sẻ thông tin chi tiết để thu hút người thuê tiềm năng.</p>
            </div>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-8">

                <div>
                    <h2 class="form-section-title">Thông tin cơ bản</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label" for="title"><span class="label-text">Tiêu đề tin đăng</span><span class="required-star">*</span></label>
                            <input type="text" id="title" name="title" placeholder="VD: Phòng trọ cao cấp gần Đại học Vinh" class="input input-bordered w-full rounded-2xl" required />
                        </div>

                        <div class="form-control">
                            <label class="label" for="user_id"><span class="label-text">Chủ trọ</span><span class="required-star">*</span></label>
                            <select id="user_id" name="user_id" class="select select-bordered w-full rounded-2xl" required <?php echo ($current_user_role !== 'admin') ? 'disabled' : ''; ?>>
                                <?php if ($current_user_role === 'admin'): ?>
                                    <option disabled selected value="">-- Chọn chủ trọ --</option>
                                    <?php while ($user = mysqli_fetch_assoc($users_query_result)): ?>
                                        <option value="<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <option value="<?php echo $current_user_id; ?>" selected><?php echo htmlspecialchars($_SESSION['name']); ?></option>
                                <?php endif; ?>
                            </select>
                            <?php if ($current_user_role !== 'admin'): ?>
                                <input type="hidden" name="user_id" value="<?php echo $current_user_id; ?>">
                            <?php endif; ?>
                        </div>

                        <div class="form-control">
                            <label class="label" for="price"><span class="label-text">Giá phòng (VNĐ/tháng)</span><span class="required-star">*</span></label>
                            <input type="number" id="price" name="price" placeholder="VD: 2500000" class="input input-bordered w-full rounded-2xl" min="0" step="50000" required />
                        </div>

                        <div class="form-control">
                            <label class="label" for="area"><span class="label-text">Diện tích (m²)</span><span class="required-star">*</span></label>
                            <input type="number" id="area" name="area" placeholder="VD: 25" class="input input-bordered w-full rounded-2xl" min="1" step="0.5" required />
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="form-section-title">Địa chỉ và Liên hệ</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label" for="phone"><span class="label-text">Số điện thoại liên hệ</span><span class="required-star">*</span></label>
                            <input type="tel" id="phone" name="phone" placeholder="VD: 09xxxxxxxx" class="input input-bordered w-full rounded-2xl" required />
                        </div>

                        <div class="form-control">
                            <label class="label" for="district_id"><span class="label-text">Quận/Huyện</span><span class="required-star">*</span></label>
                            <select id="district_id" name="district_id" class="select select-bordered w-full rounded-2xl" required>
                                <option disabled selected value="">-- Chọn quận/huyện --</option>
                                <?php mysqli_data_seek($districts_query_result, 0); // Reset pointer if used before 
                                ?>
                                <?php while ($district = mysqli_fetch_assoc($districts_query_result)): ?>
                                    <option value="<?php echo $district['district_id']; ?>"><?php echo htmlspecialchars($district['name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label" for="address"><span class="label-text">Địa chỉ chi tiết</span><span class="required-star">*</span></label>
                            <input type="text" id="address" name="address" placeholder="VD: Số 123, đường ABC, phường XYZ" class="input input-bordered w-full rounded-2xl" required />
                        </div>

                        <div class="form-control md:col-span-2">
                            <label class="label" for="latlng"><span class="label-text">Tọa độ </span></label>
                            <input type="text" id="latlng" name="latlng" placeholder="VD: 18.6790, 105.6817 (Tùy chọn)" class="input input-bordered w-full rounded-2xl" />
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="form-section-title">Hình ảnh và Tiện ích</h2>
                    <div class="grid grid-cols-1 gap-6">
                        <div class="form-control">
                            <label class="label" for="imageInput"><span class="label-text">Hình ảnh</span><span class="required-star">*</span></label>
                            <div class="flex items-stretch gap-2">
                                <input type="text" id="imageInput" name="images" placeholder="URL hình ảnh (chọn từ trình quản lý)" class="input input-bordered w-full rounded-2xl" required />
                                <button type="button" onclick="openFileManager()" class="btn btn-primary rounded-2xl no-animation">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M5.25 12A2.25 2.25 0 0 0 7.5 14.25h5A2.25 2.25 0 0 0 14.75 12V9.75A2.25 2.25 0 0 0 12.5 7.5h-5A2.25 2.25 0 0 0 5.25 9.75v2.25Zm0-2.25a.75.75 0 0 1 .75-.75h5a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-.75.75h-5a.75.75 0 0 1-.75-.75V9.75Z" />
                                        <path fill-rule="evenodd" d="M5 3.5A2.5 2.5 0 0 1 7.5 1h5A2.5 2.5 0 0 1 15 3.5v13A2.5 2.5 0 0 1 12.5 19h-5A2.5 2.5 0 0 1 5 16.5v-13Zm2.5-.5a1 1 0 0 0-1 1v13a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-13a1 1 0 0 0-1-1h-5Z" clip-rule="evenodd" />
                                    </svg>
                                    Chọn ảnh
                                </button>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label" for="utilities"><span class="label-text">Tiện ích</span></label>
                            <input type="text" id="utilities" name="utilities" placeholder="VD: Wifi, Điều hòa, Nóng lạnh, Ban công" class="input input-bordered w-full rounded-2xl" />
                        </div>

                        <div class="form-control">
                            <label class="label" for="description"><span class="label-text">Mô tả chi tiết</span></label>
                            <textarea id="description" name="description" placeholder="Mô tả thêm về phòng trọ, nội thất, an ninh, khu vực xung quanh..." class="textarea textarea-bordered w-full rounded-2xl" rows="4"></textarea>
                        </div>
                        <?php if ($current_user_role === 'admin'): ?>
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="checkbox" name="approve" value="1" class="checkbox checkbox-primary" />
                                    <span class="label-text">Duyệt tin này ngay</span>
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-6">
                    <a href="index.php" class="btn btn-ghost rounded-2xl">Hủy bỏ</a>
                    <button type="submit" class="btn btn-primary rounded-2xl px-6">Đăng tin</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../components/Footer.php'; ?>

    <dialog id="fileManagerModal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl p-0">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-10">✕</button>
            </form>
            <h3 class="font-bold text-lg p-4 border-b bg-base-100 sticky top-0 z-[5]">Quản lý tập tin</h3>
            <div class="h-[calc(80vh-68px)] overflow-y-auto">
                <iframe src="http://localhost/casestudy/admin/uploads/tinyfilemanager.php?p=admin%2Fuploads%2Fimage" frameborder="0" class="w-full h-full"></iframe>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>Đóng</button>
        </form>
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
</script>
</body>

</html>