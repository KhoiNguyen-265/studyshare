<?php 

if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

$errors = [];
$oldData = [];
$result = filterData(); 

if(isPost()) {
    // Validate email 
    if (empty($result['email'])) {
        $errors['email']['required'] = "Please enter your email.";
    } else {
        // Đúng định dạng email 
        if (!validateEmail($result['email'])) {
            $errors['email']['isEmail'] = "Please enter a valid email address.";
        }
    }

    // Validate password 
        if (empty($result['password'])) {
        $errors['password']['required'] = "Please enter your password.";
    } 

    // Handle login 
    if (empty($errors)) {
        $email = $result['email'];
        $password = $result['password'];

        $user = getOne("SELECT id, fullname, email, password, status, avatar, role FROM users WHERE email = '$email' ");
        
        if (!empty($user)) {
            // Check status 
            if ($user['status'] === 'pending') {
                $errors['login']['status'] = "Account not activated. Please check your email for the verification link.";
            } elseif ($user['status'] === 'blocked') {
                $errors['login']['status'] = "Your account has been blocked by the administrator.";
            } else {
                // Mật khẩu đúng
                if (password_verify($password, $user['password'])) {
                    // Tài khoản login 1 nơi 
                    $userId = $user['id'];
                    $checkAlready = getRows("SELECT * FROM token_login WHERE user_id = '$userId'");
                    if($checkAlready > 1) {
                        $errors['login']['multiple'] = "Your account is currently active on another device. Please try again later.";
                    } else {
                        // tạo token -> insert vào table token_login
                        $token = bin2hex(random_bytes(32));
                        $dataInsert = [
                            'token' => $token,
                            'user_id' => $user['id']
                        ];
                        $insertStatus = insert('token_login', $dataInsert);
                        if(!$insertStatus) {
                            $errors['login']['system'] = "Login is failed";
                        }

                        // Gán token và dữ liệu người dùng lên session 
                        setSession('token_login', $token);
                        setSession('user_id', $user['id']);
                        setSession('fullname', $user['fullname']);
                        setSession('avatar', $user['avatar']);
                        setSession('role', $user['role']);
                        
                        // Điều hướng trang 
                        redirect("?page=home");
                        }
                }
                else {
                    // Sai mật khẩu
                    $errors['login']['mismatch'] = "Incorrect email or password.";
                }
            }
        } else {
            // Không có email 
            $errors['login']['mismatch'] = "Incorrect email or password.";
        }
    }

    if (!empty($errors)) {
        setSessionFLash('oldData', $result);
        setSessionFLash('errors', $errors);
        
        $oldData = getSessionFlash('oldData');
        $errors = getSessionFlash('errors');
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <?php require_once "./includes/head.php" ?>
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_PAGES ?>/auth/auth.css">
</head>

<body>
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

        <!-- Login -->
        <form action="" method="POST" class="auth-form"
            enctype="multipart/form-data">
            <h2 class="auth__heading">Welcome Back!</h2>
            <p class="auth__desc">
                Dont have an account? <a
                    href="?page=auth&action=register"
                    class="auth__link">Sign up</a>
            </p>

            <div class="auth__field">
                <button class="btn">
                    <img src="<?php echo _HOST_URL_ASSETS ?>/icons/google.svg"
                        alt="Google">
                    Sign in with google
                </button>
            </div>

            <div class="auth__main login__main">
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

                <!-- Passowrd -->
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
                    <?php echo formError('login', $errors) ?>
                </div>

                <div class="auth__forget">
                    <a href="#!">Forgot password</a>
                </div>
            </div>

            <button class="btn auth__btn">Sign In</button>
        </form>
    </div>
    <script src="<?php echo _HOST_URL_PAGES ?>/auth/main.js"></script>
</body>

</html>