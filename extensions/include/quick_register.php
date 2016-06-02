<?php 
    if(!defined('IN_SCRIPT')) die("");
    global $db, $FULL_DOMAIN_NAME;
?>
<style>
    @media only screen and (min-width: 601px){
        .quick-register-modal {
            width: 600px;
        }
    }
    
    @media only screen and (max-width: 600px){
        .quick-register-modal header {
            display: none;
        }
        
        .quick-register-modal {
            width: 400px;
        }
    }
    
</style>

<?php
    if(isset($_POST['quick_register_submit'])){
        require_once ('register_handling.php'); //Perform register process
    }
?>

<form action="" id="quick-register" class="sky-form sky-form-modal quick-register-modal" method="POST">
        <header>Người tìm việc đăng ký</header>        
        <fieldset>					
            <section>
                <label class="input">
                    <i class="icon-append fa fa-envelope-o"></i>
                    <input type="email" name="email" placeholder="Địa chỉ email">
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
        
            <div class="row">                
                <section class="col col-6">
                    <label class="input">
                        <i class="icon-append fa fa-phone"></i>
                        <input type="text" name="mobile" placeholder="Số điện thoại">
                        <b class="tooltip tooltip-bottom-right">Số điện thoại của bạn</b>
                    </label>
                </section>
                
                <section class="col col-6">
                    <label class="input">
                        <input type="text" name="firstname" placeholder="Họ và tên" required>
                    </label>
                </section>
                
                <section class="col col-6">
                    <label class="input">
                        <i class="icon-append fa fa-calendar"></i>
                        <input type="text" name="dob" id="dob" placeholder="Ngày sinh">
                    </label>
                </section>

                <section class="col col-6">
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
            </div>
            
            
            
            <section>
                <label class="checkbox"><input type="checkbox" name="terms" id="terms"><i></i>Tôi đồng ý với các điều khoản của vieclambanthoigian.com.vn</label>
            </section>
            
            <section class="pull-right">
                <p>Mã Captcha</p>
                <span><input type="text" required name="code" value="" size="8"></span>
                <span><img src="/vieclambanthoigian.com.vn/include/sec_image.php" width="150" height="30" ></span>
            </section>
            
        </fieldset>
        <footer>            
            <button type="submit" class="button" name="quick_register_submit">Đăng ký</button>
            <a href="<?php echo $FULL_DOMAIN_NAME;?>/mod-vn-employers_registration.html" class="button" >Nhà tuyển dụng</a>
        </footer>
    </form>
    
    <script type="text/javascript">
        $(function()
        {
            // Validation		
            $("#quick-register").validate({					
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
                        required: 'Vui lòng nhập chính xác địa chỉ email',
                        email: 'Vui lòng nhập chính xác địa chỉ email'
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
                        required: 'Họ và tên không được để trống'
                    },
                    
                    gender:{
                        required: 'Vui lòng lựa chọn giới tính'
                    },
                    terms:{
                        required: 'Bạn chưa đồng ý với các điều khoản'
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
