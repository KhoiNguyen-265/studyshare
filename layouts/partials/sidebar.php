<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=auth&action=login");
}

$currentPage = $_GET['page'] ?? 'home';
?>

<aside class="sidebar">
    <ul class="sidebar__list">
        <li class="sidebar__item">
            <a href="?page=home"
                class="sidebar__link <?php echo isActive('home') ?>">
                <i class="fa-solid fa-house"></i>
                <span>Home</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="?page=documents"
                class="sidebar__link <?php echo isActive('documents') ?>">
                <i class="fa-regular fa-folder"></i>
                <span>Documents</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="?page=upload"
                class="sidebar__link <?php echo isActive('upload') ?>">
                <i class="fa-solid fa-upload"></i>
                <span>Upload</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="?page=my-documents"
                class="sidebar__link <?php echo isActive('my-documents') ?>">
                <i class="fa-regular fa-folder-open"></i>
                <span>My Documents</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="?page=profile"
                class="sidebar__link <?php echo isActive('profile') ?>">
                <i class="fa-regular fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
        <li class="sidebar__item sidebar__footer">
            <a href="?page=auth&action=logout" class="sidebar__link">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Log out</span>
            </a>
        </li>
    </ul>
</aside>