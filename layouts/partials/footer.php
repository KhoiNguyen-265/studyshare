<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=auth&action=login");
}

include "./includes/head.php";
?>

<footer class="footer">
    <div class="footer__inner">
        <!-- Logo -->
        <a href="#" class="logo header__logo">
            <img src="<?php echo _HOST_URL_ASSETS ?>/icons/logo.svg"
                alt="Study Share" />
            <span>StudyShare</span>
        </a>
    </div>
</footer>