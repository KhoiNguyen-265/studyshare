<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

// Set session
function setSession($key, $value) {
    if(!empty(session_id())) {
        $_SESSION[$key] = $value;
        return true;
    } 
    return false;
}

// Lấy dữ liệu từ session 
function getSession($key = "") {
    if(empty($key)) {
        return $_SESSION;
    } else {
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
    return false;
}

// Xóa session 
function removeSession($key = "") {
    if(empty($key)) {
        session_destroy();
        return true;
    } else {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
        return true;
    }
    return false;
}

// Tạo session flash
function setSessionFLash($key, $value) {
    $key = $key . 'Flash';
    $result = setSession($key, $value);
    return $result;
}

// Lấy session flash 
function getSessionFlash($key) {
    $key = $key . 'Flash';
    $result = getSession($key);

    removeSession($key);

    return $result;
}