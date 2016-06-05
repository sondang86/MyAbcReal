<?php
    global $db, $commonQueries, $commonQueries_Admin, $FULL_DOMAIN_NAME;
    
    $pending_subscription_requests = $commonQueries_Admin->getSubscriptions_List();
    
if (isset($_POST['proceed']) && ($_POST['proceed'] == '1')){ //Request submitted
    //Filter input first
    $filtered_username = filter_input(INPUT_POST,'username', FILTER_SANITIZE_EMAIL);
    $subscription_request = filter_input(INPUT_POST,'sub_request_id', FILTER_SANITIZE_NUMBER_INT);
    $employer_id = filter_input(INPUT_POST,'employer_id', FILTER_SANITIZE_NUMBER_INT);
    
    //Delete selected request id
    if (isset($_POST['delete'])){
        if(!$db->where('employer_id', $employer_id)->delete('subscription_employer_request')){
            echo 'problem when delete this shit!';die;
        } else {
            //Refresh the page
            $commonQueries->flash('message', $commonQueries->messageStyle('info', "Đã xác nhận"));
            $website->redirect("index.php?category=users&action=subscription_requests"); 
        };
    }
    
    //Approve selected request id
    if (isset($_POST['approve'])){
        //Update user subscription
        $data_update = Array(
            'subscription'          => $subscription_request, 
            'subscription_date'     => time(),
            'subscription_date_end' => time() + (86400 * 30) // Activated 30 days subscription
        );
        if(!$db->where('username', "$filtered_username")->update('employers', $data_update)){
            echo "error occurred";die;
            
        } else {
            //Succeed, 
            //set to approved status (1) in jobsportal_subscription_employer_request table
            if(!$db->where('employer_id', "$employer_id")->update('subscription_employer_request', array('is_processed' => 1))){
                echo "can't update this shit";die;
            } else {
                //Send email notify customer
                $email_subject  = 'Tin nhắn từ vieclambanthoigian.com.vn';
                $email_body     = "Chào bạn!\n"
                                . "Gói đăng ký '" . $_POST['request_subscription_name'] . "' của bạn đã được xác thực \n\n"
                                . "Để tiếp tục đăng việc mới, vui lòng truy cập địa chỉ dưới đây: \n\n"
                                . "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n"
                                . "$FULL_DOMAIN_NAME/EMPLOYERS/ \n\n"
                                . "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n"
                                . "Vui lòng gọi cho chúng tôi hotline 0984363189 hoặc email info@vieclambanthoigian.com.vn nếu bạn có bất kỳ thắc mắc gì \n\n"
                                . "Trân trọng \n\n"
                                . "Vieclambanthoigian.com.vn";

                require_once (DIR_BASE . 'extensions/include/email_handling.php');
                
                
                //Refresh the page
                $commonQueries->flash('message', $commonQueries->messageStyle('info', "Đã xác nhận"));
                $website->redirect("index.php?category=users&action=subscription_requests");              
            }
        }
    }
    
    //Reject selected request id
    if (isset($_POST['reject'])){
        //Set status to rejected (2)
        if(!$db->where('employer_id', "$employer_id")->update('subscription_employer_request', array('is_processed' => 2))){
            echo "can't update this shit";die;
        } else {           
            //Send email notify customer
            $email_subject  = 'Tin nhắn từ vieclambanthoigian.com.vn';
            $email_body     = "Chào bạn!\n"
                            . "Gói đăng ký '" . $_POST['request_subscription_name'] . "' của bạn không được xác thực \n\n"
                            . "Vui lòng gọi cho chúng tôi hotline 0984363189 hoặc email info@vieclambanthoigian.com.vn nếu bạn có bất kỳ thắc mắc/yêu cầu gì \n\n"
                            . "Trân trọng \n\n"
                            . "Vieclambanthoigian.com.vn";

            require_once (DIR_BASE . 'extensions/include/email_handling.php');            
            
            //Refresh the page
            $commonQueries->flash('message', $commonQueries->messageStyle('info', "Đã từ chối"));
            $website->redirect("index.php?category=users&action=subscription_requests");              
        }
    }
    
}    
?>

<div class="row main-nav">
    <section class="col-md-6"></section>
    <section class="col-md-6">
        <?php echo LinkTile("users","subscriptions_rejected",'Yêu cầu bị từ chối',"","yellow");?>
        <?php echo LinkTile("users","subscriptions_approved",'Yêu cầu đã xác nhận',"","blue");?>
    </section>
</div>

<?php if ($db->totalCount > 0) : //Found pending requests?>
<div id="main-content">
    <h5><?php $commonQueries->flash('message')?></h5>
    <div class="row">
        <span class="medium-font col-md-7">
            List of the current pending requests
        </span>
        <br>
        <div style="float:right" class="col-md-5">
            <form action="" method="post">
                Search in <select name="comboSearch" class="table-combo-search">
                    <option value="date">Date</option>
                    <option value="title">Title</option>
                    <option value="html">Description</option>
                    <option value="author">User</option>
                    <option value="vote">Vote</option>
                </select>                     
                <input class="table-search-field" value="" type="text" required="" name="textSearch"> 
                <input type="submit" class="btn btn-default btn-gradient" value=" Search ">
            </form>
        </div>
    </div>
    
    <div class="row">
        <form action="" id="table-form" method="post">
            <input type="hidden" name="proceed" value="1">
            <div class="table-responsive">
                <table cellpadding="3" cellspacing="0" width="100%" style="border-color:#eeeeee;border-width:1px 1px 1px 1px;border-style:solid">
                    <tbody>
                        <tr class="table-tr header-title">
                            <td class="col-md-1"></td>
                            <td class="col-md-1">                                
                                <a class="header-td underline-link" href="#">
                                    Ngày yêu cầu
                                </a>
                            </td>
                            <td class="col-md-1">
                                <a class="header-td underline-link" href="#">
                                    employer
                                </a>
                            </td>
                            <td class="col-md-1">                                
                                <a class="header-td underline-link" href="#">
                                    Gói đăng ký hiện tại
                                </a>
                            </td>
                            
                            <td class="col-md-2">                                
                                <a class="header-td underline-link" href="#">
                                    Gói đăng ký yêu cầu
                                </a>
                            </td>
                            
                            <td class="col-md-3">
                                <a class="header-td underline-link" href="#">
                                    Lời nhắn
                                </a>
                            </td>
                            <td class="col-md-1">
                                <a class="header-td underline-link" href="#">
                                    Trạng thái
                                </a>
                            </td>
                            <td class="col-md-2">
                                
                            </td>
                        </tr>
                        
                        <?php foreach ($pending_subscription_requests as $pending_subscription_request):?>
                        <tr bgcolor="#ffffff" class="header-title" height="30">
                            <td nowrap="">
                                <input type="submit" name="delete" value="Xóa" class="btn btn-danger">
                                <input type="hidden" name="subscription_id" value="<?php echo $pending_subscription_request['sub_request_id']?>">
                            </td>
                            <td width="20">
                                <?php echo date('d-m-Y',$pending_subscription_request['date'])?>
                            </td>
                            <td>
                                <?php echo $pending_subscription_request['username']?>
                                <input type="hidden" name="username" value="<?php echo $pending_subscription_request['username']?>">
                            </td>
                            <td>
                                <?php echo $pending_subscription_request['subscription_current']?>
                            </td>
                            <td>
                                <?php echo $pending_subscription_request['request_subscription']?>
                                <input type="hidden" name="current_sucscription" value="<?php echo $pending_subscription_request['current_sucscription']?>">
                                <input type="hidden" name="sub_request_id" value="<?php echo $pending_subscription_request['sub_request_id']?>">
                                <input type="hidden" name="employer_id" value="<?php echo $pending_subscription_request['employer_id']?>">
                                <input type="hidden" name="request_subscription_name" value="<?php echo $pending_subscription_request['request_subscription'];?>">
                                
                            </td>                            
                            <td>
                                <?php echo $pending_subscription_request['employer_message']?>
                                <input type="hidden" name="employer_message" value="<?php echo $pending_subscription_request['employer_message']?>">
                            </td>
                            <td>
                                <?php echo $pending_subscription_request['status_name']?>
                            </td>
                            <td>
                                <input type="submit" name="approve" value="Xác nhận" class="btn btn-info">
                                <input type="submit" name="reject" value="Từ chối" class="btn btn-warning">
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<?php else :?> 
<div id="main-content">
    <h5>Hiện không có requests nào</h5>
</div> 
<?php endif;?>
