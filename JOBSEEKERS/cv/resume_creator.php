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
$skills = $db->get('skills');

//Get the current jobseeker data
$jobseeker_data = $db->where('username', "$AuthUserName")->getOne('jobseeker_resumes');
    
//Get the jobseeker profile data
$jobseeker_profile = $commonQueries->getJobseeker_profile($AuthUserName);
$jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
$jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);

if(isset($_POST["ProceedSaveResume"])){
        
    if($database->SQLCount("jobseeker_resumes","WHERE username='".$AuthUserName."'") == 0){
        $database->SQLInsert("jobseeker_resumes",array("username"),array($AuthUserName));
    }

    //Locations update
    if(isset($_POST['preferred_locations'])){
        //If user records exists, delete them
        
        $db->where('jobseeker_id', $jobseeker_profile['jobseeker_id'])->withTotalCount()->get('jobseeker_locations');
        if($db->totalCount !== "0"){            
            $db->where('jobseeker_id', $jobseeker_profile['jobseeker_id']);
            if($db->delete('jobseeker_locations')){
                echo 'successfully deleted';
            } else {
                echo "There was a problem when deleting locations";die;
            }            
        }

        //Insert records
        foreach ($_POST['preferred_locations'] as $preferred_location) {
            $location_data = array(
                'location_id'   => $preferred_location,
                'jobseeker_id'  => $jobseeker_profile['jobseeker_id']
            );

            $id = $db->insert ('jobseeker_locations', $location_data);
            if($id){
                echo 'records were created. Id=' . $id;
            } else {
                echo 'problem while creating records';die;
            }
        }   
    }


    //categories update
    if(isset($_POST['preferred_categories'])){
        //If user records exists, delete them
        $db->where('jobseeker_id', $jobseeker_profile['jobseeker_id'])->withTotalCount()->get('jobseeker_categories');
        if($db->totalCount !== "0"){
            $db->where('jobseeker_id', $jobseeker_profile['jobseeker_id']);
            if($db->delete('jobseeker_categories')){
                echo 'successfully deleted';
            } else { //Problems when deleting
                echo "There was a problem when deleting categories";die;
            }            
        }

        //Insert again
        foreach ($_POST['preferred_categories'] as $preferred_category) {
            $category_data = array(
                'category_id'   => $preferred_category,
                'jobseeker_id'  => $jobseeker_profile['jobseeker_id']
            );

            $id = $db->insert ('jobseeker_categories', $category_data);
            if($id){
                echo 'records were created. Id=' . $id;
            } else {
                echo 'problem while creating records';die;
            }
        }
    }

    $data = array (
        "title"                 => filter_input(INPUT_POST,'js-title', FILTER_SANITIZE_STRING),
        "name_current_position" => filter_input(INPUT_POST,'js-nameCP', FILTER_SANITIZE_STRING),
        "current_position"      => filter_input(INPUT_POST,'js-current-position', FILTER_SANITIZE_NUMBER_INT), 
        "salary"                => filter_input(INPUT_POST,'js-salary', FILTER_SANITIZE_NUMBER_INT), 
        "expected_position"     => filter_input(INPUT_POST,'js-expected-position', FILTER_SANITIZE_NUMBER_INT), 
        "expected_salary"       => filter_input(INPUT_POST,'js-expected-salary', FILTER_SANITIZE_NUMBER_INT), 
        "job_category"          => filter_input(INPUT_POST,'js-category', FILTER_SANITIZE_NUMBER_INT), 
        "location"              => filter_input(INPUT_POST,'js-location', FILTER_SANITIZE_NUMBER_INT), 
        "education_level"       => filter_input(INPUT_POST,'education_level', FILTER_SANITIZE_NUMBER_INT),
        "job_type"              => filter_input(INPUT_POST,'js-jobType', FILTER_SANITIZE_NUMBER_INT),
        "career_objective"      => filter_input(INPUT_POST,'js-careerObjective', FILTER_SANITIZE_STRING),
        "facebook_URL"          => filter_input(INPUT_POST,'js-facebookURL', FILTER_SANITIZE_STRING),
        "experiences"           => filter_input(INPUT_POST,'js-experience', FILTER_SANITIZE_STRING),
        "skills"                => filter_input(INPUT_POST,'skills', FILTER_SANITIZE_STRING),
        "language"              => filter_input(INPUT_POST,'js-language', FILTER_SANITIZE_NUMBER_INT),
        "language_level"        => filter_input(INPUT_POST,'js-languageLevel', FILTER_SANITIZE_NUMBER_INT),
        "language_1"            => filter_input(INPUT_POST,'js-language1', FILTER_SANITIZE_NUMBER_INT),
        "language_1_level"      => filter_input(INPUT_POST,'js-languageLevel1', FILTER_SANITIZE_NUMBER_INT),
        "language_2"            => filter_input(INPUT_POST,'js-language2', FILTER_SANITIZE_NUMBER_INT),
        "language_2_level"      => filter_input(INPUT_POST,'js-languageLevel2', FILTER_SANITIZE_NUMBER_INT),
        "IT_skills"             => filter_input(INPUT_POST,'js-IT_skill', FILTER_SANITIZE_NUMBER_INT),
        "group_skills"          => filter_input(INPUT_POST,'js-group_skill', FILTER_SANITIZE_NUMBER_INT),
        "pressure_skill"        => filter_input(INPUT_POST,'js-pressure_skill', FILTER_SANITIZE_NUMBER_INT),
        "date_updated"          => strtotime(date("Y-m-d H:i:s"))
    );

    $db->where ('username', "$AuthUserName");
    if ($db->update ('jobseeker_resumes', $data)){
        $commonQueries->flash('message',$commonQueries->messageStyle('info',"Cập nhật thành công"));
        $website->redirect("index.php?category=cv&action=resume_creator");
    } else {
        echo 'update failed: ' . $db->getLastError();die;
    }
} 
?>
<style>
    .navmenu a {
        float: right;
    }
</style>

<nav class="row">
    <section class="col-md-9"><h4><?php $commonQueries->flash('message');?></h4></section>
    <section class="col-md-3 navmenu">
        <?php echo LinkTile("profile","current",$M_GO_BACK,"","red");?>                                                    
    </section>
</nav>        
    
<!--SonDang modify here-->
<div class="row">
    <form action="index.php" method="post" class="col-md-12" id="myform">
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
                <div class="row jobseeker-title">
                    <section class="col-md-6 col-sm-6 col-xs-12 js-forms">
                        <label>
                            <span><b>Tiêu đề hồ sơ (*): </b></span>
                            <input type="text" name="js-title" title="" value="<?php echo $jobseeker_data['title']?>" required>
                            <p><label>Ví dụ: Giám đốc kinh doanh, nhân viên marketing, nhân viên bán hàng v.v...</label></p>
                        </label>
                    </section>
                </div>
                    
                <div class="row jobseeker-mainTitle">
                    <div class="col-md-6 col-sm-6 col-xs-12 js-forms">
                        
                        <!--CURRENT POSITION-->
                        <label>
                            <span><b><?php echo $M_CURRENT_POSITION;?></b></span>                            
                            <select name="js-current-position" required>
                                <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                                <?php foreach ($positions as $value) :?>
                                <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data['current_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                            
                        <!--CURRENT SALARY-->
                        <label>
                            <span><b><?php echo $M_SALARY;?></b></span>						
                            <select name="js-salary" required>
                                <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($salaries as $value) :?>
                                <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data['salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                            <?php endforeach;?>
                            </select>            
                        </label>
                        
                        <!--EXPECTED POSITION-->
                        <label>
                            <span><b><?php echo $M_NAME_EXPECTED_POSITION;?></b></span>                                               
                            <select name="js-expected-position">
                                <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($positions as $value) :?>
                                <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data['expected_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                            <?php endforeach;?>
                            </select>
                        </label>
                        
                        <!--EXPECTED SALARY-->
                        <label>
                            <span><b><?php echo $M_NAME_EXPECTED_SALARY;?></b></span>						
                            <select name="js-expected-salary" required>
                                <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($salaries as $value) :?>
                                <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data['expected_salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                            <?php endforeach;?>
                            </select>
                        </label>
                        
                        <!--BROWSE CATEGORY-->
                        <label>
                            <span><b><?php echo $M_BROWSE_CATEGORY;?></b></span>						
                            <select name="js-category">
                        <?php foreach ($categories as $value) :?>
                                <option value="<?php echo $value['category_id']?>" <?php if($value['category_id'] == $jobseeker_data['job_category']) {echo "selected";}?>><?php echo $value['category_name_vi']?></option>
                        <?php endforeach;?>
                            </select>
                        </label>
                            
                        <!--DESIRE WORK LOCATION-->
                        <label>
                            <span><b><?php echo $WORK_LOCATION;?></b></span>						
                            <select name="js-location">
                        <?php foreach ($locations as $value) :?>
                                <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data['location']) {echo "selected";}?>><?php echo $value['City']?></option>
                        <?php endforeach;?>
                            </select>
                        </label>
                        
                        <!--EDUCATION-->
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
                    
                    <!--Language forms-->
                    <div class="col-md-6 col-sm-6 col-xs-12 js-forms">
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
                    </div>
                </div>
                <!--Language forms-->
                
                <!--CAREER OBJECTIVE-->
                <div class="jobseeker-messageArea" name="js-careerObjective" class="jobseeker-messageArea" rows="5" style="width: 100%">
                    <div class="jobseeker-title"><h4><?php echo $M_CAREER_OBJECTIVE;?></h4></div>
                    <p><label>Gợi ý: Mục tiêu ngắn hạn của bạn trong một vài năm tới, Mục tiêu dài hạn trong 5-10 năm tới</label></p>
                    <p><label>Ví dụ: </label></p>
                    <p>- Mong muốn được làm việc trong môi trường năng động, chuyên nghiệp v.v...</p>
                    <p>- Công ty tạo điều kiện tốt cho việc học tập nâng cao trình độ v.v...</p>
                    <p>- Công việc ổn định lâu dài, có cơ hội thăng tiến cao v.v...</p>
                    <p>- Mong muốn được đóng góp cho công ty </p>
                    <textarea name="js-careerObjective" id="jobseeker-experience" rows="5" style="width: 100%"><?php echo $jobseeker_data['career_objective'];?></textarea>
                </div>
                    
                <!--EXPERIENCE-->
                <div class="jobseeker-messageArea" rows="5" style="width: 100%">
                    <div class="jobseeker-title">
                        <h4><?php echo $M_EXPERIENCE;?></h4>
                        <p><label> Liệt kê các kinh nghiệm công việc từ thời gian gần nhất trở về trước.</label></p>
                        <p><label> Kinh nghiệm có thể trong công việc hoặc các hoạt động đoàn thể.</label></p>
                    </div>
                    <textarea name="js-experience" id="jobseeker-experience" rows="5" style="width: 100%"><?php echo $jobseeker_data['experiences'];?></textarea>
                </div>
                    
                <!--SKILLS-->
                <div class="jobseeker-messageArea" style="width:100%">
                    <div class="jobseeker-title"><h4><?php echo $M_YOUR_SKILLS;?></h4></div>
                    <p><label>Gợi ý: Liệt kê những kỹ năng, khả năng nổi bật của bạn</label></p>
                    <p><label>Ví dụ: </label></p>
                    <p>- Kỹ năng liên quan chuyên môn </p>
                    <p>- Kỹ năng về ngoại ngữ: tiếng Anh, Pháp, Trung v.v... </p>
                    <p>- Kỹ năng về tin học: tin học văn phòng, ngôn ngữ lập trình, quản trị điều hành mạng v.v... </p>
                    <p>- Kỹ năng giao tiếp, thuyết phục khách hàng v.v... </p>
                    <p>- Khả năng nắm bắt công việc, làm việc theo nhóm, nghiên cứu tài liệu v.v... </p>
                    <p>- Khả năng hòa nhập, thích nghi với môi trường, khả năng tư duy, thuyết trình v.v... </p>
                    <p>- Mô tả phải có ít nhất 50 ký tự trở lên</p>
                    <textarea name="skills" style="width:100%" rows="5" ><?php echo $jobseeker_data['skills'];?></textarea>
                </div>
                <!--<strong><i><?php echo $LIST_ATTACHED;?>:</i></strong>-->                  
                    
                <!--IT SKILLS-->
                <div class="row">
                    <label class="col-md-8 js-forms">
                        <span><b>Tin học văn phòng : </b></span>
                        <section class="js-radioBoxes">
                            <?php foreach ($skills as $skill) :?>
                            <span><input type="radio" name="js-IT_skill" value="<?php echo $skill['skill_id']?>" required <?php if($skill['skill_id'] == $jobseeker_data['IT_skills']){echo "checked";}?>><?php echo $skill['name']?></span>
                            <?php endforeach;?>
                        </section>
                    </label>
                </div>
                    
                <!--GROUP SKILL-->
                <section class="row">
                    <label class="col-md-8 js-forms">
                        <span><b>Khả năng làm việc nhóm: </b></span>
                        <section class="js-radioBoxes">
                            <?php foreach ($skills as $skill) :?>
                            <span><input type="radio" name="js-group_skill" value="<?php echo $skill['skill_id']?>" required <?php if($skill['skill_id'] == $jobseeker_data['group_skills']){echo "checked";}?>><?php echo $skill['name']?></span>
                            <?php endforeach;?>
                        </section>
                    </label>
                </section>
                
                <!--PRESSURE SKILL-->
                <section class="row">
                    <label class="col-md-8 js-forms">
                        <span><b>Khả năng chịu áp lực: </b></span>
                        <section class="js-radioBoxes">
                            <?php foreach ($skills as $skill) :?>
                            <span><input type="radio" name="js-pressure_skill" value="<?php echo $skill['skill_id']?>" required <?php if($skill['skill_id'] == $jobseeker_data['pressure_skill']){echo "checked";}?>><?php echo $skill['name']?></span>
                            <?php endforeach;?>
                        </section>
                    </label>
                </section>
                    
                <!--DESIRED LOCATIONS-->
                <div class="row top-bottom-margin desires-section">
                    <span class="col-md-3"><h5>Nơi làm việc mong muốn</h5></span>
                    <section class="col-md-4 ListDesires-section">
                <?php foreach ($jobseeker_locations as $jobseeker_location) :?>
                        <span class="desire-tag"><?php echo $jobseeker_location['City'] ?>,</span>
                <?php endforeach;?>
                    </section>
                    <section class="col-md-5 select2-searchBox">
                        <select name="preferred_locations[]" id="preferred_locations" multiple="multiple">
                    <?php foreach ($locations as $location) :?>
                            <option value="<?php echo $location['id']?>"><?php echo $location['City']?></option>
                    <?php endforeach; ?>
                        </select>
                    </section>
                </div>
                <!--DESIRED LOCATIONS-->
                    
                <!--DESIRED CATEGORIES-->
                <div class="row top-bottom-margin desires-section">
                    <span class="col-md-3"><h5>Ngành nghề mong muốn</h5></span>
                    <section class="col-md-4 ListDesires-section">
                <?php foreach ($jobseeker_categories as $jobseeker_category) :?>
                        <span class="desire-tag"><?php echo $jobseeker_category['category_name_vi'] ?>,</span>
                <?php endforeach;?>
                    </section>
                    <section class="col-md-5 select2-searchBox">
                        <select name="preferred_categories[]" id="preferred_categories" multiple="multiple">
                    <?php foreach ($categories as $value) :?>
                            <option value="<?php echo $value['id']?>"><?php echo $value['category_name_vi']?></option>
                    <?php endforeach; ?>
                        </select>
                    </section>
                </div>
                <!--DESIRED CATEGORIES-->
                 
                <!--FACEBOOK ADDRESS-->
                <section class="row">
                    <label class="col-md-8 js-forms">
                        <span><b><?php echo $M_FACEBOOK_URL;?>: </b></span>
                        <input type="text" name="js-facebookURL" value="<?php echo $jobseeker_data['facebook_URL'];?>">
                    </label>
                </section>
                    
                <!--JOBSEEKER ID-->
                <input type="hidden" name="jobseeker_id" value="<?php echo $jobseeker_profile['jobseeker_id']?>">
            </div>            
        </div>
        <input type="submit" value=" <?php echo $SAUVEGARDER;?> " class="btn btn-primary">
    </form>
</div>
<!--###SonDang modify here###-->