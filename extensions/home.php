<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db,$categories, $categories_subs,$commonQueries, $locations, $companies,$featured_jobs;
//echo "<pre>";
//print_r($featured_jobs);
//echo "</pre>";
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
    <li><a href="#by_latest"><i class="fa fa-newspaper-o"></i> <?php echo $M_LATEST_JOBS;?></a></li>
</ul>

<!--CATEGORIES-->
<section class="tab-content">        
    <!--BY FEATURED-->
    <div id="by_featured" class="tab-pane fade in active">
        <?php foreach ($featured_jobs as $featured_job) :?>
        <div class="row joblistArea">
            <div class="col-md-12 joblist">
                <a href="#">
                    <section class="banner">
                        <img alt="SKP Business Consulting LLP" src="http://img.naukimg.com/logo_images/v2/25773.gif">
                    </section>
                    <p title="<?php echo $featured_job['title']?>" class="desig"><?php echo $featured_job['title']?></p>
                    <p class="company">
                        <i class="fa fa-briefcase"></i>
                        <span><?php echo $featured_job['company']?></span>
                    </p>
                    <form>
                        <span class="exp"><i class="fa fa-comments-o"></i> 2-6 yrs</span>
                        <span class="loc">
                            <i class="fa fa-location-arrow"></i>
                            <span>Mumbai, Chennai</span>            
                        </span> 
                    </form>
                    <form class="more"> 
                        <i class="fa fa-diamond"></i><span> Keyskills:</span>
                        <span class="desc"> 
                            <p itemprop="skills" class="skill">Accounting, Taxation, FEMA, Assessment, Business Advisory...</p> 
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
                        <i class="fa fa-floppy-o"></i>
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
    
    <!--BY LATEST-->
    <div id="by_latest" class="tab-pane fade padding-5">
        <div class="row same_height">
            <?php foreach ($locations as $location) :?>
            <span class="col-md-3 main-category">
                <a href="index.php?mod=location&id=<?php echo $location['id']?>&lang=vn" class="main_category_link"><?php echo $location['City']?></a>
            </span>
            <?php endforeach;?>
        </div>
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
                <p><a href="index.php?mod=category&id=<?php echo $category['category_id']?>&lang=vn"><?php echo $category['category_name_vi']?></a>(<?php echo $commonQueries->countRecords("job_category", $category['category_id'], "jobs")->count?>)</p>
                
                <!--LIST SUB CATEGORIES-->
                <?php // foreach ($categories_subs as $subkey => $category_subs) :
//                        if ($category_subs['main_category_id'] == $category['category_id']){
//                            echo "<small><a href='#'><em><small>". $category_subs['sub_category_name_vn'] . "</small></em></a></small>";
//                        }
                ?>                    
                <?php // endforeach;?>
            </span>
            <?php endforeach;?>
        </div>
    </div>    
    <!--BY LOCATIONS-->
    <div id="by_location" class="tab-pane fade padding-5">
        <div class="row same_height">
            <?php foreach ($locations as $location) :?>
            <span class="col-md-3 main-category">
                <a href="index.php?mod=location&id=<?php echo $location['id']?>&lang=vn" class="main_category_link"><?php echo $location['City']?></a>
            </span>
            <?php endforeach;?>
        </div>
    </div>        
    
    <!--BY COMPANIES-->
    <div id="by_company" class="tab-pane fade padding-5">
        <div class="row same_height">
            <?php foreach ($companies as $company) :?>
            <span class="col-md-3 main-category">
                <a href="index.php?mod=company&id=<?php echo $company['id']?>&lang=vn"><?php echo $company['company']?></a>
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
