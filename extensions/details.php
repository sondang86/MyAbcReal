<?php
    if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
    global $db,$categories, $categories_subs,$commonQueries,  $SEO_setting, $userId_cookie,$Browser_detection;
    $job_id = $commonQueries->check_present_id($_GET, $SEO_setting, 3);
    $job_details = $commonQueries->jobDetails($job_id);
    
    //Check latitude/longitute values for Google Maps
    $latitude = $commonQueries->check_LatitudeLongitude($job_details['latitude'],$job_details['longitude'])['latitude'];
    $longitude = $commonQueries->check_LatitudeLongitude($job_details['latitude'],$job_details['longitude'])['longitude'];
    
    if ($job_details !== FALSE){ 
        $commonQueries->Update_job_views($job_id);//count job views for every refresh
        
    //Set to default logo if empty
    $company_logo = $commonQueries->setDefault_logoIfEmpty($job_details['logo'], "employers");
?>    
<a id="go_back_button" class="btn btn-default btn-xs pull-right no-decoration margin-bottom-5" href="javascript:GoBack()">Quay lại</a>    

<article class="job-details-wrap">
    <!--JOB's TITLE-->
    <div class="row">
        <h2 class="no-margin col-md-10"><?php echo $job_details['title']?></h2>
        <section class="social-share">
            <ul class="col-md-2">
                <li><a rel="nofollow" href="https://www.linkedin.com/shareArticle?mini=true&amp;title=Nh%C3%A0+h%C3%A0ng+Army+%28chuy%C3%AAn+m%C3%B3n+%C4%83n+Vi%E1%BB%87t+Nam%29%2C+%C4%90c%3A+17B+L%C3%BD+Nam+%C4%90%E1%BA%BF%2C+Ho%C3%A0n+Ki%E1%BA%BFm%2C+H%C3%A0+N%E1%BB%99i+c%E1%BA%A7n+tuy%E1%BB%83n+g%E1%BA%A5p%3A&amp;url=http://localhost/vieclambanthoigian.com.vn/nghenghiep-nh�-h�ng-army-chuy��n-m��n-��n-viet-76.html" target="_blank"><img src="http://localhost/vieclambanthoigian.com.vn/images/linkedin.gif" width="18" height="18"></a></li>
                <li><a rel="nofollow" href="http://plus.google.com/share?url=http://localhost/vieclambanthoigian.com.vn/nghenghiep-nh�-h�ng-army-chuy��n-m��n-��n-viet-76.html" target="_blank"><img src="http://localhost/vieclambanthoigian.com.vn/images/googleplus.gif" width="18" height="18"></a></li>
                <li><a rel="nofollow" href="http://www.twitter.com/intent/tweet?text=Nh%C3%A0+h%C3%A0ng+Army+%28chuy%C3%AAn+m%C3%B3n+%C4%83n+Vi%E1%BB%87t+Nam%29%2C+%C4%90c%3A+17B+L%C3%BD+Nam+%C4%90%E1%BA%BF%2C+Ho%C3%A0n+Ki%E1%BA%BFm%2C+H%C3%A0+N%E1%BB%99i+c%E1%BA%A7n+tuy%E1%BB%83n+g%E1%BA%A5p%3A&amp;url=http://localhost/vieclambanthoigian.com.vn/nghenghiep-nh�-h�ng-army-chuy��n-m��n-��n-viet-76.html" target="_blank"><img src="http://localhost/vieclambanthoigian.com.vn/images/twitter.gif" width="18" height="18"></a></li>
                <li><a rel="nofollow" href="http://www.facebook.com/sharer.php?u=http://localhost/vieclambanthoigian.com.vn/nghenghiep-nh�-h�ng-army-chuy��n-m��n-��n-viet-76.html" target="_blank"><img src="http://localhost/vieclambanthoigian.com.vn/images/facebook.gif" width="18" height="18"></a></li>
            </ul>
        </section>
    </div>
        
    <!--JOB DETAILS-->
    <div class="job-details-info">            
        <section class="col-md-6">
            <p><label>Thành phố: </label><span> <?php echo $job_details['City']?></span></p>
            <p><label>Đăng vào lúc: </label><span> <?php echo date("d/m/Y",$job_details['date'])?></span></p>
            <p><label>Số đơn xin việc đã nộp: </label><span> <?php echo $job_details['applications']?></span></p> 				
        </section>
        <section class="col-md-6">                    
            <p>
                <label>Loại công việc:</label>
                <span><?php echo $job_details['job_name']?></span> 
            </p>
            <p>
                <label>Mức lương:</label>
                <span><strong><?php echo $job_details['salary_range']?></strong></span>
            </p>
            <p>
                <label>Lượt xem:</label>
                <span><strong><?php echo $job_details['views_count']?></strong></span>
            </p>
        </section>
    </div>
        
    <!--JOB DESCRIPTION-->
    <section class="job-description clearfix">
        <article class="col-md-9">
            <h4><?php echo $JOB_DESCRIPTION;?></h4>
            <p><?php echo nl2br($job_details['message'])?></p>
            
            <h4><?php echo $REQUIRES_DESCRIPTION;?></h4>
            <p><?php echo nl2br($job_details['requires_description'])?></p>
            
            <h4><?php echo $PROFILECV_DESCRIPTION;?></h4>
            <p><?php echo nl2br($job_details['profileCV_description'])?></p>            
            
            <h4><?php echo $BENEFITS_DESCRIPTION;?></h4>
            <p><?php echo nl2br($job_details['benefits_description'])?></p>
            
        </article>
        
        <aside class="col-md-3 job-details-aside">
            <a href="<?php $website->check_SEO_link("companyInfo", $SEO_setting, $job_details['employer_id'],$website->seoUrl($job_details['company']));?>">
                <img class="logo-border img-responsive" src="<?php echo $company_logo;?>" alt="<?php echo $job_details['company']?>">
            </a>
            <a href="<?php $website->check_SEO_link("jobs_by_companyId", $SEO_setting, $job_details['employer_id'],$website->seoUrl($job_details['company']));?>" class="sub-text underline-link">Việc làm khác từ <?php echo $job_details['company']?></a>            
            <a href="<?php $website->check_SEO_link("companyInfo", $SEO_setting, $job_details['employer_id'],$website->seoUrl($job_details['company']));?>" class="sub-text underline-link">Thông tin công ty</a>
            
            <?php 
                //If user is employer, hide the apply job
                if ($_SESSION['user_type'] !== "employer"){
            ?>
                <a href="<?php $website->check_SEO_link("apply_job", $SEO_setting, $job_details['job_id'],$job_details['SEO_title']);?>">
                    <input type="submit" class="btn btn-default custom-gradient btn-green" value=" Nộp hồ sơ ">
                </a>
            <?php   }?>
        </aside>
    </section>
    
    <!--JOB EMPLOYER's CONTACT DETAILS-->
    <fieldset class="job-details-contact clearfix">
        <header>Thông tin liên hệ: </header>
        <section class="col-md-12">
            <label><strong>Người liên hệ:</strong> <span><?php echo $job_details['work_location'];?></span></label>

            <label><strong>Địa điểm nộp hồ sơ/làm việc:</strong>  <span><?php echo $job_details['contact_person'];?></span> </label>

            <?php if($job_details['contact_person_phone'] !== 0): ?>
            <label><strong>Số điện thoại liên hệ:</strong> <span><?php echo $job_details['contact_person_phone'];?></span> </label>
            <?php endif;?>

            <?php if($job_details['contact_person_email'] !== ''):?>
            <label><strong>Email liên hệ:</strong>  <span><?php echo $job_details['contact_person_email'];?></span> </label>
            <?php endif;?>
        </section>
        
        <!--GOOGLE MAPS-->
        <section class="col-md-12">
            <?php require_once ('include/google_maps.php');?>
        </section>
        
        <!--JOB APPLY NAV-->
        <section>
            <figure class="top-bottom-margin col-md-12">            

                <a href="#" title="Email công việc này" class="emailthisJob" id="<?php echo $job_details["job_id"]?>"><i class="fa fa-inbox" aria-hidden="true"></i>Gửi email</a> 

                <?php if(($job_details['saved_jobId'] !== $job_details['job_id']) || ($userId_cookie !== $job_details['user_uniqueId']) || ($job_details['IPAddress'] !== filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP))){ //Show save job button?>                    

                    <a href="#" data-browser="<?php echo $Browser_detection->getName();?>" data-category="<?php echo $job_details["category_id"]?>"  data-jobid="<?php echo $job_details["job_id"]?>" title="Lưu việc làm này" class="savethisJob" id="<?php echo $job_details["job_id"]?>" onclick="javascript:saveJob(this, sitePath)"><i class="fa fa-floppy-o"></i>  Lưu việc làm này</a>

                <?php } else { // Show saved ?>

                    <a href="#" title="Đã lưu" class="savethisJob" id="<?php echo $job_details["job_id"]?>"><i class="fa fa-check"></i>Đã lưu việc này</a>                    

                <?php }?>

            </figure>
        </section>
        
    </fieldset>
    
</article>

<?php } else {?>
    <article class="job-details-wrap">
        <h4>Không tìm thấy dữ liệu :(</h4>
    </article>
<?php } ?>

<style>
    .top-bottom-margin {
        margin-top: 35px !important;
        text-align: right;
    }
</style>