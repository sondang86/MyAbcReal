<?php
// Jobs Portal, copyright vieclambanthoigian.com.vn 2016
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);

if (isset($_GET['register']) && ($_GET['register'] == "email") && ($_GET['user'] == "jobseeker")){ //Jobseeker
    
    //verify and make sure user_id & code matched with database
    $jobseeker = $db->where('id', $id)->where('verification_code', $code)->withTotalCount()->getOne('jobseekers');
    
    //set jobseeker as active
    if ($db->totalCount > 0 ){
        if($db->where ('id', $id)->update('jobseekers', array('active' => 1))){
            $commonQueries->flash('message', $commonQueries->messageStyle('info', 'xác thực thành công, bạn có thể đăng nhập'));
            $website->redirect('index.php?mod=login');
        } else {
            $website->redirect('index.php?mod=verifications&status=failed');
        }
    }
} elseif (isset($_GET['register']) && ($_GET['register'] == "email") && ($_GET['user'] == "employer")) { //Employer
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";
    
    //verify and make sure user_id & code matched with database
    $employer = $db->where('id', $id)->where('verification_code', $code)->withTotalCount()->getOne('employers');
    
    //set employer as active
    if ($db->totalCount > 0 ){
        if($db->where ('id', $id)->update('employers', array('active' => 1))){
            $commonQueries->flash('message', $commonQueries->messageStyle('info', 'xác thực email thành công, bạn có thể đăng nhập tài khoản'));
            $website->redirect('index.php?mod=login');
        } else {
            $website->redirect('index.php?mod=verifications&status=failed');
        }
    }
} elseif (isset($_GET['status']) && ($_GET['status'] == "failed")) {
    echo "<h4>Xác minh email bị lỗi, bạn vui lòng liên hệ info@vieclambanthoigian.com.vn</h4>";
}


