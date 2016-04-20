<?php
include("config.php");
//Ensure that a session exists (just in case)
if(!session_id()){
    session_start();
}
if(!$DEBUG_MODE) error_reporting(0);
if (empty($website)){
    $website = new SiteManager();
}
function __autoload($classname) {
    $filename = "include/". $classname .".class.php";
    include($filename);
}
    
$db = new MysqliDb (Array (
        'host' => $DBHost,
        'username' => $DBUser, 
        'password' => $DBPass,
        'db'=> $DBName,
        'port' => 3306,
        'prefix' => $DBprefix,
        'charset' => 'utf8'
    ));

if(isset($_POST['Email']) && isset($_POST['Password'])){
    $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_EMAIL);
    $Password = filter_input(INPUT_POST, 'Password', FILTER_SANITIZE_STRING);
    //Search in employer table first
    $employer = $db->where('username', "$email")
            ->where('password', "$Password")
            ->withTotalCount()->getOne('employers',array('username', 'password'));

    if ($db->totalCount > 0){ //User is employer
        //Store user data in session
        $_SESSION['username'] = $employer['username'];
        $_SESSION['user_password'] = $employer['password'];
        $_SESSION['user_type'] = 'employer';
        $website->redirect('EMPLOYERS/index.php');
        
    } else { //User could be jobseeker
        $jobseekers = $db->where('username', "$email")
            ->where('password', "$Password")
            ->withTotalCount()->getOne('jobseekers',array('username', 'password'));
        
        if ($db->totalCount > 0){ //User is jobseeker
            //Store user data in session
            $_SESSION['username'] = $jobseekers['username'];
            $_SESSION['user_password'] = $jobseekers['password'];
            $_SESSION['user_type'] = 'jobseeker';
            $website->redirect('JOBSEEKERS/index.php');
            
        } else { //Wrong username or password
            $website->redirect('index.php?error=expired');
        }
    }
}
?>