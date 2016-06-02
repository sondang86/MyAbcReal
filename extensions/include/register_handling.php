<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $FULL_DOMAIN_NAME;

    if (isset($_POST['quick_register_submit'])){
        $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_STRING);

        //Verify captcha
        if (md5($_POST['code']) !== $_SESSION['code']){
            $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Sai mã Captcha'));
            $website->redirect($FULL_DOMAIN_NAME.'/mod-vn-jobseekers.html');
        } 

        //Check if user already exists on both employers and jobseeker tables
        $db->where('username', $email)->withTotalCount()->getOne('jobseekers');
        $jobseeker_found = $db->totalCount;
        $db->where('username', $email)->withTotalCount()->getOne('employers');
        $employer_found = $db->totalCount;

        //Make sure username is not registered
        if (($jobseeker_found > 0) || ($employer_found > 0)){
            $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Địa chỉ email này đã được đăng ký, vui lòng dùng email khác'));
            $website->redirect($FULL_DOMAIN_NAME.'/mod-vn-jobseekers.html');

        } else { // insert new user data to jobseekers, jobseeker_resumes tables
            $verification_code = $commonQueries->generateConfirmationCode();
            $password = password_hash(filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING), PASSWORD_DEFAULT, ['cost' => 12]);        

            $data = Array (
                "date"              => time(),
                "registered_on"     => time(),
                "username"          => $email,
                "active"            => 0, //default is inactive (0) until user verified email (1)
                "password"          => $password,
                "mobile"            => filter_input(INPUT_POST,'mobile', FILTER_SANITIZE_NUMBER_INT),
                "first_name"        => filter_input(INPUT_POST,'firstname', FILTER_SANITIZE_STRING),
                "dob"               => strtotime(filter_input(INPUT_POST, 'dob' ,FILTER_SANITIZE_STRING)),
    //            "last_name"         => filter_input(INPUT_POST,'lastname', FILTER_SANITIZE_STRING),
                "newsletter"        => 1, 
                "gender"            => filter_input(INPUT_POST,'gender', FILTER_SANITIZE_NUMBER_INT),
                "verification_code" => $verification_code
            );
            $id = $db->insert ('jobseekers', $data);
            if ($id) { //Success
                $db->insert('jobseeker_resumes', array('username' => filter_input(INPUT_POST,'email', FILTER_SANITIZE_STRING)));

                //Send email confirmation link
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

                $mail->Subject = 'Xác nhận tài khoản tại vieclambanthoigian.com.vn';
                $mail->Body    = "Chào bạn!\n"
                                . "Cảm ơn bạn đã đăng ký tại vieclambanthoigian\n\n"
                                . "Để hoàn tất đăng ký, bạn vui lòng truy cập vào địa chỉ dưới đây: \n\n"
                                . "http://$DOMAIN_NAME/index.php?mod=verifications&register=email&user=jobseeker&id=$id&code=$verification_code \n\n"
                                . "Trân trọng \n"
                                . "Vieclambanthoigian.com.vn";


                if(!$mail->send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo 'Message has been sent';
                }

                //Redirect back login page with message
                $commonQueries->flash('message', $commonQueries->messageStyle('info', "Cảm ơn bạn đã đăng ký, bạn hãy kiểm tra email và xác nhận tài khoản để hoàn tất"));
                $website->redirect("http://$DOMAIN_NAME/index.php?mod=login");

            } else {
                $commonQueries->flash('message', $commonQueries->messageStyle('error', 'Có lỗi xảy ra, vui lòng liên hệ info@vieclambanthoigian.com.vn'));
                $website->redirect("http://$DOMAIN_NAME/mod-vn-jobseekers.html");
            }
        }
    }; 
?>
