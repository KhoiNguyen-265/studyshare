<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

$currentAdminPage = $_GET['action'] ?? 'dashboard';

function isAdminActive($page) {
    global $currentAdminPage;
    return $page === $currentAdminPage ? 'admin-sidebar__link--active' : '';
}

?>

<aside class="admin-sidebar" id="adminSidebar">
    <!-- Navigation -->
    <nav class="admin-sidebar__nav">
        <ul class="admin-sidebar__list">
            <li class="admin-sidebar__item">
                <a href="?page=admin&action=dashboard"
                    class="admin-sidebar__link <?php echo isAdminActive('dashboard'); ?>">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="admin-sidebar__item">
                <a href="?page=admin&action=documents"
                    class="admin-sidebar__link <?php echo isAdminActive('documents'); ?>">
                    <i class="fa-solid fa-file-circle-check"></i>
                    <span>Documents</span>
                </a>
            </li>
            <li class="admin-sidebar__item">
                <a href="?page=admin&action=users"
                    class="admin-sidebar__link <?php echo isAdminActive('users'); ?>">
                    <i class="fa-solid fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="admin-sidebar__item">
                <a href="?page=admin&action=subjects"
                    class="admin-sidebar__link <?php echo isAdminActive('subjects'); ?>">
                    <i class="fa-solid fa-book"></i>
                    <span>Subjects</span>
                </a>
            </li>
            <li class="admin-sidebar__item">
                <a href="?page=home" class="admin-sidebar__link">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to Site</span>
                </a>
            </li>
            <li class="admin-sidebar__item">
                <a href="?page=auth&action=logout"
                    class="admin-sidebar__link danger">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="admin-sidebar__footer">
        <div class="admin-sidebar__user">
            <img src="<?php echo _HOST_URL ?>/uploads/avatars/<?php echo getSession('avatar') ?? 'default.jpg' ?>"
                alt="Admin" class="admin-sidebar__avatar">
            <div class="admin-sidebar__info">
                <p class="admin-sidebar__name">
                    <?php echo getSession('fullname'); ?>
                </p>
                <p class="admin-sidebar__role">Administrator</p>
            </div>
        </div>
    </div>
</aside>