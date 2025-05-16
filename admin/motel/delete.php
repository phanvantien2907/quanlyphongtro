<?php
session_start();
require_once '../../config/connection.php';
if (!isset($_SESSION['name'])) {
    $_SESSION['toast_error'] = "Bạn cần đăng nhập để thực hiện chức năng này!";
    header('Location: ../auth/login.php');
    exit();
}

$id = (int)($_GET['id'] ?? 0);
$sql = mysqli_query($conn, "DELETE FROM motel WHERE id = $id");
if ($sql) {
    $_SESSION['toast_success'] = "Xóa phòng trọ thành công!";
} else {
    $_SESSION['toast_error'] = "Xóa phòng trọ thất bại! Lỗi: " . mysqli_error($conn);
}

header('Location: index.php');
exit();