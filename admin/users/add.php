<?php
require_once '../../config/connection.php';
if (!isset($_SESSION['name'])) {
  header('Location: ..\page\index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Lấy dữ liệu từ form
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = $_POST['role'];
  $images = $_POST['images'];
  $email_verified_at = date('Y-m-d H:i:s');
  $created_at = date('Y-m-d H:i:s');
  $updated_at = date('Y-m-d H:i:s');
  $remember_token = '';
  $is_active = $_POST['is_active'];
  $password = password_hash($password, PASSWORD_BCRYPT);
  $remember_token = bin2hex(random_bytes(16));
  $sql = mysqli_query($conn, "INSERT INTO users 
  (name, email, password, role, images, email_verified_at, remember_token, is_active, created_at, updated_at)
  VALUES(
    '$name',
    '$email',
    '$password',
    '$role',
    '$images',
    '$email_verified_at',
    '$remember_token',
    '$is_active',
    '$created_at',
    '$updated_at'
)") or die("Lỗi thêm tài khoản: " . mysqli_error($conn));

  if ($sql) {
    $_SESSION['toast_success'] = "Thêm tài khoản thành công!";
  } else {
    $_SESSION['toast_error'] = "Thêm tài khoản thất bại! Lỗi: " . mysqli_error($conn);
  }

  header('Location: ' . $_SERVER['PHP_SELF']);
  exit();
}

?>
<button class="btn btn-accent w-[150px] rounded-full shadow-md hover:shadow-lg transition-all duration-300" onclick="my_modal_5.showModal()">
  </svg>
  Thêm tài khoản
</button>
<dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box w-full max-w-4xl bg-white rounded-2xl shadow-xl">
    <h3 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Thêm mới tài khoản</h3>
    <form method="POST" action="" class="space-y-6">
      <!-- Close Button -->
      <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="my_modal_5.close()">✕</button>

      <!-- Grid Inputs -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tên -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Tên</span></label>
          <input type="text" name="name" placeholder="Nhập tên tài khoản" class="input input-bordered rounded-xl w-full" required />
        </div>

        <!-- Hình ảnh -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Ảnh</span></label>
          <div class="flex items-center gap-4">
            <input id="imageInput" type="text" name="images" placeholder="Chọn file hình ảnh" class="input input-bordered rounded-xl w-full" required />
            <button type="button" onclick="openFileManager()" class="bg-[#2dd4bf] hover:bg-[#2cc5b2] text-white text-sm px-4 py-2 rounded-xl whitespace-nowrap">
              Chọn ảnh
            </button>
          </div>
        </div>

        <!-- Email -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Email</span></label>
          <input type="email" name="email" placeholder="Nhập email" class="input input-bordered rounded-xl w-full" required />
        </div>

        <!-- Mật khẩu -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Mật khẩu</span></label>
          <input type="password" name="password" placeholder="Nhập mật khẩu" class="input input-bordered rounded-xl w-full" required />
        </div>

        <!-- Quyền tài khoản -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Quyền tài khoản</span></label>
          <select name="role" class="select select-bordered rounded-xl w-full" required>
            <option value="admin">admin</option>
            <option value="user">user</option>
            <option value="manager">manager</option>
          </select>
        </div>

        <!-- Trạng thái -->
        <div class="form-control">
          <label class="label"><span class="label-text font-medium text-gray-700">Trạng thái</span></label>
          <select name="is_active" class="select select-bordered rounded-xl w-full" required>
            <option value="1">Đang hoạt động</option>
            <option value="0">Ngừng hoạt động</option>
          </select>
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

  window.addEventListener('message', function(e) {
    if (e.data && typeof e.data === 'string' && e.data.includes('/casestudy/admin/uploads/image/')) {
      document.getElementById('imageInput').value = e.data;
      closeFileManager();
    }
  });

  function selectFileFromManager(url) {
    window.parent.postMessage(url, '*');
  }
</script>