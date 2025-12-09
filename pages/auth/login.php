<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
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

        <!-- Register -->
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
                    <input type="email" id="email" class="auth__input"
                        placeholder="khoint@gmail.com">
                </div>

                <!-- Passowrd -->
                <div class="auth__field">
                    <label for="password">Password *</label>
                    <div class="password-wrapper">
                        <input type="password" id="password"
                            class="auth__input"
                            placeholder="Create a password">
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>
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