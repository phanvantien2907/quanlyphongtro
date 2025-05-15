<header class="bg-white shadow-md sticky top-0 z-30">
    <div class="navbar px-4">
        <div class="navbar-start">
            <label for="main-drawer" class="btn btn-square btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </label>
            <span class="text-xl font-bold ml-2">Admin Dashboard</span>
        </div>
        <div class="navbar-end">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full bg-primary text-white grid place-items-center">
                        <span class="text-xl font-bold">A</span>
                    </div>
                </div>
                <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                    <li><a>Hồ sơ</a></li>
                    <li><a>Cài đặt</a></li>
                    <li><a href="../auth/logout.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>