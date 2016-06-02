<?php
// Jobs Portal, copyright vieclambanthoigian.com.vn 2016
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);

if (isset($_GET['register']) && ($_GET['register'] == "email") && ($_GET['user'] == "jobseeker")){ //Jobseeker
    
    //verify and make sure user_id & code matched with database
    $jobseeker = $db->where('id', $id)->where('verification_code', $code)->withTotalCount()->getOne('jobseekers');
    
    //set jobseeker as active
    if ($db->totalCount > 0 ){
        if($db->where ('id', $id)->update('jobseekers', array('active' => 1))){
            $commonQueries->flash('message', $commonQueries->messageStyle('info', 'xác thực thành công, bạn có thể đăng nhập'));
            $website->redirect('index.php?mod=login');
        } else {
            $website->redirect('index.php?mod=verifications&status=failed');
        }
    }
} elseif (isset($_GET['register']) && ($_GET['register'] == "email") && ($_GET['user'] == "employer")) { //Employer

    
    //verify and make sure user_id & code matched with database
    $employer = $db->where('id', $id)->where('verification_code', $code)->withTotalCount()->getOne('employers');
    
    //set employer as active
    if ($db->totalCount > 0 ){
        if($db->where ('id', $id)->update('employers', array('active' => 1))){
            $commonQueries->flash('message', $commonQueries->messageStyle('info', 'xác thực email thành công, bạn có thể đăng nhập tài khoản'));
            $website->redirect('index.php?mod=login');
        } else {
            $website->redirect('index.php?mod=verifications&status=failed');
        }
    }
} elseif (isset($_GET['status']) && ($_GET['status'] == "failed")) {
    echo "<h4>Xác minh email bị lỗi, bạn vui lòng liên hệ info@vieclambanthoigian.com.vn</h4>";
}


//Password reset
if (isset($_POST['type']) && ($_POST['type'] == 'password_reset')){
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $data = $db->withTotalCount()->rawQuery(
                "SELECT username,id, 'employers' as user_type FROM jobsportal_employers WHERE username = '$email'"
                . " UNION ALL "
                . "SELECT username,id, 'jobseekers' FROM jobsportal_jobseekers WHERE username = '$email'"
            );
    
    
    if($db->totalCount > 0){ //email exists, send an email reset password to user
        $user_email = $data[0]['username'];
        $reset_email_code = $commonQueries->generateConfirmationCode();
        $id = $data[0]['id'];
        //Update verification code in db
        if ($data[0]['user_type'] == 'jobseekers'){
            $table = 'jobseekers';
        } elseif ($data[0]['user_type'] == 'employers') {
            $table = 'employers';
        };
        
        $db->where('username', $data[0]['username']);
        if (!$db->update ($table, array('reset_email_code' => "$reset_email_code"))){ //Failed
            echo 'update failed: ' . $db->getLastError();
        } else {
            
            //Send email notification and verification code to user email
            $mail = new PHPMailer;

            $mail->CharSet = 'UTF-8';                             // Unicode character encode
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'dang.viet.son.hp4@gmail.com';                 // SMTP username
            $mail->Password = 'haiphong@!#123';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            $mail->setFrom('info@vieclambanthoigian.com.vn', 'Mailer');
            $mail->addAddress('dang.viet.son.hp@gmail.com', '');     // Add a recipient

            $mail->Subject = 'Xác nhận reset mật khẩu tại vieclambanthoigian.com.vn';
            $mail->Body    = "Chào bạn!\n"
                            . "Cảm ơn bạn đã đăng ký tại vieclambanthoigian\n\n"
                            . "Để hoàn tất reset mật khẩu, bạn vui lòng truy cập vào địa chỉ dưới đây: \n\n"
                            . "http://$DOMAIN_NAME/index.php?mod=reset_password&user=$user_email&id=$id&code=$reset_email_code \n\n"
                            . "Trân trọng \n"
                            . "Vieclambanthoigian.com.vn";
            
            
            if(!$mail->send()) {//For debug purpose only
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo; die;
            } else {
                echo 'Message has been sent';
            }
            
            //Afterall, Inform to user
            $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Mail đã gửi đến email của bạn, Vui lòng kiểm tra và reset password'));
            $website->redirect('index.php?mod=login');
        }    
        
    } else {
        $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Địa chỉ email không tồn tại. Không thể thực hiện'));
        $website->redirect('index.php?mod=login');
    }
}


