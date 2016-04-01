<?php
if(!defined('IN_SCRIPT')) die("");
global $db,$categories, $categories_subs,$commonQueries, $locations, $companies,$featured_jobs,$SEO_setting;
$website->Title("Việc làm theo công ty");
$website->MetaDescription("abc");
$website->MetaKeywords("def");

$employerId = $commonQueries->check_present_id("company", $SEO_setting);

$jobs_by_employerId = $commonQueries->jobs_by_employerId($employerId);

//echo "<pre>";
//print_r($jobs_by_employerId);
//echo"</pre>";
?>

<div class="row">
    <!--TITLE-->
    <section class="col-md-12">
        <label>Tên công ty: </label>
        <label><?php echo $jobs_by_employerId[0]['company']?></label>
    </section>
    
    <!--LIST JOBS-->
        <?php 
            if($jobs_by_employerId !== FALSE){
                foreach ($jobs_by_employerId as $job) :
            ?>
                <div class="col-md-12 category-details">
                    <section class="row">
                        <figure class="col-md-3">
                            <img src="http://<?php echo $DOMAIN_NAME?>/uploaded_images/<?php echo $job['logo']?>.jpg">
                            <!--<p><a href="<?php echo $website->check_SEO_link("jobs_by_companyId", $SEO_setting, $job["employer_id"],$website->seoURL($job["company"]));?>" class="sub-text underline-link"><?php echo $M_MORE_JOBS_FROM;?> <?php echo stripslashes($job["company"]);?></a></p>-->
                            <p><a href="<?php echo $website->check_SEO_link("companyInfo", $SEO_setting, $job["employer_id"],$website->seoURL($job['company']));?>" class="sub-text underline-link"><?php echo $M_COMPANY_DETAILS;?></a></p>
                        </figure>
                        <article class="col-md-9">
                            <h5><strong>Tiêu đề công việc:</strong> <?php echo $job['title']?></h5>
                            <p><label>Mức lương (USD):</label> <?php echo $job['salary_range']?></p>
                            <p><label>Nơi làm việc:</label> <?php echo $job['City']?></p>
                            <p><label>Ngày đăng:</label> <?php // echo date('Y-m-d',$job['date'])?></p>
                            <p><label>Ngày hết hạn:</label> <?php // echo date('Y-m-d',$job['expires'])?></p>
                            <p><label>Ứng viên đã ứng tuyển:</label> <?php // echo $job['applications']?></p>
                            <main><?php echo $website->limitCharacters(nl2br($job['message']), 200)?></main>
                        </article>
                        <nav>                            
                            <span class="col-md-3 pull-right"><a href="<?php $website->check_SEO_link("details", $SEO_setting, $job['job_id'], $job['SEO_title']);?>">Chi tiết công việc</a></span> 
                            <span class="col-md-3 pull-right"><a href="<?php $website->check_SEO_link("apply_job", $SEO_setting, $job['job_id'], $website->seoURL($job['SEO_title']));?>">Nộp hồ sơ</a></span>                                                       
                        </nav>
                    </section>    
                </div>
        <?php   endforeach;
            } else { //No records found ?>
                <div class="col-md-12 category-details">
                    <h4>No records found!</h4>
                </div>
        <?php }?>
</div>