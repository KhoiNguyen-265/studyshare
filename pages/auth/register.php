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
    <title>Register</title>

    <?php 
        require_once("./includes/head.php");
     ?>

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
        <form action="" method="POST" class="auth-form">
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
                    <input type="text" id="name" class="auth__input"
                        placeholder="Nguyễn Tiến Khởi">
                </div>
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

                <!-- Confirm password -->
                <div class="auth__field">
                    <label for="confirm">Confirm Password *</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm"
                            class="auth__input"
                            placeholder="Confirm a password">
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>
                </div>

                <div class="auth__required">
                    <input type="checkbox" name="" id="">
                    <p class="auth__desc">I agree to the <a
                            href="#!">Tearms
                            of Use</a> and have read and
                        understand the <a href="#!">Privacy
                            policy</a>.
                    </p>
                </div>
            </div>

            <button class="btn auth__btn">Create my account</button>
        </form>
    </div>
    <script src="<?php echo _HOST_URL_PAGES ?>/auth/main.js"></script>
</body>

</html>