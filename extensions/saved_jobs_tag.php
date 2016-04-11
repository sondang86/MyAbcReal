<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;

//http://dba.stackexchange.com/questions/97211/get-rows-where-lastlogintimestamp-is-in-last-7-days

//Get user saved jobs in the last 7 days
 $db->where("date >= CAST(UNIX_TIMESTAMP(NOW() - INTERVAL 7 DAY) AS CHAR(10))");
//    ->where("user_uniqueId",filter_input(INPUT_COOKIE,'userId', FILTER_SANITIZE_STRING))
//    ->where("IPAddress", filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP));

$testdata = $db->get("jobs");
print_r($testdata);


if(isset($_COOKIE["saved_listings"])&&trim($_COOKIE["saved_listings"])!="")
{
    $items=explode(",",$_COOKIE["saved_listings"]);
?>
    <a href="<?php echo $website->check_SEO_link("saved_jobs", $SEO_setting);?>" class="sub-text underline-link r-margin-15"><?php echo (sizeof($items)-1)." ".$M_SAVED_JOBS;?></a>
<?php }?>
