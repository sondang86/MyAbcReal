<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
global $db, $commonQueries, $commonQueries_Front;
 
    if (isset($_POST['ContactSubmit'])){
        $subject = filter_input(INPUT_POST, 'subject',FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'message',FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'name',FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email',FILTER_SANITIZE_STRING);
        $phone = filter_input(INPUT_POST, 'phone',FILTER_SANITIZE_NUMBER_INT);
        
        //Check CAPTCHA
        if( (md5($_POST['code']) !== ($_SESSION['code'])|| trim($_POST['code']) == "" )) {//Wrong captcha
            $commonQueries->flash('message', $commonQueries->messageStyle('danger', 'Sai mã Captcha'));
            
        } else { //Insert message into db 
            $columns = Array(
                'date'      => time(),
                'subject'   => $subject,
                "message"   => $message,
                "name"      => $name,
                "email"     => $email,
                "phone"     => $phone
            );
           
            if(!$db->insert ('messages', $columns)){
                $commonQueries->flash('message', $commonQueries->messageStyle('danger', 'Có lỗi xảy ra, vui lòng liên hệ info@vieclambanthoigian.com.vn'));
                $website->redirect($website->CurrentURL());
            } else {
                //Email handling
                $email_subject  = 'vieclambanthoigian.com.vn';
                $email_body     = "Chào bạn!\n"
                                . "Cảm ơn bạn đã liên hệ với vieclambanthoigian,Chúng tôi sẽ phản hồi trong thời gian sớm nhất \n\n"
                                . "Dưới đây là nội dung tin nhắn bạn đã gửi: \n\n"
                                . "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
                                . "$title \n\n"
                                . "$message"
                                . "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n"
                                . "Trân trọng \n\n"
                                . "Vieclambanthoigian.com.vn";
                
                require_once ('include/email_handling.php');
                
                
                //Redirect back
                $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Cảm ơn bạn đã gửi tin nhắn, chúng tôi sẽ phản hồi trong thời gian sớm nhất !'));
                $website->redirect($website->CurrentURL());
            }
        }
    }
?>

<h5><?php $commonQueries->flash('message');?></h5>
<form action="" method="post" id="sky-form" class="sky-form">
    <header>Liên hệ với vieclambanthoigian.com.vn</header>
    
    <fieldset>					
        <div class="row">
            <section class="col col-4">
                <label class="label">Tên bạn</label>
                <label class="input">
                    <i class="icon-append fa fa-user"></i>
                    <input type="text" name="name" id="name" required <?php if(!empty($name)){echo "value='$name'";}?>>
                </label>
            </section>
            <section class="col col-4">
                <label class="label">E-mail</label>
                <label class="input">
                    <i class="icon-append fa fa-envelope-o"></i>
                    <input type="email" name="email" id="email" required <?php if(!empty($email)){echo "value='$email'";}?>>
                </label>
            </section>
            <section class="col col-4">
                <label class="label">Số điện thoại</label>
                <label class="input">
                    <i class="icon-append fa fa-phone"></i>
                    <input type="text" name="phone" id="phone" <?php if(!empty($phone)){echo "value='$phone'";}?>>
                </label>
            </section>
        </div>
                
        <section>
            <label class="label">Tiêu đề</label>
            <label class="input">
                <i class="icon-append fa fa-tag"></i>
                <input type="text" name="subject" id="subject" required <?php if(!empty($subject)){echo "value='$subject'";}?>>
            </label>
        </section>
                
        <section>
            <label class="label">Tin nhắn</label>
            <label class="textarea">
                <i class="icon-append fa fa-comment"></i>
                <textarea rows="4" name="message" id="message" required><?php if(!empty($message)){echo "$message";}?></textarea>
            </label>
        </section>
                
        <section>
            <label class="label">Vui lòng nhập chính xác mã CAPTCHA dưới đây:</label>
            <label class="input input-captcha">
                <img src="/vieclambanthoigian.com.vn/include/sec_image.php" width="100" height="35" alt="Captcha image" />
                <input type="text" maxlength="6" name="code" id="captcha" required>
            </label>
        </section>
                
        <section>
            <label class="checkbox"><input type="checkbox" checked="checked" name="copy"><i></i>Gửi bản copy vào địa chỉ email của tôi</label>
        </section>
    </fieldset>
            
    <footer>
        <button type="submit" name="ContactSubmit" class="button">Gửi</button>
    </footer>
            
    <div class="message">
        <i class="fa fa-check"></i>
        <p>Your message was successfully sent!</p>
    </div>
</form>	     
        
<script type="text/javascript">
    $(function()
    {
        // Validation
        $("#sky-form").validate({					
            // Rules for form validation
            rules:{
                name:{
                    required: true
                },
                
                email:{
                    required: true,
                    email: true
                },
                 
                phone:{
                    required: true
                }, 
                  
                subject:{
                    required: true
                },
                
                message:{
                    required: true,
                    minlength: 10
                },
                
                code:{
                    required: true
                }
            },
                                            
            // Messages for form validation
            messages:{
                name:
                {
                    required: 'Bạn chưa điền tên'
                },
                email:{
                    required: 'Email không thể để trống',
                    email: 'Vui lòng nhập địa chỉ email chính xác'
                },   
                
                phone:{
                    required: 'Vui lòng nhập số điện thoại'
                }, 
                
                subject:{
                    required: 'Tiêu đề không thể để trống'
                },
                
                message:{
                    required: 'Tin nhắn không thể để trống'
                },
                
                code:{
                    required: 'Vui lòng nhập CAPTCHA'
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