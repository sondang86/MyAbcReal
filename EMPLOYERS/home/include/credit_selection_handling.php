<?php
    if(!defined('IN_SCRIPT')) die("");
    
if (isset($_POST['credit_selection_submit'])){
    //Captcha checker
    if( (md5($_POST['code']) !== ($_SESSION['code'])|| trim($_POST['code']) == "" )) {//Wrong captcha
        $commonQueries->flash('message', $commonQueries->messageStyle('danger', 'Vui lòng nhập chính xác mã CAPTCHA'));
    } else {    
        //Insert on duplicate update    
        $data = array(
            'employer_id'                   => $employerInfo['id'],
            'subscription_request_type'     => filter_input(INPUT_POST, 'subscription_request_type' , FILTER_SANITIZE_NUMBER_INT),
            'date'                          => time(),
            'employer_message'              => filter_input(INPUT_POST, 'employer_message' , FILTER_SANITIZE_STRING),
            'is_processed'                  => 0, //Default is not processed, until admin approved 
        );

        $updateColumns = Array ("subscription_request_type", "date", "employer_message", "employer_id", 'is_processed');
        $db->onDuplicate($updateColumns);
        $id = $db->insert ('subscription_employer_request', $data);

        if (!$id){
            echo 'problem';die;
        }

        //Send email notify to Administrator
        $email_subject  = 'Có một yêu cầu đăng ký gói tuyển dụng mới';
        $email_body     = "Chào bạn!\n"
                        . "Bạn có một yêu cầu đăng ký gói tuyển dụng mới từ $AuthUserName \n\n"
                        . "Vui lòng truy cập địa chỉ dưới đây để approve: \n\n"
                        . "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n"
                        . "$FULL_DOMAIN_NAME/admin/index.php?category=users&action=subscription_requests \n\n"
                        . "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n"
                        . "Trân trọng \n\n"
                        . "Vieclambanthoigian.com.vn";

        require_once (DIR_BASE . 'extensions/include/email_handling.php');

        //Succeed, back to question page
        $commonQueries->flash('message', $commonQueries->messageStyle('info', "Cảm ơn bạn đã gửi yêu cầu, chúng tôi sẽ gửi email thông báo tới bạn trong vòng 30-60 phút"));
        $website->redirect($FULL_DOMAIN_NAME."/EMPLOYERS/dang-ky-dich-vu/");    
    }  
}