<?php
require_once '../../config/connection.php';
if(!isset($_SESSION['name'])) {
    header('Location: ..\page\index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
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
    $count_view = 0;
    $created_at = date('Y-m-d H:i:s');
    
    $sql = mysqli_query($conn, "INSERT INTO motel 
            (title, description, price, area, count_view, address, latlng, images, utilities, created_at, phone, approve, user_id, district_id) 
            VALUES 
            ('$title', '$description', $price, $area, $count_view, '$address', '$latlng', 
            '$images', '$utilities', '$created_at', '$phone', $approve, $user_id, $district_id)") or die("Lỗi thêm phòng trọ");
        
    if ($sql) {
        $_SESSION['toast_success'] = "Thêm phòng trọ thành công!";
    } else {
        $_SESSION['toast_error'] = "Thêm phòng trọ thất bại! Lỗi: " . mysqli_error($conn);
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$users_query = mysqli_query($conn, "SELECT user_id, name FROM users WHERE is_active = 1");
$districts_query = mysqli_query($conn, "SELECT district_id, name FROM districts");
?>
<button class="btn btn-accent w-[150px] rounded-full shadow-md hover:shadow-lg transition-all duration-300" onclick="my_modal_5.showModal()">
  </svg>
  Thêm phòng trọ
</button>
<dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box w-11/12 max-w-5xl bg-white rounded-2xl shadow-xl">
    <h3 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Thêm mới phòng trọ</h3>
    <form method="POST" action="" class="space-y-6">
      <!-- Grid inputs -->
      <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="my_modal_5.close()">✕</button>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tiêu đề -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Tên phòng trọ</span></label>
          <input type="text" name="title" placeholder="Nhập tiêu đề phòng trọ" class="input input-bordered rounded-xl w-full" required />
        </div>

        <!-- Chủ trọ -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Chủ trọ</span></label>
          <select name="user_id" class="select select-bordered rounded-xl w-full" required>
            <option value="" disabled selected>-- Chọn chủ trọ --</option>
            <?php while($user = mysqli_fetch_assoc($users_query)): ?>
              <option value="<?= $user['user_id'] ?>"><?= $user['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Giá -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Giá phòng</span></label>
          <div class="relative">
            <input type="number" name="price" placeholder="Nhập giá phòng" class="input input-bordered rounded-xl pl-11 w-full" required />
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">VNĐ</span>
          </div>
        </div>

        <!-- Diện tích -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Diện tích (m²)</span></label>
          <div class="relative">
            <input type="number" name="area" placeholder="Nhập diện tích" class="input input-bordered rounded-xl pr-10 w-full" required />
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">m²</span>
          </div>
        </div>

        <!-- Số điện thoại -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Số điện thoại</span></label>
          <input type="text" name="phone" placeholder="Nhập số điện thoại" class="input input-bordered rounded-xl w-full" required />
        </div>

        <!-- Hình ảnh -->
        <div class="form-control">
        <label class="label">
          <span class="label-text font-medium text-gray-700">Ảnh</span>
        </label>
        <div class="flex items-center gap-2">
          <input id="imageInput" type="text" name="images"s placeholder="Chọn file hình ảnh" class="input input-bordered rounded-xl w-[85%] py-2 px-3 text-sm"   required />
          <button type="button" onclick="openFileManager()" class="bg-[#2dd4bf] hover:bg-[#2cc5b2] text-white text-sm px-3 py-2 rounded-xl whitespace-nowrap cursor-pointer">
            Chọn ảnh
          </button>
        </div>
      </div>

        <!-- Địa chỉ -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Địa chỉ chi tiết</span></label>
          <input type="text" name="address" placeholder="Nhập địa chỉ" class="input input-bordered rounded-xl w-full" required />
        </div>

        <!-- Tọa độ -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Tọa độ</span></label>
          <input type="text" name="latlng" placeholder="Ví dụ: 10.7525, 106.6667" class="input input-bordered rounded-xl w-full" />
        </div>

        <!-- Quận -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Quận/Huyện</span></label>
          <select name="district_id" class="select select-bordered rounded-xl w-full" required>
            <option value="" disabled selected>-- Chọn quận/huyện --</option>
            <?php while($district = mysqli_fetch_assoc($districts_query)): ?>
              <option value="<?= $district['district_id'] ?>"><?= $district['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Trạng thái -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Trạng thái</span></label>
          <select name="approve" class="select select-bordered rounded-xl w-full" required>
            <option value="1">Đã duyệt</option>
            <option value="0">Chờ duyệt</option>
          </select>
        </div>
      </div>

      <!-- Tiện ích và mô tả -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tiện ích -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Tiện ích</span></label>
          <input type="text" name="utilities" placeholder="Ví dụ: Wifi, Gác lửng, Máy lạnh" class="input input-bordered rounded-xl w-full" />
        </div>

        <!-- Mô tả -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Mô tả</span></label>
          <textarea name="description" placeholder="Nhập mô tả chi tiết" rows="4" class="textarea textarea-bordered rounded-xl w-full resize-none"></textarea>
        </div>
      </div>

      <!-- Submit -->
      <div class="flex justify-end">
        <button type="submit" class="btn btn-primary px-8 rounded-xl">Lưu</button>
      </div>
    </form>
  </div>
             
</dialog>
<dialog id="fileManagerModal" class="modal">
  <div class="modal-box w-11/12 max-w-5xl p-0 h-[80vh]">
    <div class="flex justify-between items-center p-4 border-b">
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
</script>