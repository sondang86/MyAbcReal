<?php
// Jobs Portal
// Copyright (c) All Rights Reserved Vieclambanthoigian.com.vn
if(!defined('IN_SCRIPT')) die("");
global $db,$pagination, $categories, $categories_subs,$commonQueries, $commonQueries_Front, $locations, $companies,$SEO_setting, $Browser_detection, $userId_cookie, $FULL_DOMAIN_NAME;

$featured_jobs = $commonQueries_Front->getJobsList('featured', NULL, $userId_cookie);
$urgent_jobs = $commonQueries_Front->getJobsList('urgent', NULL, $userId_cookie);
$latest_jobs = $commonQueries_Front->getLatestJobsList(FALSE);

$segment = $website->getURL_segment($website->currentURL());
?>

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
    <div id="by_urgent" class="tab-pane fade in active perfectScrollbar">
        <?php include_once ('templates/home_urgent_jobs.php');?>
    </div>
    
    
    <!--BY FEATURED-->
    <div id="by_featured" class="tab-pane fade in perfectScrollbar">
        <?php include_once ('templates/home_featured_jobs.php');?>
    </div>
</section>

<!--LATEST JOBS-->
<section class="row stats top-title">
    <header class="col-md-12">
        <h4>Việc làm mới nhất: </h4>
    </header>
</section>

<ul class="nav nav-tabs">
    <li class="active"><a href="#by_latest"><i class="fa fa-eyedropper"></i> Việc làm mới nhất</a></li>
</ul>


<section class="tab-content">    
    <!--BY LATEST-->
    <div id="by_urgent" class="tab-pane fade in active perfectScrollbar">
        <?php include_once ('templates/home_latest_jobs.php');?>
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
