<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, vieclambanthoigian.com.vn

if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $commonQueries_Employers;
    
$user_messages = $commonQueries_Employers->get_user_messages("$AuthUserName");
    
//echo "<pre>";
//print_r($user_messages);
//echo "</pre>";
?>
    
<div class="row main-nav">
    <section class="col-md-9"></section>
    <section class="col-md-3">
        <?php echo LinkTile("home","welcome",$M_DASHBOARD,"","blue");?>
    </section>
</div>
    
<h3><?php echo $CONSULT_LIST_RECEIVED;?></h3>

<?php if ($user_messages['totalCount'] !== '0'):?>
<form action="" method="POST">
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Ngày gửi</th>
                    <th>Người gửi</th>
                    <th>Email</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Số điện thoại</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_messages['user_messages'] as $user_message) :?>
                <tr>
                    <th scope="row"><button type="submit" name="delete" class="btn btn-danger">Xóa</button></th>
                    <td><?php echo date('d-m-Y G:h:s',$user_message['date'])?></td>
                    <td><?php echo $user_message['name']?></td>
                    <td><?php echo $user_message['user_to']?></td>
                    <th scope="row"><?php echo $user_message['subject']?></th>
                    <td><?php echo $user_message['message']?></td>
                    <td><?php echo $user_message['contact_phone']?></td>
                </tr>  
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</form>
<?php else :?>

<h5>
    Hiện bạn không có tin nhắn nào.
</h5>

<?php endif;?>