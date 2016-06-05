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
//if(isset($_POST["Export"])) ob_start();
include_once("../config.php");
if(!$DEBUG_MODE) error_reporting(0);

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
$commonQueries_Employers = new CommonsQueries_Employers($db);

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

include_once("include/AdminUser.class.php");

if(!isset($AuthUserName) || !isset($AuthGroup)) {$website->ForceLogin();}

$currentUser = new AdminUser($AuthUserName, $AuthGroup);
$currentUser->LoadPermissions();
$lang = $currentUser->GetLanguage();

//Common tables
$cols = Array(
    $DBprefix."employers.id",$DBprefix."employers.username",$DBprefix."employers.company",
    $DBprefix."employers.company_description",$DBprefix."employers.contact_person",$DBprefix."employers.address",
    $DBprefix."employers.city",$DBprefix."employers.company_size",$DBprefix."employers.phone",
    $DBprefix."employers.fax",$DBprefix."employers.website",$DBprefix."employers.phone",$DBprefix."employers.password",
    $DBprefix."employers.active",$DBprefix."employers.language",$DBprefix."employers.logo",
    $DBprefix."employers.registered_on",$DBprefix."employers.gender",$DBprefix."employers.subscription",
    $DBprefix."employers.subscription_date",$DBprefix."employers.subscription_date_end",
    $DBprefix."employers.latitude",$DBprefix."employers.longitude",
    $DBprefix."subscriptions.id as subscription_id",$DBprefix."subscriptions.name as subscription_name",
    $DBprefix."subscriptions.description as subscription_description",$DBprefix."subscriptions.listings as subscription_listing",
    $DBprefix."subscriptions.featured_listings",$DBprefix."subscriptions.urgent_listings",
    $DBprefix."subscriptions.price as subscription_price"
);
$db->join('subscriptions', $DBprefix . 'employers.subscription = ' . $DBprefix . 'subscriptions.id', "LEFT");
$employerInfo = $db->where('username', "$AuthUserName")->getOne('employers', $cols);

//Check subscription
if ($employerInfo['subscription'] > 1){ //Not a free subscription
    if (time() >= $employerInfo['subscription_date_end']){ //Subscription expired
        $subscription_cols = Array(
            'subscription' => '1'
        );
        if($db->where('username', "$AuthUserName")->update('employers', $subscription_cols, 1)){
            $commonQueries->flash('message', $commonQueries->messageStyle('info', "Thông báo: gói đăng ký của bạn đã hết hạn"));
        };
    }
}


$categories         = $db->get ('categories');
$job_types          = $db->get ('job_types');
$locations          = $db->get ('locations');
$salaries           = $db->get ('salary');
$all_jobs           = $db->get('jobs');   
$experience_list    = $db->get('job_experience');
$positions          = $db->get('positions');
$education          = $db->get('education');
$gender             = $db->get('gender');
$time_range         = $db->get('time_range');
$subscriptions      = $db->get('subscriptions');

include_once("../ADMIN/texts_".$lang.".php");

$website->LoadTemplate(-1);
$website->TemplateHTML=str_replace('"css/','"/vieclambanthoigian.com.vn/css/',$website->TemplateHTML);
$website->TemplateHTML=str_replace('"images/','"/vieclambanthoigian.com.vn/images/',$website->TemplateHTML);
$website->TemplateHTML=str_replace('</head>','<link rel="stylesheet" href="/vieclambanthoigian.com.vn/employers/css/main.css"/></head>',$website->TemplateHTML);
$website->TemplateHTML=str_replace('</body>','<script type="text/javascript" src="/vieclambanthoigian.com.vn/EMPLOYERS/js/admin.js"></script></body>',$website->TemplateHTML);
    
    
include_once("include/page_functions.php");
include_once("include/AdminPage.class.php");
$currentPage = new AdminPage();
$currentPage->Process($is_mobile);
$website->Render();

?>

<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet'  type='text/css'>
<!--https://github.com/craftpip/jquery-confirm-->

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
        
        //Hide delete button if nothing selected
        $(function(){
            var checkboxes = $(':checkbox:not(#checkAll)').click(function(event){
                $('#delete').prop("disabled", checkboxes.filter(':checked').length == 0);
            });

            $('#checkAll').click(function(event) {   
                checkboxes.prop('checked', this.checked);
                $('#delete').prop("disabled", !this.checked)
            });
        });
        
        
        // Remove empty fields from GET forms
        // URL: http://www.billerickson.net/code/hide-empty-fields-get-form/        
        // Change 'form' to class or ID of your specific form
        $("#jobseekers_search").submit(function() {
            $(this).find(":input").filter(function(){ return !this.value; }).attr("disabled", "disabled");
            return true; // ensure form still submits
        });
        
        // Un-disable form fields when page loads, in case they click back after submission
        $( "#jobseekers_search" ).find( ":input" ).prop( "disabled", false );
        
    });
    
    /*Select all option*/
    $("#checkAll").change(select_all);   
</script>
