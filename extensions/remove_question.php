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

    //Remove question process
    if ($_POST['proceed'] == "1" && isset($_POST['remove_question'])){        
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
        
        //Output message
    }
    
    //Remove job Id process
    if ($_POST['proceed'] == "1" && isset($_POST['remove_job'])){
        //Sanitize first
        $job_id = filter_input(INPUT_POST, 'job_id', FILTER_SANITIZE_NUMBER_INT);
        
        //Delete job id
        $db->where('id', $job_id);
        if(!$db->delete('jobs')){
            echo "problem when delete job";die;
        } else {
            $message = array(
                "message"           => "Đã xóa việc làm này",
                "status"            => "1", //Success
                "job_id"            => $job_id
            );

            echo json_encode($message);
        }
    }
?>
