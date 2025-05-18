<?php
session_start();
require_once '../../config/connection.php';
if(!isset($_SESSION['name'])) {
    header('Location: ..\page\index.php');
    exit();
}
$tong_so_phong = mysqli_query($conn, "SELECT count(*) as tổng_phòng_trọ FROM motel mt") or die("Lỗi truy vấn");
$result_phong = mysqli_fetch_assoc($tong_so_phong);

$tong_so_phong_dang_hoat_dong = mysqli_query($conn, "SELECT count(*) as tổng_số_phòng_đang_hoạt_động FROM motel mt WHERE mt.approve = 1") or die("Lỗi truy vấn");
$result_phong_hoat_dong = mysqli_fetch_assoc($tong_so_phong_dang_hoat_dong);

$tong_so_phong_cho_duyet = mysqli_query($conn, "SELECT count(*) as tổng_số_phòng_đang_chờ_duyệt FROM motel mt WHERE mt.approve = 0") or die("Lỗi truy vấn");
$result_phong_cho_duyet = mysqli_fetch_assoc($tong_so_phong_cho_duyet);

$tong_so_tai_khoan = mysqli_query($conn, "SELECT count(*) as tổng_số_người_dùng FROM users") or die("Lỗi truy vấn");
$result_tai_khoan = mysqli_fetch_assoc($tong_so_tai_khoan);

?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Admin Dashboard - Phòng trọ thành phố Vinh</title>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="drawer lg:drawer-open">
        <input id="main-drawer" type="checkbox" class="drawer-toggle" />
        
        <!-- Main Content -->
        <div class="drawer-content flex flex-col">
            <!-- Header -->
            <?php include '../components/Header.php'; ?>

            <!-- Main Content -->
            <main class="p-6">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                    <p class="text-gray-600">Chào mừng quay trở lại, <?= $_SESSION['name'] ?>!</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Rooms Card -->
                    <div class="card bg-white shadow-md hover:shadow-lg transition-shadow">
                        <div class="card-body p-5">
                            <div class="flex justify-between">
                                <div>
                                    <div class="stat-title text-gray-500">Tổng phòng trọ</div>
                                    <div class="stat-value text-3xl font-bold text-gray-800"> <?=$result_phong['tổng_phòng_trọ'] ?> </div>
                                    <div class="stat-desc text-success">↗︎ 14% so với tháng trước</div>
                                </div>
                                <div class="stat-figure bg-blue-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Rooms Card -->
                    <div class="card bg-white shadow-md hover:shadow-lg transition-shadow">
                        <div class="card-body p-5">
                            <div class="flex justify-between">
                                <div>
                                    <div class="stat-title text-gray-500">Phòng đang hoạt động</div>
                                    <div class="stat-value text-3xl font-bold text-gray-800"> <?=$result_phong_hoat_dong['tổng_số_phòng_đang_hoạt_động'] ?> </div>
                                    <div class="stat-desc text-success">↗︎ 7% so với tháng trước</div>
                                </div>
                                <div class="stat-figure bg-green-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Rooms Card -->
                    <div class="card bg-white shadow-md hover:shadow-lg transition-shadow">
                        <div class="card-body p-5">
                            <div class="flex justify-between">
                                <div>
                                    <div class="stat-title text-gray-500">Chờ duyệt</div>
                                    <div class="stat-value text-3xl font-bold text-gray-800"><?=$result_phong_cho_duyet['tổng_số_phòng_đang_chờ_duyệt'] ?></div>
                                    <div class="stat-desc text-error">↘︎ 12% so với tháng trước</div>
                                </div>
                                <div class="stat-figure bg-yellow-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-yellow-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Users Card -->
                    <div class="card bg-white shadow-md hover:shadow-lg transition-shadow">
                        <div class="card-body p-5">
                            <div class="flex justify-between">
                                <div>
                                    <div class="stat-title text-gray-500">Người dùng</div>
                                    <div class="stat-value text-3xl font-bold text-gray-800"><?=$result_tai_khoan['tổng_số_người_dùng'] ?></div>
                                    <div class="stat-desc text-success">↗︎ 23% so với tháng trước</div>
                                </div>
                                <div class="stat-figure bg-purple-100 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-purple-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="card bg-white shadow-md mb-8">
                    <div class="card-body">
                        <h2 class="card-title text-xl font-bold text-gray-800 mb-4">Hoạt động gần đây</h2>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th class="bg-gray-50">#</th>
                                        <th class="bg-gray-50">Phòng trọ</th>
                                        <th class="bg-gray-50">Chủ trọ</th>
                                        <th class="bg-gray-50">Trạng thái</th>
                                        <th class="bg-gray-50">Giá (VNĐ/tháng)</th>
                                        <th class="bg-gray-50">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Phòng trọ cao cấp gần đại học</td>
                                        <td>Nguyễn Văn A</td>
                                        <td><span class="badge badge-success">Đã duyệt</span></td>
                                        <td>2,500,000</td>
                                        <td>
                                            <div class="flex gap-1">
                                                <button class="btn btn-xs btn-info">Xem</button>
                                                <button class="btn btn-xs btn-warning">Sửa</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Căn hộ mini trung tâm</td>
                                        <td>Trần Thị B</td>
                                        <td><span class="badge badge-warning">Chờ duyệt</span></td>
                                        <td>3,200,000</td>
                                        <td>
                                            <div class="flex gap-1">
                                                <button class="btn btn-xs btn-info">Xem</button>
                                                <button class="btn btn-xs btn-success">Duyệt</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Phòng trọ sinh viên</td>
                                        <td>Lê Văn C</td>
                                        <td><span class="badge badge-error">Từ chối</span></td>
                                        <td>1,800,000</td>
                                        <td>
                                            <div class="flex gap-1">
                                                <button class="btn btn-xs btn-info">Xem</button>
                                                <button class="btn btn-xs btn-success">Duyệt lại</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Nhà nguyên căn</td>
                                        <td>Phạm Thị D</td>
                                        <td><span class="badge badge-success">Đã duyệt</span></td>
                                        <td>5,500,000</td>
                                        <td>
                                            <div class="flex gap-1">
                                                <button class="btn btn-xs btn-info">Xem</button>
                                                <button class="btn btn-xs btn-warning">Sửa</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Phòng trọ tiện nghi</td>
                                        <td>Hoàng Văn E</td>
                                        <td><span class="badge badge-warning">Chờ duyệt</span></td>
                                        <td>2,800,000</td>
                                        <td>
                                            <div class="flex gap-1">
                                                <button class="btn btn-xs btn-info">Xem</button>
                                                <button class="btn btn-xs btn-success">Duyệt</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-actions justify-end mt-4">
                            <a href="#" class="btn btn-primary">Xem tất cả</a>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Rooms by Area -->
                    <div class="card bg-white shadow-md">
                        <div class="card-body">
                            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">Phòng theo khu vực</h2>
                            <div class="h-64 flex items-end justify-around">
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-500 w-12 rounded-t-lg" style="height: 50%"></div>
                                    <div class="mt-2 text-xs">Quận 1</div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-500 w-12 rounded-t-lg" style="height: 70%"></div>
                                    <div class="mt-2 text-xs">Quận 2</div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-500 w-12 rounded-t-lg" style="height: 90%"></div>
                                    <div class="mt-2 text-xs">Quận 3</div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-500 w-12 rounded-t-lg" style="height: 60%"></div>
                                    <div class="mt-2 text-xs">Quận 4</div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-500 w-12 rounded-t-lg" style="height: 40%"></div>
                                    <div class="mt-2 text-xs">Quận 5</div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-500 w-12 rounded-t-lg" style="height: 75%"></div>
                                    <div class="mt-2 text-xs">Quận 6</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Stats -->
                    <div class="card bg-white shadow-md">
                        <div class="card-body">
                            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">Doanh thu theo tháng</h2>
                            <div class="h-64 relative">
                                <!-- Simplified line chart -->
                                <div class="flex items-end absolute inset-0 px-4">
                                    <div class="w-full flex justify-between items-end">
                                        <div class="h-16 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-24 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-32 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-28 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-36 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-44 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-40 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-48 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-40 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-36 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-44 w-4 bg-green-400 rounded-t-sm"></div>
                                        <div class="h-48 w-4 bg-green-400 rounded-t-sm"></div>
                                    </div>
                                </div>
                                <!-- Month labels -->
                                <div class="absolute bottom-0 w-full flex justify-between px-2 text-xs text-gray-500">
                                    <div>T1</div>
                                    <div>T2</div>
                                    <div>T3</div>
                                    <div>T4</div>
                                    <div>T5</div>
                                    <div>T6</div>
                                    <div>T7</div>
                                    <div>T8</div>
                                    <div>T9</div>
                                    <div>T10</div>
                                    <div>T11</div>
                                    <div>T12</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
           <?php include '../components/Footer.php'; ?>

        <!-- Sidebar -->
       <?php include '../components/Sidebar.php'; ?>
    </div>
</body>
</html>