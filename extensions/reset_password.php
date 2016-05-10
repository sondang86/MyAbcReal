<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries;
    
//Password reset processing
if(isset($_POST['submit'])){
    print_r($_SESSION);
    //Update new password
    $user = $_SESSION['password_reset_user'];
    $user_type_table = $_SESSION['user_type_table'];
    $new_password = password_hash(filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING), PASSWORD_DEFAULT, ['cost' => 12]);
    
    if(!$db->where('username', $user)->update($user_type_table, array('password' => "$new_password"))){
        echo 'problem when update';die; //For debug purpose only
        
    } else { //remove user password reset code & redirect back
        $db->where('username', $user)->update($user_type_table, array('reset_email_code' => ""));
        
        $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Đổi mật khẩu thành công'));
        $website->redirect('index.php?mod=login');   
    };
}   

//Password reset form    
if (isset($_GET['user']) && isset($_GET['id']) && (isset($_GET['code'])) && $_GET['code'] !== ""){    
    $user = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_EMAIL);
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
    
    //Verify code
    $data = $db->withTotalCount()->rawQuery(
                "SELECT username,id, 'employers' as user_type FROM jobsportal_employers WHERE username = '$user' AND reset_email_code = '$code'"
                . " UNION ALL "
                . "SELECT username,id, 'jobseekers' FROM jobsportal_jobseekers WHERE username = '$user' AND reset_email_code = '$code'"
            );
    if($db->totalCount > 0){ //Found user, show reset password form
    $_SESSION['password_reset_user'] = $data[0]['username'];
    $_SESSION['user_type_table'] = $data[0]['user_type'];
?>

<form action="" id="password-reset-form" class="sky-form" method="POST">
    <header>Reset lại mật khẩu</header>    
    <fieldset>					
        <section>
            <div class="row">
                <label class="label col col-4">E-mail</label>
                <div class="col col-8">
                    <label><?php echo $data[0]['username']?></label>
                </div>
            </div>
        </section>
        
        <section class="row">            
            <label class="label col col-4"></label>
            
            <section class="col col-8">
                <label class="input">
                    <i class="icon-append fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Mật khẩu" id="password">
                    <b class="tooltip tooltip-bottom-right">Nhập mật khẩu mong muốn</b>
                </label>
            </section>
        </section>
        
        <section class="row">       
            <label class="label col col-4"></label>
            
            <section class="col col-8">
                <label class="input">
                    <i class="icon-append fa fa-lock"></i>
                    <input type="password" name="passwordConfirm" placeholder="Xác nhận lại mật khẩu">
                    <b class="tooltip tooltip-bottom-right">Xác nhận lại mật khẩu</b>
                </label>
            </section>
        </section>
        
    </fieldset>
    <footer>
        <button type="submit" name="submit" class="button">Lưu thay đổi</button>
    </footer>
</form>

<script>
    $(function()
    {
        // Validation		
        $("#password-reset-form").validate({					
            // Rules for form validation
            rules:{
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
                }
            },
                
            // Messages for form validation
            messages:{ 
                password:{
                    required: 'Vui lòng nhập mật khẩu',
                    minlength: 'Tối thiểu 3 ký tự'
                },
                passwordConfirm:{
                    required: 'Xác nhận lại mật khẩu',
                    equalTo: 'Mật khẩu xác nhận phải giống như mật khẩu đã viết',
                    minlength: 'Tối thiểu 3 ký tự'
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
<?php } else {
        echo "404 Not found :(";
    }
}
