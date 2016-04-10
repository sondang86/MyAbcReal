<?php
if(!defined('IN_SCRIPT')) die("");
global $db;

if (isset($_POST['jobid'])){
    $data = Array (
        "user_type" => "1",
        "job_id" => $_POST['jobid'],
        "date" => strtotime("now")
    );
    $id = $db->insert ('saved_jobs', $data);
    if($id){
        echo json_encode("DONE");
    }
}

//Prevent loading unnecessary files for performance
die;