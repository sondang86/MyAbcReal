<?php
//    if(!defined('IN_SCRIPT')) die("");

    //instantiate neccessary classes
    if(empty($db) && empty($commonQueries)){    
        require_once '../include/CommonsQueries.class.php';
        require_once '../include/MysqliDb.class.php';
        $db = new MysqliDb (Array (
                'host' => "localhost",
                'username' => "root", 
                'password' => "",
                'db'=> "viea83fe_vieclambanthoigian",
                'port' => 3306,
                'prefix' => "jobsportal_",
                'charset' => 'utf8'
            ));
        $commonQueries = new CommonsQueries($db);
    }
    
    $DBprefix = "jobsportal_";

    //Remove question
    if (isset($_POST['proceed']) && $_POST['proceed'] == "1" && isset($_POST['remove_question'])){        
        //Safety sanitize input data first
        $job_id = filter_input(INPUT_POST, 'job_id', FILTER_SANITIZE_NUMBER_INT);    
        $questionnaire_id = filter_input(INPUT_POST, 'questionnaire_id', FILTER_SANITIZE_NUMBER_INT);    
        $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_EMAIL);    
        
        //Retrieve questionnaire & sub questions
        $questionnaire = $db->where('id',$questionnaire_id)->getOne('questionnaire');

        //Retrieve sub questions
        $sub_questions = $db->where('questionnaire_id', $questionnaire_id)
                            ->where('job_id', $job_id)->where('employer', "$user")
                            ->get("questionnaire_questions", NULL, "id");
        
        //Remove sub questions first
        foreach ($sub_questions as $sub_question) {
            $db->where('id', $sub_question['id']);
            if(!$db->delete('questionnaire_questions')){
                echo "Problem while delete the sub questions";die;
            }
        }
        //Remove questionnaire
        $db->where('id', $questionnaire['id']);
        if(!$db->delete('questionnaire')){
            echo "Problem while delete the questionnaire";die;
        } else {
            $message = array(
                "message"           => "Đã xóa câu hỏi này",
                "status"            => "1", //Success
                "questionnaire_id"  => $questionnaire_id
            );

            echo json_encode($message);
        }
        
    }
    
    
    //Remove job
    if (isset($_POST['proceed']) && $_POST['proceed'] == "1" && isset($_POST['remove_job'])){
        //Sanitize first
        $job_id = filter_input(INPUT_POST, 'job_id', FILTER_SANITIZE_NUMBER_INT);
        
        //Prevent delete job if user is free subscription & current day is smaller than 3
        $date_created = $db->where('id', $job_id)->getOne('jobs', 'date');
        $days = date('d',time() - $date_created['date']);
        
        
        if ($_POST['subscription'] == '1'){//Check free account
            if ($days <= 3){
                $message = array(
                    "message"   => "Tài khoản miễn phí chỉ có thể xóa việc đã đăng sau 3 ngày",
                    "status"    => "0", //Failed
                    "job_id"    => $job_id
                );

                echo json_encode($message);die;
            }
        } elseif ($_POST['subscription'] == '2') {
            if ($days <= 1){
                $message = array(
                    "message"   => "Vui lòng đợi, bạn có thể xóa việc đã đăng sau 1 ngày",
                    "status"    => "0", //Failed
                    "job_id"    => $job_id
                );

                echo json_encode($message);die;
            }
        } 
             
        //Delete all job infos related in apply, job_statistics, saved_jobs, jobs tables
        $db->where($DBprefix.'jobs.id', $job_id);
        $db->join('apply', $DBprefix.'jobs.id = '.$DBprefix.'apply.posting_id', "LEFT");
        $db->join('job_statistics', $DBprefix.'jobs.id = '.$DBprefix.'job_statistics.job_id', "LEFT");
        $db->join('saved_jobs', $DBprefix.'jobs.id = '.$DBprefix.'saved_jobs.job_id', "LEFT");
        $db->join('jobs_location', $DBprefix.'jobs.id = '.$DBprefix.'jobs_location.job_id', "LEFT");
       
        if(!$db->delete("apply, jobsportal_job_statistics, jobsportal_saved_jobs, jobsportal_jobs_location, jobsportal_jobs")){
            echo "problem when delete job";
        } else {
            $message = array(
                "message"   => "Đã xóa việc làm này",
                "status"    => "1", //Success
                "job_id"    => $job_id
            );
            echo json_encode($message);
        }
        
    } 
?>
