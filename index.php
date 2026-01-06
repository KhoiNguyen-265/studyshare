<?php 
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    session_start();
    ob_start();

    require_once("./config/config.php");
    require_once("./config/db.php"); // connect database
    require_once("./includes/database.php");
    require_once("./includes/session.php");

    // Mailer 
    require_once("./includes/mailer/Exception.php");
    require_once("./includes/mailer/PHPMailer.php");
    require_once("./includes/mailer/SMTP.php");

    require_once("./includes/function.php");

    $page = $_GET['page'] ?? _PAGE;
    $action = $_GET['action'] ?? _ACTION;

    $publicPages = ['landing', 'auth', 'error'];
    $adminPages = ['admin'];

    if (!in_array($page, $publicPages)) {
        if(!isLogin()) {
            redirect("?page=landing");
        }
    }

    // Phân quyền 
    $layoutPath = "./layouts/user.php";
    $isAdminPage = in_array($page, $adminPages);

    if ($isAdminPage) {
        $useRole = getSession('role');

        if($useRole !== 'admin') {
            require_once "./pages/error/403.php";
            exit();
        } 

        $layoutPath = "./layouts/admin.php";
    }
    
    // Kiểm tra file tồn tại
    $path = 'pages/' . $page . '/' . $action . '.php';

    if (!file_exists($path)) {
        require_once "./pages/error/404.php";
        exit();
    }

    // Render
    if (in_array($page, $publicPages)) {
        require_once $path;
    } else {
        require_once $layoutPath;
    }