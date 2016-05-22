<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries,$categories, $job_types, $locations, $salaries, $experience_list,$jobs_by_employerId;
?>
<?php
$id=$_REQUEST["id"];
$website->ms_i($id);
if($database->SQLCount("jobs","WHERE employer='".$AuthUserName."' AND id=".$id) == 0){
    die("404 Not found");
}
$job = $db->where ("id", "$id")->getOne("jobs");
 
?>

<?php //Edit data
    if(isset($_POST['submit'])){
        //Insert data to database
        $data = Array( 
            "employer" => "$AuthUserName",
            "job_category" => filter_input(INPUT_POST, 'post-category'),
            "job_type" => filter_input(INPUT_POST, 'post-jobtypes'),
            "title" => filter_input(INPUT_POST, 'employer-post-title', FILTER_SANITIZE_STRING), 
            "message" => filter_input(INPUT_POST,'employer-post-details', FILTER_SANITIZE_STRING),
            "requires_description"  => filter_input(INPUT_POST, 'employer-post-requires',FILTER_SANITIZE_STRING), 
            "benefits_description"  => filter_input(INPUT_POST,'employer-post-benefits',FILTER_SANITIZE_STRING),
            "profileCV_description" => filter_input(INPUT_POST,'employer-post-profileCV',FILTER_SANITIZE_STRING),
            "experience" => filter_input(INPUT_POST,'experience',FILTER_SANITIZE_NUMBER_INT),
            "region" => filter_input(INPUT_POST,'post-locations'),
            "salary" => filter_input(INPUT_POST,'post-salary'),
            "date_available" => strtotime(filter_input(INPUT_POST,'employer-start-date')),
            "date_updated" => time(),
            "expires" => (strtotime(filter_input(INPUT_POST,'employer-start-date')) + 30*86400), //30 days limitation
            "status" => filter_input(INPUT_POST,'post-active'),
            "SEO_title" => $db->secure_input($website->seoURL($website->stripVN(filter_input(INPUT_POST,'employer-post-title'))))
         );
    
    
    $db->where('id', filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
    $id = $db->update('jobs', $data);
    if($id){
        $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Lưu thay đổi thành công') );
        $website->redirect($_SERVER['REQUEST_URI']);
    } else {
        $message = "there were an error occurred";
        die;
    }
}
?>


<h3><?php echo $MODIFY_SELECTED_ADD;?></h3>
<h4><?php $commonQueries->flash('message');?></h4>

<div class="row">
    <div class="col-md-3 col-md-push-9">  
        <div class="row top-bottom-margin">
            <?php echo LinkTile
                     (
                            "application_management",
                            "list&Proceed=1&id=".$id,
                            $M_APPLICATIONS." (".$database->SQLCount("apply","WHERE posting_id=".$id).")",
                            "",
                            "yellow"
                     );?>
        </div>                         
        <div class="row top-bottom-margin">
            <?php echo LinkTile
             (
                    "jobs",
                    "my",
                    $M_GO_BACK,
                    "",
                    "red"
             );?>
        </div>
    </div>
    <form action="index.php?category=jobs&folder=my&page=edit&id=<?php echo $id?>" method="POST">
        <div class="col-md-9 col-md-pull-3">
            <div class="employer-post-form">
                
                <label>
                    <span>Tiêu đề</span>
                    <input type="text" name="employer-post-title" value="<?php echo $job['title']?>">
                </label>
                
                <label>
                    <span>Chi tiết</span>
                    <aside><textarea type="text" name="employer-post-details" required><?php echo $job['message']?></textarea></aside>
                </label>
                
                <label>
                    <span>Yêu cầu công việc: </span>
                    <aside><textarea type="text" name="employer-post-requires" required><?php echo $job['requires_description']?></textarea></aside>
                </label>
                
                <label>
                    <span>Các quyền lợi được hưởng: </span>
                    <aside><textarea type="text" name="employer-post-benefits" required><?php echo $job['benefits_description']?></textarea></aside>
                </label>
                
                <label>
                    <span>Yêu cầu hồ sơ: </span>
                    <aside><textarea type="text" name="employer-post-profileCV" required><?php echo $job['profileCV_description']?></textarea></aside>
                </label>
                
                <label>
                    <span>Ngành: </span>
                    <select name="post-category" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($categories as $value) :?>
                        <option value="<?php echo $value['category_id']?>" <?php if($job['job_category'] == $value['category_id']){ echo "selected";}?>><?php echo $value['category_name_vi']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Job types-->
                <label>
                    <span>Loại công việc: </span>
                    <select name="post-jobtypes" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($job_types as $value) :?>
                        <option value="<?php echo $value['id']?>" <?php if($job['job_type'] == $value['id']){ echo "selected";}?>><?php echo $value['job_name']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Location-->
                <label>
                    <span>Địa điểm: </span>
                    <select name="post-locations" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($locations as $value) :?>
                        <option value="<?php echo $value['id']?>" <?php if($job['region'] == $value['id']){ echo "selected";}?>><?php echo $value['City']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Salary-->
                <label>
                    <span>Lương: </span>
                    <select name="post-salary" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($salaries as $value) :?>
                        <option value="<?php echo $value['salary_id']?>" <?php if($job['salary'] == $value['salary_id']){ echo "selected";}?>><?php echo $value['salary_range']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Experience-->
                <label>
                    <span>Yêu cầu kinh nghiệm: </span>
                    <select name="experience" required>
                        <option value="">Vui lòng chọn</option>
                        <?php foreach ($experience_list as $experience) :?>
                        <option value="<?php echo $experience['experience_id']?>" <?php if($job['experience'] == $experience['experience_id']){ echo "selected";}?>><?php echo $experience['name']?></option>    
                        <?php endforeach;?>
                    </select>
                </label>
                
                <!--Date start-->
                <label>
                    <span>Ngày bắt đầu: </span>
                    <input type="text" name="employer-start-date" id="datePicker" value="<?php echo date('Y-m-d',$job['date'])?>">
                </label>
                
                <!--Active or not?-->
                <label>
                    <span>Đang hoạt động : </span>
                    <select name="post-active">
                        <option value="1" <?php if($job['status'] == 1){echo "selected";} ?>>Có</option>
                        <option value="0" <?php if($job['status'] == 0){echo "selected";} ?>>Không</option>
                    </select>
                </label>
            </div>
            <ul class="inline-buttons">
                <li><input type="submit" name="submit" value="Lưu"></li>
            </ul>
        </div>
</div>
</form>
</div>    
<br/>