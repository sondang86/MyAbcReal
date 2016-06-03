<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $FULL_DOMAIN_NAME;
$job_id = filter_input(INPUT_GET,'job_id', FILTER_SANITIZE_NUMBER_INT);
$questionnaire_id = filter_input(INPUT_GET,'questionnaire_id', FILTER_SANITIZE_NUMBER_INT);
//Fetch questionnaire data   
$questionnaire = $commonQueries->getQuestionnaire($job_id, $AuthUserName,$questionnaire_id, FALSE);
$questionnaires_questions = $commonQueries->getQuestionnaireQuestions($questionnaire_id, $job_id); 

    
    
if (isset($_POST['submit'])){
    //Update question title
    $db->where ('id', $questionnaire_id);
    if ($db->update ('questionnaire', array("question" => $_POST['question-title']))){
        echo $db->count . ' record were updated';
    } else {
        echo 'update failed: ' . $db->getLastError();die;
    }
        
    //Update questions
    $data = array_combine(
        filter_input(INPUT_POST,'questionsId',FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY),
        filter_input(INPUT_POST,'answerPoll',FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY)
    );
    foreach ($data as $key => $value) {
        $db->where ('id', $key);
        if ($db->update ('questionnaire_questions', array("question_ask" => $value))){
            echo $db->count . ' records were updated';
        } else {
            echo 'update failed: ' . $db->getLastError();die;
        }
    }
        
    //Succeed, back to question page
    $commonQueries->flash('questionnaire_message', $commonQueries->messageStyle('info', 'Lưu thay đổi thành công!'));
    $website->redirect($FULL_DOMAIN_NAME."/EMPLOYERS/sua-cau-hoi/$questionnaire_id/$job_id/");
}
    
    
// username must be the same with question's username to show questions
if ($questionnaire['employer'] == $AuthUserName){?>
    
<div class="row">
    <h4 class="no-top-margin title col-md-9">
        <?php $commonQueries->flash('questionnaire_message')?>
    </h4>
    <aside class="col-md-2 fright">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/danh-sach-cau-hoi/$job_id/", 'Danh sách câu hỏi', 'red');?>
    </aside>
</div>
    
<div class="row">
    <h3 class="col-md-12"><?php echo $M_MODIFY_QUESTION;?></h3>
</div>
<form method="POST">
<?php if ($questionnaire['question_type'] == "2"){ //multichoice questions, list all questions inside questionnaire?>    
    <!--Questionnaire-->
    <div class="col-md-9 questionnaire-employer">
        <div class="row">
            <h5 class="col-md-2">Câu hỏi (*): </h5>
            <p class="col-md-8">
                <textarea name="question-title" style="width: 100%; min-height: 100px;" required><?php echo $questionnaires_questions[0]['questionnaireQuestion']?></textarea>
            </p>       
        </div>
            
        <!--Poll answers-->
        <div class="row answerPollArea" id="answerPollArea" style="margin-top: 20px;">
            <h5 class="col-md-2">Câu trả lời: </h5>
            <section class="col-md-8 answerPoll" id="answerPoll">
                <?php foreach ($questionnaires_questions as $question) :?>
                <p><span><input type="text" name="answerPoll[]" value="<?php echo $question['question_ask']?>" required></span></p>        
                <input type="hidden" name="questionsId[]" value="<?php echo $question['questionsId']?>">
                <?php endforeach;?>
            </section>  
        </div>
            
        <div class="row">
            <section class="col-md-2"></section>
            <label class="col-md-10 questionnaire-save" style="">
                <input type="submit" name="submit" value="Lưu">
            </label>
        </div>
    </div>
        
<?php } elseif ($questionnaire['question_type'] == "1") { //user input question?>
    <div class="row">
        <h5 class="col-md-2">Câu hỏi (*): </h5>
        <p class="col-md-8">
            <textarea name="question-title" style="width: 100%; min-height: 100px;" required><?php echo $questionnaire['question']?></textarea>
        </p>       
    </div>
    <div class="row">
        <section class="col-md-2"></section>
        <label class="col-md-10 questionnaire-save" style="">
            <input type="submit" name="submit" value="Lưu">
        </label>
    </div>    
<?php   }?>
    
</form>
    
<?php } else {?>
<h4>Không tìm thấy dữ liệu :(</h4> 
<?php }?>