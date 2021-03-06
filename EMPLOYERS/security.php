<?php
    define("LOGIN_PAGE", "../index.php");
    define("SUCCESS_PAGE", "index.php");
    define("LOGIN_EXPIRE_AFTER", 20000);
    global $db, $FULL_DOMAIN_NAME;

    if(empty($_SESSION['username']) || ($_SESSION['user_type'] !== "employer")){ //User not logged in or their session expired
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
        
        $username = $_SESSION['username'];
        
        $AdminUser = $arrUser = $LoginInfo = $db->where('username', "$username")
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