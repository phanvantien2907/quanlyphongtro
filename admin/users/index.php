<?php
session_start();
require_once '../../config/connection.php';
if(!isset($_SESSION['name'])) {
    header('Location: ..\page\index.php');
    exit();
}
$sql = mysqli_query($conn, "SELECT * from users order by created_at desc") or die("Lỗi truy vấn");
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
    <title>Quản lý tài khoản - Phòng trọ thành phố Vinh</title>
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
                        <h2 class="card-title text-xl font-bold text-gray-800 mb-4">Quản lý tài khoản</h2>
                        <?php include '../users/add.php'; ?>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th class="bg-gray-50">ID</th>
                                        <th class="bg-gray-50">Tên</th>
                                        <th class="bg-gray-50">Email</th>
                                        <th class="bg-gray-50">Quyền tài khoản</th>
                                        <th class="bg-gray-50">Xác minh tài khoản</th>
                                        <th class="bg-gray-50">Ảnh</th>
                                        <th class="bg-gray-50">Trạng thái</th>
                                        <th class="bg-gray-50">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($result as $item): ?>
                                    <tr>
                                        <td> <?= $i++ ?></td>
                                        <td><?=$item['name'] ?></td>
                                        <td><?= $item['email'] ?></td>
                                        <td><?= $item['role'] ?></td>
                                        </td>
                                        <?php if($item['email_verified_at'] == null): ?>
                                        <td><span class="badge badge-error">Chưa xác minh</span></td>
                                        <?php else: ?>
                                        <td><span class="badge badge-success">Đã xác minh</span></td>
                                        <?php endif; ?>
                                        <td>
                                        <div class="avatar">
                                        <div class="w-12 h-12 rounded-full overflow-hidden" id="img-container-<?= $i ?>">
                                         <img 
                                        src="<?= $item['images'] ?>" 
                                        alt="<?= $item['name'] ?>" 
                                        class="object-cover w-full h-full" 
                                        onerror="this.style.display='none'; document.getElementById('img-container-<?= $i ?>').innerHTML='<div class=\'flex items-center justify-center w-full h-full bg-gray-200 text-xs text-red-500 text-center\'>ảnh đang lỗi</div>';"
                                        />
                                        </div>
                                        </div>
                                        </td>
                                        <?php if($item['is_active'] == 1): ?>
                                        <td><span class="badge badge-success">Đang hoạt động</span></td>
                                        <?php else: ?>
                                        <td><span class="badge badge-error">Không hoạt động</span></td>
                                        <?php endif; ?>
                                        <td>
                                        <div class="dropdown dropdown-end">
                                        <div tabindex="0" role="button" class="btn btn-sm btn-info rounded-2xl">Hành động</div>
                                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-36">
                                        <li>
                                        <a href="javascript:void(0)" onclick="copyID('<?= $item['user_id'] ?>')">
                                        Copy ID
                                        </a>
                                        </li>
                                        <li><a href="detail.php?id=<?= $item['user_id'] ?>" onclick="openModal(<?= $item['user_id'] ?>); return false;">Xem</a></li>
                                        <li> <a href="edit.php?id=<?= $item['user_id'] ?>" onclick="openEditModal(<?= $item['user_id'] ?>); return false;">Sửa</a></li>
                                        <li><a href="delete.php?id=<?= $item['user_id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng trọ này?')"> Xóa </a></li>
                                        <li><a href="status_motel.php?id=<?= $item['user_id'] ?>"
                                         onclick="return confirm('Bạn chắc chắn muốn <?=$item['is_active'] == 1 ? 'khóa' : 'mở' ?> người dùng này?')"> 
                                         <?=$item['is_active'] == 1 ? 'Khóa' : 'Mở' ?> </a></li>
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