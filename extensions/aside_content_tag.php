<?php
    if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
    global $db, $website, $SEO_setting, $commonQueries, $commonQueries_Front, $FULL_DOMAIN_NAME;
    $featured_jobs = $commonQueries_Front->getJobsList('featured', 5);
    $urgent_jobs = $commonQueries_Front->getJobsList('urgent', 5);
    
    //Set default value  to avoid undefined index
    $page   = isset($_GET['page'])  ? $_GET['page'] : '';
    $mod    = isset($_GET['mod'])   ? $_GET['mod']  :  ''; 
    
    if ( ($page !== 'vn_ung-vien') && ($mod !== 'candidate_details')){//Show jobs lists for jobseekers & guests    
        
?>
    
<!--Job by attribute-->
<div class="gray-wrap">
    <header class="row top-bottom-margin">
        <h4 class="aside-header"><i class="fa fa-tags"></i> Việc làm theo tính chất</h4>
    </header>
    <section class="row">
        <article class="col-md-12">
            <ul>
                <li><a href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-luong-cao/">Việc làm lương cao</a></li>
                <li><a href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-toan-thoi-gian/">Việc làm toàn thời gian</a></li>
                <li><a href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-part-time/">Việc làm thêm, sinh viên, part-time</a></li>
                <li><a href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-freelancer/">Việc làm freelance</a></li>
            </ul>
        </article>
    </section>
</div>
    
<!--URGENT JOBS-->
<div class="gray-wrap">
    <header class="row">
        <h4 class="col-md-12 aside-header">
            <i class="fa fa-newspaper-o"></i> 
            Việc tuyển dụng gấp        
        </h4>
    </header>
    <hr class="top-bottom-margin">
    <?php foreach ($urgent_jobs['jobs_list'] as $urgent_job):?>
    <div class="row">
        <article class="col-md-12">
            <div class="row">
                <div class="col-md-12 aside-content">
                    <!--Content-->
                    <h5 class="no-margin">
                        <a href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $urgent_job['job_id']?>/<?php echo $urgent_job['SEO_title']?>" class="aside-link" title="<?php echo $urgent_job['title']?>">
                            <?php echo $website->limitCharacters($urgent_job['title'],50)?>                     
                        </a>
                        <p class="sub-text">
                            <?php echo $website->limitCharacters($urgent_job['message'],250)?>
                        </p>
                        <p class="sub-text"><strong><?php echo $commonQueries->time_ago($urgent_job['date']);?> </strong></p>
                    </h5>               
                    <hr class="top-bottom-margin">
                </div>    
            </div>
        </article>
    </div>
    <?php endforeach;?>
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/viec-tuyen-dung-gap/">Xem toàn bộ</a></div>    
</div>
    
    
<!--FEATURED JOBS-->
<div class="gray-wrap">
    <header class="row">
        <h4 class="col-md-12 aside-header">
            <i class="fa fa-newspaper-o"></i> 
            Việc làm nổi bật        
        </h4>
    </header>
    <hr class="top-bottom-margin">
    <div class="row">
        <article class="col-md-12">
            <?php foreach ($featured_jobs['jobs_list'] as $featured_job):?>
            <div class="row">
                <div class="col-md-12 aside-content">
                    <!--Content-->
                    <h5 class="no-margin">
                        <a href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $featured_job['job_id']?>/<?php echo $featured_job['SEO_title']?>" class="aside-link" title="<?php echo $featured_job['title']?>">
                            <?php echo $website->limitCharacters($featured_job['title'],50)?>                     
                        </a>
                        <p class="sub-text">
                            <?php echo $website->limitCharacters($featured_job['message'],250)?>
                        </p>
                        <p class="sub-text"><strong><?php echo $commonQueries->time_ago($featured_job['date']);?> </strong></p>
                    </h5>              
                    <hr class="top-bottom-margin">
                </div>    
            </div>
            <?php endforeach;?>
        </article>
    </div>
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-noi-bat/">Xem toàn bộ</a></div>    
</div>
    
<?php } else { //List candidates    
     
     
    if ( (!empty($_GET['mod']) && ($_GET['mod'] == 'candidate_details'))){ //Candidate details 
        $jobseeker_resume = $commonQueries->getJobseekerResume(filter_input(INPUT_GET,'jobseeker_id', FILTER_SANITIZE_NUMBER_INT))['jobseeker_resumes'];
        $jobseeker_profile = $commonQueries->getJobseeker_profile($jobseeker_resume['username']);
        $jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
        $jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);
        $jobseeker_languagues = $commonQueries->getJobseeker_languages($jobseeker_profile['jobseeker_id']);
        $skills = $db->orderBy("skill_id","desc")->get('skills');

        //Employer payment verify
        if ((isset($_SESSION['logged_in'])) && ($_SESSION['user_type'] == 'employer')){//user is employer logged in
            $employerInfo = $db->where('username', $_SESSION['username'])->getOne('employers');
            if ($employerInfo['subscription'] > 1){ //Account payment verified, show email & phone
                $verified_employer = TRUE;
            } else {
                $verified_employer = FALSE;
            }
        } else { // not logged in or account is jobseeker
            $verified_employer = FALSE;
        }


        //Check profile pic
        if ($jobseeker_profile['profile_pic'] == ""){
            $profile_pic = "avatar_nam.jpg";
        } else {
            $profile_pic = $jobseeker_profile['profile_pic'];
        }
?>
 
<!--CANDIDATE DETAILS-->
<div class="gray-wrap">
    <fieldset class="row">
        <main class="candidate_details clearfix">
            
            <!--IMAGE-->
            <header class="col-md-12">
                <img src="<?php echo $FULL_DOMAIN_NAME;?>/images/jobseekers/profile_pic/<?php echo $profile_pic;?>" alt="">
            </header>
                
            <!--CANDIDATE DETAILS-->
            <section class="col-md-12">
                <h4>Chi tiết ứng viên</h4>
                <label>Tên ứng viên: </label>
                <span><?php echo $jobseeker_profile['first_name']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Tuổi: </label>
                <span><?php echo filter_var($commonQueries->timeCalculation(time(), $jobseeker_profile['dob'],1), FILTER_SANITIZE_NUMBER_INT);?></span>
            </section>
                
            <section class="col-md-12">
                <label>Vị trí hiện tại: </label>
                <span><?php echo $jobseeker_resume['current_position_name']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Vị trí mong muốn: </label>
                <span><?php echo $jobseeker_resume['expected_position_name']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Mức lương hiện tại: </label>
                <span><?php echo $jobseeker_resume['current_salary']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Mức lương mong muốn: </label>
                <span><?php echo $jobseeker_resume['expected_salary_range']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Nơi làm việc mong muốn: </label>
                <span><?php echo $jobseeker_resume['desired_city']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Kinh nghiệm: </label>
                <span><?php echo $jobseeker_resume['job_experience_name']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Ngành nghề mong muốn: </label>
                <span><?php echo $jobseeker_resume['desired_category_vi']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Học vấn: </label>
                <span><?php echo $jobseeker_resume['education_name']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Giới tính: </label>
                <span><?php echo $jobseeker_profile['gender_name']?></span>
            </section>
                
            <section class="col-md-12">
                <label>Hôn nhân: </label>
                <span><?php echo $jobseeker_profile['marital_status_name']?></span>
            </section>
                
            <?php if ($verified_employer == TRUE){?>
            <section class="col-md-12">
                <label>Số điện thoại: </label>
                <span><?php echo $jobseeker_profile['phone'];?></span>
            </section>
                
            <section class="col-md-12">
                <label>Email liên hệ: </label>
                <span><?php echo $jobseeker_profile['username'];?></span>
            </section>
            <?php } else { ?>
                
            <footer class="note col-md-12">
                <label>Lưu ý: chỉ nhà tuyển dụng đã xác thực tài khoản mới có thể xem được email và số điện thoại của ứng viên</label>
            </footer>
                
            <?php };?>
        </main>
            
            
            
        <div class="sky-form">
            <fieldset>
                <section>
                    <label class="label">Các kỹ năng: </label>
                    <div class="rating">
                        <?php foreach ($skills as $skill) :?>
                        <input type="radio" name="it-skill-rating" id="stars-rating-<?php echo $skill['skill_id']?>" disabled <?php if($jobseeker_resume['IT_skill_name'] == $skill['name']){ echo "checked='checked'";}?>>
                        <label for="stars-rating-<?php echo $skill['skill_id']?>" title="<?php echo $skill['name']?>"><i class="fa fa-star"></i></label>                        
                        <?php endforeach;?>
                        Tin học: 
                    </div>
                        
                    <div class="rating">
                        <?php foreach ($skills as $skill) :?>
                        <input type="radio" name="group-skill-rating" id="stars-rating-<?php echo $skill['skill_id']?>" disabled <?php if($jobseeker_resume['group_skill_name'] == $skill['name']){ echo "checked='checked'";}?>>
                        <label for="stars-rating-<?php echo $skill['skill_id']?>" title="<?php echo $skill['name']?>"><i class="fa fa-heart" style="font-size:16px"></i></label>                        
                        <?php endforeach;?>
                        Làm việc nhóm: 
                    </div>
                        
                    <div class="rating">
                        <?php foreach ($skills as $skill) :?>
                        <input type="radio" name="pressure-skill-rating" id="stars-rating-<?php echo $skill['skill_id']?>" disabled <?php if($jobseeker_resume['pressure_skill_name'] == $skill['name']){ echo "checked='checked'";}?>>
                        <label for="stars-rating-<?php echo $skill['skill_id']?>" title="<?php echo $skill['name']?>"><i class="fa fa-smile-o" style="font-size:19px;"></i></label>                        
                        <?php endforeach;?>
                        Chịu áp lực công việc: 
                    </div>
                        
                    <!--LANGUAGES-->
                    <?php if($jobseeker_languagues['totalCount'] > 0){ //Show this only found records?>  
                    
                    <?php foreach ($jobseeker_languagues['jobseeker_languages'] as $jobseeker_languague):?>
                        
                    <div class="rating">
                        <?php foreach ($skills as $skill) :?>
                        <input type="radio" name="language-<?php echo $jobseeker_languague['language_name'];?>-rating" id="stars-rating-<?php echo $skill['skill_id']?>" disabled <?php if($jobseeker_languague['level_id'] == $skill['skill_id']){ echo "checked='checked'";}?>>
                        <label for="stars-rating-<?php echo $skill['skill_id']?>" title="<?php echo $skill['name']?>"><i class="fa fa-star"></i></label>                        
                        <?php endforeach;?>
                            
                        Ngoại ngữ (<?php echo $jobseeker_languague['language_name']?>): 
                    </div>
                        
                    <?php endforeach;?>                    
                    <?php };?>
                    <div class="note"><strong>Note:</strong> Theo thang điểm từ thấp đến cao.</div>
                </section>
            </fieldset>
        </div>
    </fieldset>
</div>
<?php };?>
    
    
    
<!--Featured candidates-->
<div class="gray-wrap">
    <header class="row">
        <h4 class="col-md-12 aside-header">
            <i class="fa fa-newspaper-o"></i> 
            Ứng viên nổi bật        
        </h4>
    </header>
    <hr class="top-bottom-margin">
    <?php foreach ($urgent_jobs['jobs_list'] as $urgent_job):?>
    <div class="row">
        <article class="col-md-12">
            <div class="row">
                <div class="col-md-12 aside-content">
                    <!--Content-->
                    <h5 class="no-margin">
                        <a href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $urgent_job['job_id']?>/<?php echo $urgent_job['SEO_title']?>" class="aside-link" title="<?php echo $urgent_job['title']?>">
                            <?php echo $website->limitCharacters($urgent_job['title'],50)?>                     
                        </a>
                        <p class="sub-text">
                            <?php echo $website->limitCharacters($urgent_job['message'],250)?>
                        </p>
                        <p class="sub-text"><strong><?php echo $commonQueries->time_ago($urgent_job['date']);?> </strong></p>
                    </h5>               
                    <hr class="top-bottom-margin">
                </div>    
            </div>
        </article>
    </div>
    <?php endforeach;?>
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/ung-vien-noi-bat/">Xem toàn bộ</a></div>    
</div>
    
<!--Newest candidates-->
<div class="gray-wrap">
    <header class="row">
        <h4 class="col-md-12 aside-header">
            <i class="fa fa-newspaper-o"></i> 
            Ứng viên mới nhất        
        </h4>
    </header>
    <hr class="top-bottom-margin">
    <?php foreach ($urgent_jobs['jobs_list'] as $urgent_job):?>
    <div class="row">
        <article class="col-md-12">
            <div class="row">
                <div class="col-md-12 aside-content">
                    <!--Content-->
                    <h5 class="no-margin">
                        <a href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $urgent_job['job_id']?>/<?php echo $urgent_job['SEO_title']?>" class="aside-link" title="<?php echo $urgent_job['title']?>">
                            <?php echo $website->limitCharacters($urgent_job['title'],50)?>                     
                        </a>
                        <p class="sub-text">
                            <?php echo $website->limitCharacters($urgent_job['message'],250)?>
                        </p>
                        <p class="sub-text"><strong><?php echo $commonQueries->time_ago($urgent_job['date']);?> </strong></p>
                    </h5>               
                    <hr class="top-bottom-margin">
                </div>    
            </div>
        </article>
    </div>
    <?php endforeach;?>
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/ung-vien-moi-nhat/">Xem toàn bộ</a></div>    
</div>
<?php };?>

   
<style>
    .sky-form {
        outline: none;
    }
        
    .sky-form .rating {
        font-size: 12px;
    }
</style>