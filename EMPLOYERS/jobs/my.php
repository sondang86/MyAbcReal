<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $categories, $job_types, $locations, $salaries, $all_jobs;
?>
<div class="fright">
    
	<?php
            
		echo LinkTile
		(
			"jobs",
			"add",
			$M_NEW_JOB,
			"",
			"green"
		);
                    
		echo LinkTile
		(
			"jobs",
			"my_export",
			$M_EXPORT_OR_IMPORT,
			"",
			"lila"
		);
                    
                    
	?>
            
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
                        <input type="text" name="query" placeholder="Nhập tên công việc">
                    </div>
                    <div><input type="submit" value="search"></div>
                </div>
            </form>
            
            <!--List jobs-->
            <div class="table-responsive" >          
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name=""></th>
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
                        <?php foreach ($all_jobs as $value) :?>
                        <tr>
                            <td><input type="checkbox" name=""></td>
                            <td class="col-md-1" style="text-align: center"><a href="index.php?category=jobs&amp;folder=my&amp;page=edit&amp;id=25"><img src="../images/edit-icon.gif" width="24" height="20" border="0"></a></td>
                            <td class="col-md-1"><?php echo date('Y-m-d', $value['date'])?></td>
                            <td class="col-md-1"><?php echo date('Y-m-d', $value['expires'])?></td>
                            <td class="col-md-2"><?php echo $website->limitCharacters($value['title'],50);?></td>
                            <td class="col-md-4"><?php echo $website->limitCharacters($value['message'], 200);?></td>
                            <td class="col-md-1"><a href="index.php?category=jobs&amp;action=questionnaire&amp;id=<?php echo $value['id']?>">Bảng câu hỏi</a></td>
                            <td class="col-md-1"><a href="index.php?category=jobs&amp;action=my_stat&amp;id=<?php echo $value['id']?>">Số liệu thống kê</a></td>
                            <td><a href="index.php?category=jobs&amp;action=my_featured&amp;featured=1&amp;id=<?php echo $value['id']?>"><img border="0" src="../images/active_0.gif"></a></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
    echo date('Y-m-d H:i:s', 1457197200);
?>