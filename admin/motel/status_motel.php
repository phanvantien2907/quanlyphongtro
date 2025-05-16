<?php
session_start();
require_once '../../config/connection.php';
if (!isset($_SESSION['name'])) {
    $_SESSION['toast_error'] = "Bạn cần đăng nhập để thực hiện chức năng này!";
    header('Location: ../auth/login.php');
    exit();
}

$id = (int)($_GET['id'] ?? 0);
$sql = mysqli_query($conn, "SELECT mt.approve FROM motel mt WHERE mt.id = $id;") or die("Lỗi truy vấn");
if($result = mysqli_fetch_assoc($sql)) {
    $new_status = $result['approve'] == 1 ? 0 : 1;
    $sql = mysqli_query($conn, "UPDATE motel SET approve = $new_status WHERE id = $id;") or die("Lỗi update");
    $_SESSION['toast_success'] = $new_status == 1 ? "Hiện phòng trọ thành công! " : "Ẩn phòng trọ thành công! ";
}
else {
    $_SESSION['toast_error'] = "Không tìm thấy phòng trọ!";
}

header('Location: index.php');
exit();