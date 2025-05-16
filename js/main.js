function copyID(text) {
        navigator.clipboard.writeText(text).then(() => {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded shadow';
        toast.textContent = 'Copy ID thành công!';
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 2000);
    });
}
function openFileManager() {
    document.getElementById('fileManagerModal').showModal();
  }

  function closeFileManager() {
    document.getElementById('fileManagerModal').close();
  }

  // Nhận dữ liệu từ Tiny File Manager
  window.addEventListener('message', function (e) {
    if (e.data && typeof e.data === 'string' && e.data.includes('/uploads/')) {
      document.getElementById('imageInput').value = e.data;
      closeFileManager();
    }
  });

  function selectFileFromManager(url) {
    window.parent.postMessage(url, '*');
}