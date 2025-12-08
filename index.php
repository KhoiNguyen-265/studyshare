<?php 
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    session_start();
    ob_start();

    require_once("./config/config.php");
    require_once("./config/db.php");
    require_once("./includes/database.php");
    require_once("./includes/session.php");

    $page = _PAGE;
    $action = _ACTION;

    if(!empty($_GET['page'])) {
        $page = $_GET['page'];
    }

    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    $path = 'pages/' . $page . '/' . $action . '.php';

    if(!empty($path)) {
        if(file_exists($path)) {
            require_once $path;
        } else {
            require_once "./pages/error/404.php";
        }
    } else {
        require_once "./pages/error/500.php";
    }