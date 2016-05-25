<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php 
if(!defined('IN_SCRIPT')) die("");
global $db, $SEO_setting, $commonQueries, $FULL_DOMAIN_NAME;
$job_id = $commonQueries->check_present_id($_GET, $SEO_setting, 3);
$job_details = $commonQueries->jobDetails($job_id);
//Set to default logo if empty
$company_logo = $commonQueries->setDefault_logoIfEmpty($job_details['logo'], "employers");

//Check SEO setting
if ($SEO_setting == "0" && isset($_REQUEST["posting_id"])){
    $job_id=$_REQUEST["posting_id"];
} elseif($SEO_setting == "1" && ($website->getURL_segment($website->currentURL(),3) !== NULL)) {    
    $job_id = $website->getURL_segment($website->currentURL(),3);
} else {
    die("The job ID wasn't set!");
}

$website->ms_i($job_id);
$questions =  $db->where('job_id', $job_id)->withTotalCount()->get("questionnaire", NULL, array('id','question', 'question_type'));
$questions_count = $db->totalCount;

$job_info = $db->withTotalCount()->where('id', $job_id)->getOne('jobs');
if($db->totalCount !== "0"){ //ensure job must exists
?>


<div class="page-wrap">
<h4><?php $commonQueries->flash('message');?></h4>
<?php

if (isset($_POST['submit']) && $_POST['apply_job'] == '1'){ //Update when form submitted 
    require_once ('include/apply_job_handling.php');    
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
    
    <div class="jobApply">
        <h4>Nộp hồ sơ cho việc <label>"<?php echo $job_info['title']?>"</label></h4>
    </div>
    
        <h5 class="col-md-12">Chi tiết công việc</h5>
        <!--JOB DESCRIPTION-->
        <section class="job-description-apply clearfix">
            <article class="col-md-9">
                <h4><?php echo $JOB_DESCRIPTION;?></h4>
                <p><?php echo nl2br($job_details['message'])?></p>

                <h4><?php echo $REQUIRES_DESCRIPTION;?></h4>
                <p><?php echo nl2br($job_details['requires_description'])?></p>

                <h4><?php echo $PROFILECV_DESCRIPTION;?></h4>
                <p><?php echo nl2br($job_details['profileCV_description'])?></p>            

                <h4><?php echo $BENEFITS_DESCRIPTION;?></h4>
                <p><?php echo nl2br($job_details['benefits_description'])?></p>

            </article>

            <aside class="col-md-3 job-details-aside">
                <a href="<?php $website->check_SEO_link("companyInfo", $SEO_setting, $job_details['employer_id'],$website->seoUrl($job_details['company']));?>">
                    <img class="logo-border img-responsive" src="<?php echo $company_logo;?>" alt="<?php echo $job_details['company']?>">
                </a>
                <a href="<?php $website->check_SEO_link("jobs_by_companyId", $SEO_setting, $job_details['employer_id'],$website->seoUrl($job_details['company']));?>" class="sub-text underline-link">Việc làm khác từ <?php echo $job_details['company']?></a>            
                <a href="<?php $website->check_SEO_link("companyInfo", $SEO_setting, $job_details['employer_id'],$website->seoUrl($job_details['company']));?>" class="sub-text underline-link">Thông tin công ty</a>
            </aside>
        </section>
    
    <!--SEND MESSAGE-->
        <form action="" method="POST" class="clearfix">        
            
            <?php if($questions_count > 0):?>
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
            </div>
            <?php endif;?>
            
            <div class="messageToEmployer">  
                <section>
                    <textarea placeholder="Gửi tin nhắn của bạn tại đây..." name="message_to_employer"></textarea>
                </section>
            </div>

            
            <!--CAPTCHA-->
            <section class="col-md-12">                
                <p class="captcha">
                    <label for="code">
                        <img src="http://<?php echo $DOMAIN_NAME?>/include/sec_image.php" width="100" height="30"/>
                    </label>
                    <input id="code" name="code" placeholder="<?php echo $M_PLEASE_ENTER_CODE;?>" type="text" required/>
                </p>

                <!--SUBMIT-->
                <input type="hidden" name="apply_job" value="1">
                <input type="hidden" name="job_id" value="<?php echo $job_id;?>">
                <p class="submitButton"><input type="submit" name="submit" class="btn btn-default custom-gradient btn-green"></p>
            </section>
            
            
            <!--FILE ATTACHMENTS-->
            
            
            
        </form>
        
    <?php }  	
        } else {
            echo "you must be a jobseeker to apply this job";
        }
?>    
</div>

<?php
    $website->Title($APPLY_JOB_OFFER." ".strip_tags(stripslashes($job_info["title"])));
    $website->MetaDescription("");
    $website->MetaKeywords("");
} else {
    echo "không tìm thấy việc này, vui lòng thử lại :(";
}
?>
