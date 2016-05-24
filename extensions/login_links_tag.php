<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
$link_suffix="";

if($MULTI_LANGUAGE_SITE)
{
	$link_suffix="lang=".$this->lang;
}

if(isset($_SESSION['username']) && ($_SESSION['user_type'] == "jobseeker")){
	?>
	<li><a class="btn btn-primary custom-back-color" href="http://<?php echo $DOMAIN_NAME;?>/JOBSEEKERS/index.php<?php if($MULTI_LANGUAGE_SITE) echo "?lng=".$this->lang;?>"><span class="btn-main-login"><?php echo $M_MY_SPACE;?></span></a></li>
	<?php
}
elseif(isset($_SESSION['username']) && ($_SESSION['user_type'] == "employer")){
	?>
	<li><a class="login-trigger btn btn-primary custom-back-color" href="http://<?php echo $DOMAIN_NAME;?>/EMPLOYERS/index.php<?php if($MULTI_LANGUAGE_SITE) echo "?lng=".$this->lang;?>"><span class="btn-main-login"><?php echo $M_MY_SPACE;?></span></a></li>
	<?php
}
else {

?>
	<li><button type="button" class="login-trigger btn btn-primary custom-back-color" data-toggle="modal" data-target="#login-modal"><?php echo $M_LOGIN;?></button></li>

<?php
}
?>