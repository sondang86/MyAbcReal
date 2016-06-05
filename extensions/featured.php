<?php
if(!defined('IN_SCRIPT')) die("");
global $db,$categories, $categories_subs,$commonQueries,$commonQueries_Front, $locations, $companies, $SEO_setting, $FULL_DOMAIN_NAME;
$website->Title("Việc làm nổi bật");
$website->MetaDescription("abc");
$website->MetaKeywords("def");
        
//Pagination options
$reload= $FULL_DOMAIN_NAME."/viec-lam-noi-bat/?";//Link href
//Set current page to 1 if empty
    if(!isset($_GET['trang']) || !$commonQueries->isLegal_Number($_GET['trang'])){
        $current_page = 1;
    } else {
        $current_page = filter_input(INPUT_GET,'trang', FILTER_SANITIZE_NUMBER_INT);
    }
    // set page limit to 2 results per page. 20 by default
    $pageLimit = '5';    
    $featured_jobs = $commonQueries_Front->getJobsList_pagination('featured', $current_page, $pageLimit);
?>
    
<div class="row">
    <!--TITLE-->
    <section class="col-md-12">
        <label>Việc làm nổi bật</label>
    </section>
        
    <!--LIST JOBS-->
        <?php foreach ($featured_jobs['pagination_jobslist'] as $job) :?>
        <?php //Set to default logo if empty
            $company_logo = $commonQueries->setDefault_logoIfEmpty($job['logo'], "employers");
        ?>
    <div class="col-md-12 category-details">
        <section class="row">
            <figure class="col-md-3">
                <img src="<?php echo $company_logo;?>">
                <?php if($job['subscription'] > 1):?>
                <p><label><i class="fa fa-check-square-o" aria-hidden="true"></i> NTD đã xác thực</label></p>
                <?php endif;?>
                
                <p><a href="#">Việc làm khác từ <?php echo $job['company']?></a></p>
                <p><a href="#">Thông tin công ty</a></p>
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
        <?php $commonQueries->pagination($reload, $current_page, $featured_jobs['totalPages'], 0);?>
    </section>
</div>
