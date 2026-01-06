<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if(!isLogin() || getSession('role') !== 'admin') {
    redirect("?page=landing");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Studyshare</title>

    <?php require_once "./includes/head.php" ?>

    <!-- Admin Sidebar CSS -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_ASSETS ?>/css/layouts/admin-sidebar.css">

    <!-- Admin Header CSS -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_ASSETS ?>/css/layouts/admin-header.css">
</head>

<body style="height: 10000px;">
    <?php include "./layouts/partials/admin-header.php" ?>
    <?php include "./layouts/partials/admin-sidebar.php" ?>
</body>

</html>