<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
define("IN_SCRIPT","1");
//Ensure that a session exists (just in case)
if(!session_id()){
    session_start();
}

$is_mobile=false;
if(isset($_POST["Export"])) ob_start();
include("../config.php");
if(!$DEBUG_MODE) error_reporting(0);
    
//auto load classes when call
function __autoload($classname) {
    $filename = "../include/". $classname .".class.php";
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
        
$commonQueries = new CommonsQueries($db);
    
//Common tables
$categories = $db->get ('categories');
$job_types = $db->get ('job_types');
$locations = $db->get ('locations');
$salaries = $db->get ('salary');
$all_jobs = $db->get('jobs');   
$experience_list = $db->get('job_experience');

$website = new SiteManager();
$website->isAdminPanel = true;
$database = new Database();
$database->Connect($DBHost, $DBUser,$DBPass );
$database->SelectDB($DBName);
$website->SetDatabase($database);
include("security.php");
$website->LoadSettings();

//Get job by employer id
if (isset($_REQUEST["id"])){    
    $id=$_REQUEST["id"];
    $website->ms_i($id);
    $selected_columns = array(
        $DBprefix."jobs.id as job_id",$DBprefix."jobs.employer",$DBprefix."jobs.job_category",
        $DBprefix."jobs.experience",$DBprefix."jobs.region",$DBprefix."jobs.title",
        $DBprefix."jobs.message",$DBprefix."jobs.active",$DBprefix."jobs.featured",
        $DBprefix."jobs.job_type",$DBprefix."jobs.salary",$DBprefix."jobs.status",
        $DBprefix."categories.category_name_vi",$DBprefix."categories.category_name",
        $DBprefix."job_types.job_name",$DBprefix."job_types.job_name_en",
        $DBprefix."salary.salary_range",$DBprefix."salary.salary_range_en",
    );

    $db->join("categories", $DBprefix."jobs.job_category=".$DBprefix."categories.category_id", "LEFT");
    $db->join("job_types", $DBprefix."jobs.job_type=".$DBprefix."job_types.id", "LEFT");
    $db->join("salary", $DBprefix."jobs.salary=".$DBprefix."salary.salary_id", "LEFT");
    $db->where ($DBprefix."jobs.id", "$id");
    $jobs_by_employerId = $db->get("jobs", NULL, $selected_columns);
}

    

    
include("include/AdminUser.class.php");
if(!isset($AuthUserName) || !isset($AuthGroup)) {$website->ForceLogin();}
$employerInfo = $commonQueries->getSingleValue('employers', NULL, 'username', "$AuthUserName");
$currentUser = new AdminUser($AuthUserName, $AuthGroup);
$currentUser->LoadPermissions();
$lang = $currentUser->GetLanguage();
    
if(file_exists("../ADMIN/texts_".$lang.".php"))
{
	include("../ADMIN/texts_".$lang.".php");
}
else
{
	include("../ADMIN/texts_en.php");
}
include("../include/texts_".$lang.".php");
    
    
    
$website->LoadTemplate(-1);
$website->TemplateHTML=str_replace('"css/','"../css/',$website->TemplateHTML);
$website->TemplateHTML=str_replace('"images/','"../images/',$website->TemplateHTML);
$website->TemplateHTML=str_replace('</head>','<link rel="stylesheet" href="css/main.css"/></head>',$website->TemplateHTML);
$website->TemplateHTML=str_replace
('</body>','  
<script type="text/javascript" src="js/admin.js"></script>
<script>
$(init);
    
String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, \'\');};
    
</script>
<iframe id="ajax-ifr" name="ajax-ifr" src="include/empty-page.php" style="position:absolute;top:0px;left:0px;visibility:hidden" width="1" height="1"></iframe></body>',$website->TemplateHTML);
    
    
include("include/page_functions.php");
include("include/AdminPage.class.php");
$currentPage = new AdminPage();
$currentPage->Process($is_mobile);
$website->Render();
if(isset($_POST["Export"])) ob_end_flush();

?>

<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet'  type='text/css'>
<!--https://github.com/craftpip/jquery-confirm-->
<link href="/vieclambanthoigian.com.vn/css/jquery-confirm.min.css" rel="stylesheet" type="text/css"/>
<script src="/vieclambanthoigian.com.vn/js/jquery-confirm.min.js" type="text/javascript"></script>
<!--<script src="/vieclambanthoigian.com.vn/js/jquery.confirm.js" type="text/javascript"></script>-->

<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
<script>
    jQuery(document).ready(function(){
        //Datepicker
        jQuery("#employer-start-date").datepicker({
            dateFormat: 'yy-mm-dd' 
        }).datepicker("setDate", new Date());
        
        jQuery("#datePicker").datepicker({
            dateFormat: 'yy-mm-dd' 
        });
        
    });
    
    /*Select all option*/
    $("#checkAll").change(select_all);   
</script>
