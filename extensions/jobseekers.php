<?php
// Jobs Portal, copyright vieclambanthoigian.com.vn 2016

 if(!defined('IN_SCRIPT')) die("");
 global $db, $commonQueries;

if (isset($_POST['submit'])){
    
    //Verify captcha
    if (md5($_POST['code']) !== $_SESSION['code']){
        $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Sai mã Captcha'));
        $website->redirect('http://localhost/vieclambanthoigian.com.vn/vn_Ng%C6%B0%E1%BB%9Di+t%C3%ACm+vi%E1%BB%87c.html');
    } 

    //Check if user already exists
    $db->where('username', $_POST['email'])->withTotalCount()->getOne('jobseekers');
    if ($db->totalCount > 0){
        $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Địa chỉ email này đã được đăng ký, vui lòng dùng email khác'));
        $website->redirect('http://localhost/vieclambanthoigian.com.vn/vn_Ng%C6%B0%E1%BB%9Di+t%C3%ACm+vi%E1%BB%87c.html');

    } else { // insert new user data to jobseekers, jobseeker_resumes tables
        $verification_code = $commonQueries->generateConfirmationCode();
        $data = Array (
            "date"              => time(),
            "registered_on"     => time(),
            "username"          => filter_input(INPUT_POST,'email', FILTER_SANITIZE_STRING),
            "active"            => 0, //default is inactive (0) until user verified email (1)
            "password"          => filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING),
            "mobile"            => filter_input(INPUT_POST,'mobile', FILTER_SANITIZE_NUMBER_INT),
            "first_name"        => filter_input(INPUT_POST,'firstname', FILTER_SANITIZE_STRING),
            "last_name"         => filter_input(INPUT_POST,'lastname', FILTER_SANITIZE_STRING),
            "newsletter"        => 1, 
            "gender"            => filter_input(INPUT_POST,'gender', FILTER_SANITIZE_NUMBER_INT),
            "verification_code" => $verification_code
        );
        $id = $db->insert ('jobseekers', $data);
        if ($id) { //Success
            $db->insert('jobseeker_resumes', array('username' => filter_input(INPUT_POST,'email', FILTER_SANITIZE_STRING)));
            
            //Send email confirmation link
            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
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
                            . "http://localhost/vieclambanthoigian.com.vn/index.php?mod=verifications&register=email&user=jobseeker&id=$id&code=$verification_code \n\n";
            
//            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if(!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent';
            }
            
            //Redirect back with message
            $commonQueries->flash('message', $commonQueries->messageStyle('info', "Cảm ơn bạn đã đăng ký, bạn hãy kiểm tra email và xác nhận tài khoản để hoàn tất"));
            $website->redirect('http://localhost/vieclambanthoigian.com.vn/vn_Ng%C6%B0%E1%BB%9Di+t%C3%ACm+vi%E1%BB%87c.html');

        } else {
            $commonQueries->flash('message', $commonQueries->messageStyle('error', 'Có lỗi xảy ra, vui lòng liên hệ info@vieclambanthoigian.com.vn'));
            $website->redirect('http://localhost/vieclambanthoigian.com.vn/vn_Ng%C6%B0%E1%BB%9Di+t%C3%ACm+vi%E1%BB%87c.html');
        }
    }
}; ?>

<?php echo $commonQueries->flash('message');?>
<div class="page-wrap">        
    <form action="" id="register-form" class="sky-form" method="POST">
        <header>Người tìm việc đăng ký</header>
            
        <fieldset>					
            <section>
                <label class="input">
                    <i class="icon-append fa fa-envelope-o"></i>
                    <input type="email" name="email" placeholder="Địa chỉ email">
                    <b class="tooltip tooltip-bottom-right">Địa chỉ email của bạn</b>
                </label>
            </section>
                
            <section>
                <label class="input">
                    <i class="icon-append fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Mật khẩu" id="password">
                    <b class="tooltip tooltip-bottom-right">Nhập mật khẩu mong muốn</b>
                </label>
            </section>
                
            <section>
                <label class="input">
                    <i class="icon-append fa fa-lock"></i>
                    <input type="password" name="passwordConfirm" placeholder="Xác nhận lại mật khẩu">
                    <b class="tooltip tooltip-bottom-right">Xác nhận lại mật khẩu</b>
                </label>
            </section>
            
            <section>
                <label class="input">
                    <i class="icon-append fa fa-phone"></i>
                    <input type="text" name="mobile" placeholder="Số điện thoại">
                    <b class="tooltip tooltip-bottom-right">Số điện thoại của bạn</b>
                </label>
            </section>
            
        </fieldset>
            
        <fieldset>
            <div class="row">
                <section class="col col-6">
                    <label class="input">
                        <input type="text" name="firstname" placeholder="Họ" required>
                    </label>
                </section>
                <section class="col col-6">
                    <label class="input">
                        <input type="text" name="lastname" placeholder="Tên" required>
                    </label>
                </section>
            </div>
                
            <section>
                <label class="select">
                    <select name="gender">
                        <option value="0" selected disabled>Giới tính</option>
                        <option value="1">Nam</option>
                        <option value="2">Nữ</option>
                        <option value="3">Khác</option>
                    </select>
                    <i></i>
                </label>
            </section>
                
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
            <button type="submit" class="button" name="submit">Submit</button>
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
                    firstname:
                            {
                                required: true
                    },
                    lastname:
                            {
                                required: true
                    },
                    gender:
                            {
                                required: true
                    },
                    terms:
                            {
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
                        required: 'Họ của bạn'
                    },
                    lastname:{
                        required: 'Tên bạn'
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
?>