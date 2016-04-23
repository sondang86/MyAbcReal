<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
$positions = $db->get('positions');
$salaries = $db->get('salary');
$categories = $db->get('categories');
$education = $db->get('education');
$job_types = $db->get('job_types');
$locations = $db->get('locations');
$languages = $db->get('languages');
$language_levels = $db->get('language_levels');

$selected_columns = array(
    $DBprefix."jobseekers.id as jobseeker_id",$DBprefix."jobseekers.username",
    $DBprefix."jobseekers.first_name",$DBprefix."jobseekers.last_name",
    $DBprefix."jobseekers.address",$DBprefix."jobseekers.phone",
    $DBprefix."jobseekers.description",$DBprefix."jobseekers.profile_pic",
    $DBprefix."jobseekers.profile_description",
    $DBprefix."marital_status.name as marital_status_name",$DBprefix."marital_status.name_en as marital_status_name_en",
    $DBprefix."gender.name as gender_name",$DBprefix."gender.name_en as gender_name_en"
);

$db->join("marital_status", $DBprefix."jobseekers.marital_status=".$DBprefix."marital_status.marital_id", "LEFT");
$db->join("gender", $DBprefix."jobseekers.gender=".$DBprefix."gender.gender_id", "LEFT");
$jobseeker_profile = $db->where('username', "$AuthUserName")->getOne('jobseekers', $selected_columns);

//echo "<pre>";
//print_r($jobseeker_profile);
//echo "</pre>";

    $job_types = $db->get_data('job_types');
        
    if(isset($_POST["ProceedSaveResume"])){
        
        if($database->SQLCount("jobseeker_resumes","WHERE username='".$AuthUserName."'") == 0){
            $database->SQLInsert("jobseeker_resumes",array("username"),array($AuthUserName));
        }  
            
        $data = array (
            "name_current_position" => filter_input(INPUT_POST,'js-nameCP', FILTER_SANITIZE_STRING),
            "current_position" => filter_input(INPUT_POST,'js-current-position', FILTER_SANITIZE_NUMBER_INT), 
            "salary" => filter_input(INPUT_POST,'js-salary', FILTER_SANITIZE_NUMBER_INT), 
            "expected_position" => filter_input(INPUT_POST,'js-expected-position', FILTER_SANITIZE_NUMBER_INT), 
            "expected_salary" => filter_input(INPUT_POST,'js-expected-salary', FILTER_SANITIZE_NUMBER_INT), 
            "job_category" => filter_input(INPUT_POST,'js-category', FILTER_SANITIZE_NUMBER_INT), 
            "location" => filter_input(INPUT_POST,'js-location', FILTER_SANITIZE_NUMBER_INT), 
            "education_level" => filter_input(INPUT_POST,'education_level', FILTER_SANITIZE_NUMBER_INT),
            "job_type" => filter_input(INPUT_POST,'js-jobType', FILTER_SANITIZE_NUMBER_INT),
            "career_objective" => filter_input(INPUT_POST,'js-careerObjective', FILTER_SANITIZE_STRING),
            "facebook_URL" => filter_input(INPUT_POST,'js-facebookURL', FILTER_SANITIZE_STRING),
            "experiences" => filter_input(INPUT_POST,'js-experience', FILTER_SANITIZE_STRING),
            "skills" => filter_input(INPUT_POST,'skills', FILTER_SANITIZE_STRING),
            "language" => filter_input(INPUT_POST,'js-language', FILTER_SANITIZE_NUMBER_INT),
            "language_level" => filter_input(INPUT_POST,'js-languageLevel', FILTER_SANITIZE_NUMBER_INT),
            "language_1" => filter_input(INPUT_POST,'js-language1', FILTER_SANITIZE_NUMBER_INT),
            "language_1_level" => filter_input(INPUT_POST,'js-languageLevel1', FILTER_SANITIZE_NUMBER_INT),
            "language_2" => filter_input(INPUT_POST,'js-language2', FILTER_SANITIZE_NUMBER_INT),
            "language_2_level" => filter_input(INPUT_POST,'js-languageLevel2', FILTER_SANITIZE_NUMBER_INT),
        );
            
        $db->where ('username', "$AuthUserName");
        if ($db->update ('jobseeker_resumes', $data)){
            $commonQueries->flash('message',$commonQueries->messageStyle('info',"Cập nhật thành công"));
            $website->redirect("index.php?category=cv&action=resume_creator");
        } else {
            echo 'update failed: ' . $db->getLastError();die;
        }
    }
        
    $arrResume = $database->DataArray("jobseeker_resumes","username='".$AuthUserName."'");
        
    //Get the current jobseeker data
    $jobseeker_data = $db->where('username', "$AuthUserName")->getOne('jobseeker_resumes');
        
    ?>
<style>
    .navmenu a {
        float: right;
    }
</style>
<nav class="row">
    <section class="col-md-9"><h4><?php $commonQueries->flash('message');?></h4></section>
    <section class="col-md-3 navmenu">
        <?php echo LinkTile("cv","description",$M_GO_BACK,"","red");?>                                                    
    </section>
</nav>        
    
<!--SonDang modify here-->
<div class="row">
    <form action="index.php" method="post" class="col-md-12">
        <input type="hidden" name="ProceedSaveResume" value="1">
        <input type="hidden" name="action" value="<?php echo $action;?>">
        <input type="hidden" name="category" value="<?php echo $category;?>">
        <div class="jobseeker-cv">
            <section class="col-md-2 profilePic">
                <figure><img src="../images/jobseekers/profile_pic/<?php echo $jobseeker_profile['profile_pic'];?>" id="preview" alt="Ảnh cá nhân hiện tại" class="img-responsive"></figure>
            </section>
            <section class="col-md-10 JS_profile">
                <p><label>Họ và Tên: </label> <span><?php echo $jobseeker_profile['first_name']?> <?php echo $jobseeker_profile['last_name']?></span></p>
                <p><label>Địa chỉ: </label> <span><?php echo $jobseeker_profile['address']?></span></p>
                <p><label>Email: </label> <span><?php echo $jobseeker_profile['username']?></span></p>
                <p><label>Số điện thoại: </label> <span><?php echo $jobseeker_profile['phone']?></span></p>
                <p><label>Tình trạng hôn nhân: </label> <span><?php echo $jobseeker_profile['marital_status_name']?></span></p>
                <p><label>Giới tính: </label> <span><?php echo $jobseeker_profile['gender_name']?></span></p>
                <a href="index.php?category=profile&action=current">Sửa thông tin cá nhân</a>
            </section>
            <div class="col-md-12 jobseeker-main">
                <div class="row jobseeker-mainTitle">
                    <div class="col-md-6 col-sm-6 col-xs-12 js-forms">
                        <label>
                            <span><b><?php echo $M_CURRENT_POSITION;?></b></span>                            
                            <select name="js-current-position" required>
                                <option value="">Please select</option>
                                <?php foreach ($positions as $value) :?>
                                <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data['current_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                        <label>
                            <span><b><?php echo $M_SALARY;?></b></span>						
                            <select name="js-salary" required>
                                <option value="">Please select</option>
                            <?php foreach ($salaries as $value) :?>
                                <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data['salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                            <?php endforeach;?>
                            </select>            
                        </label>
                        <label>
                            <span><b><?php echo $M_NAME_EXPECTED_POSITION;?></b></span>                                               
                            <select name="js-expected-position">
                                <option value="">Please select</option>
                            <?php foreach ($positions as $value) :?>
                                <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data['expected_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                            <?php endforeach;?>
                            </select>
                        </label>
                        <label>
                            <span><b><?php echo $M_NAME_EXPECTED_SALARY;?></b></span>						
                            <select name="js-expected-salary" required>
                                <option value="">Please select</option>
                            <?php foreach ($salaries as $value) :?>
                                <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data['expected_salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                            <?php endforeach;?>
                            </select>
                        </label>
                        <label>
                            <span><b><?php echo $M_BROWSE_CATEGORY;?></b></span>						
                            <select name="js-category">
                        <?php foreach ($categories as $value) :?>
                                <option value="<?php echo $value['category_id']?>" <?php if($value['category_id'] == $jobseeker_data['job_category']) {echo "selected";}?>><?php echo $value['category_name_vi']?></option>
                        <?php endforeach;?>
                            </select>
                        </label>
                            
                        <label>
                            <span><b><?php echo $WORK_LOCATION;?></b></span>						
                            <select name="js-location">
                        <?php foreach ($locations as $value) :?>
                                <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data['location']) {echo "selected";}?>><?php echo $value['City']?></option>
                        <?php endforeach;?>
                            </select>
                        </label>                    
                        <label>
                            <span><b><?php echo $M_EDUCATION;?></b></span>						
                            <select name="education_level">
                                <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($education as $value) :?>
                                <option value="<?php echo $value['education_id']?>" <?php if($value['education_id'] == $jobseeker_data['education_level']) {echo "selected";}?>><?php echo $value['education_name']?></option>
                            <?php endforeach;?>
                            </select>
                        </label>
                            
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 js-forms">
                        
                        <!--Language forms-->
                        <label>
                            <span><b><?php echo $M_JOB_TYPE;?></b></span>						
                            <select name="js-jobType">                            
                        <?php foreach ($job_types as $value) :?>
                                <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data['job_type']) {echo "selected";}?>><?php echo $value['job_name']?></option>
                        <?php endforeach;?>
                            </select>
                        </label>
                        <label>
                            <span><label for="js-Language"><?php echo $M_FOREIGN_LANGUAGE;?>: </label></span>
                            <select name="js-language" required>
                                <option value="">Select language</option>
                        <?php foreach ($languages as $value) :?>
                                <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data['language']) {echo "selected";}?>><?php echo $value['name']?></option>
                        <?php endforeach;?>
                            </select>
                            <span></span>
                            <select name="js-languageLevel" required>
                                <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($language_levels as $value) :?>
                                <option value="<?php echo $value['level']?>" <?php if($value['level'] == $jobseeker_data['language_level']) {echo "selected";}?>><?php echo $value['level_name']?></option>
                            <?php endforeach;?>
                            </select>
                        </label>
                        <label>
                            <span><label for="js-Language"><?php echo $M_FOREIGN_LANGUAGE;?>: </label></span>
                            <select name="js-language1">
                                <option value="">Select language</option>
                        <?php foreach ($languages as $value) :?>
                                <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data['language_1']) {echo "selected";}?>><?php echo $value['name']?></option>
                        <?php endforeach;?>
                            </select>
                            <span></span>
                            <select name="js-languageLevel1">
                                <option value="">Select level</option>
                                <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($language_levels as $value) :?>
                                <option value="<?php echo $value['level']?>" <?php if($value['level'] == $jobseeker_data['language_1_level']) {echo "selected";}?>><?php echo $value['level_name']?></option>
                            <?php endforeach;?>
                            </select>
                        </label>
                        <label>
                            <span><label for="js-Language"><?php echo $M_FOREIGN_LANGUAGE;?>: </label></span>
                            <select name="js-language2">
                                <option value="">Select language</option>
                        <?php foreach ($languages as $value) :?>
                                <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data['language_2']) {echo "selected";}?>><?php echo $value['name']?></option>
                        <?php endforeach;?>
                            </select>
                            <span></span>
                            <select name="js-languageLevel2">
                                <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($language_levels as $value) :?>
                                <option value="<?php echo $value['level']?>" <?php if($value['level'] == $jobseeker_data['language_2_level']) {echo "selected";}?>><?php echo $value['level_name']?></option>
                            <?php endforeach;?>
                            </select>
                        </label>
                        <!--Language forms-->
                            
                    </div>
                </div>
                <div class="jobseeker-messageArea" name="js-careerObjective" class="jobseeker-messageArea" rows="5" style="width: 100%">
                    <div class="jobseeker-title"><h4><?php echo $M_CAREER_OBJECTIVE;?></h4></div>
                    <p><label>Gợi ý: Mục tiêu ngắn hạn của bạn trong một vài năm tới, Mục tiêu dài hạn trong 5-10 năm tới</label></p>
                    <textarea name="js-careerObjective" id="jobseeker-experience" rows="5" style="width: 100%"><?php echo $jobseeker_data['career_objective'];?></textarea>
                </div>
                    
                <div class="jobseeker-messageArea" rows="5" style="width: 100%">
                    <div class="jobseeker-title">
                        <h4><?php echo $M_EXPERIENCE;?></h4>
                        <p><label> Liệt kê các kinh nghiệm công việc từ thời gian gần nhất trở về trước.</label></p>
                        <p><label> Kinh nghiệm có thể trong công việc hoặc các hoạt động đoàn thể.</label></p>
                    </div>
                    <textarea name="js-experience" id="jobseeker-experience" rows="5" style="width: 100%"><?php echo $jobseeker_data['experiences'];?></textarea>
                </div>
                <br><br>
                    
                <div class="jobseeker-messageArea" style="width:100%">
                    <div class="jobseeker-title"><h4><?php echo $M_YOUR_SKILLS;?></h4></div>
                    <p><label>Gợi ý: Liệt kê những kỹ năng, khả năng nổi bật của bạn</label></p>
                    <textarea name="skills" style="width:100%" rows="5" ><?php echo $jobseeker_data['skills'];?></textarea>
                </div>
                <!--<strong><i><?php echo $LIST_ATTACHED;?>:</i></strong>-->  
                
                <label class="col-md-6 js-forms">
                    <span><b><?php echo $M_FACEBOOK_URL;?>: </b></span>
                    <input type="text" name="js-facebookURL" value="<?php echo $jobseeker_data['facebook_URL'];?>">
                </label>
                    
            </div>            
        </div>
        <input type="submit" value=" <?php echo $SAUVEGARDER;?> " class="btn btn-primary">
    </form>
</div>
<!--###SonDang modify here###-->