<?php 

if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

$errors = [];
$oldData = [];
$showSuccessModal = false;
if (isPost()) {
    $result = filterData();


    // Validate fullname 
    if (empty($result['fullname'])) {
        $errors['fullname']['required'] = "Please enter your full name.";
    } elseif (strlen($result['fullname']) < 4) {
        $errors['fullname']['length'] = "Full name must be at least 4 characters.";
    }   

    // Validate email 
    if (empty($result['email'])) {
        $errors['email']['required'] = "Please enter your email.";
    } else {
        // Đúng định dạng email 
        if (!validateEmail($result['email'])) {
            $errors['email']['isEmail'] = "Please enter a valid email address.";
        } else {
            // Tồn tại email hay chưa
            $email = $result['email'];

            $checkEmail = getRows("SELECT * FROM users WHERE email = '$email'");

            if ($checkEmail) {
                $errors['email']['check'] = "This email address is already registered.";
            }
        }
    }

    // Validate password 
        if (empty($result['password'])) {
        $errors['password']['required'] = "Please enter your password.";
    } elseif (strlen($result['password']) < 8) {
        $errors['password']['length'] = "Password must be at least 8 characters.";
    }   

    // Validate password 
    if (empty($result['confirm'])) {
        $errors['confirm']['required'] = "Please confirm your password.";
    } elseif ($result['confirm'] !== $result['password']) {
        $errors['confirm']['like'] = "Passwords do not match. Please try again.";
    } 

    // Xử lý database và sendmail
    if (empty($errors)) {
        $activeToken = bin2hex(random_bytes(32));
        
        $data = [
            'fullname' => $result['fullname'],
            'email' => $result['email'],
            'password' => password_hash($result['password'], PASSWORD_DEFAULT),
            'active_token' => $activeToken,
            'status' => 'pending',
            'created_at' => date('Y:m:d H:i:s')
        ];

        // Lưu dữ liệu vào database
        $insertStatus = insert('users', $data);
        
        if ($insertStatus) {
            $emailTo = $result['email'];
            $subject = 'Kích hoạt tài khoản Studyshare.';
            $verifyUrl = _HOST_URL . '?page=auth&action=active&token=' . $activeToken;

            $content = "
                <h2 style='color:#212121;'>Xác Minh Tài Khoản</h2>

                <p style='color:#757575;'>
                    Cảm ơn bạn đã đăng ký tài khoản tại <strong>StudyShare</strong>.
                    Vui lòng nhấn nút bên dưới để kích hoạt tài khoản:
                </p>

                <p style='text-align:center; margin:30px 0;'>
                    <a href='" . $verifyUrl . "' 
                    style='background:#1e88e5; color:#fff; padding:12px 24px; border-radius:6px; text-decoration:none;'>
                        Kích Hoạt Tài Khoản
                    </a>
                </p>
            ";

            $sendStatus = sendMail($emailTo, $subject, $content);
            if ($sendStatus) {
                $showSuccessModal = true;
                echo "<script>
                window.showModal = true
                </script>";
            } else {
                echo "<script>
                window.showModal = true
                </script>";
            }
    } else {
        $errors['system']['mail'] = "System error: Unable to send verification email. Please try again later.";
    }
} else {
        // Lưu dữ liệu cũ
        setSessionFLash('oldData', $result);

        // Lưu errors
        setSessionFLash('errors', $errors);
        }

        $oldData = getSessionFlash('oldData');
        $errors = getSessionFlash('errors');
        }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <?php 
        require_once("./includes/head.php");
     ?>

    <!-- Modal -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/KhoiNguyen-265/togbox-lib@v1.0.1/togbox.min.css">

    <!-- Auth CSS -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_PAGES ?>/auth/auth.css">
</head>

<body>
    <div id="modal"></div>
    <div class="auth__container">
        <!-- Logo -->
        <a href="#" class="logo auth__logo">
            <img src="<?php echo _HOST_URL_ASSETS ?>/icons/logo.svg"
                alt="Study Share" />
            <span>StudyShare</span>
        </a>

        <!-- Decoration -->
        <img src="<?php echo _HOST_URL_ASSETS ?>/icons/auth-decoration.png"
            class="auth-deco" alt="">

        <!-- Register -->
        <form action="" method="POST" class="auth-form"
            enctype="multipart/form-data">
            <h2 class="auth__heading">Sign Up</h2>
            <p class="auth__desc">
                Already have an account? <a
                    href="?page=auth&action=login"
                    class="auth__link">Sign in</a>
            </p>

            <div class="auth__field">
                <button class="btn">
                    <img src="<?php echo _HOST_URL_ASSETS ?>/icons/google.svg"
                        alt="Google">
                    Sign up with google
                </button>
                <p class="auth__desc">Or sign up with email</p>
            </div>


            <div class="auth__main">
                <!-- Name -->
                <div class="auth__field">
                    <label for="name">Full Name *</label>
                    <input name="fullname" type="text" id="name"
                        class="auth__input"
                        placeholder="Nguyễn Tiến Khởi"
                        value="<?php echo oldData('fullname',$oldData) ?>">

                    <!-- Error -->
                    <?php echo formError('fullname', $errors) ?>
                </div>
                <!-- Email -->
                <div class="auth__field">
                    <label for="email">Email *</label>
                    <input name="email" type="email" id="email"
                        class="auth__input"
                        placeholder="khoint@gmail.com"
                        value="<?php echo oldData('email',$oldData) ?>">

                    <!-- Error -->
                    <?php echo formError('email', $errors) ?>
                </div>

                <!-- Password -->
                <div class="auth__field">
                    <label for="password">Password *</label>
                    <div class="password-wrapper">
                        <input name="password" type="password"
                            id="password" class="auth__input"
                            placeholder="Create a password"
                            value="<?php echo oldData('password',$oldData) ?>">
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>

                    <!-- Error -->
                    <?php echo formError('password', $errors) ?>
                </div>

                <!-- Confirm password -->
                <div class="auth__field">
                    <label for="confirm">Confirm Password *</label>
                    <div class="password-wrapper">
                        <input name="confirm" type="password"
                            id="confirm" class="auth__input"
                            placeholder="Confirm a password"
                            value="<?php echo oldData('confirm',$oldData) ?>">
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>

                    <!-- Error -->
                    <?php echo formError('confirm', $errors) ?>
                </div>
            </div>

            <button class="btn auth__btn">Create my account</button>

            <!-- Error System -->
            <?php echo formError('system', $errors) ?>
        </form>
    </div>
    <script
        src="https://cdn.jsdelivr.net/gh/KhoiNguyen-265/togbox-lib@v1.0.1/togbox.min.js">
    </script>
    <script src="<?php echo _HOST_URL_PAGES ?>/auth/main.js"></script>
    <script>
    // Khởi tạo modal
    if (window.showModal) {
        const modal = new Togbox({
            content: '<h2 class="modal-heading"><?php echo $showSuccessModal ? "Đăng ký thành công!" : "Đăng ký không thành công!" ?></h2><p class="modal-desc"><?php echo $showSuccessModal ? "Vui lòng kiểm tra email của bạn để kích hoạt tài khoản." : "Vui lòng thử lại" ?></p>',
            templateId: "modal",
            footer: true,
            destroyOnClose: true,
            closeMethods: ['overlay', 'button', 'escape'],
        });
        modal.addFooterButton(
            "OK!",
            "modal-btn <?php echo $showSuccessModal ? "modal-btn--success" : "modal-btn--error" ?>",
            () => {
                modal.close();
            }
        );
        modal.open();
    }
    </script>
</body>

</html>