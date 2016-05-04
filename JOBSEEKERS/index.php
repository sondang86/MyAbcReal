<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net

define("IN_SCRIPT","1");
$is_mobile=false;
include("../config.php");
if(!$DEBUG_MODE) error_reporting(0);
//Ensure that a session exists (just in case)
if(!session_id()){
    session_start();
}

//Autoload classes once called
function __autoload($classname) {
    $filename = "../include/". $classname .".class.php";
    include_once($filename);
}

$website = new SiteManager();
$website->isAdminPanel = true;
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
//ensure user not inactive
$commonQueries->CheckSession();

$database = new Database();
$database->Connect($DBHost, $DBUser,$DBPass );
$database->SelectDB($DBName);
$website->SetDatabase($database);
include("security.php");
$website->LoadSettings();

include("include/AdminUser.class.php");
if(!isset($AuthUserName) || !isset($AuthGroup)) $website->ForceLogin();
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

//Common tables

$positions = $db->get('positions');
$salaries = $db->get('salary');
$categories = $db->get('categories');
$education = $db->get('education');
$job_types = $db->get('job_types');
$locations = $db->get('locations');
$language_levels = $db->get('language_levels');
$languages = $db->get('skill_languages');
$skills = $db->get('skills');

$job_experience = $db->get_data('job_experience');
$job_availability = $db->get_data('job_availability');
$current_language = $db->get_data('languages','id',"WHERE default_language=1")[0];

$profile_columns = array(
    $DBprefix."jobseekers.id as jobseeker_id",$DBprefix."jobseekers.username",$DBprefix."jobseekers.first_name",
    $DBprefix."jobseekers.last_name",$DBprefix."jobseekers.address",$DBprefix."jobseekers.phone",
    $DBprefix."jobseekers.marital_status",$DBprefix."jobseekers.description",$DBprefix."jobseekers.dob as date_of_birth",
    $DBprefix."jobseekers.mobile",$DBprefix."jobseekers.gender",$DBprefix."jobseekers.profile_pic",
    $DBprefix."jobseekers.date as date_joined",$DBprefix."jobseekers.profile_description",
    $DBprefix."jobseekers.profile_public",$DBprefix."jobseekers.newsletter",
    $DBprefix."gender.name as gender_name",$DBprefix."gender.name_en as gender_name_en"
);

$db->join('common_yes_no as profile_public', $DBprefix."jobseekers.profile_public = profile_public.common_id", "LEFT");
$db->join('gender', $DBprefix."jobseekers.gender =". $DBprefix."gender.gender_id", "LEFT");
$jobseeker_profile = $db->where('username', "$AuthUserName")->getOne('jobseekers',$profile_columns);


$website->LoadTemplate(-1);
$website->TemplateHTML=str_replace('"css/','"../css/',$website->TemplateHTML);
$website->TemplateHTML=str_replace('"images/','"../images/',$website->TemplateHTML);
$website->TemplateHTML=str_replace('</head>','<link rel="stylesheet" href="css/main.css"/></head>',$website->TemplateHTML);
$website->TemplateHTML=str_replace
('</body>','
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
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



?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet'  type='text/css'>
<link href="/vieclambanthoigian.com.vn/css/sky-forms.css" rel="stylesheet" type="text/css"/>
<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">-->
<!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->

<script src="/vieclambanthoigian.com.vn/js/jquery.serializeObject.min.js" type="text/javascript"></script>
<script src="/vieclambanthoigian.com.vn/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/vieclambanthoigian.com.vn/js/additional-methods.min.js" type="text/javascript"></script>
<script src="/vieclambanthoigian.com.vn/js/jquery.maskedinput.js" type="text/javascript"></script>
<link href="../include/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
<script src="../include/select2/js/select2.min.js" type="text/javascript"></script>

<script>
    jQuery(document).ready(function(){
        jQuery("#datePicker").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear:true,
            yearRange: "1950:+0"
        });
    });
    
    /*file upload validation*/
    $('#editForm').validate({
        rules: {
            logo: {
                /*required: true,*/
                extension: "jpg|png|gif|jpeg|bmp"
            }
        },
        messages: {
            logo: {
                /*required: "<p style='color:red;'>Vui lòng chọn ảnh</p>",*/
                extension: "<p style='color:red;'>Vui lòng up định dạng ảnh với đuôi mở rộng là jpg|png|gif|jpeg|bmp </p>"
            }
        }
    });
    /*file upload validation*/
    
    /*
     * Preview image before upload
     * http://stackoverflow.com/questions/18694437/how-to-preview-image-before-uploading-in-jquery
     */
    
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#logo").change(function() {
        readURL(this);
    });
    /*Preview image before upload*/


    /*select2 options*/
    $("#preferred_locations").select2({
        minimumResultsForSearch: 1,
        maximumSelectionLength: 3
    });
    $("#preferred_categories").select2({
        minimumSelectionLength: 1,
        maximumSelectionLength: 3
    });
    
    $("#myform").validate({
        rules:{
          ppp:{
            required: true
          }
        }
    }); 
</script>
