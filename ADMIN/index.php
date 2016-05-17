<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!file_exists("../config.php")) die("<script>document.location.href='setup.php';</script>");
//Ensure that a session exists (just in case)
if(!session_id()){
    session_start();
}

define("IN_SCRIPT","1");
$is_mobile=false;
include_once("../config.php");
if(!$DEBUG_MODE) error_reporting(0);
include_once("../include/SiteManager.class.php");
include_once("../include/Database.class.php");

//auto load classes when call
function __autoload($classname) {
    $filename = "../include/". $classname .".class.php";
    include_once($filename);
}
   
$db = new MysqliDb (Array (
        'host' => $DBHost,
        'username' => $DBUser, 
        'password' => $DBPass,
        'db'=> $DBName,
        'port' => 3306,
        'prefix' => $DBprefix,
        'charset' => 'utf8'
    ));
        
$commonQueries = new CommonsQueries($db);

//Default user session will expire in 2 hour
$commonQueries->CheckSession("7200");

$website = new SiteManager();
$website->isAdminPanel = true;
$database = new Database();
$database->Connect($DBHost, $DBUser,$DBPass );
$database->SelectDB($DBName);
$website->SetDatabase($database);
include_once("security.php");
$website->LoadSettings();

include_once("include/AdminUser.class.php");
if(!isset($AuthUserName) || !isset($AuthGroup)) die("");
$currentUser = new AdminUser($AuthUserName, $AuthGroup);
$currentUser->LoadPermissions();
$lang = $currentUser->GetLanguage();

include_once("texts_".$lang.".php");
include_once("../include/texts_".$lang.".php");

if($is_mobile&&file_exists("mobile-template.htm"))
{
	$website->LoadTemplate("mobile-template");
}
else
{
	$website->LoadTemplate(0);
}


include_once("include/page_functions.php");
include_once("include/AdminPage.class.php");
$currentPage = new AdminPage();
$currentPage->Process($is_mobile);
$website->Render();
?>