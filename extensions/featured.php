<?php
if(!defined('IN_SCRIPT')) die("");
global $db,$categories, $categories_subs,$commonQueries, $locations, $companies,$featured_jobs,$SEO_setting;
$website->Title("Việc làm nổi bật");
$website->MetaDescription("abc");
$website->MetaKeywords("def");
    
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
    
//Pagination options
$reload="http://localhost/vieclambanthoigian.com.vn/viec-lam-noi-bat/?";//Link href
//Set current page to 1 if empty
if (isset($_GET['trang'])){
    $current_page = filter_input(INPUT_GET, 'trang', FILTER_SANITIZE_NUMBER_INT);
} else {
    $current_page = 1;
}
// set page limit to 2 results per page. 20 by default
$db->pageLimit = 3;
$featured_jobs = $db->arraybuilder()->paginate("jobs", $current_page,$featured_jobs_columns);    
?>
    
<div class="row">
    <!--TITLE-->
    <section class="col-md-12">
        <label>Việc làm nổi bật</label>
    </section>
        
    <!--LIST JOBS-->
        <?php foreach ($featured_jobs as $job) :?>
    <div class="col-md-12 category-details">
        <section class="row">
            <figure class="col-md-3">
                <img src="http://<?php echo $DOMAIN_NAME?>/uploaded_images/<?php echo $job['logo']?>.jpg">
                <p>Việc làm khác từ TEK</p>
                <p>Thông tin công ty</p>
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
                        <?php if($SEO_setting == 0){?>
                <span class="col-md-3 pull-right"><a href="http://<?php echo $DOMAIN_NAME?>/index.php?mod=apply_job&posting_id=<?php echo $job['job_id']?>&lang=vn">Nộp hồ sơ</a></span>
                <span class="col-md-3 pull-right"><a href="http://<?php echo $DOMAIN_NAME?>/index.php?mod=details&id=<?php echo $job['job_id']?>&lang=vn">Chi tiết công việc</a></span>
                        <?php }else {?>
                <span class="col-md-3 pull-right"><a href="http://<?php echo $DOMAIN_NAME?>/nop-ho-so/<?php echo $job['job_id']?>/<?php echo $job['SEO_title']?>">Nộp hồ sơ</a></span>
                <span class="col-md-3 pull-right"><a href="http://<?php echo $DOMAIN_NAME?>/chi-tiet-cong-viec/<?php echo $job['job_id']?>/<?php echo $job['SEO_title']?>">Chi tiết công việc</a></span>
                        <?php }?>
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