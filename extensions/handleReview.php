<?php
//if(!defined('IN_SCRIPT')) die(""); cant use this at the moment
//instantiate database class
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
    
//Common queries 
$commonQueries = new CommonsQueries($db); 
    
//Reviews handling
if (isset($_POST['request_type']) && ($_POST['request_type'] == 'reviews_employer')){
    
    $dataaa =   $db->where('company_id', filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT))
                ->where("jobseeker_id", filter_input(INPUT_POST, 'jobseeker_id', FILTER_SANITIZE_NUMBER_INT))->withTotalCount()->get("company_reviews");
    if ($db->totalCount > 0){//user already reviewed
        echo "already reviewed";        
    } else { //insert data to db, always remember to sanitize before insert
        $data = Array (
            "date" => time(),
            "company_id" => filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT),
            "title" => filter_input(INPUT_POST, 'review_name', FILTER_SANITIZE_STRING),
            "jobseeker_id" => filter_input(INPUT_POST, 'jobseeker_id', FILTER_SANITIZE_NUMBER_INT),
            "html" => filter_input(INPUT_POST, 'review', FILTER_SANITIZE_STRING),
            "email" => filter_input(INPUT_POST, 'review_email', FILTER_SANITIZE_EMAIL),
            "review_reliable" => filter_input(INPUT_POST, 'review_reliable', FILTER_SANITIZE_NUMBER_INT),
            "review_professional" => filter_input(INPUT_POST, 'review_professional', FILTER_SANITIZE_NUMBER_INT),
            "review_overall" => filter_input(INPUT_POST, 'review_overall', FILTER_SANITIZE_NUMBER_INT),
            "status" => "1",
            "anonymous" => filter_input(INPUT_POST, 'anonymous', FILTER_SANITIZE_NUMBER_INT),
        );
        $id = $db->insert('company_reviews', $data);
        if($id) {
            $message = array(
                "message"   => "Cảm ơn bạn đã đánh giá",
                "status"    => "1", //Success
            );            
            echo json_encode($message);
            
        } else {
            $message = array(
                "message"   => "Có lỗi xảy ra",
                "status"    => "0", //Success
            );            
            echo json_encode($message);
        }
    }
} 
//Contact form handling
elseif (isset($_POST['request_type']) && ($_POST['request_type'] == 'contact_employer')){
    //Send mail notification to employer
    mail(filter_input(INPUT_POST, 'employer_email', FILTER_SANITIZE_EMAIL), filter_input(INPUT_POST, 'contact_title', FILTER_SANITIZE_STRING),filter_input(INPUT_POST, 'contact_comment', FILTER_SANITIZE_STRING));    
} 
//Missing required fields
else { 
    echo "there was an problem";
}


