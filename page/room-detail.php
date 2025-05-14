<?php
require_once '../config/connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
$room_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($room_id <= 0) {
    header("Location: index.php");
    exit();
}
mysqli_query($conn, "UPDATE motel SET count_view = count_view + 1 WHERE id = $room_id");

$sql = mysqli_query($conn, "SELECT mt.id, u.name as tên_chủ_trọ, u.images as ảnh_tác_giả,  dt.name as khu_vực, mt.title as tên_phòng, 
mt.price as giá_phòng, mt.description as mô_tả, mt.address as địa_chỉ, 
mt.images as ảnh, mt.created_at as ngày_đăng, 
CASE WHEN mt.approve = 1 THEN 'Còn phòng' ELSE 'Hết phòng' END AS tình_trạng_phòng,
mt.area as diện_tích, mt.count_view as lượt_xem, mt.district_id 
FROM motel mt
JOIN districts dt on mt.district_id = dt.district_id
JOIN users u ON mt.user_id = u.user_id
WHERE mt.id = $room_id") or die("Lỗi truy vấn: " . mysqli_error($conn));
$room = mysqli_fetch_assoc($sql);
$images = [];
if (!empty($room['ảnh'])) {
    $images = explode(',', $room['ảnh']);
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title><?= htmlspecialchars($room['tên_phòng']) ?> - Chi tiết phòng trọ</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .image-gallery {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-template-rows: repeat(2, minmax(150px, 1fr));
            gap: 8px;
            max-height: 500px;
        }

        .main-image-container {
            grid-row: span 2;
            overflow: hidden;
            border-radius: 0.5rem;
        }

        .main-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .main-image-container img:hover {
            transform: scale(1.05);
        }

        .side-image-container {
            overflow: hidden;
            border-radius: 0.5rem;
        }

        .side-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .side-image-container img:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .image-gallery {
                grid-template-columns: 1fr;
                /* Stack images on smaller screens */
                grid-template-rows: auto;
                max-height: none;
            }

            .main-image-container {
                grid-row: span 1;
                height: 300px;
                /* Adjust height for mobile */
            }

            .side-image-container {
                height: 200px;
                /* Adjust height for mobile */
            }
        }

        .single-image-container {
            height: 400px;
            /* Or your preferred height */
            width: 100%;
            overflow: hidden;
            border-radius: 0.5rem;
        }

        .single-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <?php include '../components/Header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center text-sm mb-6 text-gray-600">
            <a href="index.php" class="hover:text-blue-600">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="index.php?district=<?= $room['district_id'] ?? '' ?>" class="hover:text-blue-600"><?= htmlspecialchars($room['khu_vực']) ?></a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium"><?= htmlspecialchars($room['tên_phòng']) ?></span>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-2">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900"><?= htmlspecialchars($room['tên_phòng']) ?></h1>
            <div class="flex items-center">
                <?php if ($room['tình_trạng_phòng'] == 'Còn phòng'): ?>
                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <?= htmlspecialchars($room['tình_trạng_phòng']) ?>
                    </span>
                <?php else: ?>
                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <?= htmlspecialchars($room['tình_trạng_phòng']) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-8">
            <?php if (count($images) >= 1): ?>
                <?php if (count($images) == 1): ?>
                    <div class="single-image-container">
                        <img src="<?= $room['ảnh'] ?>" alt="<?= htmlspecialchars($room['tên_phòng']) ?>">
                    </div>
                <?php else: ?>
                    <div class="image-gallery">
                        <div class="main-image-container">
                            <img src="<?=$room['ảnh'] ?>" alt="<?= htmlspecialchars($room['tên_phòng']) ?> - Ảnh chính">
                        </div>
                        <?php for ($i = 1; $i < min(3, count($images)); $i++): ?>
                            <div class="side-image-container">
                                <img src="<?= htmlspecialchars(trim($images[$i])) ?>" alt="<?= htmlspecialchars($room['tên_phòng']) ?> - Ảnh <?= $i + 1 ?>">
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="h-64 w-full bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                    Không có ảnh
                </div>
            <?php endif; ?>
        </div>


        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Room Details Column -->
            <div class="lg:col-span-2">
                <!-- Price and basic info -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-red-600"><?= number_format($room['giá_phòng']) ?> đ/tháng</h2>
                        <div class="flex items-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span><?= number_format($room['lượt_xem'] ?? 0) ?> lượt xem</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-x-6 gap-y-4 border-t border-b border-gray-200 py-4 my-4">
                        <!-- Diện tích -->
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                            </svg>
                            <span><strong>Diện tích:</strong> <?= htmlspecialchars($room['diện_tích'] ?? '--') ?> m²</span>
                        </div>

                        <!-- Ngày đăng -->
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span><strong>Ngày đăng:</strong> <?= date('d/m/Y', strtotime($room['ngày_đăng'])) ?></span>
                        </div>
                    </div>

                    <!-- Địa chỉ -->
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <p class="font-medium">Địa chỉ:</p>
                            <p class="text-gray-700"><?= htmlspecialchars($room['địa_chỉ']) ?>, <?= htmlspecialchars($room['khu_vực']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Mô tả chi tiết
                    </h2>
                    <div class="prose max-w-none text-gray-700 whitespace-pre-line">
                        <?= nl2br(htmlspecialchars($room['mô_tả'] ?? 'Không có mô tả chi tiết.')) ?>
                    </div>
                </div>

                <!-- Map -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Bản đồ
                    </h2>
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                        <iframe
                            class="w-full h-64 rounded-lg"
                            frameborder="0"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAwxuFoiM5jft5Uw4T3WptTALMHC6iiTnU&q=<?= urlencode($room['địa_chỉ'] . ', ' . $room['khu_vực']) ?>"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Thông tin chủ trọ
                    </h2>
                    <div class="flex items-center mb-4">
                        <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-300 mr-4 flex items-center justify-center text-2xl font-bold text-white">
                            <img src="<?= $room['ảnh_tác_giả'] ?>" alt="">
                            <?= strtoupper(substr(htmlspecialchars($room['tên_chủ_trọ']), 0, 1)) ?>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg"><?= htmlspecialchars($room['tên_chủ_trọ']) ?></h3>
                            <p class="text-gray-600 text-sm">Chủ phòng trọ</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 cursor-pointer">Để biết thêm thông tin chi tiết về chủ trọ, vui lòng liên hệ qua các kênh được cung cấp (nếu có) hoặc đến xem phòng trực tiếp.</p>
                    <button type="button" onclick="alert('Vui lòng liên hệ chủ trọ qua thông tin được cung cấp (nếu có) hoặc đến xem phòng trực tiếp.')" class="mt-4 cursor-pointer block w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-center transition">
                        Liên hệ chủ trọ
                    </button>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Phòng trọ cùng khu vực</h2>
                    <?php
                    $similar_sql_query = "SELECT mt.id, mt.title as tên_phòng, mt.price as giá_phòng, 
                                          mt.images as ảnh, mt.area as diện_tích 
                                          FROM motel mt
                                          WHERE mt.district_id = " . intval($room['district_id']) . "
                                          AND mt.id != " . intval($room['id']) . "
                                          AND mt.approve = 1
                                          LIMIT 3";
                    $similar_sql = mysqli_query($conn, $similar_sql_query);

                    if ($similar_sql && mysqli_num_rows($similar_sql) > 0):
                        while ($similar = mysqli_fetch_assoc($similar_sql)):
                            $similar_images_array = [];
                            if (!empty($similar['ảnh'])) {
                                $similar_images_array = explode(',', $similar['ảnh']);
                            }
                    ?>
                            <div class="border-b pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                                <a href="room-detail.php?id=<?= $similar['id'] ?>" class="flex hover:bg-gray-50 p-2 rounded transition -m-2">
                                    <div class="w-24 h-20 flex-shrink-0 rounded overflow-hidden bg-gray-200">
                                        <?php if (!empty($similar_images_array[0])): ?>
                                            <img src="<?= htmlspecialchars(trim($similar_images_array[0])) ?>" alt="<?= htmlspecialchars($similar['tên_phòng']) ?>" class="w-full h-full object-cover rounded-2xl">
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-3 overflow-hidden">
                                        <h3 class="font-medium text-gray-900 truncate"><?= htmlspecialchars($similar['tên_phòng']) ?></h3>
                                        <p class="text-red-600 font-semibold"><?= number_format($similar['giá_phòng']) ?> đ/tháng</p>
                                        <p class="text-sm text-gray-600"><?= htmlspecialchars($similar['diện_tích'] ?? '--') ?> m²</p>
                                    </div>
                                </a>
                            </div>

                        <?php
                        endwhile;
                    else:
                        ?>

                        <div class="text-gray-600 text-center py-4">
                            Không tìm thấy phòng trọ cùng khu vực
                        </div>
                    <?php endif; ?>

                    <a href="index.php?district=<?= $room['district_id'] ?? '' ?>" class="mt-4 block text-center text-blue-600 hover:underline">
                        Xem thêm phòng trọ tại <?= htmlspecialchars($room['khu_vực']) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../components/Footer.php'; ?>
</body>

</html>