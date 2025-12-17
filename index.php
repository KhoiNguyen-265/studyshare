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

    $page = _PAGE;
    $action = _ACTION;

    if(!empty($_GET['page'])) {
        $page = $_GET['page'];
    }

    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    $path = 'pages/' . $page . '/' . $action . '.php';

    if(empty($path)) {
        require_once "./pages/error/500.php";
        exit();
    } else if(!file_exists($path)) {
        require_once "./pages/error/404.php";
        exit();
    }

    $publicPages = ['landing', 'auth', 'error'];

    if(in_array($page, $publicPages)) {
        require_once $path;
    } else {
        if(!isLogin()) {
            redirect('?page=landing');
        }

        require_once "./layouts/user.php";
    }