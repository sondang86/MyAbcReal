<?php
// Vieclambanthoigian 2016 Copyright All Rights Reserved
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $FULL_DOMAIN_NAME, $categories, $job_types, $locations, $salaries, $experience_list,$jobs_by_employerId, $employerInfo;
?>
<?php
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if($database->SQLCount("jobs","WHERE employer='".$AuthUserName."' AND id=".$id) == 0){ //Prevent access to job that does not belong to user
    $website->redirect($FULL_DOMAIN_NAME.'/404.php');
}


$db->join('jobs_location', $DBprefix."jobs.id = ".$DBprefix."jobs_location.job_id", "LEFT");
$job = $db->where ("id", "$id")->getOne("jobs");

//Get latitude/longitute values
$latitude = $commonQueries->check_LatitudeLongitude($job['latitude'],$job['longitude'])['latitude'];
$longitude = $commonQueries->check_LatitudeLongitude($job['latitude'],$job['longitude'])['longitude'];

?>

<?php //Edit data
    if(isset($_POST['submit'])){
 
        //Insert data to database
        $data = Array( 
            "employer"              => "$AuthUserName",
            "job_category"          => filter_input(INPUT_POST, 'post-category', FILTER_SANITIZE_NUMBER_INT),
            "job_type"              => filter_input(INPUT_POST, 'post-jobtypes', FILTER_SANITIZE_NUMBER_INT),
            "title"                 => filter_input(INPUT_POST, 'employer-post-title',FILTER_SANITIZE_STRING), 
            "message"               => filter_input(INPUT_POST,'employer-post-details',FILTER_SANITIZE_STRING),
            "requires_description"  => filter_input(INPUT_POST, 'employer-post-requires',FILTER_SANITIZE_STRING), 
            "benefits_description"  => filter_input(INPUT_POST,'employer-post-benefits',FILTER_SANITIZE_STRING),
            "profileCV_description" => filter_input(INPUT_POST,'employer-post-profileCV',FILTER_SANITIZE_STRING),
            "experience"            => filter_input(INPUT_POST,'experience',FILTER_SANITIZE_NUMBER_INT),
            "region"                => filter_input(INPUT_POST,'post-locations', FILTER_SANITIZE_NUMBER_INT),
            "salary"                => filter_input(INPUT_POST,'post-salary', FILTER_SANITIZE_NUMBER_INT),
            "date"                  => time(),
            "date_available"        => strtotime(filter_input(INPUT_POST,'employer-start-date'). " " . date("G:i:s")),
            "expires"               => (strtotime(filter_input(INPUT_POST,'employer-start-date')) + (21*86400)), //21 days limitation
            "status"                => 1,
            "SEO_title"             => $website->seoURL($website->stripVN(filter_input(INPUT_POST,'employer-post-title'))),
            "work_location"         => filter_input(INPUT_POST,'job_location',FILTER_SANITIZE_STRING),
            "contact_person"        => filter_input(INPUT_POST,'contact_person',FILTER_SANITIZE_STRING),
            "contact_person_phone"  => filter_input(INPUT_POST,'contact_person_phone',FILTER_SANITIZE_NUMBER_INT),
            "contact_person_email"  => filter_input(INPUT_POST,'contact_person_email',FILTER_SANITIZE_STRING)
         );
    
    
    $db->where('id', filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
    $id = $db->update('jobs', $data);
    if($id){//Update in jobs_location table        
        $job_location = array(
            'latitude'  => filter_input(INPUT_POST,'job_map_latitude',FILTER_SANITIZE_STRING),
            'longitude' => filter_input(INPUT_POST,'job_map_longitude',FILTER_SANITIZE_STRING),
            'job_id'    => filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT)
        );        
        
        $updateColumns = Array ('latitude', 'longitude', 'job_id');
        $db->onDuplicate($updateColumns);
        if(!$db->insert('jobs_location', $job_location)){
            echo "problem"; die;            
        } else {             
            $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Lưu thay đổi thành công') );
            $website->redirect($_SERVER['REQUEST_URI']);
        }
       
    } else {
        $message = "there were an error occurred";
        die;
    }
}
?>

<!--NAVIGATION-->
<div class="row">
    <section class="col-md-8 col-sm-6 col-xs-12">
        <h5><?php $commonQueries->flash('message');?></h5>
    </section>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/danh-sach-don-xin-viec/", 'Danh sách đơn xin việc', 'blue');?>
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/danh-sach-cong-viec/", 'Danh sách công việc', 'green');?>
    </div>
</div>


<form action="" method="POST" class="sky-form newJob"> 
    <header>Chỉnh sửa chi tiết công việc</header>
    <fieldset>    
        <section class="col col-6">
            <label class="label">Tiêu đề công việc(*): </label>
            <label class="input"><input type="text" name="employer-post-title" required value="<?php echo $job['title']?>"></label>
        </section>

        <section class="col col-12">
            <label class="label">
                Chi tiết công việc:
            </label>
            <label class="textarea">
                    <textarea type="text" name="employer-post-details" required><?php echo $job['message']?></textarea>
            </label>
            <div class="note"><strong>Gợi ý:</strong> Hãy mô tả chi tiết nhưng đầu mục công việc để ứng viên có thể hiểu rõ hơn về yêu cầu của công ty bạn với vị trí này.</div>
        </section>

        <section class="col col-12">
            <label class="label">
                Yêu cầu công việc: 
            </label>
            <label class="textarea">
                    <textarea type="text" name="employer-post-requires" required><?php echo $job['requires_description']?></textarea>
            </label>
            <div class="note"><strong>Gợi ý:</strong> Yêu cầu chi tiết về kỹ năng, thái độ của ứng viên với công việc này.</div>
        </section>

        <section class="col col-12">
            <label class="label">
                Các quyền lợi được hưởng: 
            </label>
            <label class="textarea">
                    <textarea type="text" name="employer-post-benefits" required><?php echo $job['benefits_description']?></textarea>
            </label>
            <div class="note"><strong>Gợi ý:</strong> Liệt kê những quyền lợi hấp dẫn mà ứng viên sẽ được hưởng đối với vị trí này.</div>
        </section>

        <section class="col col-12">
            <label class="label">
                Yêu cầu hồ sơ: 
            </label>
            <label class="textarea">
                    <textarea type="text" name="employer-post-profileCV" required>
<?php echo $job['profileCV_description']?>
                    </textarea>
            </label>
            <div class="note"><strong>Gợi ý:</strong> Liệt kê hồ sơ ứng viên cần chuẩn bị khi được nhận vào làm.</div>
        </section>
        
        </fieldset>

        <fieldset>

        <section class="col col-4">
            <label class="label">Ngành nghề: </label>
            <label class="select">
                <select name="post-category" required>
                    <option value="">Vui lòng chọn</option>
                                <?php foreach ($categories as $value) :?>
                    <option value="<?php echo $value['category_id']?>" <?php if($value['category_id'] == $job['job_category']){echo "selected";}?>><?php echo $value['category_name_vi']?></option>    
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
                    <option value="<?php echo $value['id']?>" <?php if($value['id'] == $job['job_type']){echo "selected";}?>><?php echo $value['job_name']?></option>    
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
                    <option value="<?php echo $value['id']?>" <?php if($value['id'] == $job['region']){echo "selected";}?>><?php echo $value['City']?></option>    
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
                    <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $job['salary']){echo "selected";}?>><?php echo $value['salary_range']?></option>    
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
                    <option value="<?php echo $experience['experience_id']?>" <?php if($experience['experience_id'] == $job['experience']){echo "selected";}?>><?php echo $experience['name']?></option>    
                        <?php endforeach;?>
                </select>
                <i></i>
            </label>
        </section>
    
        <section class="col col-4">
            <label class="label">Ngày bắt đầu: </label>
            <label class="input">
                    <i class="icon-append fa fa-calendar"></i>
                    <input type="text" name="employer-start-date" id="datePicker" placeholder="Start date" value="<?php echo date('Y-m-d',$job['date_available'])?>" required>
            </label>
        </section>

        <section class="col col-3">
            <label class="label">Địa điểm làm việc*: </label>
            <label class="input"><input type="text" name="job_location" required value="<?php echo $job['work_location']?>"></label>
        </section>
            
        <section class="col col-3">
            <label class="label">Người liên hệ*: </label>
            <label class="input"><input type="text" name="contact_person" required value="<?php echo $job['contact_person']?>"></label>
        </section>
            
        <section class="col col-3">
            <label class="label">Số Điện thoại liên hệ*: </label>
            <label class="input"><input type="text" name="contact_person_phone" required value="<?php echo $job['contact_person_phone']?>"></label>
        </section>
            
        <section class="col col-3">
            <label class="label">Email liên hệ*: </label>
            <label class="input"><input type="text" name="contact_person_email" required value="<?php echo $job['contact_person_email']?>"></label>
        </section>
            
        <!--GOOGLE MAPS-->
        <section class="col col-12">
            <?php require_once('../../vieclambanthoigian.com.vn/extensions/include/google_maps.php');?>
        </section>
        
    </fieldset>
    
    <footer>
            <button type="submit" name="submit" class="button">Lưu</button>
            <button type="button" class="button button-secondary" onclick="window.history.back();">Quay lại trang trước</button>
    </footer>
    
    
</form>
