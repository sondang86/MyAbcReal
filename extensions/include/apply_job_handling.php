<?php
if(!defined('IN_SCRIPT')) die("");
    //Update when form submitted
    //Wrong captcha
    if($website->GetParam("USE_CAPTCHA_IMAGES") && ( (md5($_POST['code']) !== $_SESSION['code'])|| trim($_POST['code']) == "" ) ){
        $commonQueries->flash('message', $commonQueries->messageStyle('danger', 'Sai mã Captcha'));
        $website->redirect($website->CurrentURL());
    } else {
        $attachment = isset($_POST['attachment']) ? '1' : '0'; //Convert to true or false value
        //Insert record to apply table
        $data = Array (
            'message'       => filter_input(INPUT_POST,'message_to_employer',FILTER_SANITIZE_STRING),
            'posting_id'    => $job_id,
            'jobseeker'     => filter_var($_SESSION['username'], FILTER_SANITIZE_EMAIL),
            'status'        => '0', // Awaiting approved (0), Approved (1), Rejected (2)
            "date"          => strtotime(date("Y-m-d G:i:s")),
            'attachment'    => $attachment,
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
