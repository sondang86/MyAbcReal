<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!file_exists("config.php")) die("<script>document.location.href='ADMIN/setup.php';</script>");
define("IN_SCRIPT","1");
session_start();
require("config.php");
if(!$DEBUG_MODE) error_reporting(0);

function __autoload($classname) {
    $filename = "include/". $classname .".class.php";
    include($filename);
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

/// Initialization of the site manager and database objects
$database = new Database();

/// Connect to the website database
$database->Connect($DBHost, $DBUser,$DBPass );
$database->SelectDB($DBName);
$website = new SiteManager();
$website->SetDatabase($database);

$commonQueries = new CommonsQueries($db);

//Common tables
$categories = $db->get ('categories');

$db->join('categories', $DBprefix."categories_sub.main_category_id =".$DBprefix."categories.category_id", 'LEFT');
$categories_subs = $db->get('categories_sub');
$job_types = $db->get ('job_types');
$locations = $db->get ('locations');
$salaries = $db->get ('salary');
$all_jobs = $db->get('jobs');
$companies = $db->get('employers');



//Pagination
//$page = 4;
// set page limit to 2 results per page. 20 by default
//$db->pageLimit = 2;
//$products = $db->arraybuilder()->paginate("jobs", $page);
//echo "showing $page out of " . $db->totalPages;
//echo "<pre>";
//print_r($products);
//echo "</pre>";


//Get SEO setting 
$db->where('id', 99);
$SEO_setting = $db->get('settings')[0]['value'];

//URL Site
$URL_site = "http://localhost/vieclambanthoigian.com.vn/";


/// Loading the website default settings
$website->LoadSettings();
include("include/texts_".$website->lang.".php");

include("include/functions.php");

$website->GenerateMenu();

if($website->IsExtension)
{
	include("include/Extension.class.php");
	$currentExtension = new Extension($website->ExtensionFile);
}
else
{
	include("include/Page.class.php");
	$currentPage = new Page(isset($_REQUEST["page"])?$_REQUEST["page"]:"");

	$currentPage->LoadPageData();
	
}

$website->LoadTemplate(isset($currentPage->templateID)?$currentPage->templateID:0);
$website->ProcessTags();

if($website->IsExtension)
{
	$currentExtension->Process();
}
else
{
	$currentPage->Process();
}

/// Rendering the final html of the website
$website->Render();

/// Inserrting the statistics information in the database
$website->Statistics();

?>