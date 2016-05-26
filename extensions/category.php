<?php
if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
global $db, $SEO_setting, $commonQueries;
$website->Title("Việc làm");
$website->MetaDescription("abc");
$website->MetaKeywords("def");
if (isset($_GET['id'])){
    //sanitize first
    $website->ms_i($_GET['id']); 
    
    //Find jobs based on specific category
    
    $jobs_list = $commonQueries->jobs_by_type("job_category");

    
    if ($jobs_list !== FALSE){ //Found records      
    
?>

<div class="row">
    <!--TITLE-->
    <section class="col-md-12">
        <label>Ngành: <?php echo $jobs_list[0]['category_name_vi'];?></label>
    </section>
    
    <!--LIST JOBS-->
        <?php foreach ($jobs_list as $job) :?>
            <div class="col-md-12 category-details">
                <section class="row">
                    <figure class="col-md-3">
                        <img src="<?php echo $commonQueries->setDefault_logoIfEmpty($job['company_logo'], "employers")?>">
                        <p><a href="<?php echo $website->check_SEO_link("jobs_by_companyId", $SEO_setting, $job["employer_id"], $website->seoURL($job["company"]));?>">Việc làm khác từ <?php echo $job["company"]?></a></p>
                        <p><a href="<?php echo $website->check_SEO_link("companyInfo", $SEO_setting, $job["employer_id"], $website->seoURL($job["company"]));?>">Thông tin công ty</a></p>
                    </figure>
                    <article class="col-md-9">
                        <h5><strong>Tiêu đề công việc:</strong> <?php echo $job['title']?></h5>
                        <p><label>Mức lương (USD):</label> <?php echo $job['salary_range']?></p>
                        <p><label>Nơi làm việc:</label> <?php echo $job['City']?></p>
                        <p><label>Ngày đăng:</label> <?php echo date('Y-m-d',$job['date'])?></p>
                        <p><label>Ngày hết hạn:</label> <?php echo date('Y-m-d',$job['expires'])?></p>
                        <p><label>Ứng viên đã ứng tuyển:</label> <?php echo $job['applications']?></p>
                        <main><?php echo $website->limitCharacters(nl2br($job['message']), 200)?></main>
                    </article>
                    <nav>
                        <span class="col-md-3 pull-right"><a href="<?php echo $website->check_SEO_link("apply_job", $SEO_setting, $job["job_id"], $website->seoURL($job["title"]));?>">Nộp hồ sơ</a></span>
                        <span class="col-md-3 pull-right"><a href="<?php echo $website->check_SEO_link("details", $SEO_setting, $job["job_id"], $website->seoURL($job["title"]));?>">Chi tiết công việc</a></span>
                    </nav>
                </section>    
            </div>
        <?php endforeach;?>
</div>
    <?php } else { // not found any records
     echo "Oopps, Not found any records!";
 }
}
?>