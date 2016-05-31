<?php
    if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
    global $db, $website, $SEO_setting, $commonQueries, $commonQueries_Front, $FULL_DOMAIN_NAME;
    $featured_jobs = $commonQueries_Front->getJobsList('featured', 5);
    $urgent_jobs = $commonQueries_Front->getJobsList('urgent', 5);

?>

<?php // if($_GET['mod'] !== 'candidate_details'){
//    echo 'true';
//}?>

<?php if (empty($_GET['page']) || ($_GET['page'] !== 'vn_ung-vien') && ($_GET['mod'] !== 'candidate_details')):?>
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

<?php else: //List candidates
if (!empty($_GET['page'] && $_GET['page'] == 'jobseeker_details')): //Candidate details 
    $jobseeker_resume = $commonQueries->getJobseekerResume(filter_input(INPUT_GET,'jobseeker_id', FILTER_SANITIZE_NUMBER_INT))['jobseeker_resumes'];
    $jobseeker_profile = $commonQueries->getJobseeker_profile($jobseeker_resume['username']);
    $jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
    $jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);
    $jobseeker_languagues = $commonQueries->getJobseeker_languages($jobseeker_profile['jobseeker_id']);
?>
<div class="gray-wrap">
    <fieldset class="row">
        <main class="candidate_details">
            
            <!--IMAGE-->
            <header class="col-md-12">
                <img src="http://431.da1.myftpupload.com/wp-content/themes/careersWP/assets/img/userpic.gif" alt="">
            </header>
            
            <!--CANDIDATE DETAILS-->
            <section class="col-md-12">
                <h4>Chi tiết ứng viên</h4>
                <label>Tên ứng viên: </label>
                <span><?php echo $jobseeker_profile['first_name']?></span>
            </section>
            
            <section class="col-md-12">
                <label>Tuổi: </label>
                <span><?php echo $jobseeker_resume['username']?></span>
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
            
            <section class="col-md-12">
                <label>Phone: </label>
                <span><a href="/vieclambanthoigian.com.vn/mod-vn-employers_registration.html">Đăng ký</a> để thấy link</span>
            </section>
            
            <section class="col-md-12">
                <label>Email: </label>
                <span><a href="/vieclambanthoigian.com.vn/mod-vn-employers_registration.html">Đăng ký</a> để thấy link</span>
            </section>
            
        </main>
    </fieldset>
</div>
<?php endif;?>



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
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/viec-tuyen-dung-gap/">Xem toàn bộ</a></div>    
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
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/viec-tuyen-dung-gap/">Xem toàn bộ</a></div>    
</div>
<?php endif;?>