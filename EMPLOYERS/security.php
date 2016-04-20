<?php
    define("LOGIN_PAGE", "../index.php");
    define("SUCCESS_PAGE", "index.php");
    define("LOGIN_EXPIRE_AFTER", 20000);
    global $db;
 
    if(empty($_SESSION['username']) || empty($_SESSION['user_type'])){ //User not logged in or their session expired
            die("<script>document.location.href=\"".LOGIN_PAGE."?error=expired\";</script>");
    }
    else {        
        //Verify if user information match in db
        if($_SESSION['user_type'] == "employer"){ 
            $user_type = "employers";        
        } else {
            $user_type = "jobseekers";
        }

        $data = $db->where('username', $_SESSION['username'])
            ->where('password', $_SESSION['user_password'])
            ->withTotalCount()->getOne($user_type);

        if ($db->totalCount == "0"){// User not matched
            echo "not matched";die;
        } else {
            //Set username
            $AuthUserName = $data['username'];
            $AuthGroup = $data['type'];            
//            print_r($AuthUserName);die;
        }
    }
?>