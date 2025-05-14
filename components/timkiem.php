<?php
require_once '../config/connection.php';
$search_rooms = [];
if (isset($_GET['submit_search']) && $_GET['submit_search'] == 1) {
    $where = ["mt.approve = 1"];

    if (!empty($_GET['khu_vuc'])) {
        $where[] = "mt.district_id = " . (int)$_GET['khu_vuc'];
    }

    if (!empty($_GET['gia_tien'])) {
        $gia = $_GET['gia_tien'];
        if ($gia[0] == '>') {
            $where[] = "mt.price > " . (int)substr($gia, 1);
        } elseif (strpos($gia, '-') !== false) {
            [$min, $max] = explode('-', $gia);
            $where[] = "mt.price BETWEEN " . (int)$min . " AND " . (int)$max;
        }
    }

    if (!empty($_GET['tien_ich'])) {
        $tien_ich = mysqli_real_escape_string($conn, $_GET['tien_ich']);
        $where[] = "mt.utilities LIKE '%$tien_ich%'";
    }

    $sql = mysqli_query($conn, "SELECT mt.*, u.name AS owner_name, dt.name AS district_name
            FROM motel mt
            JOIN users u ON mt.user_id = u.user_id
            JOIN districts dt ON mt.district_id = dt.district_id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY mt.created_at DESC") or die("Lỗi truy vấn");

    $search_rooms = mysqli_fetch_all($sql, MYSQLI_ASSOC);
}

?>
<div class="container mx-auto py-6 px-4">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Tìm kiếm phòng trọ</h2>
        <form method="GET">
            <input type="hidden" name="submit_search" value="1">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="khu_vuc" class="block text-gray-700 mb-2">Khu vực</label>
                    <select name="khu_vuc" id="khu_vuc" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả khu vực</option>
                        <?php foreach ($motel as $item): ?>
                            <option value="<?= $item['id'] ?>" <?= (isset($_GET['khu_vuc']) && $_GET['khu_vuc'] == $item['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($item['tên']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="gia_tien" class="block text-gray-700 mb-2">Giá tiền</label>
                    <select name="gia_tien" id="gia_tien" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả mức giá</option>
                        <option value="500000-700000" <?= (isset($_GET['gia_tien']) && $_GET['gia_tien'] == '500000-700000') ? 'selected' : '' ?>>500-700K</option>
                        <option value="900000-1000000" <?= (isset($_GET['gia_tien']) && $_GET['gia_tien'] == '900000-1000000') ? 'selected' : '' ?>>900K - 1 triệu</option>
                        <option value=">2000000" <?= (isset($_GET['gia_tien']) && $_GET['gia_tien'] == '>2000000') ? 'selected' : '' ?>>Trên 2 triệu</option>
                        <option value="3000000-5000000" <?= (isset($_GET['gia_tien']) && $_GET['gia_tien'] == '3000000-5000000') ? 'selected' : '' ?>>3 - 5 triệu</option>
                        <option value=">5000000" <?= (isset($_GET['gia_tien']) && $_GET['gia_tien'] == '>5000000') ? 'selected' : '' ?>>Trên 5 triệu</option>
                    </select>
                </div>
                <div>
                    <label for="tien_ich" class="block text-gray-700 mb-2">Tiện ích</label>
                    <select name="tien_ich" id="tien_ich" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả tiện ích</option>
                        <option value="Wifi" <?= (isset($_GET['tien_ich']) && $_GET['tien_ich'] == 'Wifi') ? 'selected' : '' ?>>Wifi</option>
                        <option value="Nội thất" <?= (isset($_GET['tien_ich']) && $_GET['tien_ich'] == 'Nội thất') ? 'selected' : '' ?>>Nội thất</option>
                        <option value="Máy giặt" <?= (isset($_GET['tien_ich']) && $_GET['tien_ich'] == 'Máy giặt') ? 'selected' : '' ?>>Máy giặt</option>
                        <option value="Điều hòa" <?= (isset($_GET['tien_ich']) && $_GET['tien_ich'] == 'Điều hòa') ? 'selected' : '' ?>>Điều hòa</option>
                        <option value="Gác lửng" <?= (isset($_GET['tien_ich']) && $_GET['tien_ich'] == 'Gác lửng') ? 'selected' : '' ?>>Gác lửng</option>
                        <option value="Chỗ để xe" <?= (isset($_GET['tien_ich']) && $_GET['tien_ich'] == 'Chỗ để xe') ? 'selected' : '' ?>>Chỗ để xe</option>
                        <option value="Nhà vệ sinh riêng" <?= (isset($_GET['tien_ich']) && $_GET['tien_ich'] == 'Nhà vệ sinh riêng') ? 'selected' : '' ?>>Nhà vệ sinh riêng</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition cursor-pointer">Tìm kiếm</button>
        </form>
    </div>

    <?php if ($is_search): ?>
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Kết quả tìm kiếm</h2>
            </div>
            <?php if (!empty($search_rooms)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($search_rooms as $room): ?>
                        <?php 
                            $room_images = !empty($room['images']) ? explode(',', $room['images']) : [];
                            $thumb = !empty($room_images[0]) ? trim($room_images[0]) : '../assets/images/default-room.jpg';
                        ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <a href="room-detail.php?id=<?= $room['id'] ?>" class="block relative">
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($room['title']) ?>" class="w-full h-48 object-cover">
                                </div>
                                <?php if ($room['approve'] == 1): ?>
                                    <span class="absolute top-3 left-3 bg-green-500 text-white px-2 py-1 rounded-lg text-xs font-medium">Còn phòng</span>
                                <?php else: ?>
                                    <span class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded-lg text-xs font-medium">Hết phòng</span>
                                <?php endif; ?>
                            </a>
                            <div class="p-4">
                                <a href="room-detail.php?id=<?= $room['id'] ?>" class="block">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition">
                                        <?= htmlspecialchars($room['title']) ?>
                                    </h3>
                                </a>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-red-600 font-bold"><?= number_format($room['price']) ?> đ/tháng</span>
                                    <span class="text-sm text-gray-600"><?= htmlspecialchars($room['area']) ?> m²</span>
                                </div>
                                <div class="text-gray-600 text-sm mb-3 flex items-center line-clamp-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <?= htmlspecialchars($room['address']) ?>, <?= htmlspecialchars($room['district_name']) ?>
                                </div>
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span>Đăng bởi: <?= htmlspecialchars($room['owner_name']) ?></span>
                                    <span><?= htmlspecialchars($room['count_view'] ?? 0) ?> lượt xem</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-lg text-center">
                    <p>Không tìm thấy phòng trọ nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>