<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries;
?>
<div class="row">
    <div class="col-md-3 pull-right">
    <?php
        echo LinkTile ("jobs","my",$M_GO_BACK,"","red");
    ?>
    </div>
</div>         
<h3>
	<?php echo $MANAGE_YOUR_JOB_ADS;?>
</h3>

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
                                <td class="col-md-1"><a href="index.php?category=jobs&amp;action=questionnaire&amp;id=<?php echo $value['id']?>">Bảng câu hỏi (<?php echo $questions_count;?>)</a></td>
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