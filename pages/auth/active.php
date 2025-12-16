<?php 
if(!defined("_NTK")) {
    die("Truy cập không hợp lệ");
}

$msg = "";
$msgDesc = "";
$msgType = "error";

$result = filterData();
// echo "<pre>";
// print_r($result);
// echo "</pre>";

if (!empty($result['token'])) {
    $token = $result['token'];
    $user = getOne("SELECT * FROM users WHERE active_token = '$token' AND status = 'pending'");

    if(!empty($user)) {
        // Token hợp lệ
        $userId = $user['id'];
        $dataUpdate = [
            'status' => 'activated',
            'active_token' => null
        ];
        $updateStatus = update('users', $dataUpdate, "id = $userId");

        // update success 
        if($updateStatus) {
            $msg = "Congratulations!";
            $msgDesc = "Your account has been successfully activated.";
            $msgType = "success";
        } else {
            $msg = "Please try again!";
            $msgDesc = "System error: Unable to activate your account.";
            $msgType = "error";
        }
    } else {
        // Token không tồn tại
        $msg = "Please try again!";
        $msgDesc = "The activation link is invalid or your account has already been activated.";
        $msgType = "error";
    }

} else {
    $msg = "Invalid request!";
    $msgDesc = "The activation token is missing.";
    $msgType = "error";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Active account</title>

    <?php 
        require_once("./includes/head.php");
     ?>

    <!-- Auth CSS -->
    <link rel="stylesheet"
        href="<?php echo _HOST_URL_PAGES ?>/auth/auth.css">
</head>

<body>
    <!-- Decoration -->
    <img src="<?php echo _HOST_URL_ASSETS ?>/icons/auth-decoration.png"
        class="auth-deco active-deco" alt="">

    <div class="auth__container active__container">
        <!-- Logo -->
        <a href="#" class="logo auth__logo">
            <img src="<?php echo _HOST_URL_ASSETS ?>/icons/logo.svg"
                alt="Study Share" />
            <span>StudyShare</span>
        </a>

        <!-- Content -->
        <div class="auth-form active-form">
            <img src="<?php echo _HOST_URL_ASSETS ?>/icons/trophy.svg"
                alt="">
            <h2 class="auth__heading active__heading">
                <?php echo $msg ?>
            </h2>
            <p class="auth__desc active__desc"><?php echo $msgDesc ?>
            </p>

            <?php 
                echo $msgType === "success" ? 
                    "<a href='?page=auth&action=login' class='auth__btn'>
                        Go to sign in
                    </a>" 
                    : 
                    "<a href='?page=auth&action=register' class='auth__btn'>
                        Back to registration
                    </a>"; 
            ?>
        </div>
    </div>
</body>

</html>