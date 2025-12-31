<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

if (!isLogin()) {
    redirect("?page=landing");
}

// Lấy ID của người dùng khi login
$userId = getSession('user_id');

$user = getOne("SELECT * FROM users WHERE id = $userId");

$avatarURL = _HOST_URL . "uploads/avatars/" . $user['avatar'];

$errors = [];
$success = '';
$fail = '';

// Thống kê cá nhân
// Tổng số tài liệu đã upload
$totalDocs = getOne("SELECT COUNT(*) as total FROM documents WHERE user_id = $userId ")['total'];

// Tổng số lượt xem 
$totalViews = getOne("SELECT IFNULL(SUM(view_count), 0) as total FROM documents WHERE user_id = $userId")['total'];

// Tổng lượt tải
$totalDowns = getOne("SELECT IFNULL(SUM(download_count), 0) as total FROM documents WHERE user_id = $userId")['total'];

// Handle Edit profile 
if(isPost() && isset($_POST['update_profile'])) {
    $filter = filterData();

    $fullname = $filter['fullname'] ?? '';

    // Validate fullname
    if(empty($fullname)) {
        $errors['fullname']['required'] = 'Fullname is required';
    } elseif (strlen($fullname) < 4) {
        $errors['fullname']['length'] = 'Fullname must be at least 4 character';
    }

    // Upload avatar
    $avatarName = $user['avatar'];

    if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;

        // Validate file type 
        if(!in_array($avatar['type'], $allowedTypes)) {
            $errors['avatar']['type'] = 'Only JPG, PNG, GIF, WEBP images are allowed';
        }

        // Validate file size 
        if($avatar['size'] > $maxSize) {
            $errors['avatar']['size'] = 'File size must be less than 5MB';
        }

        // Upload file 
        if(empty($errors['avatar'])) {
            $uploadDir = 'uploads/avatars/';

            // Tạo folder nếu chưa có 
            if(!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Tạo tên file unique
            $extension = pathinfo($avatar['name'], PATHINFO_EXTENSION);
            $avatarName = 'avatar_' . $userId . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $avatarName;

            // Di chuyển file từ thư mục tạm vào thư mục đích
            if(!move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
                $errors['avatar'][] = 'Failed to upload avatar';
            } else {
                // Xóa avatar cũ
                if($user['avatar'] !== 'default.jpg' && file_exists($uploadDir . $user['avatar'])) {
                    unlink($uploadDir . $user['avatar']);
                }
            }
        }
    }

    // Update database nếu kh có lỗi
    if(empty($errors)) {
        $updateData = [
            'fullname' => $fullname,
            'avatar' => $avatarName
        ];

        $updateStatus = update('users', $updateData, "id = $userId");

        if($updateStatus) {
            // Update session
            setSession('fullname', $fullname);
            setSession('avatar', $avatarName);

            $success = 'Profile updated successfully!';

            // Refresh user data
            $user = getOne("SELECT * FROM users WHERE id = $userId");
        } else {
            $errors['general'][] = 'Failed to update profile';
        }
    } else {
        $fail = "Profile update failed!";
    }
}

// Handle Change Password
if(isPost() && isset($_POST['update_password'])) {
    $filter = filterData();

    $currentPassword = $filter['current_password'] ?? '';
    $newPassword = $filter['new_password'] ?? '';
    $confirmPassword = $filter['confirm_password'] ?? '';
    
    // Validate current password
    if(empty($currentPassword)) {
        $errors['current_password']['required'] = 'Current password is required';
    } elseif(!password_verify($currentPassword, $user['password'])) {
        $errors['current_password']['correct'] = 'Current password is incorrect';
    } 

    // Validate new password 
    if(empty($newPassword)) {
         $errors['new_password']['required'] = 'New password is required';
    } elseif(strlen($newPassword) < 8) {
        $errors['new_password']['length'] = 'Password must be at least 8 characters';
    }

    // Validate confirm new password 
    if(empty($confirmPassword)) {
        $errors['confirm_password']['required'] = 'Confirm password is required';
    } elseif($confirmPassword !== $newPassword) {
        $errors['new_password']['like'] = 'Passwords do not match. Please try again';
    }

    if(empty($errors)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $updateStatus = update('users', ['password' => $hashedPassword], "id = $userId");

        if($updateStatus) {
            $success = 'Password changed successfully!';
        } else {
            $errors['general'][] = 'Failed to change password';
            $fail = 'Password update failed!';
        }
    } else {
        $fail = 'Password update failed!';
    }
}

?>

<div class="profile">
    <!-- Header -->
    <div class="profile__header">
        <h2 class="heading-2">My Profile</h2>
        <p class="profile__desc">Manage your profile information and
            security</p>
    </div>

    <!-- Success Message -->
    <?php if (!empty($success)): ?>
    <div class="alert alert--success">
        <i class="fa-solid fa-circle-check"></i>
        <?php echo $success; ?>
    </div>
    <?php elseif(!empty($fail)): ?>
    <div class="alert alert--error">
        <i class="fa-solid fa-xmark"></i>
        <?php echo $fail; ?>
    </div>
    <?php endif; ?>

    <!-- Body -->
    <div class="profile__body">
        <!-- profile left -->
        <div class="profile-left">
            <!-- Profile Card -->
            <div class="profile-card">
                <!-- Top -->
                <div class="profile-card__top">
                    <div class="profile__avatar-wrapper">
                        <img class="profile__avatar"
                            id="avatarPreview"
                            src="<?php echo $avatarURL ?>"
                            alt="<?php echo $user['fullname'] ?>">
                        <div class="avatar-badge">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                    </div>
                    <h3 class="profile__name">
                        <?php echo $user['fullname'] ?>
                    </h3>
                    <p class="profile__email">
                        <?php echo $user['email'] ?>
                    </p>
                    <p
                        class="badge <?php echo $user['role'] === 'admin' ? 'badge--admin' : 'badge--user' ?>">
                        <?php echo $user['role'] ?>
                    </p>
                    <p class="badge badge--status">
                        <?php echo $user['status'] ?>
                    </p>
                </div>

                <!-- Bottom -->
                <div class="profile-card__bottom">
                    <p class="profile__joined">
                        Joined
                        <?php echo date("M Y", strtotime($user['created_at'])) ?>
                    </p>
                </div>
            </div>

            <!-- Statistic Card -->
            <div class="profile-card">
                <div class="stats-card">
                    <h3 class="stats-card__title">
                        My Statistics
                    </h3>
                    <ul class="stats-card__list">
                        <!-- Stats-card Item 1 -->
                        <li>
                            <div class="stats-card__item">
                                <!-- Icon -->
                                <div
                                    class="stats-card__icon stats-card__icon--primary">
                                    <i
                                        class="fa-solid fa-file-lines"></i>
                                </div>
                                <div class="stats-card__content">
                                    <h4 class="stats-card__label">
                                        Documents
                                    </h4>
                                    <p class="stats-card__value">
                                        <?php echo $totalDocs ?>
                                    </p>
                                </div>
                            </div>
                        </li>

                        <!-- Stats-card Item 2 -->
                        <li>
                            <div class="stats-card__item">
                                <!-- Icon -->
                                <div
                                    class="stats-card__icon stats-card__icon--success">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                                <div class="stats-card__content">
                                    <h4 class="stats-card__label">
                                        Total Views
                                    </h4>
                                    <p class="stats-card__value">
                                        <?php echo $totalViews ?>
                                    </p>
                                </div>
                            </div>
                        </li>

                        <!-- Stats-card Item 3 -->
                        <li>
                            <div class="stats-card__item">
                                <!-- Icon -->
                                <div
                                    class="stats-card__icon stats-card__icon--warning">
                                    <i
                                        class="fa-solid fa-download"></i>
                                </div>
                                <div class="stats-card__content">
                                    <h4 class="stats-card__label">
                                        Downloads
                                    </h4>
                                    <p class="stats-card__value">
                                        <?php echo $totalDowns ?>
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Profile Right -->
        <div class="profile-right">
            <!-- Edit Profile Form -->
            <div class="profile-card">
                <div class="profile-form">
                    <h3 class="profile-form__title">Edit Profile</h3>
                    <form action="" method="POST"
                        enctype="multipart/form-data"
                        class="profile-form__inner">
                        <!-- Avatar Upload -->
                        <div class="profile-form__group">
                            <label for="avatarInput"
                                class="profile-form__label">Edit
                                Avatar</label>
                            <div class="">
                                <input type="file" name="avatar"
                                    id="avatarInput" accept="image/*"
                                    class="profile-form__input avatar-input">
                                <label for="avatarInput"
                                    class="profile-form__upload">
                                    <i
                                        class="fa-solid fa-cloud-arrow-up"></i>
                                    Choose Image
                                </label>
                                <span class="profile-form__hint">JPG,
                                    PNG, GIF or WEBP (Max 5MB)</span>
                            </div>

                            <!-- Errors -->
                            <?php echo formError('avatar', $errors); ?>
                        </div>

                        <!-- Full name -->
                        <div class="profile-form__group">
                            <label for="fullname"
                                class="profile-form__label">Fullname</label>
                            <input type="text" id="fullname"
                                name="fullname"
                                value="<?php echo $user['fullname'] ?>"
                                placeholder="Enter your fullname"
                                class="profile-form__input <?php echo !empty($errors['fullname']) ? 'error' : '' ?>">

                            <!-- Errors -->
                            <?php echo formError('fullname', $errors) ?>
                        </div>

                        <!-- Email -->
                        <div class="profile-form__group">
                            <label for="email"
                                class="profile-form__label">Email
                                Address</label>
                            <input type="email"
                                value="<?php echo $user['email'] ?>"
                                readonly disabled
                                class="profile-form__input">
                            <p class="profile-form__hint">
                                Email cannot be changed
                            </p>
                        </div>

                        <button name="update_profile"
                            class="btn btn--primary profile-form__btn">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="profile-card">
                <div class="profile-form">
                    <h3 class="profile-form__title">Change Password
                    </h3>
                    <form action="" method="POST"
                        class="profile-form__inner">
                        <!-- Current Password -->
                        <div class="profile-form__group">
                            <label for="current_password"
                                class="profile-form__label">Current
                                Password</label>
                            <div
                                class="profile-form__input-wrapper password-wrapper">
                                <input type="password"
                                    name="current_password"
                                    id="current_password"
                                    class="profile-form__input <?php echo $errors ? "error" : "" ?>"
                                    placeholder="Enter your current password"
                                    autocomplete="current-password">
                                <button type="button"
                                    class="toggle-password"><i
                                        class="fa-regular fa-eye-slash"></i>
                                </button>
                            </div>

                            <!-- Errors -->
                            <?php echo formError('current_password', $errors) ?>
                        </div>

                        <!-- New Password -->
                        <div class="profile-form__group">
                            <label for="new_password"
                                class="profile-form__label">New
                                Password</label>
                            <div
                                class="profile-form__input-wrapper password-wrapper">
                                <input type="password"
                                    name="new_password"
                                    id="new_password"
                                    class="profile-form__input <?php echo $errors ? "error" : "" ?>"
                                    placeholder="Enter your new password"
                                    autocomplete="new-password">
                                <button type="button"
                                    class="toggle-password"><i
                                        class="fa-regular fa-eye-slash"></i>
                                </button>
                            </div>

                            <!-- Errors -->
                            <?php echo formError('new_password', $errors) ?>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="profile-form__group">
                            <label for="confirm_password"
                                class="profile-form__label">Confirm
                                New
                                Password</label>
                            <div
                                class="profile-form__input-wrapper password-wrapper">
                                <input type="password"
                                    name="confirm_password"
                                    id="confirm_password"
                                    class="profile-form__input <?php echo $errors ? "error" : "" ?>"
                                    placeholder="Confirm your new password"
                                    autocomplete="new-password">
                                <button type="button"
                                    class="toggle-password"><i
                                        class="fa-regular fa-eye-slash"></i>
                                </button>
                            </div>

                            <!-- Errors -->
                            <?php echo formError('confirm_password', $errors) ?>
                        </div>

                        <button name="update_password"
                            class="btn btn--primary profile-form__btn">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CountUp JS -->
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.9.0/countUp.umd.min.js">
</script>

<!-- Main JS -->
<script src="<?php echo _HOST_URL ?>pages/auth/main.js">
</script>

<script>
// Xem trước avatar
const avatarInput = document.querySelector("#avatarInput");
const uploadLabel = document.querySelector(".profile-form__upload");
const avatarPreview = document.querySelector("#avatarPreview");


avatarInput.onchange = (e) => {
    const file = e.target.files[0];
    console.log(file);
    if (file) {
        uploadLabel.innerHTML = file.name;
        uploadLabel.style.borderColor = '#4caf50'; // Màu xanh lá
        uploadLabel.style.color = '#4caf50';
        uploadLabel.style.background = '#e8f5e9';

        // Preview avatar
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log(e.target);
            avatarPreview.src = e
                .target.result;
        }
        reader.readAsDataURL(file);
    }
}

// Ẩn alert 
setTimeout(() => {
    const alert = document.querySelector(".alert");
    if (alert) {
        alert.style.opacity = 0;
        setTimeout(() => {
            alert.remove();
        }, 300)
    }
}, 3000)

// Count statistic
const counts = document.querySelectorAll(".stats-card__value");
counts.forEach(count => {
    const finalValue = +(count.innerText) || 0;
    const up = new countUp.CountUp(count, finalValue, {
        duration: 4,
    });
    if (!up.error) {
        up.start();
    } else {
        console.error(up.error);
    }
})
</script>