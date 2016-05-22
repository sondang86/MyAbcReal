<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $categories, $job_types, $locations, $salaries,$experience_list, $commonQueries;
$employer_jobs = $db->where('employer', "$AuthUserName")->withTotalCount()->get('jobs');
$jobs_count = $db->totalCount;

$db->join('subscriptions', $DBprefix."employers.subscription = " . $DBprefix."subscriptions.id", "LEFT");
$employer_subscription = $db->where('username', "$AuthUserName")->withTotalCount()->getOne('employers', array($DBprefix.'employers.subscription', $DBprefix.'subscriptions.listings', $DBprefix.'subscriptions.featured_listings'));

    //New job submitted
    if(isset($_POST['submit'])){
        //Insert data to database
        $data = Array( 
            "employer"              => "$AuthUserName",
            "job_category"          => filter_input(INPUT_POST, 'post-category'),
            "job_type"              => filter_input(INPUT_POST, 'post-jobtypes'),
            "title"                 => filter_input(INPUT_POST, 'employer-post-title',FILTER_SANITIZE_STRING), 
            "message"               => filter_input(INPUT_POST,'employer-post-details',FILTER_SANITIZE_STRING),
            "requires_description"  => filter_input(INPUT_POST, 'employer-post-requires',FILTER_SANITIZE_STRING), 
            "benefits_description"  => filter_input(INPUT_POST,'employer-post-benefits',FILTER_SANITIZE_STRING),
            "profileCV_description" => filter_input(INPUT_POST,'employer-post-profileCV',FILTER_SANITIZE_STRING),
            "experience"            => filter_input(INPUT_POST,'experience',FILTER_SANITIZE_NUMBER_INT),
            "region"                => filter_input(INPUT_POST,'post-locations'),
            "salary"                => filter_input(INPUT_POST,'post-salary'),
            "date"                  => time(),
            "date_available"        => strtotime(filter_input(INPUT_POST,'employer-start-date'). " " . date("G:i:s")),
            "expires"               => (strtotime(filter_input(INPUT_POST,'employer-start-date')) + (21*86400)), //21 days limitation
            "status"                => filter_input(INPUT_POST,'post-active'),
            "SEO_title"             => $db->secure_input($website->seoURL($website->stripVN(filter_input(INPUT_POST,'employer-post-title')))),
         );
        
    $id = $db->insert('jobs', $data);
    if($id){
        $commonQueries->flash('message', '<h4>Thêm việc mới thành công</h4>' );
        $website->redirect("index.php?category=jobs&action=my&result=added");
    } else {
        echo "there were an error occurred";
        die;
    }
}
?>

<!--NAVIGATION-->
<div class="row">
    <section class="col-md-9 col-sm-6 col-xs-12">
        <h4><label>Đăng việc mới</label></h4>
    </section>
    <div class="col-md-3 col-sm-6 col-xs-12 fright">
        <?php echo LinkTile("jobs","my",$MY_JOB_ADS,"","blue");?>
    </div>
</div>

<?php if ($jobs_count >= $employer_subscription['listings']): ?>
    <h5><?php echo $M_REACHED_MAXIMUM_SUBSCR;?></h5>
    <a class="underline-link" href="index.php?category=home&action=credits"><?php echo $M_PLEASE_SELECT_TO_POST;?></a>
    
<?php else : //New job post form, Allow employers to post job if they're not reached limit subscription posts?>

<h3><?php echo $POST_NEW_ADD;?></h3>

<div class="container">
    <form action="index.php?category=jobs&action=add" method="POST">
        <div class="row">
            <div class="col-md-12 employer-post-form">
                
                <!--Categories-->
                <label>
                    <span>Ngành: </span>
                    <select name="post-category" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($categories as $value) :?>
                        <option value="<?php echo $value['category_id']?>"><?php echo $value['category_name_vi']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Job types-->
                <label>
                    <span>Loại công việc: </span>
                    <select name="post-jobtypes" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($job_types as $value) :?>
                        <option value="<?php echo $value['id']?>"><?php echo $value['job_name']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Title-->
                <label>
                    <span>Tiêu đề (*): </span>
                    <input type="text" name="employer-post-title" required style="text-align: left">
                </label>
                
                <!--Descriptions-->
                <label>
                    <span>Chi tiết công việc: <p>(Hãy mô tả chi tiết nhưng đầu mục công việc để ứng viên có thể hiểu rõ hơn về yêu cầu của công ty bạn với vị trí này.)</p> </span>
                    <textarea type="text" name="employer-post-details" required></textarea>
                </label>
                
                <!--Job requirements-->
                <label class="textarea">
                    <span>Yêu cầu công việc: </span>
                    <textarea type="text" name="employer-post-requires" required></textarea>
                </label>
               
                <!--Benefits-->
                <label>
                    <span>Các quyền lợi được hưởng: </span>
                    <textarea type="text" name="employer-post-benefits" required></textarea>
                </label>
                
                <!--Yêu cầu hồ sơ-->
                <label>
                    <span>Yêu cầu hồ sơ: </span>
                    <textarea type="text" name="employer-post-profileCV" required>
- Sơ yếu lý lịch.
- Hộ khẩu, chứng minh nhân dân và giấy khám sức khỏe photo.
- Các bằng cấp có liên quan.
                    </textarea>
                </label>
                
                <!--Location-->
                <label>
                    <span>Địa điểm: </span>
                    <select name="post-locations" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($locations as $value) :?>
                        <option value="<?php echo $value['id']?>"><?php echo $value['City']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Salary-->
                <label>
                    <span>Lương: </span>
                    <select name="post-salary" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($salaries as $value) :?>
                        <option value="<?php echo $value['salary_id']?>"><?php echo $value['salary_range']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Experience-->
                <label>
                    <span>Yêu cầu kinh nghiệm: </span>
                    <select name="experience" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($experience_list as $experience) :?>
                        <option value="<?php echo $experience['experience_id']?>"><?php echo $experience['name']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Date start-->
                <label>
                    <span>Ngày bắt đầu: </span>
                    <input type="text" name="employer-start-date" id="employer-start-date">
                </label>
                
                <!--Active or not?-->
                <label>
                    <span>Đang hoạt động : </span>
                    <select name="post-active">
                        <option value="1">Có</option>
                        <option value="0">Không</option>
                    </select>
                </label>
                
                <div class="submit">
                    <input type="submit" name="submit" value="Gửi">
                </div>
            </div>
        </div>
    </form>
</div>
<?php endif;?>

