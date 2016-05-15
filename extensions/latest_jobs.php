<?php
if(!defined('IN_SCRIPT')) die("");
global $db,$categories, $categories_subs,$commonQueries, $locations, $companies,$featured_jobs,$SEO_setting;
$website->Title("Việc làm mới nhất");
$website->MetaDescription("abc");
$website->MetaKeywords("def");
    
$latest_jobs_columns = array(
    $DBprefix."jobs.id as job_id",$DBprefix."jobs.job_category",$DBprefix."jobs.title",
    $DBprefix."jobs.SEO_title",$DBprefix."jobs.date",$DBprefix."jobs.expires",
    $DBprefix."jobs.applications",$DBprefix."jobs.message",
    $DBprefix."employers.id as employer_id",$DBprefix."employers.company",$DBprefix."employers.logo",
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
$db->orderBy($DBprefix."jobs.date", "DESC");


//Pagination options
$reload="http://$DOMAIN_NAME/viec-lam-moi-nhat/?";//Link href
//Set current page to 1 if empty
if (isset($_GET['trang'])){
    $current_page = filter_input(INPUT_GET, 'trang', FILTER_SANITIZE_NUMBER_INT);
} else {
    $current_page = 1;
}
// set page limit to 2 results per page. 20 by default
$db->pageLimit = 3;
$latest_jobs = $db->arraybuilder()->paginate("jobs", $current_page,$latest_jobs_columns);   

?>
    
<div class="row">
    <!--TITLE-->
    <section class="col-md-12">
        <label>Việc làm mới nhất</label>
    </section>
        
    <!--LIST JOBS-->
        <?php foreach ($latest_jobs as $job) :?>
    <div class="col-md-12 category-details">
        <section class="row">
            <figure class="col-md-3">
                <img src="http://<?php echo $DOMAIN_NAME?>/images/employers/logo/<?php echo $job['logo']?>">
                <p><a href="<?php echo $website->check_SEO_link("jobs_by_companyId", $SEO_setting, $job["employer_id"], $website->seoURL($job['company']));?>" class="sub-text underline-link"><?php echo $M_MORE_JOBS_FROM;?> <?php echo stripslashes($job["company"]);?></a></p>
                <p><a href="<?php echo $website->check_SEO_link("companyInfo", $SEO_setting, $job["employer_id"]), $website->seoURL($job['company']);?>" class="sub-text underline-link"><?php echo $M_COMPANY_DETAILS;?></a></p>
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
                <span class="col-md-3 pull-right"><a href="<?php $website->check_SEO_link("apply_job", $SEO_setting, $job['job_id'],$job['SEO_title'])?>">Nộp hồ sơ</a></span>
                <span class="col-md-3 pull-right"><a href="<?php $website->check_SEO_link("details", $SEO_setting, $job['job_id'],$job['SEO_title'])?>">Chi tiết công việc</a></span>
            </nav>
        </section>    
    </div>
        <?php endforeach;?>
</div>

<!--PAGINATION-->
<div class="row">
    <section class="col-md-12 paginationArea">
        <?php $commonQueries->pagination($reload, $current_page, $db->totalPages, 0);?>
    </section>
</div>
