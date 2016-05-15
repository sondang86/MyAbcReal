<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
$oLinkTexts=array($M_HOME,$M_PROFILE2,$M_MY_LISTINGS,$M_APPLICATIONS,$M_JOBSEEKERS);
$oLinkActions=array("home","profile","jobs","application_management","jobseekers");

$profile_oLinkTexts=array($M_VIEW,$M_EDIT,$M_LOGO);
$profile_oLinkActions=array("current","edit","logo");

$application_management_oLinkTexts=array($JOBSEEKERS_APPLIED,$M_APPROVED_APPLICATIONS,$M_REJECTED_APPLICATIONS);
$application_management_oLinkActions=array("list","approved","rejected");

$jobs_oLinkTexts=array($M_NEW_JOB,$MY_JOB_ADS,$M_COURSES,$M_BANNERS,$EXPIRED_ADS);
$jobs_oLinkActions=array("add","my","courses","banners","expired_ads");

$jobseekers_oLinkTexts=array($SEARCH);
$jobseekers_oLinkActions=array("search");

if($website->GetParam("CHARGE_TYPE")==0||$website->GetParam("CHARGE_TYPE")==3)
{
	$home_oLinkTexts=array($M_WELCOME,$M_CHANGE_PASSWORD,$M_MESSAGES2);
	$home_oLinkActions=array("welcome","password","received");
}
else
{
	$home_oLinkTexts=array($M_WELCOME,($website->GetParam("CHARGE_TYPE")==2?$M_CREDITS:$M_SUBSCRIPTIONS),$M_CHANGE_PASSWORD,$M_MESSAGES2);
	$home_oLinkActions=array("welcome","credits","password","received");
}

$exit_oLinkTexts=array($M_THANK_YOU);
$exit_oLinkActions=array("exit");
?>