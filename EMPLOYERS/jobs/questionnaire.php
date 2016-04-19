<?php
// Jobs Portal All Rights Reserved
// A software product of Vieclambanthoigian Media, All Rights Reserved
// Find out more about our products and services on:
// 
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries,$DOMAIN_NAME;
$job_id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
    
//Fetch questionnaire data   
$questionnaires_list = $commonQueries->getQuestionnaire($job_id); 
//print_r($questionnaires_list);

if(isset($_POST['delete'])){
    //Sanitize data first
    $questions = filter_input(INPUT_POST, 'questions', FILTER_SANITIZE_NUMBER_INT);    
    
}

    if ($questionnaires_list !== FALSE){ //Found questions
        if ($AuthUserName == $questionnaires_list[0]['employer']){//Job question does belong to current employer
?>  


<div class="row questionnaire-title">
    <section class="col-md-8 col-xs-12"><h4><?php echo $commonQueries->flash('message');?></h4></section>
    <aside class="col-md-2 col-sm-6 col-xs-12">
        <?php echo LinkTile ("jobs","new_questionnaire&job_id=$job_id",$M_ADD_NEW_QUESTION,"","blue");?> 
    </aside>
    <aside class="col-md-2 col-sm-6 col-xs-12">
        <?php echo LinkTile ("jobs","my",$M_GO_BACK,"","red");?>    
    </aside>
</div>

<!--List questions-->
<div class="col-md-12">
    <h4>Danh sách câu hỏi: </h4>
    <div class="table-responsive">
        <table class="table table-hover admin-table">
            <thead>
                <tr class="table-tr">
                    <th width="10"></th>
                    <th width="70">                    
                        <a class="header-td" href="index.php?category=home&amp;action=apply&amp;order=date&amp;order_type=desc">
                            Ngày đăng
                        </a>    
                    </th>
                    <th width="140">                    
                        <span class="header-td">
                            Loại câu hỏi
                        </span>    
                    </th>
                    <th width="330">                    
                        <a class="header-td" href="index.php?category=home&amp;action=apply&amp;order=employer_reply&amp;order_type=desc">
                            Tiêu đề
                        </a>    
                    </th>
                    <th width="80">                    
                        <span class="header-td">
                            Chi tiết
                        </span>    
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questionnaires_list as $questionnaire) :?>
                <tr bgcolor="#ffffff" id="questionnaire_<?php echo $questionnaire['questionnaire_id']?>">
                    <td>
                        <a href="#" data-jobid="<?php echo $questionnaire['job_id']?>" data-questionnaireId="<?php echo $questionnaire['questionnaire_id']?>" class="confirm_remove" title="Xóa câu hỏi này">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td><?php echo date('Y m d G:i',$questionnaire['date']);?></td>
                    <td valign="middle"><?php echo $questionnaire['questionnaire_typeName'];?></td>
                    <td><?php echo $questionnaire['question'];?></td>
                    <td><a href="index.php?category=jobs&folder=questionnaire&page=edit&id=<?php echo $questionnaire['questionnaire_id'];?>&job_id=<?php echo $questionnaire['job_id'];?>" title="Sửa câu hỏi này"><img src="../images/job-details.png"></a></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
<?php } else { //Job question does not belong to employer
        echo "Không tìm thấy dữ liệu :(";
    } 
} else { ?>
<div class="row questionnaire-title">
    <section class="col-md-8 col-xs-12"></section>
    <aside class="col-md-2 col-sm-6 col-xs-12">
            <?php echo LinkTile ("jobs","new_questionnaire&job_id=$job_id",$M_ADD_NEW_QUESTION,"","blue");?> 
    </aside>
    <aside class="col-md-2 col-sm-6 col-xs-12">
            <?php echo LinkTile ("jobs","my",$M_GO_BACK,"","red");?>    
    </aside>
</div>
<div class="col-md-12">
    <h4>Hiện chưa có câu hỏi nào cho việc làm này</h4>
</div>
<?php }?>
<script>
    $(document).ready(function(){
        /*Confirmation modal box*/
        $('a.confirm_remove').confirm({
            title: 'Vui lòng xác nhận',
            content: 'Xóa câu hỏi này?',
            confirmButton: 'Có',
            cancelButton: 'Không',
            confirm: function(){
                $.ajax({ //Sending data to Server side                    
                    url: "http://<?php echo $DOMAIN_NAME?>/extensions/remove_question.php",
                    type: "post",
                    dataType: "JSON",
                    data: {
                        proceed: 1,
                        remove_question: 1,
                        user: '<?php echo $AuthUserName;?>',
                        job_id: this.$target.data('jobid'),                                    
                        questionnaire_id: this.$target.data('questionnaireid')
                    },
                    success: function (response) {
                        $.alert(response.message);
                        //Hide the removed div if success
                        if (response.status == "1"){
                            $("#questionnaire_"+response.questionnaire_id).hide('fade');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            },
            cancel: function(){
                console.log('the user clicked cancel');
            }
        });
    });    
</script>