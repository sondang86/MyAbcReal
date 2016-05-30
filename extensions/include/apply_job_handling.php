<?php
if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
    //Update when form submitted
    
    if($website->GetParam("USE_CAPTCHA_IMAGES") && ( (md5($_POST['code']) !== $_SESSION['code'])|| trim($_POST['code']) == "" ) ){//Wrong captcha
        $commonQueries->flash('message', $commonQueries->messageStyle('danger', 'Sai mã Captcha'));
        $website->redirect($website->CurrentURL());
    } else {
        //Insert record to apply table
        $attachment = isset($_POST['attachment']) ? '1' : '0'; //Convert to true or false value
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
        
        //Email notification to employer        
        $email_subject = "Một ứng viên vừa nộp hồ sơ cho công việc ". $job_details['title'];
        $email_body = "Chào bạn!\n"
                    . "Một ứng viên vừa nộp hồ sơ cho công việc: \n\n"
                    . "$FULL_DOMAIN_NAME/chi-tiet-cong-viec/$job_id/" .$job_details['SEO_title']. " \n\n"
                    . "Để xem chi tiết hồ sơ ứng viên, bạn vui lòng truy cập vào địa chỉ dưới đây: \n\n"
                    . "$FULL_DOMAIN_NAME/EMPLOYERS/index.php \n\n"
                    . "Vieclambanthoigian xin chúc bạn tìm được người phù hợp sớm nhất \n\n"
                    . "Trân trọng \n\n Vieclambanthoigian.com.vn";
        
        require_once ('email_handling.php');
        
        //Finally, redirect with success message
        $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Hồ sơ đã được nộp, cảm ơn bạn.'));            
        $website->redirect($website->CurrentURL());
        
    }
