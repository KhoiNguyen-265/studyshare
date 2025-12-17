<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=auth&action=login");
}

?>

<aside class="sidebar">
    <div class="sidebar__inner">
        <ul class="sidebar__list">
            <li class="sidebar__item">
                <a href="?page=home" class="sidebar__link">
                    <i class="fa-solid fa-house"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="sidebar__item">
                <a href="?page=documents" class="sidebar__link">
                    <i class="fa-regular fa-folder"></i>
                    <span>Documents</span>
                </a>
            </li>
            <li class="sidebar__item">
                <a href="?page=home&action=upload"
                    class="sidebar__link">
                    <i class="fa-solid fa-upload"></i>
                    <span>Upload</span>
                </a>
            </li>
            <li class="sidebar__item">
                <a href="#!" class="sidebar__link">
                    <i class="fa-regular fa-folder-open"></i>
                    <span>My Documents</span>
                </a>
            </li>
            <li class="sidebar__item">
                <a href="?page=profile" class="sidebar__link">
                    <i class="fa-regular fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </div>
</aside>