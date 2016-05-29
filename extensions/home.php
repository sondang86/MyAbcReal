<?php
// Jobs Portal
// Copyright (c) All Rights Reserved Vieclambanthoigian.com.vn
if(!defined('IN_SCRIPT')) die("");
global $db,$pagination, $categories, $categories_subs,$commonQueries, $commonQueries_Front, $locations, $companies,$SEO_setting, $Browser_detection, $userId_cookie;

$featured_jobs = $commonQueries_Front->getJobsList('featured', NULL, $userId_cookie);
$urgent_jobs = $commonQueries_Front->getJobsList('urgent', NULL, $userId_cookie);
$latest_jobs = $commonQueries_Front->getLatestJobsList(FALSE);


$segment = $website->getURL_segment($website->currentURL());
?>
<style>
    #by_featured, #by_urgent {
        width: 100%;
        height: 500px;
        margin: 50px auto 0 auto;
        position: relative;
        overflow: auto;
    }
</style>

<section class="row stats top-title">
    <header class="col-md-12">
        <h4>Việc làm hấp dẫn: </h4>
    </header>
</section>

<ul class="nav nav-tabs">
    <li class="active"><a href="#by_urgent"><i class="fa fa-eyedropper"></i> Tuyển dụng gấp</a></li>
    <li><a href="#by_featured"><i class="fa fa-eyedropper"></i> <?php echo $M_JOBS_BY_FEATURED;?></a></li>
</ul>

<!--CATEGORIES-->
<section class="tab-content">
    
    <!--BY URGENT-->
    <div id="by_urgent" class="tab-pane fade in active">
        <?php foreach ($urgent_jobs['jobs_list'] as $key => $urgent_job) :?>
        <div class="row joblistArea">
            <div class="col-md-12 joblist">
                <a href="<?php $website->check_SEO_link("details", $SEO_setting, $urgent_job['job_id'],$urgent_job['SEO_title']);?>">
                    <section class="banner">
                        <img alt="SKP Business Consulting LLP" src="http://<?php echo $DOMAIN_NAME;?>/images/employers/logo/<?php echo $urgent_job['logo']?>" width="120" height="50">
                    </section>
                    <p title="<?php echo $urgent_job['title']?>" class="desig"><?php echo $urgent_job['title']?></p>
                    <p class="company">
                        <i class="fa fa-briefcase"></i>
                        <span><?php echo $urgent_job['company']?></span>
                    </p>
                    
                    <form class="more">
                        <span class="exp"><i class="fa fa-comments-o"></i> <?php echo $urgent_job['experience_name']?></span>
                        <span class="loc">
                            <i class="fa fa-location-arrow"></i>
                            <span><?php echo $urgent_job['City']?></span>            
                        </span> 
                    </form>
                    
                    <form class="more"> 
                        <i class="fa fa-diamond"></i> <span> Chuyên ngành:</span>
                        <span class="desc"> 
                            <p iclass="skill"><?php echo $urgent_job['category_name_vi']?></p> 
                        </span>  
                    </form>
                    
                    <form class="more"> 
                        <i class="fa fa-money"></i> <span> Mức lương:</span>
                        <span class="experience"> 
                            <p><?php echo $urgent_job['salary_range']?> $</p> 
                        </span>  
                    </form>
                </a>
                <span class="featuredjob" title="Tuyển dụng gấp">
                    <i class="fa fa-fire" aria-hidden="true"></i>
                </span> 
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
    </div>
    
    
    <!--BY FEATURED-->
    <div id="by_featured" class="tab-pane fade in">
        <?php foreach ($featured_jobs['jobs_list'] as $key => $featured_job) :?>
        <div class="row joblistArea">
            <div class="col-md-12 joblist">
                <a href="<?php $website->check_SEO_link("details", $SEO_setting, $featured_job['job_id'],$featured_job['SEO_title']);?>">
                    <section class="banner">
                        <img alt="SKP Business Consulting LLP" src="http://<?php echo $DOMAIN_NAME;?>/images/employers/logo/<?php echo $featured_job['logo']?>" width="120" height="50">
                    </section>
                    <p title="<?php echo $featured_job['title']?>" class="desig"><?php echo $featured_job['title']?></p>
                    <p class="company">
                        <i class="fa fa-briefcase"></i>
                        <span><?php echo $featured_job['company']?></span>
                    </p>
                    
                    <form class="more">
                        <span class="exp"><i class="fa fa-comments-o"></i> <?php echo $featured_job['experience_name']?></span>
                        <span class="loc">
                            <i class="fa fa-location-arrow"></i>
                            <span><?php echo $featured_job['City']?></span>            
                        </span> 
                    </form>
                    
                    <form class="more"> 
                        <i class="fa fa-diamond"></i> <span> Chuyên ngành:</span>
                        <span class="desc"> 
                            <p iclass="skill"><?php echo $featured_job['category_name_vi']?></p> 
                        </span>  
                    </form>
                    
                    <form class="more"> 
                        <i class="fa fa-money"></i> <span> Mức lương:</span>
                        <span class="experience"> 
                            <p><?php echo $featured_job['salary_range']?> $</p> 
                        </span>  
                    </form>
                </a>
                <span class="featuredjob" title="Featured Job">
                    <i class="fa fa-star"></i>
                </span> 
            </div>
            
            <!--SAVE JOB-->
            <div class="col-md-12 more-details">           
                <section class="col-md-6 col-xs-6 other_details">
                    <span title=" Save this job " class="action savejob fav  favReady">
                        
                    <?php if($featured_job['saved_jobId'] !== $featured_job['job_id'] || $userId_cookie !== $featured_job['user_uniqueId']){ //Show save job button?>                    
                        
                        <a href="#" data-browser="<?php echo $Browser_detection->getName();?>" data-category="<?php echo $featured_job["category_id"]?>"  data-jobid="<?php echo $featured_job["job_id"]?>" title="Lưu việc làm này" class="savethisJob" id="<?php echo $featured_job["job_id"]?>" onclick="javascript:saveJob(this, sitePath)"><i class="fa fa-floppy-o"></i>  Lưu việc làm này</a>
                        
                    <?php } else { // Show saved ?>
                        
                        <a href="#" title="Đã lưu" class="savethisJob" id="<?php echo $featured_job["job_id"]?>"><i class="fa fa-check"></i>Đã lưu việc này</a>                    
                        
                    <?php }?>
                    </span> 
                    <span class="salary"><em></em>  Not disclosed </span> 
                </section>
                <section class="col-md-6 col-xs-6 rec_details">
                    <span> Đăng bởi   <a title="việc làm đăng bởi  <?php echo $featured_job['company']?> " class="rec_name">  <?php echo $featured_job['company']?>  </a></span> 
                    <span><i class="fa fa-clock-o"></i> <?php echo $commonQueries->time_ago($featured_job['date']);?></span>
                </section>
            </div>    
        </div>
        <?php endforeach;?>
    </div>
</section>
<!--CATEGORIES--> 


<!--LATEST JOBS-->
<section class="row stats top-title">
    <header class="col-md-12">
        <h4>Việc làm mới nhất: </h4>
    </header>
</section>

<ul class="nav nav-tabs">
    <li class="active"><a href="#by_latest"><i class="fa fa-eyedropper"></i> Việc làm mới nhất</a></li>
    <!--<li><a href="#by_featured"><i class="fa fa-eyedropper"></i> <?php echo $M_JOBS_BY_FEATURED;?></a></li>-->
</ul>

<!--CATEGORIES-->

<section class="tab-content">    
    <!--BY LATEST-->
    <div id="by_urgent" class="tab-pane fade in active">
        <?php foreach ($latest_jobs['jobs_list'] as $key => $latest_job) :?>
        <div class="row joblistArea">
            
            <div class="col-md-12 joblist">
                <a href="<?php $website->check_SEO_link("details", $SEO_setting, $latest_job['job_id'],$latest_job['SEO_title']);?>">
                    <section class="banner">
                        <img alt="SKP Business Consulting LLP" src="http://<?php echo $DOMAIN_NAME;?>/images/employers/logo/<?php echo $latest_job['logo']?>" width="120" height="50">
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
    </div>
    
</section>


<!--ADS HERE-->
<section class="ads">
    
</section>

<!--STATS-->
<section class="row stats top-title">
    <header class="col-md-12">
        <h4>Hiện vieclambanthoigian.com.vn đang có: </h4>
        <p></p>
    </header>    
    <main>
        <div class="col-xs-3 counter">
            <p class="counter-value"><?php echo $commonQueries->countAllRecords("jobseeker_resumes", array('id'))?></p>
            <p><strong>Hồ sơ</strong></p>
        </div>
        <div class="col-xs-3 counter">
            <p class="counter-value"><?php echo $commonQueries->countAllRecords("jobs", array('id'))?></p>
            <p><strong>Việc đã đăng</strong></p>
        </div>
        <div class="col-xs-3 counter">
            <p class="counter-value"><?php echo $commonQueries->countAllRecords("employers", array('id'))?></p>
            <p><strong>Nhà tuyển dụng</strong></p>
        </div>
        <div class="col-xs-3 counter">
            <p class="counter-value"><?php echo ($commonQueries->countAllRecords("jobseekers", array('id')))?></p>
            <p><strong>Thành viên đã đăng ký</strong></p>
        </div>
    </main>
</section>

<!--List jobs by CATEGORIES/LOCATIONS/COMPANIES-->
<section class="row stats top-title">
    <header class="col-md-12">
        <h4>Tìm việc theo: </h4>
    </header>
</section>

<section class="stats top-title">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#by_category"><i class="fa fa-briefcase"></i> <?php echo $M_BROWSE_CATEGORY;?></a></li>    
        <li><a href="#by_location"><i class="fa fa-location-arrow"></i> <?php echo $M_JOBS_BY_LOCATION;?></a></li>
        <li><a href="#by_company"><i class="fa fa fa-building-o"></i> <?php echo $M_JOBS_BY_COMPANY;?></a></li>
    </ul>
</section>

<section class="tab-content">
    <!--BY CATEGORIES-->
    <div id="by_category" class="tab-pane fade in active">
        <div class="row same_height">
            <?php foreach ($categories as $catkey => $category) :?>
            <span class="col-md-4 main-category">
                <!--MAIN CATEGORIES-->
                <p><a href="<?php $website->check_SEO_link("jobs_by_category",$SEO_setting, $category['category_id'], $website->seoUrl($category['category_name_vi']))?>"><?php echo $category['category_name_vi']?></a>(<?php echo $commonQueries->countRecords("job_category", $category['category_id'], "jobs")->count?>)</p>
            </span>
            <?php endforeach;?>
        </div>
    </div>    
    
    <!--BY LOCATIONS-->
    <div id="by_location" class="tab-pane fade padding-5">
        <div class="row same_height">
            <?php foreach ($locations as $location) :?>
            <span class="col-md-3 main-category">
                <a href="<?php $website->check_SEO_link("jobs_by_location",$SEO_setting, $location['id'], $website->seoUrl($location['City']))?>" class="main_category_link"><?php echo $location['City']?></a> (<?php echo $commonQueries->countRecords("region", $location['id'], "jobs")->count?>)
            </span>
            <?php endforeach;?>
        </div>
    </div>        
    
    <!--BY COMPANIES-->
    <div id="by_company" class="tab-pane fade padding-5">
        <div class="row same_height">
            <?php foreach ($companies as $company) :?>
            <span class="col-md-3 main-category">
                <a href="<?php $website->check_SEO_link("jobs_by_companyId",$SEO_setting, $company['id'], $website->seoUrl($company['company']))?>"><?php echo $company['company']?></a> (<?php echo $commonQueries->countRecords("employer", $company['username'], "jobs")->count?>)
            </span>
            <?php endforeach;?>
        </div>
    </div>
</section>

<!--job tabs js-->
<script>
    $(document).ready(function(){
        $(".nav-tabs a").click(function(){
            $(this).tab('show');
        });
    });
</script>
