<?php
// Jobs Portal, copyright vieclambanthoigian.com.vn 2016
 if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
 global $db, $commonQueries, $gender, $FULL_DOMAIN_NAME;
 
if (isset($_POST['jobseekers_submit'])){
    $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_STRING);
    
    //Verify captcha
    if (md5($_POST['code']) !== $_SESSION['code']){
        echo $commonQueries->messageStyle('warning', 'Sai mã Captcha');
    
    } else {  //Captcha correct

        //Check if user already exists on both employers and jobseeker tables
        $db->where('username', $email)->withTotalCount()->getOne('jobseekers');
        $jobseeker_found = $db->totalCount;
        $db->where('username', $email)->withTotalCount()->getOne('employers');
        $employer_found = $db->totalCount;

        //Make sure username is not registered
        if (($jobseeker_found > 0) || ($employer_found > 0)){
            echo $commonQueries->messageStyle('warning', 'Địa chỉ email này đã được đăng ký, vui lòng dùng email khác');

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
                                . "$FULL_DOMAIN_NAME/index.php?mod=verifications&register=email&user=jobseeker&id=$id&code=$verification_code \n\n"
                                . "Trân trọng \n"
                                . "Vieclambanthoigian.com.vn";


                if(!$mail->send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo 'Message has been sent';
                }

                //Redirect back with message
                $commonQueries->flash('message', $commonQueries->messageStyle('info', "Cảm ơn bạn đã đăng ký, bạn hãy kiểm tra email và xác nhận tài khoản để hoàn tất"));
                $website->redirect("$FULL_DOMAIN_NAME/index.php?mod=login");

            } else {
                $commonQueries->flash('message', $commonQueries->messageStyle('error', 'Có lỗi xảy ra, vui lòng liên hệ info@vieclambanthoigian.com.vn'));
                $website->redirect("$FULL_DOMAIN_NAME/index.php?mod=login");
            }
        }
    }
} ?>

<?php 
    if(!isset($_SESSION['username'])){ //Only show this form if user is guest.
        echo $commonQueries->flash('message');
?>
<div class="page-wrap">        
    <form action="" id="register-form" class="sky-form" method="POST">
        <header>Người tìm việc đăng ký</header>
            
        <fieldset>					
            <section>
                <label class="input">
                    <i class="icon-append fa fa-envelope-o"></i>
                    <input type="email" name="email" placeholder="Địa chỉ email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>">
                    <b class="tooltip tooltip-bottom-right">Địa chỉ email của bạn</b>
                </label>
            </section>
          
            <div class="row">
                <section class="col col-6">
                    <label class="input">
                        <i class="icon-append fa fa-lock"></i>
                        <input type="password" name="password" placeholder="Mật khẩu" id="password">
                        <b class="tooltip tooltip-bottom-right">Nhập mật khẩu mong muốn</b>
                    </label>
                </section>

                <section class="col col-6">
                    <label class="input">
                        <i class="icon-append fa fa-lock"></i>
                        <input type="password" name="passwordConfirm" placeholder="Xác nhận lại mật khẩu">
                        <b class="tooltip tooltip-bottom-right">Xác nhận lại mật khẩu</b>
                    </label>
                </section>
            </div>
            
        </fieldset>
            
        <fieldset>
            <div class="row">                
                <section class="col col-3">
                    <label class="input">
                        <i class="icon-append fa fa-phone"></i>
                        <input type="text" name="mobile" placeholder="Số điện thoại" value="<?php if(isset($_POST['mobile'])){echo $_POST['mobile'];}?>">
                        <b class="tooltip tooltip-bottom-right">Số điện thoại của bạn</b>
                    </label>
                </section>
                
                <section class="col col-3">
                    <label class="input">
                        <input type="text" name="firstname" placeholder="Họ và tên" required value="<?php if(isset($_POST['firstname'])){echo $_POST['firstname'];}?>">
                    </label>
                </section>

                <section class="col col-3">
                    <label class="input">
                        <i class="icon-append fa fa-calendar"></i>
                        <input type="text" name="dob" id="dob" placeholder="Ngày sinh">
                    </label>
                </section>
                
                
                <section class="col col-3">
                    <label class="select">
                        <select name="gender">
                            <option value="" <?php if(!isset($_POST['gender'])){echo "selected";}?> disabled>Giới tính</option>
                            <?php foreach ($gender as $value) :?>
                            <option value="<?php echo $value['gender_id']?>" <?php if(isset($_POST['gender']) && $_POST['gender'] == $value['gender_id']){echo "selected";}?>><?php echo $value['name']?></option>
                            <?php endforeach;?>
                        </select>
                        <i></i>
                    </label>
                </section>
            </div>
                
            <section>
                <label class="checkbox"><input type="checkbox" name="subscription" id="subscription"><i></i>Tôi muốn nhận tin tức từ vieclambanthoigian.com.vn</label>
                <label class="checkbox"><input type="checkbox" name="terms" id="terms"><i></i>Tôi đồng ý với các điều khoản của vieclambanthoigian.com.vn</label>
            </section>
            
            <section class="pull-right">
                <p>Mã Captcha</p>
                <span><input type="text" required name="code" value="" size="8"></span>
                <span><img src="/vieclambanthoigian.com.vn/include/sec_image.php" width="150" height="30" ></span>
            </section>
            
        </fieldset>
        <footer>
            <button type="submit" class="button" name="jobseekers_submit">Đăng ký</button>
        </footer>
    </form>
        
    <script type="text/javascript">        
        $(function()
        {
            // Validation		
            $("#register-form").validate({					
                // Rules for form validation
                rules:
                {                         
                    email:{
                        required: true,
                        email: true
                    },
                    password:{
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    passwordConfirm:{
                        required: true,
                        minlength: 3,
                        maxlength: 20,
                        equalTo: '#password'
                    },
                    
                    mobile: {
                        required: true,
                        minlength: 6,
                        number: true
                    },
                    firstname:{
                        required: true
                    },
                    gender:{
                        required: true
                    },
                    dob: {
                        required: true,
//                        date: true
                    },
                    
                    terms:{
                        required: true
                    }
                },
                
                // Messages for form validation
                messages:{                            
                    email:{
                        required: 'Vui lòng nhập chính xác địa chỉ email (ví dụ: abc@mail.com)',
                        email: 'Vui lòng nhập chính xác địa chỉ email (ví dụ: abc@mail.com)'
                    },
                    password:{
                        required: 'Vui lòng nhập mật khẩu',
                        minlength: 'Tối thiểu 3 ký tự'
                    },
                    passwordConfirm:{
                        required: 'Xác nhận lại mật khẩu',
                        equalTo: 'Mật khẩu xác nhận phải giống như mật khẩu đã viết',
                        minlength: 'Tối thiểu 3 ký tự'
                    },
                    
                    mobile: {
                        required: 'Vui lòng nhập chính xác số điện thoại',
                        minlength: 'Số điện thoại phải có tối thiểu 6 ký tự',
                        number: 'Vui lòng nhập số điện thoại chính xác'
                    },
                    
                    firstname:{
                        required: 'Họ và tên của bạn'
                    },
                    gender:{
                        required: 'Vui lòng lựa chọn giới tính'
                    },
                    terms:{
                        required: 'Bạn phải đồng ý với các điều khoản của vieclambanthoigian.com.vn mới có thể tiếp tục'
                    }
                },					
                
                // Do not change code below
                errorPlacement: function(error, element)
                {
                    error.insertAfter(element.parent());
                }
            });
        });			
    </script>													
</div>
    
<?php
    $website->Title($M_ARE_YOU_JOBSEEKER);
    $website->MetaDescription("");
    $website->MetaKeywords(""); 
}
?>