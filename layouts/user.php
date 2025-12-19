<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Studyshare - Ứng dụng chia sẻ tài liệu cho học sinh,
        sinh viên</title>

    <!--  -->
    <?php require_once "./includes/head.php"; ?>

    <!-- Header CSS  -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_ASSETS ?>/css/layouts/header.css?v=<?php echo rand(); ?>">

    <!-- Sidebar CSS -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_ASSETS ?>/css/layouts/sidebar.css?v=<?php echo rand(); ?>">

    <!-- Pages CSS -->
    <?php 
        $cssFile ="assets/css/pages/" . $page . ".css";
        if(file_exists($cssFile)) {
            echo '<link rel="stylesheet" href="' . _HOST_URL_ASSETS . 'css/pages/' . $page . '.css">';
        }
    ?>

</head>

<body>
    <!-- Header -->
    <?php include "./layouts/partials/header.php"; ?>

    <!-- Main -->
    <main class="main">
        <!-- Sidebar -->
        <?php include "./layouts/partials/sidebar.php" ?>

        <!-- Content -->
        <div class="main-content" style="padding-bottom: 40px;">
            <?php 
                if(file_exists($path)) {
                    require_once $path;
                }
            ?>
        </div>
    </main>
</body>

</html>