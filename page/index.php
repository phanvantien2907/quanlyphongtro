<?php
session_start();
require_once '../config/connection.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
$sql_motel = mysqli_query($conn, "SELECT dt.district_id as id, dt.name as tên from districts dt") or die("Lỗi truy vấn");
$motel = mysqli_fetch_all($sql_motel, MYSQLI_ASSOC);
$is_search = isset($_GET['submit_search']) && $_GET['submit_search'] == 1;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Tìm phòng trọ thành phố Vinh</title>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include '../components/Header.php'; ?>

    <?php include '../components/timkiem.php'; ?>

    <?php if (!$is_search): ?>
    <div class="container mx-auto px-4 pb-12">
        <?php include '../components/top_count_view.php';unset($is_home); ?>
        <?php include '../components/top_create_new.php'; ?>
        <?php include '../components/pagination.php'; ?>

    </div>
    <?php endif; ?>
    <?php include '../components/Footer.php'; ?>
</body>

</html>