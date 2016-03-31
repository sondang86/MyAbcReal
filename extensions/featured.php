<?php
if(!defined('IN_SCRIPT')) die("");
global $db,$categories, $categories_subs,$commonQueries, $locations, $companies,$featured_jobs,$SEO_setting;
$website->Title("Việc làm nổi bật");
$website->MetaDescription("abc");
$website->MetaKeywords("def");

//echo "<pre>";
//print_r($website->check_SEO_link());
//echo "</pre>";
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