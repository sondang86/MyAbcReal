<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, 

if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $employer_data, $FULL_DOMAIN_NAME;

//Password changing handle
if (isset($_POST['submit'])){
    $current_password = filter_input(INPUT_POST,'current_password', FILTER_SANITIZE_STRING);        
    $new_password = password_hash(filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING), PASSWORD_DEFAULT, ['cost' => 12]);        
    
    if (password_verify($current_password, $employer_data['password'])){//Password matched
        //Change user password
        if(!$db->where('username', "$AuthUserName")->update('employers', array('password' => "$new_password"))){
            echo 'There was a problem while update password';  
        } else {
            //Redirect back with message
            $commonQueries->flash('message', $commonQueries->messageStyle('info', "Bạn đã thay đổi mật khẩu thành công"));
            $website->redirect($FULL_DOMAIN_NAME . '/EMPLOYERS/doi-mat-khau/');
        };  

    } else {
        $commonQueries->flash('message', $commonQueries->messageStyle('warning', "Mật khẩu không chính xác, vui lòng kiểm tra lại"));
        $website->redirect($FULL_DOMAIN_NAME . '/EMPLOYERS/doi-mat-khau/');
    }    
    
}

?>

<div class="row main-nav">
    <section class="col-md-9">
        <?php $commonQueries->flash('message')?>
    </section>
    <section class="col-md-3">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/", 'Về trang chính', 'blue');?>
    </section>
</div>

<div class="body body-s">		
    <form action="" method="POST" id="sky-form" class="sky-form">
        <header>Thay đổi mật khẩu</header>
        
        <fieldset>					
            <section>
                <label class="input">
                    <i class="icon-append fa fa-lock"></i>
                    <input type="password" name="current_password" placeholder="Mật khẩu hiện tại của bạn" id="current_password" required>
                    <b class="tooltip tooltip-bottom-right">Mật khẩu hiện tại</b>
                </label>
            </section>
            
            <section>
                <label class="input">
                    <i class="icon-append fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Mật khẩu mới" id="password" required>
                    <b class="tooltip tooltip-bottom-right">Mật khẩu mới</b>
                </label>
            </section>
            
            <section>
                <label class="input">
                    <i class="icon-append fa fa-lock"></i>
                    <input type="password" name="password_confirm" placeholder="Xác nhận mật khẩu mới" required>
                    <b class="tooltip tooltip-bottom-right">Xác nhận mật khẩu mới</b>
                </label>
            </section>
        </fieldset>
        
        <footer>
            <button type="submit" name="submit" class="button">Lưu thay đổi</button>
        </footer>
    </form>			
</div>

<script type="text/javascript">
    $(function()
    {
        // Validation		
        $("#sky-form").validate(
        {					
            // Rules for form validation
            rules:
            {
                current_password:{
                    required: true,
                    minlength: 3
                },
                password:{
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                password_confirm:{
                    required: true,
                    maxlength: 20,
                    equalTo: '#password'
                }                
            },
					
            // Messages for form validation
            messages:
            {
                current_password:{
                    required: 'Không để trống ô này',
                    minlength: 'Mật khẩu tối thiểu phải là 3 ký tự'
                },                
                password:
                {
                    required: 'Mật khẩu mới không được để trống',
                    minlength: 'Mật khẩu tối thiểu phải là 3 ký tự'
                },
                password_confirm:
                {
                    required: 'Xác nhận lại mật khẩu mới',
                    equalTo: 'Mật khẩu xác nhận phải giống nhau'
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