<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $locations, $gender;
$company_sizes = $db->get('employers_company_size');

if (isset($_POST['submit'])){
    
    //Verify captcha
    if (md5($_POST['code']) !== $_SESSION['code']){
        $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Sai mã Captcha'));
    } else {

        //Check if user already exists
        $db->where('username', filter_input(INPUT_POST, 'email',FILTER_SANITIZE_EMAIL))->withTotalCount()->getOne('employers');
        if ($db->totalCount > 0){
            $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Địa chỉ email này đã được đăng ký, vui lòng dùng email khác'));

        } else { // insert new user data to jobseekers, jobseeker_resumes tables
            $verification_code = $commonQueries->generateConfirmationCode();
            $password = password_hash(filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING), PASSWORD_DEFAULT, ['cost' => 12]);        

            $data = Array (
                "date"                  => time(),
                "registered_on"         => time(),
                "username"              => filter_input(INPUT_POST,'email', FILTER_SANITIZE_STRING),
                "active"                => 0, //default is inactive (0) until user verified email (1)
                "password"              => $password,
                "company"               => filter_input(INPUT_POST,'company_name', FILTER_SANITIZE_STRING),
                "address"               => filter_input(INPUT_POST,'company_address', FILTER_SANITIZE_STRING),
                "city"                  => filter_input(INPUT_POST,'company_city', FILTER_SANITIZE_NUMBER_INT),
                "company_size"          => filter_input(INPUT_POST,'company_size', FILTER_SANITIZE_NUMBER_INT),
                "company_description"   => filter_input(INPUT_POST,'company_description', FILTER_SANITIZE_STRING),
                "website"               => filter_input(INPUT_POST,'company_website', FILTER_SANITIZE_STRING),            
                "contact_person"        => filter_input(INPUT_POST,'contact_person', FILTER_SANITIZE_STRING),
                "phone"                => filter_input(INPUT_POST,'mobile', FILTER_SANITIZE_NUMBER_INT),
                "gender"                => filter_input(INPUT_POST,'gender', FILTER_SANITIZE_NUMBER_INT),
                "newsletter"            => 1, 
                "verification_code"     => $verification_code,
                "latitude"  => filter_input(INPUT_POST,'job_map_latitude',FILTER_SANITIZE_STRING),
                "longitude" => filter_input(INPUT_POST,'job_map_longitude',FILTER_SANITIZE_STRING)
            );
            $id = $db->insert ('employers', $data);
            if ($id) { //Success

                //Send confirmation link
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
                                . "http://localhost/vieclambanthoigian.com.vn/index.php?mod=verifications&register=email&user=employer&id=$id&code=$verification_code \n\n"
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
                $website->redirect('http://localhost/vieclambanthoigian.com.vn/index.php?mod=login');

            } else {
                $commonQueries->flash('message', $commonQueries->messageStyle('error', 'Có lỗi xảy ra, vui lòng liên hệ info@vieclambanthoigian.com.vn'));
                $website->redirect('http://localhost/vieclambanthoigian.com.vn/mod-vn-employers_registration.html');
            }
        }
    }
}; 

//If user logged in, hide this form
?>
<h5><?php $commonQueries->flash('message')?></h5>
<form action="" id="register-form" class="sky-form" method="POST">
    <header>Nhà tuyển dụng đăng ký</header>    
    <fieldset>
        
        <!--EMAIL ADDRESS-->
        <section>
            <label class="input">
                <i class="icon-append fa fa-envelope-o"></i>
                <input type="email" name="email" placeholder="Địa chỉ email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>">
                <b class="tooltip tooltip-bottom-right">Địa chỉ email của bạn</b>
            </label>
        </section>
        
        <!--PASSWORD-->
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
        
        <!--COMPANY NAME-->
        <div class="row">
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-home"></i>
                    <input type="text" name="company_name" placeholder="Tên công ty" value="<?php if(isset($_POST['company_name'])){echo $_POST['company_name'];}?>">
                    <b class="tooltip tooltip-bottom-right">Tên công ty của bạn</b>
                </label>
            </section>
            
            <!--CONTACT PERSON-->
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-user"></i>
                    <input type="text" name="contact_person" placeholder="Người đại diện" required value="<?php if(isset($_POST['contact_person'])){echo $_POST['contact_person'];}?>">
                    <b class="tooltip tooltip-bottom-right">Tên người đại diện tuyển dụng</b>
                </label>
            </section>
            
        </div>
                
        <div class="row">
            <!--CITY-->
            <section class="col col-6">
                <label class="select">
                    <select name="company_city">
                        <option value="0" selected="" disabled="">Thành phố</option>
                        <?php foreach ($locations as $location) :?>
                        <option value="<?php echo $location['id']?>" <?php if(isset($_POST['company_city']) && ($_POST['company_city'] == $location['id'])){echo "selected";}?>><?php echo $location['City']?></option>
                        <?php endforeach;?>
                    </select>
                    <i></i>
                </label>
            </section>
            
            <!--SIZE-->
            <section class="col col-6">
                <label class="select">
                    <select name="company_size">
                        <option value="0" selected="" disabled="">Quy mô công ty</option>
                        <?php foreach ($company_sizes as $company_size) :?>
                        <option value="<?php echo $company_size['company_size_id']?>" <?php if(isset($_POST['company_size']) && ($_POST['company_size'] == $company_size['company_size_id'])){echo "selected";}?>><?php echo $company_size['company_size_name']?></option>
                        <?php endforeach;?>
                    </select>
                    <i></i>
                </label>
            </section>            
        </div>    
        
        <div class="row">
            
            <!--DESCRIPTION-->
            <section class="col col-6">
                <label class="textarea">
                    <i class="icon-append fa fa-file-text"></i>
                    <textarea type="text" name="company_description" placeholder="Giới thiệu"><?php if(isset($_POST['company_description'])){echo $_POST['company_description'];}?></textarea>
                    <b class="tooltip tooltip-bottom-right">Giới thiệu về công ty</b>
                </label>
            </section>
        
            <!--WEBSITE-->
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-external-link"></i>
                    <input type="text" name="company_website" placeholder="Website" value="<?php if(isset($_POST['company_website'])){echo $_POST['company_website'];}?>">
                    <b class="tooltip tooltip-bottom-right">Website công ty</b>
                </label>
            </section>            
                        
        </div>
        
    </fieldset>
    
    <fieldset>
        <div class="row">
            <!--COMPANY ADDRESS-->
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-location-arrow"></i>
                    <input type="text" name="company_address" placeholder="Địa chỉ" value="<?php if(isset($_POST['company_address'])){echo $_POST['company_address'];}?>">
                    <b class="tooltip tooltip-bottom-right">Địa chỉ công ty</b>
                </label>
            </section>
                        
            <!--PHONE NUMBER-->
            <section class="col col-3">
                <label class="input">
                    <i class="icon-append fa fa-phone"></i>
                    <input type="text" name="mobile" placeholder="Số điện thoại" value="<?php if(isset($_POST['mobile'])){echo $_POST['mobile'];}?>">
                    <b class="tooltip tooltip-bottom-right">Số điện thoại của bạn</b>
                </label>
            </section>
            
            <!--GENDER-->
            <section class="col col-3">
                <label class="select">
                    <select name="gender">
                        <option value="0" selected disabled>Giới tính</option>
                        <?php foreach ($gender as $value) :?>
                        <option value="<?php echo $value['gender_id']?>" <?php if(isset($_POST['gender']) && ($_POST['gender'] == $value['gender_id'])){echo "selected";}?>><?php echo $value['name']?></option>
                        <?php endforeach;?>
                    </select>
                    <i></i>
                </label>
            </section>
            
            <!--GOOGLE MAPS-->
            
            <section class="col col-12">
                <?php
                    //Check latitude/longitute values for Google Maps
                    $latitude = $commonQueries->check_LatitudeLongitude('','')['latitude'];
                    $longitude = $commonQueries->check_LatitudeLongitude('','')['longitude'];
                    require_once('include/google_maps.php');
                ?>
            </section>
            
        </div>
        
        <!--TERMS-->
        <section>
            <label class="checkbox"><input type="checkbox" name="subscription" id="subscription"><i></i>Tôi muốn nhận tin tức từ vieclambanthoigian.com.vn</label>
            <label class="checkbox"><input type="checkbox" name="terms" id="terms"><i></i>Tôi đồng ý với các điều khoản của vieclambanthoigian.com.vn</label>
        </section>
        
        <!--CAPTCHA-->
        <section class="pull-right">
            <p>Mã Captcha</p>
            <span><input type="text" required name="code" value="" size="8"></span>
            <span><img src="/vieclambanthoigian.com.vn/include/sec_image.php" width="150" height="30" ></span>
        </section>
        
    </fieldset>
    
    <!--SUBMIT-->
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
                
                company_name: {
                    required: true
                },
                
                company_address: {
                    required: true
                },
                
                company_city : {
                    required: true
                },
                
                company_size : {
                    required: true
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
                
                company_name: {
                    required: 'Bạn chưa điền tên công ty'
                },
                
                company_address: {
                    required: 'Bạn chưa điền tên công ty'
                },
                
                company_city : {
                    required: 'Vui lòng chọn thành phó'
                },
                
                company_size : {
                    required: 'Vui lòng chọn số nhân viên'
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
<?php
$website->Title($M_SIGNUP_EMPLOYER);
$website->MetaDescription("");
$website->MetaKeywords("");
?>
