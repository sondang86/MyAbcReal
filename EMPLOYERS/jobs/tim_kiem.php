<?php
//No direct access 
if(!defined('IN_SCRIPT')) die("");
global $db, $categories, $job_types, $locations, $salaries, $all_jobs;
      
//Perform search query    
if (isset($_GET['query'])):
    $query = $_GET['query'];
    $cv = $db->rawQuery("SELECT * from jobsportal_jobs WHERE `title` LIKE '%$query%' AND employer = '$AuthUserName'");
?>

<div class="row">
    <section class="col-md-9 col-sm-6"></section>
    <aside class="col-md-3 col-sm-4 col-xs-12 pull-right">
        <?php echo LinkTile("jobs","add",$M_NEW_JOB,"","green");?>            
    </aside>
</div>

<div class="container">
    <div class="row" style="width: 99%">
        <div class="col-md-12 job-details">
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
                            <th><input type="checkbox" name="select_all" id="checkAll"></th>
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
                        <?php foreach ($cv as $value) :?>
                        <tr>
                            <td><input type="checkbox" name="post" value="<?php echo $value['id']?>"></td>
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
<?php endif;?>