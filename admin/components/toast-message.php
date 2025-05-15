<div id="toast-container" class="toast toast-top toast-end z-50">
    <?php if (isset($_SESSION['toast_error'])): ?>
        <div class="alert alert-error shadow-lg toast-anim-in" id="toast-error">
            <div>
                <div><?= $_SESSION['toast_error']; ?></div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-error');
                toast.classList.remove('toast-anim-in');
                toast.classList.add('toast-anim-out');
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 300);
            }, 4000);
        </script>
        <?php unset($_SESSION['toast_error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['toast_success'])): ?>
        <div class="alert alert-success shadow-lg toast-anim-in" id="toast-success">
            <div>
                <div><?= $_SESSION['toast_success']; ?></div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-success');
                toast.classList.remove('toast-anim-in');
                toast.classList.add('toast-anim-out');
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 300);
            }, 4000);
        </script>
        <?php unset($_SESSION['toast_success']); ?>
    <?php endif; ?>
</div>