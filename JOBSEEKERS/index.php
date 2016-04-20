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
$commonQueries = new CommonsQueries(new MysqliDb ());

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
$categories = $db->get_data();
$locations = $db->get_data('locations');
$education = $db->get_data('education');
$job_types = $db->get_data('job_types');
$job_experience = $db->get_data('job_experience');
$job_availability = $db->get_data('job_availability');
$salaries = $db->get_data('salary');
$current_language = $db->get_data('languages','id',"WHERE default_language=1")[0];
$jobseeker_profile = $db->get_data('jobseekers', '', "WHERE username='$AuthUserName'");
$gender = $db->get_data('gender','',"WHERE language_id=$current_language AND gender_id=".$jobseeker_profile[0]['gender'])[0];




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
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
<link href="../include/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
<script src="../include/select2/js/select2.min.js" type="text/javascript"></script>

<script>
    jQuery(document).ready(function(){
        jQuery("#datePicker").datepicker({
            dateFormat: 'yy-mm-dd' 
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

    $("#preferred_locations").select2({});
    $("#preferred_categories").select2({});
</script>
