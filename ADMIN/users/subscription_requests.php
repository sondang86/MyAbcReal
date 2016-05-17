<?php
    global $db, $commonQueries;
    
    $cols = array(
        $DBprefix."subscription_employer_request.employer_id as employer_id",$DBprefix."subscription_employer_request.employer_message",
        $DBprefix."subscription_employer_request.subscription_request_type as sub_request_id",$DBprefix."subscription_employer_request.date",
        $DBprefix."employers.username",$DBprefix."employers.subscription as current_sucscription",
        $DBprefix."apply_status.name as status_name",$DBprefix."apply_status.name_en as status_name_en",
        $DBprefix."subscriptions.name as subscription_current",
        "employer_current_subscription.name as request_subscription",
    );
    
    $db->join("employers", $DBprefix."subscription_employer_request.employer_id=".$DBprefix."employers.id", "LEFT");
    $db->join("apply_status", $DBprefix."subscription_employer_request.is_processed=".$DBprefix."apply_status.status_id", "LEFT");
    $db->join("subscriptions", $DBprefix."employers.subscription=".$DBprefix."subscriptions.id", "LEFT");
    $db->join("subscriptions as employer_current_subscription", $DBprefix."subscription_employer_request.subscription_request_type=employer_current_subscription.id", "LEFT");
    $pending_subscription_requests = $db->where('is_processed', '0')->get('subscription_employer_request', NULL, $cols);
    
    
    echo "<pre>";
    print_r($pending_subscription_requests);
    echo "</pre>";
    
    //Delete selected request id
    if (isset($_POST['delete'])){
        echo 'deleted';
    }
    
    //Approve selected request id
    if (isset($_POST['approve'])){
        $filtered_username = filter_input(INPUT_POST,'username', FILTER_SANITIZE_EMAIL);
        $subscription_request = filter_input(INPUT_POST,'sub_request_id', FILTER_SANITIZE_NUMBER_INT);
        $employer_id = filter_input(INPUT_POST,'employer_id', FILTER_SANITIZE_NUMBER_INT);
        //Set user subscription to the requested subscription

        if(!$db->where('username', "$filtered_username")->update('employers', array('subscription' => $subscription_request))){
            echo "error occurred";die;
            
        } else {
            //Succeed, 
            //set approved (1) in jobsportal_subscription_employer_request table
            if(!$db->where('employer_id', "$employer_id")->update('subscription_employer_request', array('is_processed' => 1))){
                echo "can't update this shit";die;
            } else {            
                //Refresh the page
                $commonQueries->flash('message', $commonQueries->messageStyle('info', "Đã xác nhận"));
                $website->redirect("index.php?category=users&action=subscription_requests");              
            }
        };
    }
    
    //Reject selected request id
    if (isset($_POST['reject'])){
        //Set status to rejected (2)
        echo 'rejected';
    }
    
?>
<style>
    .header-title {
        text-align: center;
    }
</style>

<div id="main-content">
    <h5><?php $commonQueries->flash('message')?></h5>
    <div class="row">
        <div class="col-lg-12">
            <div class="fright"></div>
            <br>
            <span class="medium-font">
                List of the current pending requets
            </span>
            <br>
            <div style="float:right">
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
    </div>
    
    <div class="row">
        <form action="" id="table-form" method="post">            
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

