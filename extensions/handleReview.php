<?php
//if(!defined('IN_SCRIPT')) die("");

//Make sure 4 options are not empty
if (isset($_POST['review']) && isset($_POST['rating']) && isset($_POST['anonymous']) && isset($_POST['jobseeker_id'])){
    
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
//    print_r($commonQueries->convert_TrueFalse($_POST['anonymous']));die;
    
    $dataaa = $db->where("jobseeker_id", $_POST['jobseeker_id'])->withTotalCount()->get("company_reviews");
    if ($db->totalCount > 0){//user already reviewed
        echo "you are already reviewed";        
    } else { //insert data to db
        $data = Array (
            "date" => time(),
            "jobseeker_id" => $_POST['jobseeker_id'],
            "html" => $_POST['review'],
            "email" => $_POST['jobseeker_email'],
            "vote" => $_POST['rating'],
            "status" => "1",
            "anonymous" => $_POST['anonymous'],
        );
        $id = $db->insert('company_reviews', $data);
        if($id) {
            echo "$id record has been created succesfully";
        } else {
            echo "error occurred";
        }
    }
    
} else { //Missing fields for some reason
    echo "missing";
}
