<?php 
    if(!defined("_NTK")) {
        die("Truy cập không hợp lệ");
    }

// Send mail 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($emailTo, $subject, $content) {

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = '9akhoint11@gmail.com';                     //SMTP username
        $mail->Password   = 'vlny zfzz vycr zzyg';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('9akhoint11@gmail.com', 'Khoi Studyshare');
        $mail->addAddress($emailTo);     //Add a recipient

        //Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,          // Tắt kiểm tra chứng chỉ
                'verify_peer_name' => false,     // Tắt kiểm tra tên miền
                'allow_self_signed' => true      // Cho phép chứng chỉ tự ký
            )
        );

        return $mail->send();
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Kiểm tra phương thức POST
function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// Kiểm tra phương thức GET
function isGet() {    
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

// Lọc dữ liệu
function filterData($method = '') {
    $result = [];

    $process = function($inputArray) {
        $out = [];
        foreach($inputArray as $key => $value) {
            $key = strip_tags($key); // strip_tags: loại bỏ thẻ html, js 

            if(is_array($value)) {
                // lọc từng phần tử của mảng
                $tmp = [];
                foreach($value as $k => $v) {
                    $tmp[$k] = is_string($v) ? trim(filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS)) : $v;
                }

                $out[$key] = $tmp;
            } else {
                $out[$key] = is_string($v) ? trim(filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS)) : $v;
            }
        }

        return $out;
    };

    if (empty($method)) {
        if (isGet() && !empty($_GET)) {
            $result = array_merge($result, $process($_GET));
        }

        if (isPost() && !empty($_POST)) {
            $result = array_merge($result, $process($_POST));
        }
    } else {
        if ($method === 'get') {
            $result = $process($_GET);
        } elseif ($method === 'post') {
            $result = $process($_POST);
        }
    }

    return $result;
}