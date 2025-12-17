<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=landing");
}

?>

<header class="header">
    <div class="header__inner">
        <!-- Logo -->
        <a href="#" class="logo header__logo">
            <img src="<?php echo _HOST_URL_ASSETS ?>/icons/logo.svg"
                alt="Study Share" />
            <span>StudyShare</span>
        </a>

        <!-- Search -->
        <div class="header__search-wrapper">
            <!-- Icon search -->
            <div class="search__icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <!-- input -->
            <input class="header__search" type="text" name="search"
                id="search" placeholder="Search documents...">
        </div>

        <!-- Right -->
        <div class="header__right">
            <!-- Bell -->
            <div class="bell-wrapper">
                <i class="fa-regular fa-bell"></i>
            </div>
            <!-- Avatar -->
            <div class="avatar-wrapper">
                <img src="<?php echo _HOST_URL_ASSETS ?>/images/avatar/default.jpg"
                    alt="avatar" class="avatar">
            </div>
        </div>
    </div>
</header>