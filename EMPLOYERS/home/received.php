<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, vieclambanthoigian.com.vn

if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $commonQueries_Employers;


$user_messages = $commonQueries_Employers->get_user_messages("$AuthUserName");

//Delete selected message
if (isset($_POST['delete']) && ($_POST['delete'] == "1")){
//    print_r($_POST);die;
    $message_id = filter_input(INPUT_POST,'selected_message', FILTER_SANITIZE_NUMBER_INT);
    
    //Make sure the selected message id does belong to user
    if(!$db->where('id', $message_id)->withTotalCount()->where('user_to', "$AuthUserName")->delete('user_messages')){
        //Failed
        $commonQueries->flash('message', $commonQueries->messageStyle('danger', "Có lỗi, không thể xóa"));
        $website->redirect('index.php?category=home&action=received');
    } else { //Success
        $commonQueries->flash('message', $commonQueries->messageStyle('info', "Xóa thành công"));
        $website->redirect('index.php?category=home&action=received');
    };
    
}
$id = 0;
?>
    
<div class="row main-nav">
    <section class="col-md-9">
        <?php $commonQueries->flash('message')?>
    </section>
    <section class="col-md-3">
        <?php echo LinkTile("home","welcome",$M_DASHBOARD,"","blue");?>
    </section>
</div>
    
<h3><?php echo $CONSULT_LIST_RECEIVED;?></h3>

<?php if ($user_messages['totalCount'] !== '0'):?>
<form action="" method="POST" id="messages">
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
                    <th scope="row">
                        <input type="hidden" name="delete" value="1">
                        <button type="submit" name="message_id" id="<?php echo $user_message['id']?>" value="<?php echo $user_message['id']?>" class="btn btn-danger confirmation">Xóa</button>
                    </th>
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

<script>
    $('.confirmation').confirm({
        content: 'Xóa tin nhắn đã chọn?',
        title: 'Vui lòng xác nhận',
        confirm: function(){
            $('<input>').attr('type','hidden').attr('name', 'selected_message').attr('value', this.$target.val()).appendTo('form');
            $('#messages').submit();
        }
    });

</script>
