<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
    
    define("LOGIN_PAGE", "../index.php");
    define("SUCCESS_PAGE", "index.php");
    define("LOGIN_EXPIRE_AFTER", 20000);
        
    global $db, $FULL_DOMAIN_NAME;
    if(empty($_SESSION['username']) || ($_SESSION['user_type'] !== "jobseeker")){ //User not logged in or their session expired
//            die("<script>document.location.href=\"".LOGIN_PAGE."?error=expired\";</script>");
            $website->redirect($FULL_DOMAIN_NAME . '/index.php?error=expired');
    }
    else {        
        //Verify if user information match in db
        if($_SESSION['user_type'] == "employer"){ 
            $user_type = "employers";        
        } else {
            $user_type = "jobseekers";
        }
            
        $AdminUser = $arrUser = $LoginInfo = $db->where('username', $_SESSION['username'])
//            ->where('password', $_SESSION['user_password'])
            ->withTotalCount()->getOne($user_type);
                
        if ($db->totalCount == "0"){// User not matched
            echo "STOP!! you are not allowed to access this page";die;
        } else {
            //Set user data
            $AuthUserName = $LoginInfo['username'];
            $AuthGroup = $LoginInfo['type'];            
        }
    }
?>