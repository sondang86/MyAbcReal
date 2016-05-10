<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $DOMAIN_NAME,$db, $commonQueries;
?>
<?php
    // Jobs Portal All Rights Reserved
?><?php
    if(!defined('IN_SCRIPT')) die("");
    global $DOMAIN_NAME;
?>
<br/>
<div class="page-wrap">
    <h5><?php $commonQueries->flash('message');?></h5>
    <form action="loginaction.php" id="login-form" class="sky-form" method="POST">
        <header>Đăng nhập</header>
            
        <fieldset>					
            <section>
                <div class="row">
                    <label class="label col col-4">E-mail</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="email" name="email" required>
                            <b class="tooltip tooltip-bottom-right">Địa chỉ email của bạn</b>
                        </label>
                    </div>
                </div>
            </section>
                
            <section>
                <div class="row">
                    <label class="label col col-4">Password</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-lock"></i>
                            <input type="password" name="password" required>
                        </label>
                        <div class="note"><a href="#sky-form2" class="modal-opener">Quên mật khẩu?</a></div>
                    </div>
                </div>
            </section>
                
            <section>
                <div class="row">
                    <div class="col col-4"></div>
                    <div class="col col-8">
                        <label class="checkbox"><input type="checkbox" name="remember" checked><i></i>Keep me logged in</label>
                    </div>
                </div>
            </section>
        </fieldset>
        <footer>
            <button type="submit" class="button">Đăng nhập</button>
            <a href="#register-modal" class="button button-secondary modal-opener">Đăng ký</a>
        </footer>
    </form>			
</div>
    
<style>
    .sky-form-modal {
        width: 500px;
    }
</style>

<!--REGISTER-->
<section id="register-modal" class="sky-form register-modal sky-form-modal">
    <header>Đăng ký: </header>
        <footer>
            <div class="col col-6"><a href="http://<?php echo $DOMAIN_NAME?>/mod-vn-employers_registration.html" class="button button-primary">Nhà tuyển dụng</a></div>
            <div class="col col-6"><a href="http://<?php echo $DOMAIN_NAME?>/mod-vn-jobseekers.html" class="button button-secondary">Người tìm việc</a></div>
        </footer>
</section>
    

<!--LOGIN-->
<form action="http://<?php echo $DOMAIN_NAME?>/index.php?mod=verifications" id="sky-form2" class="sky-form sky-form-modal" method="POST">
    <input type="hidden" name="type" value="password_reset">
    <header>Reset mật khẩu</header>
        
    <fieldset>					
        <section>
            <label class="label">E-mail</label>
            <label class="input">
                <i class="icon-append fa fa-envelope-o"></i>
                <input type="email" name="email" id="email">
                <b class="tooltip tooltip-bottom-right">Vui lòng nhập địa chỉ email để reset mật khẩu</b>
            </label>
        </section>
    </fieldset>
        
    <footer>
        <button type="submit" name="submit" class="button">Gửi</button>
        <a href="#" class="button button-secondary modal-closer">Đóng</a>
    </footer>
        
    <div class="message">
        <i class="fa fa-check"></i>
        <p>Your request successfully sent!<br><a href="#" class="modal-closer">Close window</a></p>
    </div>
</form>
    
<script type="text/javascript">
    $(function()
    {
        // Validation for login form
        $("#login-form").validate(
        {					
            // Rules for form validation
            rules:
            {
                email:
                {
                    required: true,
                    email: true
                },
                password:
                {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                }
            },
            
            // Messages for form validation
            messages:
            {
                email:
                {
                    required: 'Vui lòng điền chính xác địa chỉ email',
                    email: 'Vui lòng điền chính xác địa chỉ email'
                },
                password:
                {
                    required: 'Nhập mật khẩu để tiếp tục',
                    minlength: 'Mật khẩu phải có tối thiểu 3 ký tự'
                }
            },					
            
            // Do not change code below
            errorPlacement: function(error, element)
            {
                error.insertAfter(element.parent());
            }
        });
        
        
        // Validation for recovery form
        $("#sky-form2").validate(
        {					
            // Rules for form validation
            rules:
            {
                email:
                {
                    required: true,
                    email: true
                }
            },
            
            // Messages for form validation
            messages:
            {
                email:
                {
                    required: 'Vui lòng điền chính xác địa chỉ email',
                    email: 'Vui lòng điền chính xác địa chỉ email'
                }
            },
            
            // Ajax form submition					
            submitHandler: function(form)
            {
                $(form).ajaxSubmit(
                        {
                            beforeSend: function()
                    {
                        $('#sky-form button[type="submit"]').attr('disabled', true);
                    },
                    success: function()
                    {
                        $("#sky-form2").addClass('submited');
                    }
                });
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
$website->Title($M_LOGIN);
$website->MetaDescription("");
$website->MetaKeywords("");
?>