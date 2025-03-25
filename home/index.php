<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Dashboard</title>
</head>

<body>
    <h2 class="text-red-700 text-2xl">Xin chào, <?php echo $_SESSION['name']; ?></h2>
</body>

</html>