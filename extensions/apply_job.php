<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php 
if(!defined('IN_SCRIPT')) die("");
global $db, $SEO_setting;

if ($SEO_setting == "0" && isset($_REQUEST["posting_id"])){
    $posting_id=$_REQUEST["posting_id"];
} elseif($SEO_setting == "1" && ($website->getURL_segment($website->currentURL(),3) !== NULL)) {    
    $posting_id = $website->getURL_segment($website->currentURL(),3);
} else {
    die("The job ID wasn't set!");
}
$website->ms_i($posting_id);
$job_info = $db->where('id', $posting_id)->getOne('jobs');
$questions =  $db->where('job_id', $posting_id)->get("questionnaire", NULL, array('id','question', 'question_type'));
?>


<div class="page-wrap">
<?php


//Update when form submitted
if (isset($_POST['submit'])){
    print_r($_POST);die;
}

if(get_param("ProceedApply_Update") != ""){
    if($website->GetParam("USE_CAPTCHA_IMAGES") && ( (md5($_POST['code']) != $_SESSION['code'])|| trim($_POST['code']) == "" ) )
    {
        echo "<br><span class=\"red-font\"><b>
        ".$M_WRONG_CODE."
        </b></span><br><br>";

        $show_page_form = true;
    }
    else
    {

        $iInsertID = 
        $database->SQLInsert
                (
                    "apply",
                    array("date","posting_id","jobseeker","message"),
                    array(time(),$posting_id,$username,get_param("message"))
                );

        $database->Query
        ("
                UPDATE ".$DBprefix."jobs
                SET applications=applications+1
                WHERE id=".$posting_id
        );	

        $questions = $database->DataTable("questionnaire","WHERE job_id=".$posting_id);

            if($database->num_rows($questions)>0)
            {
                    while($question = $database->fetch_array($questions))
                    {
                            if(trim(get_param("question".$question["id"]))!="")
                            {
                                $database->SQLInsert
                                (
                                    "questionnaire_answers",
                                    array("question_id","app_id","answer","user", "job_id"),
                                    array($question["id"],$iInsertID,strip_tags(get_param("question".$question["id"])),$username, $posting_id)
                                );
                            }
                    }
            }


            $show_page_form = false;

            //Send email
            if($website->GetParam("ENABLE_EMAIL_NOTIFICATIONS"))
            {	

                    $oPosting = $database->DataArray("jobs","id=".$posting_id);

                    $headers  = "From: \"".$website->GetParam("SYSTEM_EMAIL_FROM")."\"<".$website->GetParam("SYSTEM_EMAIL_ADDRESS").">\n";


                    $message="[$username] ".$M_APPLIED_JOB_POSITION.": ".strip_tags($oPosting["title"])."\n\n".$M_WE_INVITE_LOGIN." ".$DOMAIN_NAME." ".$M_AND_VIEW_INFO;

                    mail($oPosting["employer"], "".$M_NEW_JOBSEEKER_APPLIED.": \"".strip_tags($oPosting["title"])."\"", $message, $headers);

            }

            //Attachments handle
            if(isset($_POST['Ids']))	
            {
                foreach($_POST['Ids'] as $file_id)
                {
                    //Insert to db
                    $database->SQLInsert(
                        "apply_documents",
                        array("file_id","apply_id","job_id", "jobseeker"),
                        array($file_id,$iInsertID,$posting_id, $username)
                    );
                }
            }	
            echo "<h3>".$CONGRATULATIONS_APPLIED_SUCCESS."</h3>";
    }
}
    //Showing form submittion
    if(isset($_SESSION['username']) && $_SESSION['user_type'] == "jobseeker"){ //User must logged in as jobseeker to apply this form
            $username = $_SESSION['username'];
            $password = $_SESSION['user_password'];
            $userFiles = $db->where('user', $username)->get("files");


            $userInfo = $db->where('username', "$username")->withTotalCount()->getOne("jobseekers");
            $userExists = $db->totalCount;

            $is_applied = $db->where('jobseeker', "$username")->withTotalCount()->where('posting_id', $posting_id)->getValue("apply", "count(*)");

            echo $is_applied;


            //User already applied for this job
            if($is_applied > "0" ){	
                echo "<br><span class=\"red-font\"><strong>".$M_ALREADY_APPLIED."</strong></span><br>";		
            } elseif(($userExists !== "0") && ($userInfo["password"])== $password){//User authenticated, show submitting form ?>

        <div class="row messageToEmployer">
            <h4 class="col-md-12">Nộp hồ sơ cho việc số 76</h4>
            <section class="col-md-6">
                <textarea placeholder="Write your message here..." name="message_to_employer"></textarea>
            </section>
        </div>

        <!--LIST QUESTIONS-->
        <form action="" method="POST">
                <div class="questionnaires col-md-12">
                    <?php 
                    foreach ($questions as $question) { //List each questionnaire?>
                    <section>
                        <header>
                            <p><label>Câu hỏi: </label><span><?php echo $question['question']?></span></p>
                        </header>
                        
                        <p><label>Câu trả lời :</label></p>
                        <?php 
                            if ($question['question_type'] == "2"){ //This is multiple choice question
                                foreach($questionnaire_questions = $db->where('job_id', $posting_id)->where('questionnaire_id', $question['id'])->get('questionnaire_questions', NULL, array('id','question_ask', 'questionnaire_id')) as $questionnaire_question): ?>

                                <p>                            
                                    <span>                                    
                                        <input type="radio" name="question_<?php echo $question['id']?>" value="<?php echo $questionnaire_question['id']?>" required>
                                        <?php echo $questionnaire_question['question_ask']?>
                                    </span>
                                </p>
                            
                        <?php endforeach;
                            } else {//This is short answer question?>
                                <textarea placeholder="Write your message here..." name="short_answer" required></textarea>
                        <?php }?>
                    </section> 
                    <?php }?>
                    
                    <!--CAPTCHA-->
                    <p>
                        <label for="code">
                        <img src="http://<?php echo $DOMAIN_NAME?>/include/sec_image.php" width="100" height="30"/>
                        </label>
                        <input id="code" name="code" placeholder="<?php echo $M_PLEASE_ENTER_CODE;?>" type="text" required/>
                    </p>
                    
                    <p><input type="submit" name="submit"></p>
                </div>
        </form>

    <?php }  	
        } else {
            echo "you must be a jobseeker to apply this job";
        }
?>    
</div>
<style>
    textarea {        
        width: 100%; /* the inital width of the textarea */
        height: 10em; /* the inital height of the textarea */      
        padding: 1em;        
        font-family: "Montserrat", "sans-serif";
        font-size: 1em;        
        border: 0.1em solid #ccc;
        border-radius: 0.5em;        
        background-color: #ffffe6;        
    }
    
    .questionnaires {
        border: 1px solid #ccc;
    }
    
    .questionnaires section {
        margin: 25px 0;
        border-bottom: 1px solid #ccc;
    }
</style>

<?php
$website->Title($APPLY_JOB_OFFER." ".strip_tags(stripslashes($job_info["title"])));
$website->MetaDescription("");
$website->MetaKeywords("");
?>
