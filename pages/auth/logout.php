<?php
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if(isLogin()) {
    $token = getSession('token_login');
    $removeToken = delete('token_login', "token = '$token'");

    if($removeToken) {
        removeSession('token_login');
        removeSession('user_id');
        removeSession('fullname');
        removeSession('role');
        redirect("?page=auth&action=login");
    }
}