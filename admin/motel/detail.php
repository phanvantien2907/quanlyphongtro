<?php
require '../../config/connection.php';
$id = (int)($_GET['id'] ?? 0);
$sql = mysqli_query($conn, "SELECT mt.id, u.name as tên_chủ_trọ, u.images as ảnh_tác_giả, dt.name as khu_vực, mt.title as tên_phòng, mt.price as giá_phòng, mt.description as mô_tả, mt.address as địa_chỉ, mt.images as ảnh, mt.created_at as ngày_đăng, mt.approve AS tình_trạng_phòng
FROM motel mt
JOIN districts dt on mt.district_id = dt.district_id
join users u ON mt.user_id = u.user_id
WHERE mt.id = $id") or die("Lỗi truy vấn");
$result = mysqli_fetch_assoc($sql);
?>

<h2 class="text-xl font-bold mb-2"><?= $result['tên_phòng'] ?></h2>
<img src="<?= $result['ảnh'] ?>" class="w-full h-48 object-cover rounded-xl mb-3">
<p><strong>Giá phòng:</strong> <?= number_format($result['giá_phòng']) ?> <sup>đ</sup> </p>
<p><strong>Địa chỉ:</strong> <?= $result['địa_chỉ'] ?></p>
<p><strong>Khu vực:</strong> <?= $result['khu_vực'] ?></p>
<p class="mt-2">
<strong class="mr-1">Mô tả:</strong>
<span class="inline"><?=$result['mô_tả'] ?></span>
</p>

<p class="mt-2">
  <strong>Tình trạng:</strong>
  <?php if ($result['tình_trạng_phòng'] == 1): ?>
    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
      Đã duyệt
    </span>
  <?php else: ?>
    <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
      Chưa duyệt
    </span>
  <?php endif; ?>
</p>
<div class="flex items-center gap-3 mt-4">
<img src="<?=$result['ảnh_tác_giả'] ?>" class="w-10 h-10 rounded-full">
<div>
<p class="font-semibold"><?= ($result['tên_chủ_trọ'])?></p>
<p class="text-sm text-gray-500">Ngày đăng: <?= date("d-m-Y", strtotime($result['ngày_đăng'])) ?></p>
 </div>
</div>
