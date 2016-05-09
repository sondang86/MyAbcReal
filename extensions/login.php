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
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $DOMAIN_NAME;
?>
<br/>
<div class="page-wrap">
    <h5><?php $commonQueries->flash('message');?></h5>
    <form action="loginaction.php" id="sky-form" class="sky-form" method="POST">
        <header>Đăng nhập</header>
            
        <fieldset>					
            <section>
                <div class="row">
                    <label class="label col col-4">E-mail</label>
                    <div class="col col-8">
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="email" name="Email">
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
                            <input type="password" name="Password">
                        </label>
                        <div class="note"><a href="#sky-form2" class="modal-opener">Forgot password?</a></div>
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
            <button type="submit" class="button">Log in</button>
            <a href="#" class="button button-secondary">Register</a>
        </footer>
    </form>			
</div>
    
    
    
<form action="demo-login-process.php" id="sky-form2" class="sky-form sky-form-modal">
    <header>Password recovery</header>
        
    <fieldset>					
        <section>
            <label class="label">E-mail</label>
            <label class="input">
                <i class="icon-append fa fa-envelope-o"></i>
                <input type="email" name="email" id="email">
            </label>
        </section>
    </fieldset>
        
    <footer>
        <button type="submit" name="submit" class="button">Submit</button>
        <a href="#" class="button button-secondary modal-closer">Close</a>
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
        $("#sky-form").validate(
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
                            required: 'Please enter your email address',
                    email: 'Please enter a VALID email address'
                },
                password:
                        {
                            required: 'Please enter your password'
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
                            required: 'Please enter your email address',
                    email: 'Please enter a VALID email address'
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