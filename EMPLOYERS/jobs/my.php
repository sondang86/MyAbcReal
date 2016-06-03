<?php
// Jobs Portal All Rights Reserved
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $categories, $job_types, $locations, $salaries, $all_jobs, $commonQueries, $employerInfo, $FULL_DOMAIN_NAME;
$job_by_employer = $commonQueries->job_by_id('jobs','employer',"$AuthUserName");

?>

<nav class="row">
    <div class="col-md-9">
        <?php $commonQueries->flash('message')?>
    </div>
    <div class="col-md-3 pull-right">    
    <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/dang-viec-moi/", 'Đăng việc mới', 'green');?>
    </div>
</nav>     

<h3><?php echo $MANAGE_YOUR_JOB_ADS;?></h3>

<div class="container">
    <div class="row" style="width: 99%">
        <div class="col-md-12">
            <!--Search jobs-->
            <form action="index.php" method="GET" id="search">
                <div class="pull-right sort-by" id="sort-by">
                    <div>
                        <input type="hidden" name="category" value="jobs" placeholder="Nhập tên công việc">
                        <input type="hidden" name="action" value="tim_kiem">
                        <input type="text" name="query" placeholder="Tiêu đề công việc">
                    </div>
                    <div><input type="submit" value="Tìm kiếm"></div>
                </div>
            </form>
            
            
            
            <!--List jobs-->
            <form action="" role="form" method="POST" id="form_delete">
                <div class="table-responsive" >          
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Sửa đổi</th>
                                <th>Ngày đăng</th>
                                <th>Hạn đăng</th>
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th></th>
                                <th class="col-md-1">Ưu tiên</th>
                                <th class="col-md-2">Tuyển gấp</th>
                            </tr>
                        </thead>
                        <tbody>                                
                            <?php foreach ($job_by_employer as $value) :
                                //Count total questions found
                                $questions_count = $db->where('job_id', $value['id'])->where('employer', $AuthUserName)->getValue ("questionnaire", "count(*)");
                            ?>
                            
                            <tr id="job_id_<?php echo $value['id']?>">
                                <td>
                                    <a href="#" data-jobid="<?php echo $value['id']?>" data-questionsCount="<?php echo $questions_count;?>" class="confirm_remove" title="Xóa câu hỏi này">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                </td>                                 
                                <td class="col-md-1" style="text-align: center"><a href="index.php?category=jobs&amp;folder=my&amp;page=edit&amp;id=<?php echo $value['id']?>"><img src="../images/edit-icon.gif" width="24" height="20" border="0"></a></td>
                                <td class="col-md-1"><?php echo date('Y-m-d', $value['date'])?></td>
                                <td class="col-md-1"><?php echo date('Y-m-d', $value['expires'])?></td>
                                <td class="col-md-2"><?php echo $website->limitCharacters($value['title'],50);?></td>
                                <td class="col-md-3"><?php echo $website->limitCharacters($value['message'], 200);?></td>
                                <td class="col-md-2"><a href="index.php?category=jobs&amp;action=questionnaire&amp;id=<?php echo $value['id']?>">Bảng câu hỏi (<?php echo $questions_count;?>)</a></td>
                                
                                <td>
                                    <a href="#" class="confirm_featured" data-title="Vui lòng xác nhận" data-jobid="<?php echo $value['id']?>" data-featured="<?php echo $value['featured']?>">
                                        <img border="0" src="<?php echo $FULL_DOMAIN_NAME;?>/images/active_<?php echo $value['featured']?>.gif" id="featured_image_icon_<?php echo $value['id'];?>">
                                    </a>
                                </td>
                                
                                <td>
                                    <a href="#" class="confirm_urgent" data-title="Vui lòng xác nhận" data-jobid="<?php echo $value['id']?>" data-urgent="<?php echo $value['urgent']?>">
                                        <img border="0" src="<?php echo $FULL_DOMAIN_NAME;?>/images/active_<?php echo $value['urgent']?>.gif" id="urgent_image_icon_<?php echo $value['id'];?>">
                                    </a>
                                </td>
                            </tr>
                                <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        /*Confirmation on set featured job*/
        $('a.confirm_featured').confirm({
            title: 'Vui lòng xác nhận',
            content: 'Việc làm này sẽ hiển thị lên đầu danh sách?',
            confirmButton: 'Có',
            cancelButton: 'Không',
            confirm: function(){
                $.ajax({ //Sending data to Server side
                    
                    url: "http://<?php echo $DOMAIN_NAME?>/extensions/set_featured.php",
                    type: "post",
                    dataType: "JSON",
                    data: {
                        proceed: 1,
                        request: 'set_featured',
                        user: '<?php echo $AuthUserName;?>',
                        subscription: '<?php echo $employerInfo['subscription'];?>',
                        job_id: this.$target.data('jobid'),                                    
                        title: this.$target.data('title'),
                        job_featured: this.$target.data('featured')
                    },
                    success: function (response) {
                        $.alert(response.message);
                        //Replace icon respectively
                        $('#featured_image_icon_'+response.jobid).attr('src',"http://localhost/vieclambanthoigian.com.vn/images/" + response.icon);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
        });
        
        /*Confirmation on set urgent job*/
        $('a.confirm_urgent').confirm({
            title: 'Vui lòng xác nhận',
            content: 'Việc làm này sẽ hiển thị lên danh sách tuyển dụng gấp?',
            confirmButton: 'Có',
            cancelButton: 'Không',
            confirm: function(){
                $.ajax({ //Sending data to Server side
                    
                    url: "http://<?php echo $DOMAIN_NAME?>/extensions/set_featured.php",
                    type: "post",
                    dataType: "JSON",
                    data: {
                        proceed: 1,
                        request: 'set_urgent',
                        user: '<?php echo $AuthUserName;?>',
                        subscription: '<?php echo $employerInfo['subscription'];?>',
                        job_id: this.$target.data('jobid'),                                    
                        title: this.$target.data('title'),
                        job_featured: this.$target.data('featured')
                    },
                    success: function (response) {
                        $.alert(response.message);
                        //Replace icon respectively
                        $('#urgent_image_icon_'+response.jobid).attr('src',"<?php echo $FULL_DOMAIN_NAME;?>/images/" + response.icon);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
        });

        
        /*Confirmation on delete job*/
        $('a.confirm_remove').confirm({
            title: 'Vui lòng xác nhận',
            content: 'Xóa việc làm này?',
            confirmButton: 'Có',
            cancelButton: 'Không',
            
            confirm: function(){
                var question_count = this.$target.data('questionscount');

                //Make sure user delete questions first, questions list must be empty to proceed next
                if(question_count == "0"){
                    $.ajax({ //Sending data to Server side                    
                        url: "http://<?php echo $DOMAIN_NAME?>/extensions/remove_question.php",
                        type: "post",
                        dataType: "JSON",
                        data: {
                            proceed: 1,
                            remove_job: 1,
                            user: '<?php echo $AuthUserName;?>',
                            subscription: '<?php echo $employerInfo['subscription'];?>',
                            job_id: this.$target.data('jobid'),                                    
                            questionnaire_id: this.$target.data('questionnaireid')
                        },
                        success: function (response) {
                            $.alert(response.message);
                            //Hide the removed div if success
                            if (response.status == "1"){
                                $("#job_id_"+response.job_id).hide('fade');
                            } 
                        },
                        error: function(response) {
                            console.log(response.message);
                        }
                    });                      
                } else {                                      
                    $.alert('Bạn vui lòng xóa câu hỏi trong bảng câu hỏi trước!');
                }
            },
            cancel: function(){
            
            }
        });
                    
    });                 
</script>

