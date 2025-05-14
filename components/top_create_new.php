<?php
include_once '../config/connection.php';
global $total_pages, $current_page;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 3; 
$offset = ($current_page - 1) * $items_per_page;

$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM motel");
$count_data = mysqli_fetch_assoc($count_query);
$total_items = $count_data['total'];
$total_pages = ceil($total_items / $items_per_page);

$sql = mysqli_query($conn, "SELECT mt.id, u.name as người_đăng, dt.name as khu_vực, mt.title as tên_phòng, 
        mt.price as giá, mt.area as diện_tích, mt.count_view as lượt_xem, mt.address as địa_chỉ,
        mt.images as ảnh, mt.created_at as ngày_đăng, mt.approve
        FROM motel mt 
        JOIN districts dt ON mt.district_id = dt.district_id
        JOIN users u ON mt.user_id = u.user_id
        WHERE mt.created_at >= now() - INTERVAL 7 Day
        ORDER BY mt.created_at DESC
        LIMIT $offset, $items_per_page") or die("Lỗi truy vấn: " . mysqli_error($conn));
$motel = mysqli_fetch_all($sql, MYSQLI_ASSOC);
?>

<h2 class="text-2xl mt-10 font-bold mb-6 flex items-center">Phòng trọ mới được thêm</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($motel as $item): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="relative h-48">
                <img src="<?= $item['ảnh'] ?>" alt="<?= $item['tên_phòng'] ?>" class="w-full h-full object-cover hover:scale-105 transition">                
                <?php if ($item['approve'] == 0): ?>
                    <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-3xl text-sm">Hết phòng</span>
                <?php else: ?>
                    <span class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-3xl text-sm">Còn trống</span>
                <?php endif; ?>
                
                <span class="absolute top-2 left-2 bg-blue-500/80 text-white px-2 py-1 rounded-lg text-sm backdrop-blur-sm">
                    <?= $item['khu_vực'] ?>
                </span>
            </div>
            
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-2 truncate"><?= $item['tên_phòng'] ?></h3>
                <p class="text-red-600 font-bold mb-2"><?= number_format($item['giá']) ?> đ/tháng</p>
                
                <p class="text-gray-600 mb-2 flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 flex-shrink-0 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="line-clamp-1"><?= $item['địa_chỉ'] ?></span>
                </p>
                
                <div class="flex justify-between items-center mb-3 text-sm text-gray-600">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                        </svg>
                        <?= $item['diện_tích'] ?> m²
                    </span>
                    
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <?= number_format($item['lượt_xem']) ?> lượt xem
                    </span>
                </div>
                
                <!-- Thông tin người đăng và ngày đăng -->
                <div class="flex justify-between items-center mb-3 text-sm text-gray-600 border-t pt-2">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <?= $item['người_đăng'] ?>
                    </span>
                    
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <?php 
                        $date = new DateTime($item['ngày_đăng']);
                        echo $date->format('d/m/Y');
                        ?>
                    </span>
                </div>
                
                <a href="room-detail.php?id=<?= $item['id'] ?>" class="block text-center bg-blue-600 text-white py-2 rounded-3xl hover:bg-blue-700 transition">
                    <span class="flex justify-center items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Xem chi tiết
                    </span>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>