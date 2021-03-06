<?php
if(!defined('IN_SCRIPT')) die("");
global $db;    
    
//Save selected job
if (isset($_POST['requestType']) && $_POST['requestType'] == "save"){
    //Check and sanitize userId cookie first
    if (!empty($_COOKIE['userId'])){ 
        $userId_Cookie = filter_input(INPUT_COOKIE,'userId', FILTER_SANITIZE_STRING);
    } else {
        $userId_Cookie = "";
    }
  
    //Retrieve user unique Id and their IP address
    $userAddress = $db->where('IPAddress', filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP))
                ->where("user_uniqueId", $userId_Cookie)
                ->withTotalCount()->getOne('saved_jobs', NULL, array("IPAddress", "user_uniqueId"));  
                    
    //When user IP Address & Cookie are the same with Database 
    if  ($userAddress['IPAddress'] == filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP) 
        && ($userAddress['user_uniqueId'] == $_COOKIE['userId'])){ 
            
        $user_uniqueId = $userAddress['user_uniqueId']; //Asign same user_uniqueId
            
    } else { //Generate new uniqueId
        
        $user_uniqueId = uniqid();
            
    }
          
    //Prepare and insert data to db
    $data = Array (
        "user_type" => "1",
        "job_id" => filter_input(INPUT_POST,'jobid', FILTER_SANITIZE_NUMBER_INT),
        "date" => strtotime("now"),
        "user_uniqueId" => $user_uniqueId,
        "IPAddress" => filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP),
        "browser" => filter_input(INPUT_POST,'browser', FILTER_SANITIZE_STRING),
    );
    $id = $db->insert ('saved_jobs', $data);   
        
    if($id){ //Success
        //store user unique Id to cookie
        $user_info = $db->where('id', $id)->getOne("saved_jobs", NULL, array("user_uniqueId", "IPAddress"));
        setcookie("userId", $user_info['user_uniqueId'],time()+86400); //Expires in 1 day
        echo json_encode("DONE");
    } else {
        echo "problem";
    }
}

//Remove selected job
if (isset($_POST['requestType']) && $_POST['requestType'] == "remove"){
    $db->where('job_id', filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_NUMBER_INT));
    $db->where('user_uniqueId', filter_input(INPUT_POST, 'user_uniqueId', FILTER_SANITIZE_STRING));
    
    if($db->delete('saved_jobs')) {
        echo 'successfully deleted';    
    } else {
        echo "problem while deleting job";        
    }
}
    
//Prevent loading unnecessary files 
die;