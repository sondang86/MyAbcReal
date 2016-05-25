<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $FULL_DOMAIN_NAME;
$link_suffix="";

if($MULTI_LANGUAGE_SITE){
    $link_suffix="lang=".$this->lang;
} 
?>

<?php if(isset($_SESSION['username']) && ($_SESSION['user_type'] == "jobseeker"))://Jobseekers?>
<li><a href="<?php echo $FULL_DOMAIN_NAME;?>/JOBSEEKERS/index.php"><span class="btn-main-login"><?php echo $M_MY_SPACE;?></span></a></li>

<?php elseif(isset($_SESSION['username']) && ($_SESSION['user_type'] == "employer"))://Employers?>
<li><a class="login-trigger" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/index.php"><span class="btn-main-login"><?php echo $M_MY_SPACE;?></span></a></li>

<?php else: //User not logged in?>
<!--<li><a href="#"><i class="fa fa-user" aria-hidden="true"></i> Đăng ký</a></li>-->                                
<li><a href="#" class="login-trigger" data-toggle="modal" data-target="#login-modal"><i class="fa fa-sign-in" aria-hidden="true"></i> Đăng nhập</a></li>
<?php endif;?>