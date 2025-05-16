<?php
session_start();
require_once '../../config/connection.php';
if(!isset($_SESSION['name'])) {
    header('Location: ..\page\index.php');
    exit();
}
$sql = mysqli_query($conn, "SELECT mt.id, u.name as tên_chủ_trọ, u.images as ảnh_tác_giả, dt.name as khu_vực, mt.title as tên_phòng, mt.price as giá_phòng, mt.description as mô_tả, mt.address as địa_chỉ, mt.images as ảnh, mt.created_at as ngày_đăng,  mt.approve as tình_trạng_phòng
FROM motel mt
JOIN districts dt on mt.district_id = dt.district_id
join users u ON mt.user_id = u.user_id order by mt.created_at desc") or die("Lỗi truy vấn");
$result = mysqli_fetch_all($sql, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="../../js/main.js"></script>
    <title>Quản lý phòng  trọ - Phòng trọ thành phố Vinh</title>
</head>
<body class="bg-gray-100 min-h-screen">
<?php include '../components/toast-message.php'; ?>
    <div class="drawer lg:drawer-open">
        <input id="main-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <?php include '../components/Header.php'; ?>

            <!-- Main Content -->
            <main class="p-6">
                <div class="card bg-white shadow-md mb-8">
                    <div class="card-body">
                        <h2 class="card-title text-xl font-bold text-gray-800 mb-4">Quản lý phòng trọ</h2>
                        <?php include '../motel/add.php'; ?>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th class="bg-gray-50">ID</th>
                                        <th class="bg-gray-50">Phòng trọ</th>
                                        <th class="bg-gray-50">Chủ trọ</th>
                                        <th class="bg-gray-50">Ảnh</th>
                                        <th class="bg-gray-50">Giá (VNĐ/tháng)</th>
                                        <th class="bg-gray-50">Trạng thái</th>
                                        <th class="bg-gray-50">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($result as $item): ?>
                                    <tr>
                                        <td> <?= $i++ ?></td>
                                        <td><?=$item['tên_phòng'] ?></td>
                                        <td><?= $item['tên_chủ_trọ'] ?></td>
                                        <td>
                                        <div class="avatar">
                                        <div class="w-12 h-12 rounded-full overflow-hidden" id="img-container-<?= $i ?>">
                                         <img 
                                        src="<?= $item['ảnh'] ?>" 
                                        alt="<?= $item['tên_phòng'] ?>" 
                                        class="object-cover w-full h-full" 
                                        onerror="this.style.display='none'; document.getElementById('img-container-<?= $i ?>').innerHTML='<div class=\'flex items-center justify-center w-full h-full bg-gray-200 text-xs text-red-500 text-center\'>ảnh đang lỗi</div>';"
                                        />
                                        </div>
                                        </div>
                                     </td>
                                        <td><?= number_format($item['giá_phòng']) ?></td>
                                        <?php if($item['tình_trạng_phòng'] == 1): ?>
                                        <td><span class="badge badge-success">Đã duyệt</span></td>
                                        <?php else: ?>
                                        <td><span class="badge badge-error">Chưa duyệt</span></td>
                                        <?php endif; ?>
                                        <td>
                                        <div class="dropdown dropdown-end">
                                        <div tabindex="0" role="button" class="btn btn-sm btn-info rounded-2xl">Hành động</div>
                                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-36">
                                        <li>
                                        <a href="javascript:void(0)" onclick="copyID('<?= $item['id'] ?>')">
                                        Copy ID
                                        </a>
                                        </li>
                                        <li><a href="detail.php?id=<?= $item['id'] ?>" onclick="openModal(<?= $item['id'] ?>); return false;">Xem</a></li>
                                        <li> <a href="edit.php?id=<?= $item['id'] ?>" onclick="openEditModal(<?= $item['id'] ?>); return false;">Sửa</a></li>
                                        <li><a href="delete.php?id=<?= $item['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng trọ này?')"> Xóa </a></li>
                                        <li><a href="status_motel.php?id=<?= $item['id'] ?>"
                                         onclick="return confirm('Bạn chắc chắn muốn <?=$item['tình_trạng_phòng'] == 1 ? 'ẩn' : 'hiện' ?> phòng trọ này?')"> 
                                         <?=$item['tình_trạng_phòng'] == 1 ? 'Ẩn' : 'Hiện' ?> </a></li>
                                        </ul>
                                        </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>               
            </main>

            <!-- Footer -->
           <?php include '../components/Footer.php'; ?>
           <div id="dynamic-toast" class="toast toast-top toast-end z-50 hidden"></div>

        <!-- Sidebar -->
       <?php include '../components/Sidebar.php'; ?>
    </div>
</body> 
    <dialog id="modalDetail" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
    <div id="modalContent">Đang tải dữ liệu...</div>
    <div class="modal-action">
    <form method="dialog">
    <button class="btn">Đóng</button>
    </form>
    </div>
  </div>
</dialog>

<dialog id="modalEdit" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box">
    <div id="modalEditContent">Đang tải dữ liệu...</div>
    <div class="modal-action">
      <form method="dialog">
        <button class="btn">Đóng</button>
      </form>
    </div>
  </div>
</dialog>

<script>
function openModal(id) {
fetch(`detail.php?id=${id}`)
.then(res => res.text())
.then(html => {
modalContent.innerHTML = html;
modalDetail.showModal();
});
}
</script>


</html>