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
    <title>Home Page</title>

    <!--  -->
    <?php require_once "./includes/head.php"; ?>

    <!-- Header CSS  -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_ASSETS ?>/css/header.css">
</head>

<body style="height: 10000px;">

    <?php include "./layouts/partials/header.php"; ?>
</body>

</html>