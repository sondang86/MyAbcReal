<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $DOMAIN_NAME, $website, $categories, $locations, $SEO_setting,$commonQueries, $userId_cookie, $Browser_detection;

    //Perform search    
    if (isset($_GET['q'])){$queryString = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);} else {$queryString = "";}    
    //Search with category option
    if(isset($_GET['category'])){ $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);} else {$category="";}
    //Search with location option
    if(isset($_GET['location'])){ $location = filter_input(INPUT_GET, 'location', FILTER_SANITIZE_NUMBER_INT);} else {$location="";} 

    //List jobs
    $jobs_found = $commonQueries->jobs_by_keywords($queryString,$category,$location, NULL,$userId_cookie);
?>
<!--SEARCH BAR-->
<main class="col-md-12 search-form-wrap">
    <form name="home_form" id="home_form" action="" style="margin-top:0px;margin-bottom:0px;margin-left:0px;margin-right:0px" method="GET"> 
        <?php if($SEO_setting == 0): //show search module if SEO disabled?>
        <input type="hidden" name="mod" value="search">
        <?php endif;?>
                
        <div class="text-center">
            <h4 class="bottom-margin-5">Tìm kiếm việc làm</h4>
        </div>
                
        <!--JOB TITLE-->
        <div class="col-md-4 form-group group-1">
            <span class="main-search-label"><br></span>                    
            <input type="text" name="q" class="input-job" id="title" placeholder="Từ khoá">
        </div>
                
        <!--JOB CATEGORY-->
        <div class="col-md-3 form-group group-2">
            <span id="label_category" class="main-search-label"><br></span>                    
            <select name="category" id="category" class="input-job">
                <option value=""><?php echo $M_CATEGORY;?></option>
                <?php foreach ($categories as $categoryy) :?>
                <option value="<?php echo $categoryy['category_id']?>" <?php if($categoryy['category_id'] == $category){echo "selected";}?>><?php echo $categoryy['category_name_vi']?></option>    
                <?php endforeach;?>
            </select>
        </div>
                
        <!--LOCATIONS-->
        <div class="col-md-3 form-group group-3">
            <span id="label_location" class="main-search-label"><br></span>                    
            <select class="input-location" name="location" id="location"  onchange="dropDownChange(this,'location')">
                <option value=""><?php echo $M_REGION;?></option>	
                <?php foreach ($locations as $locationn) :?>
                <option value="<?php echo $locationn['id']?>" <?php if($locationn['id'] == $location){echo "selected";}?>><?php echo $locationn['City']?></option>    
                <?php endforeach;?>
            </select>
        </div>
                
        <!--SUBMIT-->
        <div class="col-md-2 no-padding">
            <span class="main-search-label"><br></span>
            <button type="submit" class="btn custom-gradient-2 btn-default btn-green pull-right no-margin">Tìm kiếm</button>
                    
        </div>
        <div class="clearfix"></div>
    </form>
</main>
      
<!--RESULT AREA-->
<h4>Tìm thấy <?php echo $jobs_found['totalCount']?> việc:</h4>
<div class="contentArea">
    <?php if($jobs_found['totalCount'] > 0){
        foreach ($jobs_found['data'] as $job):
    ?>
    <article class="row joblistArea">
        <!--JOB DETAILS-->
        <header class="col-md-12 joblist">            
            <a href="<?php $website->check_SEO_link("details", $SEO_setting, $job['job_id'],$job['SEO_title'])?>">
                <section class="banner">
                    <img src="http://<?php echo $DOMAIN_NAME;?>/uploaded_images/<?php echo $job['company_logo']?>.jpg" width="120" height="50">
                </section>
                <p title="<?php echo $job['title']?>" class="desig"><?php echo $job['title']?></p>
                <p class="company">
                    <i class="fa fa-briefcase"></i>
                    <span><?php echo $job['company']?></span>
                </p>

                <form class="more">
                    <span class="exp"><i class="fa fa-comments-o"></i> <?php echo $job['job_name']?></span>
                    <span class="loc">
                        <i class="fa fa-location-arrow"></i>
                        <span><?php echo $job['City']?></span>            
                    </span> 
                </form>
                
                <form class="more"> 
                    <i class="fa fa-diamond"></i> <span> Chuyên ngành:</span>
                    <span class="desc"> 
                        <p iclass="skill"><?php echo $job['category_name_vi']?></p> 
                    </span>  
                </form>
                
                <form class="more"> 
                    <i class="fa fa-money"></i> <span> Mức lương:</span>
                    <span class="experience"> 
                        <p><?php echo $job['salary_range']?></p> 
                    </span>  
                </form>
            </a>
            <?php if($job['featured'] == "1"):?>
            <span class="featuredjob" title="Featured Job">
                <i class="fa fa-star"></i>
            </span> 
            <?php endif;?>
        </header>        
                
        <!--MORE BOTTOM-->        
        <footer class="col-md-12 more-details">           
            <section class="col-md-6 col-xs-6 other_details">
                <span title=" Save this job " class="action savejob fav  favReady">
                    
                    <?php if(($job['saved_jobId'] !== $job['job_id']) || ($userId_cookie !== $job['user_uniqueId']) || ($job['IPAddress'] !== filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP))){ //Show save job button?>                    
                        
                        <a href="#" data-browser="<?php echo $Browser_detection->getName();?>" data-category="<?php echo $job["category_id"]?>"  data-jobid="<?php echo $job["job_id"]?>" title="Lưu việc làm này" class="savethisJob" id="<?php echo $job["job_id"]?>" onclick="javascript:saveJob(this, sitePath)"><i class="fa fa-floppy-o"></i>  Lưu việc làm này</a>

                    <?php } else { // Show saved ?>

                        <a href="#" title="Đã lưu" class="savethisJob" id="<?php echo $job["job_id"]?>"><i class="fa fa-check"></i>Đã lưu việc này</a>                    

                    <?php }?>
                        
                </span> 
                <span class="salary"><em></em>  Not disclosed </span> 
            </section>
            <section class="col-md-6 col-xs-6 rec_details">
                <span> Đăng bởi   <a title="việc làm đăng bởi  <?php echo $job['company']?> " class="rec_name">  <?php echo $job['company']?>  </a></span> 
                <span><i class="fa fa-clock-o"></i> <?php echo $commonQueries->time_ago($job['date']);?> </span>
            </section>
        </footer>    
    </article>
    <?php endforeach;
        } else { //No jobs found
    ?>
        <h4>Không tìm thấy việc phù hợp với tiêu chí đặt ra. Bạn vui lòng thử lại</h4>
    <?php }
    
    ?> 
        
    <!--PAGINATION-->
    <div class="row">
        <section class="col-md-12 paginationArea">
            <?php 
                //Make sure default pagination works correctly
                if ($queryString == ""){ $reload = "?"; } else { $reload = "?q=$queryString&"; }
                $commonQueries->pagination($reload, $jobs_found['current_page'], $jobs_found['total_pages'], 0);
            ?>
        </section>
    </div>
</div>    
<?php
$website->Title("Tìm kiếm");
$website->MetaDescription("");
$website->MetaKeywords("");
?>