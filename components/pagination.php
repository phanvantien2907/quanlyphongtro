<?php
global $total_pages, $current_page;
if (!isset($current_page)) $current_page = 1;
if (!isset($total_pages)) $total_pages = 1;
?>

<div class="mt-12 flex justify-center">
    <div class="inline-flex">
        <a href="?page=<?= max(1, $current_page-1) ?>" class="px-4 py-2 border border-gray-300 bg-white text-gray-500 rounded-l-3xl hover:bg-gray-100 <?= $current_page == 1 ? 'pointer-events-none opacity-50' : '' ?>">Trước</a>
        <?php for($i=1; $i<=$total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="px-4 py-2 border-t border-b border-gray-300 <?= $i == $current_page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    <a href="?page=<?= min($total_pages, $current_page+1) ?>" class="px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-r-3xl hover:bg-gray-100 <?= $current_page == $total_pages ? 'pointer-events-none opacity-50' : '' ?>">Sau</a>
    </div>
</div>