<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php 
if(!defined('IN_SCRIPT')) die("");
global $db, $SEO_setting, $commonQueries;

if ($SEO_setting == "0" && isset($_REQUEST["posting_id"])){
    $job_id=$_REQUEST["posting_id"];
} elseif($SEO_setting == "1" && ($website->getURL_segment($website->currentURL(),3) !== NULL)) {    
    $job_id = $website->getURL_segment($website->currentURL(),3);
} else {
    die("The job ID wasn't set!");
}
$website->ms_i($job_id);
$questions =  $db->where('job_id', $job_id)->get("questionnaire", NULL, array('id','question', 'question_type'));

$job_info = $db->withTotalCount()->where('id', $job_id)->getOne('jobs');
if($db->totalCount !== "0"){ //ensure job must exists
?>


<div class="page-wrap">
<h4>
    <?php $commonQueries->flash('message');?>
</h4>
<?php
//Update when form submitted
if (isset($_POST['submit'])){
    //Wrong captcha
    if($website->GetParam("USE_CAPTCHA_IMAGES") && ( (md5($_POST['code']) !== $_SESSION['code'])|| trim($_POST['code']) == "" ) ){
        $commonQueries->flash('message', $commonQueries->messageStyle('danger', 'Sai mã Captcha'));
        $website->redirect($website->CurrentURL());
    } else {
        //Insert record to apply table
        $data = Array (
            'message'       => filter_input(INPUT_POST,'message_to_employer',FILTER_SANITIZE_STRING),
            'posting_id'    => $job_id,
            'jobseeker'     => filter_var($_SESSION['username'], FILTER_SANITIZE_EMAIL),
            'status'        => '0', // Awaiting approved (0), Approved (1), Rejected (2)
            "date"          => strtotime(date("Y-m-d G:i:s"))            
        );
        $id = $db->insert ('apply', $data);
        if ($id){
            echo 'record was created. Id=' . $id;            
        } else {
            echo 'insert failed: ' . $db->getLastError();die;
        }
        
        //Add +1 to job's applicants applied
        $db->where ('id', "$job_id");
        if ($db->update ( "jobs", array('applications' => $db->inc(1) ))){
            echo $db->count . ' records were updated';
        } else {
            echo 'update failed: ' . $db->getLastError();die;
        }
        
        //Insert answers to questionnaire_answers table
        if (isset($_POST['question'])){
            foreach ($_POST['question'] as $key => $value) {
                $data = Array (
                    'questionnaire_id'          => $key,
                    'questionnaire_question_id' => $value,
                    'job_id'                    => $job_id,
                    'user'                      => filter_var($_SESSION['username'], FILTER_SANITIZE_EMAIL)
                );
                $id = $db->insert ('questionnaire_answers', $data);
                if (!$id){
                    echo 'insert failed: ' . $db->getLastError();die;
                }
            }
        }
        // Insert short answer data
        if (isset($_POST['short_answer'])){
            foreach ($_POST['short_answer'] as $key => $value) {
                $data = Array(
                    'questionnaire_id'  => $key,
                    'short_answer'      => "$value",
                    'job_id'            => $job_id,
                    'user'              => filter_var($_SESSION['username'], FILTER_SANITIZE_EMAIL)
                );                
                $id = $db->insert ('questionnaire_answers', $data);
                if (!$id){
                    echo 'insert failed: ' . $db->getLastError();die;
                }
            }
        }
        
        //Finally, redirect with success message
        $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Hồ sơ đã được nộp, cảm ơn bạn.'));            
        $website->redirect($website->CurrentURL());
        
    }
}

    //Showing form submittion
    if(isset($_SESSION['username']) && $_SESSION['user_type'] == "jobseeker"){ //User must logged in as jobseeker to apply this form
            $username = $_SESSION['username'];
            $userFiles = $db->where('user', $username)->get("files", NULL, "file_name");            
            $userInfo = $db->where('username', "$username")->withTotalCount()->getOne("jobseekers");
            $userExists = $db->totalCount;

            $is_applied = $db->where('jobseeker', "$username")->withTotalCount()->where('posting_id', $job_id)->getValue("apply", "count(*)");

            //User already applied for this job
            if($is_applied > "0" ){	
                echo "<br><span class=\"red-font\"><strong>".$M_ALREADY_APPLIED."</strong></span><br>";		
            } elseif($userExists !== "0"){//User authenticated, show submitting form ?>
    
    
    <form action="" method="POST">
        <div class="jobApply">
            <h4>Nộp hồ sơ cho việc <label>"<?php echo $job_info['title']?>"</label></h4>
        </div>
        <div class="messageToEmployer">  
            <h5>Gửi tin nhắn của bạn tại đây: </h5>
            <section>
                <textarea placeholder="Write your message here..." name="message_to_employer"></textarea>
            </section>
        </div>
        
        <!--LIST QUESTIONS-->
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
                        foreach($questionnaire_questions = $db->where('job_id', $job_id)->where('questionnaire_id', $question['id'])->get('questionnaire_questions', NULL, array('id','question_ask', 'questionnaire_id')) as $questionnaire_question): ?>
                
                <p>                            
                    <span>                                    
                        <input type="radio" name="question[<?php echo $question['id']?>]" value="<?php echo $questionnaire_question['id']?>" required>
                                        <?php echo $questionnaire_question['question_ask']?>
                    </span>
                </p>
                
                <?php endforeach;
                        } else {//This is short answer question?>
                        <textarea placeholder="Write your message here..." name="short_answer[<?php echo $question['id']?>]" required></textarea>
                <?php }?>
            </section> 
                    <?php }?>
            
            <!--CAPTCHA-->
            <p class="captcha">
                <label for="code">
                    <img src="http://<?php echo $DOMAIN_NAME?>/include/sec_image.php" width="100" height="30"/>
                </label>
                <input id="code" name="code" placeholder="<?php echo $M_PLEASE_ENTER_CODE;?>" type="text" required/>
            </p>
            
            <!--SUBMIT-->
            <input type="hidden" name="job_id" value="<?php echo $job_id;?>">
            <p class="submitButton"><input type="submit" name="submit" class="btn btn-default custom-gradient btn-green"></p>
        </div>
    </form>
    
    <?php }  	
        } else {
            echo "you must be a jobseeker to apply this job";
        }
?>    
</div>
<style>
    .messageToEmployer textarea, .questionnaires textarea {        
        width: 100%; /* the inital width of the textarea */
        height: 10em; /* the inital height of the textarea */      
        padding: 1em;        
        font-family: "Montserrat", "sans-serif";
        font-size: 1em;        
        border: 0.1em solid #ccc;
        border-radius: 0.5em;        
        background-color: #ffffe6;        
        padding-bottom: 25px;
    }
    
    .questionnaires {
        border: 1px solid #ccc;
        border-radius: 0.5em;  
        margin-top: 25px;
    }
    
    .questionnaires section {
        margin: 25px 0;
        border-bottom: 1px solid #ccc;
        padding-bottom: 15px;
    }    
    
    .submitButton {
        text-align: right;
        margin: 25px 0;
    }
    
    .messageToEmployer {
        margin-top: 25px;
    }
    
    .captcha {
        text-align: right;
        margin-top: 20px;
    }
</style>

<?php
$website->Title($APPLY_JOB_OFFER." ".strip_tags(stripslashes($job_info["title"])));
$website->MetaDescription("");
$website->MetaKeywords("");
} else {
    echo "không tìm thấy việc này, vui lòng thử lại :(";
}
?>
