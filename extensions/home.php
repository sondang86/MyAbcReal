<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db,$categories, $categories_subs,$commonQueries, $locations, $companies,$SEO_setting;


$featured_jobs_columns = array(
    $DBprefix."jobs.id as job_id",$DBprefix."jobs.job_category",$DBprefix."jobs.title",$DBprefix."jobs.SEO_title",
    $DBprefix."jobs.message",$DBprefix."employers.company",$DBprefix."employers.logo",
    $DBprefix."categories.category_name_vi",$DBprefix."categories.category_name",
    $DBprefix."locations.City",$DBprefix."locations.City_en",
    $DBprefix."job_types.job_name",$DBprefix."job_types.job_name_en",
    $DBprefix."job_experience.name as experience_name",$DBprefix."job_experience.name_en as experience_name_en",
    $DBprefix."salary.salary_range",$DBprefix."salary.salary_range_en"
);

$db->join("employers", $DBprefix."jobs.employer=".$DBprefix."employers.username", "LEFT");
$db->join("categories", $DBprefix."jobs.job_category=".$DBprefix."categories.category_id", "LEFT");
$db->join("locations", $DBprefix."jobs.region=".$DBprefix."locations.id", "LEFT");
$db->join("job_types", $DBprefix."jobs.job_type=".$DBprefix."job_types.id", "LEFT");
$db->join("job_experience", $DBprefix."jobs.experience=".$DBprefix."job_experience.experience_id", "LEFT");
$db->join("salary", $DBprefix."jobs.salary=".$DBprefix."salary.salary_id", "LEFT");
$db->where($DBprefix."jobs.active", "YES");
$db->where($DBprefix."jobs.status", "1");
$db->where($DBprefix."jobs.expires", time(), ">");
$db->where($DBprefix."jobs.featured", "1");
$db->orderBy('RAND()');
$featured_jobs = $db->get("jobs", array(0,10),$featured_jobs_columns);

$segment = $website->getURL_segment($website->currentURL());

?>
<style>    
 
</style>
<section class="row stats top-title">
    <header class="col-md-12">
        <h4>Việc làm nổi bật: </h4>
    </header>
</section>

<ul class="nav nav-tabs">
    <li class="active"><a href="#by_featured"><i class="fa fa-eyedropper"></i> <?php echo $M_JOBS_BY_FEATURED;?></a></li>
</ul>

<!--CATEGORIES-->
<section class="tab-content">        
    <!--BY FEATURED-->
    <div id="by_featured" class="tab-pane fade in active">
        <?php foreach ($featured_jobs as $featured_job) :?>
        <div class="row joblistArea">
            <div class="col-md-12 joblist">
                <?php if($SEO_setting == 0){?>
                <a href="index.php?mod=details&id=<?php echo $featured_job['job_id']?>&lang=vn">
                <?php } else {?>    
                <a href="chi-tiet-cong-viec/<?php echo $featured_job['job_id']?>/<?php echo $featured_job['SEO_title']?>">    
                <?php }?>    
                    <section class="banner">
                        <img alt="SKP Business Consulting LLP" src="uploaded_images/<?php echo $featured_job['logo']?>.jpg" width="120" height="50">
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
            <div class="col-md-12 more-details">           
                <section class="col-md-6 col-xs-6 other_details">
                    <span title=" Save this job " class="action savejob fav  favReady" jid="280316900272">
                        <a href="javascript:SaveListing('<?php echo $featured_job["job_id"]?>')" id="save_<?php echo $featured_job["job_id"]?>" title="Lưu việc làm này"><i class="fa fa-floppy-o"></i>  Lưu việc làm này</a>
                    </span> 
                    <span class="salary"><em></em>  Not disclosed </span> 
                </section>
                <section class="col-md-6 col-xs-6 rec_details">
                    <span> Posted  by   <a title="Job Posted by  HR " class="rec_name">  HR  </a>   , </span> 
                    <span>Just Now</span>
                </section>
            </div>    
        </div>
        <?php endforeach;?>
    </div>
</section>
<!--CATEGORIES--> 


<!--ADS HERE-->
<section class="ads">
    
</section>

<!--STATS-->
<section class="row stats top-title">
    <header class="col-md-12">
        <h4>Thống kê: </h4>
        <p>Here we list our stats and how many people we've helped find a job and companies have found recruits. It's Pretty awesome stats area!</p>
    </header>    
    <main>
        <div class="col-xs-3 counter">
            <p class="counter-value">150</p>
            <p><strong>Hồ sơ</strong></p>
        </div>
        <div class="col-xs-3 counter">
            <p class="counter-value">1500</p>
            <p><strong>Việc đã đăng</strong></p>
        </div>
        <div class="col-xs-3 counter">
            <p class="counter-value">15</p>
            <p><strong>Nhà tuyển dụng</strong></p>
        </div>
        <div class="col-xs-3 counter">
            <p class="counter-value">255</p>
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
                <a href="<?php $website->check_SEO_link("jobs_by_location",$SEO_setting, $location['id'], $website->seoUrl($location['City']))?>" class="main_category_link"><?php echo $location['City']?></a>
            </span>
            <?php endforeach;?>
        </div>
    </div>        
    
    <!--BY COMPANIES-->
    <div id="by_company" class="tab-pane fade padding-5">
        <div class="row same_height">
            <?php foreach ($companies as $company) :?>
            <span class="col-md-3 main-category">
                <a href="<?php $website->check_SEO_link("jobs_by_companyId",$SEO_setting, $company['id'], $website->seoUrl($company['company']))?>"><?php echo $company['company']?></a>
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
