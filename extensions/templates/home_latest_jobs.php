<?php
    if(!defined('IN_SCRIPT')) die("");
?>

        <?php foreach ($latest_jobs['jobs_list'] as $key => $latest_job) :
            $logo = $commonQueries->setDefault_ifEmpty($latest_job['logo'], 'sample.jpg', $latest_job['logo']);
        ?>
<div class="row joblistArea">
    
    <div class="col-md-12 joblist">
        <a href="<?php $website->check_SEO_link("details", $SEO_setting, $latest_job['job_id'],$latest_job['SEO_title']);?>">
            
            <section class="banner">
                <img alt="SKP Business Consulting LLP" src="<?php echo $FULL_DOMAIN_NAME;?>/images/employers/logo/<?php echo $logo;?>" width="120" height="50">
                
                <?php if($latest_job['employer_subscription'] > 1):?>
                <p><small><i class="fa fa-check-square-o" aria-hidden="true"></i> NTD đã xác thực</small></p>
                <?php endif;?>
            </section>
            
            
            <p title="<?php echo $latest_job['title']?>" class="desig"><?php echo $latest_job['title']?></p>
            <p class="company">
                <i class="fa fa-briefcase"></i>
                <span><?php echo $latest_job['company']?></span>
            </p>
            
            <form class="more">
                <span class="exp"><i class="fa fa-comments-o"></i> <?php echo $latest_job['experience_name']?></span>
                <span class="loc">
                    <i class="fa fa-location-arrow"></i>
                    <span><?php echo $latest_job['City']?></span>            
                </span> 
            </form>
            
            <form class="more"> 
                <i class="fa fa-diamond"></i> <span> Chuyên ngành:</span>
                <span class="desc"> 
                    <p iclass="skill"><?php echo $latest_job['category_name_vi']?></p> 
                </span>  
            </form>
            
            <form class="more"> 
                <i class="fa fa-money"></i> <span> Mức lương:</span>
                <span class="experience"> 
                    <p><?php echo $latest_job['salary_range']?> $</p> 
                </span>  
            </form>
        </a>
        <!--FEATURED/URGENT JOB ICONS-->
                <?php if($latest_job['featured'] == "1"):?>
        <span class="featuredjob" title="Việc làm nổi bật">
            <i class="fa fa-star"></i>
        </span> 
                <?php endif;?>
                <?php if($latest_job['urgent'] == "1"):?>
        <span class="urgentjob" title="Tuyển dụng gấp">
            <i class="fa fa-fire" aria-hidden="true"></i>
        </span> 
                <?php endif;?>
    </div>
    
    
    <!--SAVE JOB-->
    <div class="col-md-12 more-details">           
        <section class="col-md-6 col-xs-6 other_details">
            <span title=" Save this job " class="action savejob fav  favReady">
                
                    <?php if($urgent_job['saved_jobId'] !== $urgent_job['job_id'] || $userId_cookie !== $urgent_job['user_uniqueId']){ //Show save job button?>                    
                
                <a href="#" data-browser="<?php echo $Browser_detection->getName();?>" data-category="<?php echo $urgent_job["category_id"]?>"  data-jobid="<?php echo $urgent_job["job_id"]?>" title="Lưu việc làm này" class="savethisJob" id="<?php echo $urgent_job["job_id"]?>" onclick="javascript:saveJob(this, sitePath)"><i class="fa fa-floppy-o"></i>  Lưu việc làm này</a>
                
                    <?php } else { // Show saved ?>
                
                <a href="#" title="Đã lưu" class="savethisJob" id="<?php echo $urgent_job["job_id"]?>"><i class="fa fa-check"></i>Đã lưu việc này</a>                    
                
                    <?php }?>
            </span> 
            <span class="salary"><em></em>  Not disclosed </span> 
        </section>
        <section class="col-md-6 col-xs-6 rec_details">
            <span> Đăng bởi   <a title="việc làm đăng bởi  <?php echo $urgent_job['company']?> " class="rec_name">  <?php echo $urgent_job['company']?>  </a></span> 
            <span><i class="fa fa-clock-o"></i> <?php echo $commonQueries->time_ago($urgent_job['date']);?></span>
        </section>
    </div>    
</div>
        <?php endforeach;?>
<div class="row footer-section">
    <section class="col-md-12">
        <a href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-moi-nhat/" class="btn btn-blue">Xem toàn bộ</a>
    </section>
</div>


