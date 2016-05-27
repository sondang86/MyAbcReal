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
    <div class="col-md-3 col-sm-6 col-xs-12">
        <?php echo LinkTile("jobs","my",$MY_JOB_ADS,"","blue");?>
    </div>
</div>

<?php if ($jobs_count >= $employer_subscription['listings']): ?>
<h5><?php echo $M_REACHED_MAXIMUM_SUBSCR;?></h5>
<a class="underline-link" href="index.php?category=home&action=credits"><?php echo $M_PLEASE_SELECT_TO_POST;?></a>

<?php else : //New job post form, Allow employers to post job if they're not reached limit subscription posts?>

<style>
    .newJob fieldset section label textarea {
        min-height: 150px;
    }
    
    .newJob {
        margin-top: 25px;
        margin-bottom: 25px;
    }
</style>

<form action="index.php?category=jobs&action=add" method="POST" class="sky-form newJob"> 
    <header><?php echo $POST_NEW_ADD;?></header>
    <fieldset>    
        <section class="col col-6">
            <label class="label">Tiêu đề công việc(*): </label>
            <label class="input"><input type="text" name="employer-post-title" required></label>
        </section>

        <section class="col col-12">
            <label class="label">
                Chi tiết công việc:
            </label>
            <label class="textarea">
                    <textarea type="text" name="employer-post-details" required></textarea>
            </label>
            <div class="note"><strong>Note:</strong> Hãy mô tả chi tiết nhưng đầu mục công việc để ứng viên có thể hiểu rõ hơn về yêu cầu của công ty bạn với vị trí này.</div>
        </section>

        <section class="col col-12">
            <label class="label">
                Yêu cầu công việc: 
            </label>
            <label class="textarea">
                    <textarea type="text" name="employer-post-requires" required></textarea>
            </label>
            <div class="note"><strong>Note:</strong> Yêu cầu chi tiết về kỹ năng, thái độ của ứng viên với công việc này.</div>
        </section>

        <section class="col col-12">
            <label class="label">
                Các quyền lợi được hưởng: 
            </label>
            <label class="textarea">
                    <textarea type="text" name="employer-post-benefits" required></textarea>
            </label>
            <div class="note"><strong>Note:</strong> Liệt kê những quyền lợi hấp dẫn mà ứng viên sẽ được hưởng đối với vị trí này.</div>
        </section>

        <section class="col col-12">
            <label class="label">
                Yêu cầu hồ sơ: 
            </label>
            <label class="textarea">
                    <textarea type="text" name="employer-post-profileCV" required>
- Sơ yếu lý lịch.
- Hộ khẩu, chứng minh nhân dân và giấy khám sức khỏe photo.
- Các bằng cấp có liên quan.
                    </textarea>
            </label>
            <div class="note"><strong>Note:</strong> Liệt kê hồ sơ ứng viên cần chuẩn bị khi được nhận vào làm.</div>
        </section>

        <section class="col col-4">
            <label class="label">Ngành nghề: </label>
            <label class="select">
                <select name="post-category" required>
                    <option value="">Vui lòng chọn</option>
                                <?php foreach ($categories as $value) :?>
                    <option value="<?php echo $value['category_id']?>"><?php echo $value['category_name_vi']?></option>    
                                <?php endforeach;?>
                </select>
                <i></i>
            </label>
        </section>

        <section class="col col-4">
            <label class="label">Loại công việc: </label>
            <label class="select">
                <select name="post-jobtypes" required>
                    <option value="">Vui lòng chọn</option>
                                <?php foreach ($job_types as $value) :?>
                    <option value="<?php echo $value['id']?>"><?php echo $value['job_name']?></option>    
                                <?php endforeach;?>
                </select>
                <i></i>
            </label>
        </section>

        <section class="col col-4">
            <label class="label">Địa điểm: </label>
            <label class="select">
                <select name="post-locations" required>
                    <option value="">Vui lòng chọn</option>
                        <?php foreach ($locations as $value) :?>
                    <option value="<?php echo $value['id']?>"><?php echo $value['City']?></option>    
                        <?php endforeach;?>
                </select>
                <i></i>
            </label>
        </section>

        <section class="col col-4">
            <label class="label">Mức lương: </label>
            <label class="select">
                <select name="post-salary" required>
                    <option value="">Vui lòng chọn</option>
                        <?php foreach ($salaries as $value) :?>
                    <option value="<?php echo $value['salary_id']?>"><?php echo $value['salary_range']?></option>    
                        <?php endforeach;?>
                </select>
                <i></i>
            </label>
        </section>

        <section class="col col-4">
            <label class="label">Yêu cầu kinh nghiệm: </label>
            <label class="select">
                <select name="experience" required>
                    <option value="">Vui lòng chọn</option>
                        <?php foreach ($experience_list as $experience) :?>
                    <option value="<?php echo $experience['experience_id']?>"><?php echo $experience['name']?></option>    
                        <?php endforeach;?>
                </select>
                <i></i>
            </label>
        </section>

        <section class="col col-4">
            <label class="label">Ngày bắt đầu: </label>
            <label class="input">
                    <i class="icon-append fa fa-calendar"></i>
                    <input type="text" name="employer-start-date" id="employer-start-date" placeholder="Start date">
            </label>
        </section>
    </fieldset>
    
    <footer>
            <button type="submit" name="submit" class="button">Gửi</button>
            <button type="button" class="button button-secondary" onclick="window.history.back();">Quay lại trang trước</button>
    </footer>
    
    
</form>
<?php endif;?>

