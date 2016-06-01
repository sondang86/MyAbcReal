<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $employer_data;
    
$job_id=filter_input(INPUT_GET,'posting_id', FILTER_SANITIZE_NUMBER_INT);
$apply_id= filter_input(INPUT_GET,'apply_id', FILTER_SANITIZE_NUMBER_INT);

$arrPosting = $db->where('employer', "$AuthUserName")->getOne('jobs');    

$arrPostingApply = $db->where('id', $apply_id)->getOne("apply");

if($arrPostingApply["posting_id"]!=$job_id) die("");
    
$jobseeker_username = $arrPostingApply["jobseeker"];

       
//Get the current jobseeker data
$jobseeker_data = $db->where('username', "$jobseeker_username")->getOne("jobseeker_resumes");
$user_file = $db->where('user_id', $jobseeker_data['id'])->withTotalCount()->getOne('files');
$user_file_count = $db->totalCount;

//Count view to the database
$commonQueries->Insert_View($jobseeker_data['id'], $AuthUserName, $jobseeker_username, $employer_data['id']);


$jobseeker_resume = $commonQueries->getJobseekerResume($jobseeker_data['id']);
$jobseeker_profile = $commonQueries->getJobseeker_profile($jobseeker_resume['jobseeker_resumes']['username']);
$jobseeker_languagues = $commonQueries->getJobseeker_languages($jobseeker_profile['jobseeker_id']);
$jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
$jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);

//Questionnaire answers list
$answers = $commonQueries->getQuestionnaireAnswers($jobseeker_data['username'], $job_id);
?>
<!--NAVIGATION-->
<div class="row">
    <section class="col-md-9 col-sm-6 col-xs-12">
        <h4><label>Chi tiết hồ sơ ứng viên: <?php echo $jobseeker_username;?></label></h4>
    </section>
    <div class="col-md-3 col-sm-6 col-xs-12 fright">
        <?php echo LinkTile("","",$M_GO_BACK,"","red","small","true","window.history.back");?>
    </div>
</div>

<div class="row section">
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

<?php if($answers['totalCount'] > 0):?>
<div class="row questionnaire">
    <section class="form-group-title">
        <label for="maxOfInterval" class="control-label">Câu hỏi: </label>
    </section>
    <section class="questionnaire-form">
    <?php foreach ($answers['answers'] as $answer) :?>
        <article>
            <div class="form-group">
                <label class="control-label"><?php echo $answer['question']?></label>
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


<h4>Chi tiết hồ sơ</h4>
<!--JOBSEEKER CV-->
<div class="jobseeker-cv">    
    <div class="jobseeker-main">
        <!--        <h4>Thông tin ứng viên: </h4>-->
        <div class="row">
            <section class="col-md-2 profilePic">
                <figure><img src="/vieclambanthoigian.com.vn/images/jobseekers/profile_pic/<?php echo $jobseeker_profile['profile_pic'];?>" id="preview" alt="Ảnh cá nhân hiện tại" class="img-responsive"></figure>
            </section>
            <section class="col-md-10 JS_profile">
                <p><label>Họ và Tên: </label> <span><?php echo $jobseeker_profile['first_name'] ." ".$jobseeker_profile['last_name']?></span></p>
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
                    <aside><?php echo $jobseeker_resume['jobseeker_resumes']['current_position_name']?></aside>
                </label>
                
                <label>
                    <span><b><?php echo $M_SALARY;?></b></span>						
                    <aside><?php echo $jobseeker_resume['jobseeker_resumes']['current_salary']?> </aside>
                </label>
                
                <label>
                    <span><b><?php echo $M_NAME_EXPECTED_POSITION;?></b></span>                                               
                    <aside><?php echo $jobseeker_resume['jobseeker_resumes']['expected_position_name']?> </aside>
                </label>
                <label>
                    <span><b><?php echo $M_NAME_EXPECTED_SALARY;?></b></span>						
                    <aside><?php echo $jobseeker_resume['jobseeker_resumes']['expected_salary_range']?> </aside>
                </label>
                
                <label>
                    <span><b>Tin học văn phòng: </b></span>
                    <aside>
                        <?php echo $jobseeker_resume['jobseeker_resumes']['IT_skill_name']?>
                    </aside>
                </label>
                
                <label>
                    <span><b>Khả năng làm việc nhóm: </b></span>
                    <aside>
                        <?php echo $jobseeker_resume['jobseeker_resumes']['group_skill_name']?>
                    </aside>
                </label>
                
                <label>
                    <span><b>Khả năng chịu áp lực: </b></span>
                    <aside>
                        <?php echo $jobseeker_resume['jobseeker_resumes']['pressure_skill_name']?>
                    </aside>
                </label>
                
                <!--LANGUAGES-->
                <?php foreach ($jobseeker_languagues['jobseeker_languages'] as $jobseeker_languague) :?>
                <label>
                    <span><b><?php echo $M_FOREIGN_LANGUAGE;?>/Trình độ: </b></span>
                    <aside>
                        <?php echo $jobseeker_languague['language_name']?>/
                        <?php echo $jobseeker_languague['level_name']?>
                    </aside>
                </label>
                <?php endforeach;?>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 cv-details">
                <label>
                    <span><b><?php echo $M_BROWSE_CATEGORY;?></b></span>						
                    <aside><?php echo $jobseeker_resume['jobseeker_resumes']['desired_category_vi']?></aside>
                </label>
                
                <label>
                    <span><b><?php echo $WORK_LOCATION;?></b></span>						
                    <aside><?php echo $jobseeker_resume['jobseeker_resumes']['desired_city']?></aside>
                </label>
                
                <label>
                    <span><b><?php echo $M_EDUCATION;?></b></span>						
                    <aside><?php echo $jobseeker_resume['jobseeker_resumes']['education_name']?></aside>
                </label>
                <label>
                    <span><b><?php echo $M_JOB_TYPE;?></b></span>						
                    <aside><?php echo $jobseeker_resume['jobseeker_resumes']['job_experience_name']?></aside>
                </label>
                
                <!--DESIRED LOCATIONS-->
                <label>
                    <span><b>Nơi làm việc mong muốn</b></span>						
                    <aside>
                        <?php 
                            if(!empty($jobseeker_locations)){ //Show jobseeker desired locations
                                foreach ($jobseeker_locations as $jobseeker_location) :                        
                                    echo $jobseeker_location['City'] . ",";
                                endforeach; 
                            }
                        ?>
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
        
        <!--REFERENCER INFORMATION-->
        <div class="jobseeker-messageArea" name="js-careerObjective" class="jobseeker-messageArea" rows="5" style="width: 100%">
            <div class="jobseeker-title"><h4><?php echo $M_CAREER_OBJECTIVE;?></h4></div>
                <?php echo nl2br($jobseeker_data['referrers']);?>
        </div>
        
        
        <?php if ($arrPostingApply['attachment'] == '1') ://User include attached file?>
        <!--LIST FILE-->        
        <form action="" method="POST" id="list_files" class="list_files">            
            <h4>Danh sách tập tin đính kèm</h4>
            <section class="table-responsive">
                <table class="table table-hover .table-striped">
                    <thead>
                        <tr>
                            <th>Tiêu đề tập tin</th>
                            <th>Thông tin tập tin</th>
                            <th>Kích cỡ (KB)</th>
                            <th>Định dạng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $user_file['title'];?></td>
                            <td><?php echo $user_file['description'];?></td>
                            <td><?php echo $commonQueries->formatSizeUnits($user_file['file_size']);?></td>
                            <td><?php echo $user_file['mime_type'];?></td>
                            <td class="col-md-1">
                                <a href="/vieclambanthoigian.com.vn/user_files/jobseekers/<?php echo $user_file['file_name'];?>" class="btn btn-primary btn-block" target="_blank" title="Lưu file">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                </a>
                            </td>                            
                        </tr>        
                    </tbody>
                </table>
            </section>
        </form>
        <?php endif;?>
    </div>    
    
</div>