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

    <!-- TogBox CSS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/KhoiNguyen-265/togbox-lib@v1.0.1/togbox.min.css">

    <!-- Admin Sidebar CSS -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_ASSETS ?>/css/layouts/admin-sidebar.css">

    <!-- Admin Header CSS -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_ASSETS ?>/css/layouts/admin-header.css">

    <!-- Admin CSS -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_ASSETS ?>/css/layouts/admin.css">

    <!-- Admin Page CSS -->
    <?php 
        $adminPage = $_GET['action'] ?? 'dashboard';
        $adminCssFile = "assets/css/pages/admin/" . $adminPage . '.css';

        if(file_exists($adminCssFile)) {
            echo '<link rel="stylesheet" href="' . _HOST_URL . $adminCssFile . '">';
        }
    ?>

    <style>
    .admin-container {
        padding: 20px;
        margin-left: 280px;
        transition: 0.3s;
    }

    body.sidebar-mini .admin-container {
        margin-left: 80px;
    }
    </style>
</head>

<body>
    <!-- Admin Header -->
    <?php require_once "./layouts/partials/admin-header.php" ?>

    <main>
        <?php require_once "./layouts/partials/admin-sidebar.php" ?>
        <div class="admin-container">
            <?php 
                $adminPage = $_GET['action'] ?? 'dashboard';
                $adminPagePath = "./pages/admin/" . $adminPage . ".php";

                if(file_exists($adminPagePath)) {
                    require_once $adminPagePath;
                } else {
                    require_once "./pages/error/404.php";
                }
            ?>
        </div>
    </main>
    <!-- Togbox JS -->
    <script
        src="https://cdn.jsdelivr.net/gh/KhoiNguyen-265/togbox-lib@v1.0.1/togbox.min.js">
    </script>

    <!-- Main JS -->
    <script src="<?php echo _HOST_URL_ASSETS ?>js/main.js"></script>
    <script>
    const navToggle = document.querySelector("#navToggle");
    const iconToggle = navToggle.querySelector("i");

    const isSidebarMini = localStorage.getItem('sidebar-mini') ===
        'true';
    if (isSidebarMini) {
        document.body.classList.add('sidebar-mini');
        iconToggle.classList.remove('fa-square-caret-right');
        iconToggle.classList.add('fa-square-caret-left');
    }

    navToggle.onclick = () => {
        document.body.classList.toggle('sidebar-mini');

        if (document.body.classList.contains('sidebar-mini')) {
            iconToggle.classList.remove('fa-square-caret-right');
            iconToggle.classList.add('fa-square-caret-left');
            localStorage.setItem('sidebar-mini', 'true');
        } else {
            iconToggle.classList.remove('fa-square-caret-left');
            iconToggle.classList.add('fa-square-caret-right');
            localStorage.setItem('sidebar-mini', 'false');
        }

    }
    </script>
</body>

</html>