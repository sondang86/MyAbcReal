<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $categories, $job_types, $locations, $salaries, $all_jobs, $commonQueries;
$job_by_employer = $commonQueries->job_by_id('jobs','employer',"$AuthUserName");

//Delete jobs posted
if (isset($_POST['delete']) && !empty($_POST['post'])){
    foreach ($_POST['post'] as $value) { 
        $filted_value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        $db->where('id', $filted_value);
        $db->delete('jobs');
    }
    $website->redirect("index.php?category=jobs&action=my");
}
?>
<div class="row">
    <div class="col-md-3 pull-right">
            <?php
                echo LinkTile
                (
                    "jobs",
                    "add",
                    $M_NEW_JOB,
                    "",
                    "green"
                );

//                echo LinkTile
//                (
//                    "jobs",
//                    "my_export",
//                    $M_EXPORT_OR_IMPORT,
//                    "",
//                    "lila"
//                );
            ?>
    </div>
</div>         
<div class="clear"></div>

<h3>
	<?php echo $MANAGE_YOUR_JOB_ADS;?>
</h3>
<br/>

<div class="container">
    <div class="row" style="width: 99%">
        <div class="col-md-12">
            <!--Search jobs-->
            <form action="index.php" method="GET">
                <div class="pull-right sort-by" id="sort-by">
                    <div>
                        <input type="hidden" name="category" value="jobs" placeholder="Nhập tên công việc">
                        <input type="hidden" name="action" value="tim_kiem">
                        <input type="text" name="query" placeholder="Tiêu đề công việc">
                    </div>
                    <div><input type="submit" value="Tìm kiếm"></div>
                </div>
            </form>
            <script>
                $(document).ready(function(){
                    $(function(){
                        var checkboxes = $(':checkbox:not(#checkAll)').click(function(event){
                            $('#submit').prop("disabled", checkboxes.filter(':checked').length == 0);
                        });

                        $('#checkAll').click(function(event) {   
                            checkboxes.prop('checked', this.checked);
                            $('#submit').prop("disabled", !this.checked)
                        });
                    });
                });                                    
            </script>
            <!--List jobs-->
            <form action="" method="POST">
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
                                <th></th>
                                <th>Ưu tiên</th>
                            </tr>
                        </thead>
                        <tbody>                                
                                <?php foreach ($job_by_employer as $value) :?>
                            <tr>
                                <td><input type="checkbox" name="post[]" value="<?php echo $value['id']?>"></td>
                                <td class="col-md-1" style="text-align: center"><a href="index.php?category=jobs&amp;folder=my&amp;page=edit&amp;id=<?php echo $value['id']?>"><img src="../images/edit-icon.gif" width="24" height="20" border="0"></a></td>
                                <td class="col-md-1"><?php echo date('Y-m-d', $value['date'])?></td>
                                <td class="col-md-1"><?php echo date('Y-m-d', $value['expires'])?></td>
                                <td class="col-md-2"><?php echo $website->limitCharacters($value['title'],50);?></td>
                                <td class="col-md-4"><?php echo $website->limitCharacters($value['message'], 200);?></td>
                                <td class="col-md-1"><a href="index.php?category=jobs&amp;action=questionnaire&amp;id=<?php echo $value['id']?>">Bảng câu hỏi</a></td>
                                <td class="col-md-1"><a href="index.php?category=jobs&amp;action=my_stat&amp;id=<?php echo $value['id']?>">Số liệu thống kê</a></td>
                                <td><a href="index.php?category=jobs&amp;action=my_featured&amp;featured=1&amp;id=<?php echo $value['id']?>"><img border="0" src="../images/active_<?php echo $value['featured']?>.gif"></a></td>
                            </tr>
                                <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="form-submit">
                    <input type="submit" name="delete" value="Xóa" id="submit" disabled>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
