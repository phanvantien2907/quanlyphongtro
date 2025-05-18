<?php
// filepath: d:\laragon\www\CaseStudy\admin\users\detail.php
require '../../config/connection.php';
$id = (int)($_GET['id'] ?? 0);
$sql = mysqli_query($conn, "SELECT * from users WHERE user_id = $id") or die("Lỗi truy vấn");
$result = mysqli_fetch_assoc($sql);
?>

<div class="bg-white p-6 rounded-lg">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Thông tin cơ bản -->
    <div class="flex flex-col items-center md:items-start">
      <!-- Ảnh đại diện -->
      <div class="avatar mb-4">
        <div class="w-24 h-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 overflow-hidden">
          <?php if (!empty($result['images'])): ?>
            <img src="<?= $result['images'] ?>" alt="<?= $result['name'] ?>" class="object-cover w-full h-full" />
          <?php else: ?>
            <div class="bg-gray-200 w-full h-full flex items-center justify-center">
              <span class="text-gray-500 text-xs">No Image</span>
            </div>
          <?php endif; ?>
        </div>
      </div>
      
      <h2 class="text-xl font-bold text-center md:text-left"><?= $result['name'] ?></h2>
      
      <div class="badge badge-primary mt-2"><?= $result['role'] ?></div>
      
      <?php if($result['is_active'] == 1): ?>
        <div class="badge badge-success mt-2">Đang hoạt động</div>
      <?php else: ?>
        <div class="badge badge-error mt-2">Không hoạt động</div>
      <?php endif; ?>
    </div>
    
    <div class="space-y-3">
      <div>
        <h3 class="text-sm text-gray-500">Email:</h3>
        <p class="font-medium"><?= $result['email'] ?></p>
      </div>
      
      <div>
        <h3 class="text-sm text-gray-500">Trạng thái tài khoản:</h3>
        <?php if($result['email_verified_at'] == null): ?>
          <span class="badge badge-error mt-1">Chưa xác minh</span>
        <?php else: ?>
          <span class="badge badge-success mt-1">Đã xác minh</span>
        <?php endif; ?>
      </div>
      
      <div>
        <h3 class="text-sm text-gray-500">Ngày tạo tài khoản:</h3>
        <p><?= date('d/m/Y H:i', strtotime($result['created_at'])) ?></p>
      </div>
      
      <div>
        <h3 class="text-sm text-gray-500">Lần cuối cập nhật:</h3>
        <p><?= date('d/m/Y H:i', strtotime($result['updated_at'])) ?></p>
      </div>
    </div>
  </div>
</div>