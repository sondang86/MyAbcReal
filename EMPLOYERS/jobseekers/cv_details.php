<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries,$FULL_DOMAIN_NAME, $EMPLOYER_DOMAIN_NAME, $employerInfo;
//If employer using free subscription, do not allow them to see jobseeker's contact 

//Check if employer is allowed to see this CV

//Stop if employer see more than 100 CVs for today

$jobseeker_resume = $commonQueries->getJobseekerResume(filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT))['jobseeker_resumes'];
$jobseeker_username = $jobseeker_resume['username'];
$jobseeker_profile = $commonQueries->getJobseeker_profile($jobseeker_resume['username']);
$jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
$jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);
$jobseeker_languagues = $commonQueries->getJobseeker_languages($jobseeker_profile['jobseeker_id']);
$jobseeker_data = $db->where('username', "$jobseeker_username")->getOne("jobseeker_resumes");

//Count view to the database
$commonQueries->Insert_View($jobseeker_data['id'], $AuthUserName, $jobseeker_username, $employerInfo['id']);

//Set default image if empty
$profile_pic = $commonQueries->setDefault_ifEmpty($jobseeker_profile['profile_pic'],"$FULL_DOMAIN_NAME/images/commons/jobs_portal_logo_demo.jpg","$FULL_DOMAIN_NAME/images/jobseekers/profile_pic/" . $jobseeker_profile['profile_pic']);
?>

<div class="row">
    <section class="col-md-9 col-sm-6 col-xs-12">
        <h4><label>Chi tiết hồ sơ ứng viên: <?php echo $jobseeker_profile['first_name'] ." ".$jobseeker_profile['last_name']?></label></h4>
    </section>
    <div class="col-md-3 col-sm-6 col-xs-12 fright">
        <?php echo LinkTile("jobseekers","search",$M_GO_BACK,"","red","small");?>
    </div>
</div>

<!--JOBSEEKER CV-->
<div class="jobseeker-cv">    
    <div class="jobseeker-main">
        <!--        <h4>Thông tin ứng viên: </h4>-->
        <div class="row">
            <section class="col-md-2 profilePic">
                <figure><img src="<?php echo $profile_pic;?>" id="preview" alt="Ảnh cá nhân hiện tại" class="img-responsive"></figure>
            </section>
            <section class="col-md-10 JS_profile">
                <p><label>Họ và Tên: </label> <span><?php echo $jobseeker_profile['first_name'] ." ".$jobseeker_profile['last_name']?></span></p>
                <p><label>Địa chỉ: </label> <span><?php echo $jobseeker_profile['address']?></span></p>
                
                <?php if($employerInfo['subscription'] > '1'): //show this to our VIP employers?>
                <p><label>Email: </label> <span><?php echo $jobseeker_profile['username']?></span></p>
                <p><label>Số điện thoại: </label> <span><?php echo $jobseeker_profile['phone']?></span></p>
                
                <?php else: //No free meal for you my friend?>                
                <p><label>Email: </label> <span>Tài khoản miễn phí không thể xem thông tin cá nhân ứng viên, vui lòng nâng cấp<a href="index.php?category=home&action=credits"> tại đây</a></span></p>
                <p><label>Số điện thoại: </label> <span>Tài khoản miễn phí không thể xem thông tin cá nhân ứng viên, vui lòng nâng cấp<a href="index.php?category=home&action=credits"> tại đây</a></span></p>                
                <?php endif;?>
                
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
        
                
        <!--LIST ATTACHED FILES-->        
    </div>    
    
</div>