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
    
//Make sure 4 options are not empty for review submit
if (isset($_POST['review']) && isset($_POST['rating']) && isset($_POST['anonymous']) && isset($_POST['jobseeker_id'])){
    
    $dataaa = $db->where("jobseeker_id", filter_input(INPUT_POST, 'jobseeker_id', FILTER_SANITIZE_NUMBER_INT))->withTotalCount()->get("company_reviews");
    if ($db->totalCount > 0){//user already reviewed
        echo "you are already reviewed";        
    } else { //insert data to db, always remember to sanitize before insert
        $data = Array (
            "date" => time(),
            "company_id" => filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT),
            "title" => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            "jobseeker_id" => filter_input(INPUT_POST, 'jobseeker_id', FILTER_SANITIZE_NUMBER_INT),
            "html" => filter_input(INPUT_POST, 'review', FILTER_SANITIZE_STRING),
            "email" => filter_input(INPUT_POST, 'jobseeker_email', FILTER_SANITIZE_EMAIL),
            "vote" => filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT),
            "status" => "1",
            "anonymous" => filter_input(INPUT_POST, 'anonymous', FILTER_SANITIZE_NUMBER_INT),
        );
        $id = $db->insert('company_reviews', $data);
        if($id) {
            echo "$id record has been created succesfully";
        } else {
            echo "error occurred";
        }
    }
} 
//Contact form handle, make sure 3 required fields is set
elseif (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['email'])){
    //Send mail notification to employer
    
} 
//Missing required fields
else { 
    
}


