<?php
?>

<nav class="bg-indigo-600 text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Logo -->
        <a href="../page/index.php" class="text-xl font-bold">PhongTro.vn</a>

        <div class="hidden md:flex space-x-4">
            <a href="../page/index.php" class="hover:underline">Trang chủ</a>
            <a href="../page/post_new.php" class="hover:underline">Đăng bài</a>
            <a href="../auth/profile.php" class="hover:underline">Tài khoản</a>
            <a href="../auth/logout.php" class="hover:underline">Đăng xuất</a>
        </div>

        <button id="menuToggle" class="md:hidden focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <div id="mobileMenu" class="hidden md:hidden flex flex-col items-center bg-indigo-700 py-2 space-y-2">
        <a href="index.php" class="hover:underline">Trang chủ</a>
        <a href="../auth/my-favorites.php" class="hover:underline">Đăng bài</a>
        <a href="../auth/profile.php" class="hover:underline">Tài khoản</a>
        <a href="../auth/logout.php" class="hover:underline">Đăng xuất</a>
    </div>
</nav>

<script>
    document.getElementById('menuToggle').addEventListener('click', function() {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
</script>