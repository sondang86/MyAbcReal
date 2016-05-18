<?php
    global $db, $commonQueries, $commonQueries_Admin;        
    $approved_subscription_requests = $commonQueries_Admin->getSubscriptions_List('1');
?>
    
<div class="row main-nav">
    <section class="col-md-6"></section>
    <section class="col-md-6">
        <?php echo LinkTile("users","subscriptions_rejected",'Yêu cầu bị từ chối',"","yellow");?>
        <?php echo LinkTile("users","subscriptions_approved",'Yêu cầu đã xác nhận',"","blue");?>
    </section>
</div>
    
<div id="main-content">
    <h5></h5>
    <div class="row">
        <span class="medium-font col-md-7">
            List of the approved requests
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
                            
                            <td class="col-md-5">
                                <a class="header-td underline-link" href="#">
                                    Lời nhắn
                                </a>
                            </td>
                            <td class="col-md-1">
                                <a class="header-td underline-link" href="#">
                                    Trạng thái
                                </a>
                            </td>
                        </tr>
                        
                        <?php foreach ($approved_subscription_requests as $approved_subscription_request):?>
                        <tr bgcolor="#ffffff" class="header-title" height="30">
                            <td nowrap="">
                                <input type="submit" name="delete" value="Xóa" class="btn btn-danger">
                                <input type="hidden" name="subscription_id" value="<?php echo $approved_subscription_request['sub_request_id']?>">
                            </td>
                            <td width="20">
                                <?php echo date('d-m-Y',$approved_subscription_request['date'])?>
                            </td>
                            <td>
                                <?php echo $approved_subscription_request['username']?>
                                <input type="hidden" name="username" value="<?php echo $approved_subscription_request['username']?>">
                            </td>
                            <td>
                                <?php echo $approved_subscription_request['subscription_current']?>
                            </td>
                            <td>
                                <?php echo $approved_subscription_request['request_subscription']?>
                                <input type="hidden" name="current_sucscription" value="<?php echo $approved_subscription_request['current_sucscription']?>">
                                <input type="hidden" name="sub_request_id" value="<?php echo $approved_subscription_request['sub_request_id']?>">
                                <input type="hidden" name="employer_id" value="<?php echo $approved_subscription_request['employer_id']?>">
                            </td>                            
                            <td>
                                <?php echo $approved_subscription_request['employer_message']?>
                                <input type="hidden" name="employer_message" value="<?php echo $approved_subscription_request['employer_message']?>">
                            </td>
                            <td>
                                <?php echo $approved_subscription_request['status_name']?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>