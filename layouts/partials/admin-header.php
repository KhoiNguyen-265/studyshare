<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}
?>

<header class="admin-header">
    <!-- Logo -->
    <div class="admin-header__logo">
        <a href="?page=admin&admin_page=dashboard" class="logo">
            <img src="<?php echo _HOST_URL_ASSETS ?>/icons/logo.svg"
                alt="StudyShare" />
            <span>Admin Panel</span>
        </a>
    </div>

    <!-- Nav -->
    <div class="admin-header__nav">
        <!-- Left -->
        <div class="admin-header__left">
            <button id="navToggle" class="admin-header__btn"><i
                    class="fa-regular fa-square-caret-right"></i>
            </button>
        </div>

        <!-- Right -->
        <div class="admin-header__right">
            <!-- Notifications -->
            <div class="admin-header__notifications">
                <button id="notificationBtn"
                    class="admin-header__btn">
                    <i class="fa-regular fa-bell"></i>
                </button>

                <!-- Dropdown -->
                <div id="notificationDropdown"
                    class="notifications-dropdown">
                    <!-- Dropdown Header -->
                    <div class="notifications-header">
                        <h3>
                            <i class="fa-regular fa-bell"></i>
                            Notifications
                        </h3>
                        <button
                            class="admin-header__btn notifications-header__btn">
                            Mark all read
                        </button>
                    </div>

                    <!-- Dropdown Content -->
                    <ul class="notification__list">
                        <li class="notification-item">
                            <a href="#!"
                                class="notification-item__link">
                                <div class="notification-item__icon">
                                    <i class="fa-regular fa-file"></i>
                                </div>
                                <div class="notification-item__info">
                                    <h4
                                        class="notification-item__title">
                                        Lorem ipsum dolor sit amet.
                                    </h4>
                                    <p
                                        class="notification-item__time">
                                        8 min ago</p>
                                </div>
                            </a>
                        </li>

                        <li class="notification-item">
                            <a href="#!"
                                class="notification-item__link">
                                <div class="notification-item__icon">
                                    <i class="fa-regular fa-file"></i>
                                </div>
                                <div class="notification-item__info">
                                    <h4
                                        class="notification-item__title">
                                        Lorem ipsum dolor sit amet.
                                    </h4>
                                    <p
                                        class="notification-item__time">
                                        8 min ago</p>
                                </div>
                            </a>
                        </li>

                        <li class="notification-item">
                            <a href="#!"
                                class="notification-item__link">
                                <div class="notification-item__icon">
                                    <i class="fa-regular fa-file"></i>
                                </div>
                                <div class="notification-item__info">
                                    <h4
                                        class="notification-item__title">
                                        Lorem ipsum dolor sit amet.
                                    </h4>
                                    <p
                                        class="notification-item__time">
                                        8 min ago</p>
                                </div>
                            </a>
                        </li>

                        <li class="notification-item">
                            <a href="#!"
                                class="notification-item__link">
                                <div class="notification-item__icon">
                                    <i class="fa-regular fa-file"></i>
                                </div>
                                <div class="notification-item__info">
                                    <h4
                                        class="notification-item__title">
                                        Lorem ipsum dolor sit amet.
                                    </h4>
                                    <p
                                        class="notification-item__time">
                                        8 min ago</p>
                                </div>
                            </a>
                        </li>

                        <li class="notification-item">
                            <a href="#!"
                                class="notification-item__link">
                                <div class="notification-item__icon">
                                    <i class="fa-regular fa-file"></i>
                                </div>
                                <div class="notification-item__info">
                                    <h4
                                        class="notification-item__title">
                                        Lorem ipsum dolor sit amet.
                                    </h4>
                                    <p
                                        class="notification-item__time">
                                        8 min ago</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Profile -->
            <div class="admin-header__profile">
                <button id="profileBtn" class="">
                    <img class="admin-header__avatar"
                        src="<?php echo _HOST_URL ?>/uploads/avatars/<?php echo getSession('avatar') ?>"
                        alt="admin">
                </button>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown" id="profileDropdown">
                    <!-- Dropdown Header -->
                    <div class="profile-dropdown__header">
                        <img class=""
                            src="<?php echo _HOST_URL ?>/uploads/avatars/<?php echo getSession('avatar') ?>"
                            alt="Admin">
                        <div class="">
                            <p class="profile-dropdown__name">
                                <?php echo getSession('fullname'); ?>
                            </p>
                            <p class="profile-dropdown__email">
                                <?php echo getSession('email'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Dropdown Content -->
                    <div class="profile-dropdown__menu">
                        <a href="?page=profile"
                            class="profile-dropdown__item">
                            <i class="fa-regular fa-user"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="?page=home"
                            class="profile-dropdown__item">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Back to Site</span>
                        </a>
                        <div class="profile-dropdown__divider"></div>
                        <a href="?page=auth&action=logout"
                            class="profile-dropdown__item profile-dropdown__item--danger">
                            <i
                                class="fa-solid fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
const notificationBtn = document.querySelector(
    "#notificationBtn");
const notificationDropdown = document.querySelector(
    "#notificationDropdown");

const profileBtn = document.querySelector('#profileBtn');
const profileDropdown = document.querySelector('#profileDropdown');

notificationBtn.onclick = () => {
    notificationDropdown.classList.toggle('show');
    profileDropdown.classList.remove('show');
}

profileBtn.onclick = () => {
    profileDropdown.classList.toggle('show');
    notificationDropdown.classList.remove('show');
}

document.addEventListener('click', (e) => {
    if (!notificationDropdown.contains(e.target) && !
        notificationBtn.contains(e.target)) {
        notificationDropdown.classList.remove('show');
    }

    if (!profileDropdown.contains(e.target) && !
        profileBtn.contains(e.target)) {
        profileDropdown.classList.remove('show');
    }
})
</script>