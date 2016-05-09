<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!file_exists("config.php")) die("<script>document.location.href='ADMIN/setup.php';</script>");
define("IN_SCRIPT","1");
//Ensure that a session exists (just in case)
if(!session_id()){
    session_start();
}
if (!isset($_SESSION['user_type'])){ $_SESSION['user_type'] = "guest";}

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

//Browser detection
$Browser_detection = new BrowserDetection();

    
/// Connect to the website database
$database->Connect($DBHost, $DBUser,$DBPass );
$database->SelectDB($DBName);
$website = new SiteManager();
$website->SetDatabase($database);
    
$commonQueries = new CommonsQueries($db);

//Track user inactivity
$commonQueries->CheckSession();
    
//Common tables
$categories = $db->get ('categories');
    
$db->join('categories', $DBprefix."categories_sub.main_category_id =".$DBprefix."categories.category_id", 'LEFT');
$categories_subs = $db->get('categories_sub');
$job_types = $db->get ('job_types');
$locations = $db->get ('locations');
$salaries = $db->get ('salary');
$all_jobs = $db->get('jobs');
$companies = $db->get('employers');
$gender = $db->get('gender');

//Set default user ID if their cookie empty
if (empty($_COOKIE['userId'])){
    $userId_cookie = setcookie('userId', "null");
} else {
    $userId_cookie = filter_input(INPUT_COOKIE,'userId', FILTER_SANITIZE_STRING);
}


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
    
/// Inserting the statistics information in the database
$website->Statistics();
    
?>
    
<script>
    jQuery(document).ready(function($){  
        // Remove empty fields from GET forms
        // URL: http://www.billerickson.net/code/hide-empty-fields-get-form/
        
        // Change 'form' to class or ID of your specific form
        $("#home_form").submit(function() {
            $(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
            return true; // ensure form still submits
        });
        
        // Un-disable form fields when page loads, in case they click back after submission
        $( "#home_form" ).find( ":input" ).prop( "disabled", false );
        

        //Change save job status to saved
        $(".savethisJob").on('click', function(e){
            $(this).removeAttr("onclick").removeAttr("href"); //Prevent duplicate records
            $(this).html('<i class="fa fa-check"></i>Đã lưu việc này');
            e.preventDefault();
        });
        
        //change status to Removed
        $(".removethisJob").on('click', function(e){
            $(this).removeAttr("onclick").removeAttr("href"); //Prevent duplicate records
            $(this).html('<i class="fa fa-check"></i>Đã xóa việc này');
            e.preventDefault();
        });

        //Slider
        $('.bxslider').bxSlider({
            slideWidth: 150,
            minSlides: 2,
            maxSlides: 8,
            slideMargin: 30
        });

    });
</script>