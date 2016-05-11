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

$commonQueries = new CommonsQueries($db);

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $Password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

   //Search in employer table first
    $employer = $db->where('username', "$email")
            ->withTotalCount()->getOne('employers',array('username', 'password', 'active'));
    
    if($employer['active'] == "0"){//employer has not active account yet
        $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Bạn chưa kích hoạt tài khoản, vui lòng kiểm tra lại email và kích hoạt!'));
        $website->redirect('index.php?mod=login');
    }

    if ($db->totalCount > 0){ //User is employer
        if (password_verify($Password, $employer['password'])){ //Password matched
            //Store user data in session
            $_SESSION['username'] = $employer['username'];
            $_SESSION['user_password'] = $employer['password'];
            $_SESSION['user_type'] = 'employer';
            $website->redirect('EMPLOYERS/index.php');
        } else {//Wrong username or password
            $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Sai tên đăng nhập hoặc mật khẩu! Vui lòng thử lại'));
            $website->redirect('index.php?mod=login');
        }
        
    } else { //User could be jobseeker
        $jobseekers = $db->where('username', "$email")
            ->withTotalCount()->getOne('jobseekers',array('username', 'password', 'active'));
        
        if($jobseekers['active'] == "0"){//jobseeker has not active account yet
            $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Bạn chưa kích hoạt tài khoản, vui lòng kiểm tra lại email và kích hoạt!'));
            $website->redirect('index.php?mod=login');
        }
        
        if ($db->totalCount > 0){ //User is jobseeker
            if (password_verify($Password, $jobseekers['password'])){ //Password matched
                //Store user data in session
                $_SESSION['username'] = $jobseekers['username'];
                $_SESSION['user_password'] = $jobseekers['password'];
                $_SESSION['user_type'] = 'jobseeker';
                $website->redirect('JOBSEEKERS/index.php');
                
            } else {//Wrong username or password
                $commonQueries->flash('message', $commonQueries->messageStyle('warning', 'Sai tên đăng nhập hoặc mật khẩu! Vui lòng thử lại'));
                $website->redirect('index.php?mod=login');
            }
        } 
    }
}
?>