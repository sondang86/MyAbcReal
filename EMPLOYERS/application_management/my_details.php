<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
$data_sanitize = new GUMP;
$GET = $data_sanitize->sanitize($_GET);
    
$posting_id=$_REQUEST["posting_id"];
$website->ms_i($posting_id);
$arrPosting = $db->where('employer', "$AuthUserName")->getOne('jobs');    
if(!isset($arrPosting["id"])) {
    die("");
};
$apply_id=$_REQUEST["apply_id"];
$website->ms_i($apply_id);
$arrPostingApply = $database->DataArray("apply","id=".$apply_id);
    
if($arrPostingApply["posting_id"]!=$posting_id) die("");
    
$id = $arrPostingApply["jobseeker"];

       
//Get the current jobseeker data
$jobseeker_data = $db->where('username', "$id")->getOne("jobseeker_resumes");


$jobseeker_resume_columns = array(
    $DBprefix."jobseeker_resumes.id as resume_id",$DBprefix."jobseeker_resumes.username",
    $DBprefix."jobseeker_resumes.skills",$DBprefix."jobseeker_resumes.username",
    $DBprefix."jobseeker_resumes.career_objective",$DBprefix."jobseeker_resumes.facebook_URL",
    $DBprefix."jobseeker_resumes.experiences",$DBprefix."jobseeker_resumes.name_current_position",    
    $DBprefix."job_experience.name as job_experience_name",$DBprefix."job_experience.name_en as job_experience_name_en",
    $DBprefix."education.education_name",$DBprefix."education.education_name_en",
    $DBprefix."languages.name as language_name",
    $DBprefix."language_levels.level_name as language_level_name",
    $DBprefix."salary.salary_range as current_salary",$DBprefix."salary.salary_range_en current_salary_en",
    $DBprefix."categories.category_name as desired_category",$DBprefix."categories.category_name_vi as desired_category_vi",
    $DBprefix."locations.City as desired_city",$DBprefix."locations.City_en as desired_city_en",
    $DBprefix."job_types.job_name",$DBprefix."job_types.job_name_en",
    $DBprefix."positions.position_name as current_position_name",$DBprefix."positions.position_name_en as current_position_name_en",
    "expected_position.position_name as expected_position_name",
    "expected_position.position_name_en as expected_position_name_en",
    "expected_salary.salary_range as expected_salary_range",
    "expected_salary.salary_range_en as expected_salary_range_en",
    "IT_skill.name as IT_skill_name","IT_skill.name_en as IT_skill_name_en",
    "group_skill.name as group_skill_name","group_skill.name_en as group_skill_name_en",
    "pressure_skill.name as pressure_skill_name","pressure_skill.name_en as pressure_skill_name_en",
);

$db->join("job_experience", $DBprefix."jobseeker_resumes.experience_level=".$DBprefix."job_experience.experience_id", "LEFT");
$db->join("education", $DBprefix."jobseeker_resumes.education_level=".$DBprefix."education.education_id", "LEFT");
$db->join("languages", $DBprefix."jobseeker_resumes.language=".$DBprefix."languages.id", "LEFT");
$db->join("language_levels", $DBprefix."jobseeker_resumes.language_level=".$DBprefix."language_levels.level", "LEFT");
$db->join("salary", $DBprefix."jobseeker_resumes.salary=".$DBprefix."salary.salary_id", "LEFT");
$db->join("categories", $DBprefix."jobseeker_resumes.job_category=".$DBprefix."categories.category_id", "LEFT");
$db->join("locations", $DBprefix."jobseeker_resumes.location=".$DBprefix."locations.id", "LEFT");
$db->join("job_types", $DBprefix."jobseeker_resumes.job_type=".$DBprefix."job_types.id", "LEFT");
$db->join("positions", $DBprefix."jobseeker_resumes.current_position=".$DBprefix."positions.position_id", "LEFT");
$db->join("positions as expected_position", $DBprefix."jobseeker_resumes.expected_position=expected_position.position_id", "LEFT");
$db->join("salary as expected_salary", $DBprefix."jobseeker_resumes.expected_salary=expected_salary.salary_id", "LEFT");
$db->join("skills as IT_skill", $DBprefix."jobseeker_resumes.IT_skills=IT_skill.skill_id", "LEFT");
$db->join("skills as group_skill", $DBprefix."jobseeker_resumes.group_skills=group_skill.skill_id", "LEFT");
$db->join("skills as pressure_skill", $DBprefix."jobseeker_resumes.pressure_skill=pressure_skill.skill_id", "LEFT");

$db->where ($DBprefix."jobseeker_resumes.username", "$id");                
$jobseeker_resume = $db->getOne("jobseeker_resumes", $jobseeker_resume_columns);
$jobseeker_profile = $commonQueries->getJobseeker_profile($jobseeker_resume['username']);
$jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
$jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);

//echo '<pre>';
//print_r($jobseeker_resume);
//echo "</pre>";

$questionnaire_answers_columns = array(
    $DBprefix."questionnaire_answers.questionnaire_id as QA_questionnaire_id",
    $DBprefix."questionnaire_answers.questionnaire_question_id",
    $DBprefix."questionnaire_answers.short_answer",$DBprefix."questionnaire_answers.job_id",
    $DBprefix."questionnaire.question",$DBprefix."questionnaire.question_type",
    $DBprefix."questionnaire.employer",
    $DBprefix."questionnaire_questions.question_ask as question_answered",$DBprefix."questionnaire.question_type",
);

$db->join("questionnaire", $DBprefix."questionnaire_answers.questionnaire_id=".$DBprefix."questionnaire.id", "LEFT");
$db->join("questionnaire_questions", $DBprefix."questionnaire_answers.questionnaire_question_id=".$DBprefix."questionnaire_questions.id", "LEFT");
$db->where ($DBprefix."questionnaire_answers.user", $jobseeker_data['username']);                
$db->where ($DBprefix."questionnaire_answers.job_id", $posting_id);               
$answers = $db->withTotalCount()->get("questionnaire_answers", NULL, $questionnaire_answers_columns);
?>
<div class="row">
    <section class="col-md-9 col-sm-6 col-xs-12">
        <h4><label>Chi tiết hồ sơ ứng viên: <?php echo $id;?></label></h4>
    </section>
    <div class="col-md-3 col-sm-6 col-xs-12 fright">
        <?php echo LinkTile("","",$M_GO_BACK,"","red","small","true","window.history.back");?>
    </div>
</div>

<div class="row">
    <div class="col-md-12"> 
    <?php if(trim($arrPostingApply["message"])!=""):?>
        <section class="jobseeker-title">
            <h4>
                <strong><i><?php echo $MESSAGE_SENT_JS;?></i></strong>
            </h4>
        </section>
        <article class="jobseeker-message">
            <?php echo stripslashes($arrPostingApply["message"]); ?>
        </article>
    <?php endif; ?>
    </div>
</div>

<!--Questionnaire-->

<?php if($db->totalCount > 0):?>
<div class="row questionnaire">
    <section class="form-group-title">
        <label for="maxOfInterval" class="control-label">Câu hỏi: </label>
    </section>
    <section class="questionnaire-form">
    <?php foreach ($answers as $answer) :?>
        <article>
            <div class="form-group">
                <label for="maxOfInterval" class="control-label"><?php echo $answer['question']?></label>
                <aside>
                    <p>Trả lời:</p>
                    <?php if($answer['question_answered'] == ""){
                            echo $answer['short_answer'];                        
                        } else {
                            echo $answer['question_answered'];
                        }
                    ?>
                </aside>
            </div>
        </article>
    <?php endforeach;?>
    </section>
</div>
<?php endif;?>

<div class="row">
    <h4 class="col-md-12"><label></label></h4>
</div>
<!--JOBSEEKER CV-->
<div class="jobseeker-cv">    
    <div class="jobseeker-main">
        <!--        <h4>Thông tin ứng viên: </h4>-->
        <div class="row">
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
            </section>
        </div>
        <!--MAIN-->
        <div class="row jobseeker-messageArea">
            <div class="col-md-6 col-sm-6 col-xs-12 cv-details">
                <label>
                    <span><b><?php echo $M_CURRENT_POSITION;?></b></span>
                    <aside><?php echo $jobseeker_resume['current_position_name']?></aside>
                </label>
                
                <label>
                    <span><b><?php echo $M_SALARY;?></b></span>						
                    <aside><?php echo $jobseeker_resume['current_salary']?> </aside>
                </label>
                
                <label>
                    <span><b><?php echo $M_NAME_EXPECTED_POSITION;?></b></span>                                               
                    <aside><?php echo $jobseeker_resume['expected_position_name']?> </aside>
                </label>
                <label>
                    <span><b><?php echo $M_NAME_EXPECTED_SALARY;?></b></span>						
                    <aside><?php echo $jobseeker_resume['expected_salary_range']?> </aside>
                </label>
                
                <label>
                    <span><b>Tin học văn phòng: </b></span>
                    <aside>
                        <?php echo $jobseeker_resume['IT_skill_name']?>
                    </aside>
                </label>
                
                <label>
                    <span><b>Khả năng làm việc nhóm: </b></span>
                    <aside>
                        <?php echo $jobseeker_resume['group_skill_name']?>
                    </aside>
                </label>
                
                <label>
                    <span><b>Khả năng chịu áp lực: </b></span>
                    <aside>
                        <?php echo $jobseeker_resume['pressure_skill_name']?>
                    </aside>
                </label>
                
                <label>
                    <span><b><?php echo $M_FOREIGN_LANGUAGE;?>/Level: </b></span>
                    <aside>
                        <?php echo $jobseeker_resume['language_name']?>
                        <?php echo $jobseeker_resume['language_level_name']?>
                    </aside>
                </label>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 cv-details">
                <label>
                    <span><b><?php echo $M_BROWSE_CATEGORY;?></b></span>						
                    <aside><?php echo $jobseeker_resume['desired_category_vi']?></aside>
                </label>
                
                <label>
                    <span><b><?php echo $WORK_LOCATION;?></b></span>						
                    <aside><?php echo $jobseeker_resume['desired_city']?></aside>
                </label>
                
                <label>
                    <span><b><?php echo $M_EDUCATION;?></b></span>						
                    <aside><?php echo $jobseeker_resume['education_name']?></aside>
                </label>
                <label>
                    <span><b><?php echo $M_JOB_TYPE;?></b></span>						
                    <aside><?php echo $jobseeker_resume['job_experience_name']?></aside>
                </label>
                
                <!--DESIRED LOCATIONS-->
                <label>
                    <span><b>Nơi làm việc mong muốn</b></span>						
                    <aside>
                        <?php foreach ($jobseeker_locations as $jobseeker_location) :?>
                        <?php echo $jobseeker_location['City'] ?>,
                        <?php endforeach;?>
                    </aside>
                </label>
                <!--DESIRED LOCATIONS-->
                
                <!--DESIRED CATEGORIES-->
                <label>
                    <span><b>Ngành nghề mong muốn</b></span>						
                    <aside>
                        <?php foreach ($jobseeker_categories as $jobseeker_category) :?>
                        <?php echo $jobseeker_category['category_name_vi'] ?>,
                        <?php endforeach;?>
                    </aside>
                </label>
                <!--DESIRED CATEGORIES-->
                
                <!--FACEBOOK URL-->
                <label>
                    <span><b>Địa chỉ facebook</b></span>						
                    <aside>
                        <a href="<?php echo $jobseeker_data['facebook_URL'];?>" target="blank"><?php echo $jobseeker_data['facebook_URL'];?></a>
                    </aside>
                </label>
                <!--FACEBOOK URL-->
                
            </div>
        </div>             
        
        <!--CAREER OBJECTIVE-->
        <div class="jobseeker-messageArea" name="js-careerObjective" class="jobseeker-messageArea" rows="5" style="width: 100%">
            <div class="jobseeker-title"><h4><?php echo $M_CAREER_OBJECTIVE;?></h4></div>
                <?php echo nl2br($jobseeker_data['career_objective']);?>
        </div>
        
        <!--EXPERIENCE AREA-->
        <div class="jobseeker-messageArea" rows="5" style="width: 100%">
            <div class="jobseeker-title"><h4><?php echo $M_EXPERIENCE;?></h4></div>
                <?php echo nl2br($jobseeker_data['experiences']);?>
        </div>
        
        <!--SKILLS AREA-->
        <div class="jobseeker-messageArea" style="width:100%">
            <div class="jobseeker-title"><h4><?php echo $M_YOUR_SKILLS;?></h4></div>
                <?php echo nl2br($jobseeker_data['skills']);?>
        </div>        
        
        
                
        <!--LIST ATTACHED FILES-->        
    </div>    
    
</div>
<!--###SonDang modify here###-->
