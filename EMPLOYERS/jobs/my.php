<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $categories, $job_types, $locations, $salaries, $all_jobs, $commonQueries, $employerInfo;
$job_by_employer = $commonQueries->job_by_id('jobs','employer',"$AuthUserName");

//Delete jobs posted
if (isset($_POST['delete']) && !empty($_POST['post'])){
    foreach ($_POST['post'] as $value) { 
        $filted_value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        $db->where('id', $filted_value);
        if(!$db->delete('jobs')){
            echo "problem when deleting records";die;
        };
    }
    $commonQueries->flash('message', 'Xóa thành công');
    $website->redirect("index.php?category=jobs&action=my");
}
?>

<nav class="row">
    <div class="col-md-9">
        <?php $commonQueries->flash('message')?>
    </div>
    <div class="col-md-3 pull-right">
    <?php
        echo LinkTile("jobs","add",$M_NEW_JOB,"","green");
    ?>
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
                                <th><input type="checkbox" name="selectAll" id="checkAll"></th>
                                <th>Sửa đổi</th>
                                <th>Ngày đăng</th>
                                <th>Hạn đăng</th>
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th></th>
                                <th>Ưu tiên</th>
                            </tr>
                        </thead>
                        <tbody>                                
                            <?php foreach ($job_by_employer as $value) :
                                //Count total questions found
                                $questions_count = $db->where('job_id', $value['id'])->where('employer', $AuthUserName)->getValue ("questionnaire", "count(*)");
                            ?>
                            
                            <tr>
                                <td><input type="checkbox" name="post[]" value="<?php echo $value['id']?>"></td>
                                <td class="col-md-1" style="text-align: center"><a href="index.php?category=jobs&amp;folder=my&amp;page=edit&amp;id=<?php echo $value['id']?>"><img src="../images/edit-icon.gif" width="24" height="20" border="0"></a></td>
                                <td class="col-md-1"><?php echo date('Y-m-d', $value['date'])?></td>
                                <td class="col-md-1"><?php echo date('Y-m-d', $value['expires'])?></td>
                                <td class="col-md-2"><?php echo $website->limitCharacters($value['title'],50);?></td>
                                <td class="col-md-4"><?php echo $website->limitCharacters($value['message'], 200);?></td>
                                <td class="col-md-2"><a href="index.php?category=jobs&amp;action=questionnaire&amp;id=<?php echo $value['id']?>">Bảng câu hỏi (<?php echo $questions_count;?>)</a></td>
                                <!--<td class="col-md-1"><a href="index.php?category=jobs&amp;action=my_stat&amp;id=<?php echo $value['id']?>">Số liệu thống kê</a></td>-->
                                <td>
                                    <a href="#" class="confirm" data-title="Vui lòng xác nhận" data-jobid="<?php echo $value['id']?>" data-featured="<?php echo $value['featured']?>">
                                        <img border="0" src="../images/active_<?php echo $value['featured']?>.gif" id="image_icon_<?php echo $value['id'];?>">
                                    </a>
                                </td>
                            </tr>
                                <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="form-submit">
                    <input type="hidden" name="delete">
                    <input type="submit" value="Xóa" id="delete" disabled>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        /*Confirmation modal box*/
        $('a.confirm').confirm({
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
                        user: '<?php echo $AuthUserName;?>',
                        subscription: '<?php echo $employerInfo['subscription'];?>',
                        job_id: this.$target.data('jobid'),                                    
                        title: this.$target.data('title'),
                        job_featured: this.$target.data('featured')
                    },
                    success: function (response) {
                        $.alert(response.message);
                        //Replace icon respectively
                        $('#image_icon_'+response.jobid).attr('src',"http://localhost/vieclambanthoigian.com.vn/images/" + response.icon);
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
                    
        //Delete confirmation        
        $('#delete').confirm({
            content: 'bạn có muốn xóa những việc đã chọn?',
            confirm: function(){
                $('#form_delete').submit();
            },
            cancel: function(){
                console.log('the user clicked cancel');
            },
            title: 'Vui lòng xác nhận',
            confirmButton: 'Có',
            cancelButton: 'Không'
        });
                    
    });                 
</script>

