<?php
// Jobs Portal
// Copyright (c) All Rights Reserved Vieclambanthoigian 

$oLinkTexts=array($M_HOME,$M_PROFILE2,$M_MY_LISTINGS,$M_APPLICATIONS,$M_JOBSEEKERS);
$oLinkActions=array("home","profile","jobs","application_management","jobseekers");

$profile_oLinkTexts=array($M_VIEW,$M_EDIT);
$profile_oLinkActions=array("current","edit");

$application_management_oLinkTexts=array($JOBSEEKERS_APPLIED,$M_APPROVED_APPLICATIONS,$M_REJECTED_APPLICATIONS);
$application_management_oLinkActions=array("list","approved","rejected");

$jobs_oLinkTexts=array($MY_JOB_ADS,$M_NEW_JOB);
$jobs_oLinkActions=array("my","add");

$jobseekers_oLinkTexts=array($SEARCH);
$jobseekers_oLinkActions=array("search");

if($website->GetParam("CHARGE_TYPE")==0||$website->GetParam("CHARGE_TYPE")==3)
{
	$home_oLinkTexts=array($M_HOME,$M_CHANGE_PASSWORD,$M_MESSAGES2);
	$home_oLinkActions=array("welcome","password","received");
}
else
{
	$home_oLinkTexts=array($M_HOME,($website->GetParam("CHARGE_TYPE")==2?$M_CREDITS:$M_SUBSCRIPTIONS),$M_CHANGE_PASSWORD,$M_MESSAGES2);
	$home_oLinkActions=array("welcome","credits","password","received");
}

$exit_oLinkTexts=array($M_THANK_YOU);
$exit_oLinkActions=array("exit");
?>