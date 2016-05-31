<?php
    if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
    global $db, $website, $SEO_setting, $commonQueries, $commonQueries_Front, $FULL_DOMAIN_NAME;
    $featured_jobs = $commonQueries_Front->getJobsList('featured', 5);
    $urgent_jobs = $commonQueries_Front->getJobsList('urgent', 5);

?>

<?php if (empty($_GET['page']) || $_GET['page'] !== 'vn_ung-vien'):?>
<!--Job by attribute-->
<div class="gray-wrap">
    <header class="row top-bottom-margin">
        <h4 class="aside-header"><i class="fa fa-tags"></i> Việc làm theo tính chất</h4>
    </header>
    <section class="row">
        <article class="col-md-12">
            <ul>
                <li><a href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-luong-cao/">Việc làm lương cao</a></li>
                <li><a href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-toan-thoi-gian/">Việc làm toàn thời gian</a></li>
                <li><a href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-part-time/">Việc làm thêm, sinh viên, part-time</a></li>
                <li><a href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-freelancer/">Việc làm freelance</a></li>
            </ul>
        </article>
    </section>
</div>

<!--URGENT JOBS-->
<div class="gray-wrap">
    <header class="row">
        <h4 class="col-md-12 aside-header">
            <i class="fa fa-newspaper-o"></i> 
            Việc tuyển dụng gấp        
        </h4>
    </header>
    <hr class="top-bottom-margin">
    <?php foreach ($urgent_jobs['jobs_list'] as $urgent_job):?>
    <div class="row">
        <article class="col-md-12">
            <div class="row">
                <div class="col-md-12 aside-content">
                    <!--Content-->
                    <h5 class="no-margin">
                        <a href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $urgent_job['job_id']?>/<?php echo $urgent_job['SEO_title']?>" class="aside-link" title="<?php echo $urgent_job['title']?>">
                            <?php echo $website->limitCharacters($urgent_job['title'],50)?>                     
                        </a>
                        <p class="sub-text">
                            <?php echo $website->limitCharacters($urgent_job['message'],250)?>
                        </p>
                        <p class="sub-text"><strong><?php echo $commonQueries->time_ago($urgent_job['date']);?> </strong></p>
                    </h5>               
                    <hr class="top-bottom-margin">
                </div>    
            </div>
        </article>
    </div>
    <?php endforeach;?>
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/viec-tuyen-dung-gap/">Xem toàn bộ</a></div>    
</div>


<!--FEATURED JOBS-->
<div class="gray-wrap">
    <header class="row">
        <h4 class="col-md-12 aside-header">
            <i class="fa fa-newspaper-o"></i> 
            Việc làm nổi bật        
        </h4>
    </header>
    <hr class="top-bottom-margin">
    <div class="row">
        <article class="col-md-12">
            <?php foreach ($featured_jobs['jobs_list'] as $featured_job):?>
            <div class="row">
                <div class="col-md-12 aside-content">
                    <!--Content-->
                    <h5 class="no-margin">
                        <a href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $featured_job['job_id']?>/<?php echo $featured_job['SEO_title']?>" class="aside-link" title="<?php echo $featured_job['title']?>">
                            <?php echo $website->limitCharacters($featured_job['title'],50)?>                     
                        </a>
                        <p class="sub-text">
                            <?php echo $website->limitCharacters($featured_job['message'],250)?>
                        </p>
                        <p class="sub-text"><strong><?php echo $commonQueries->time_ago($featured_job['date']);?> </strong></p>
                    </h5>              
                    <hr class="top-bottom-margin">
                </div>    
            </div>
            <?php endforeach;?>
        </article>
    </div>
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/viec-lam-noi-bat/">Xem toàn bộ</a></div>    
</div>

<?php else: //List candidates?>

<!--Featured candidates-->
<div class="gray-wrap">
    <header class="row">
        <h4 class="col-md-12 aside-header">
            <i class="fa fa-newspaper-o"></i> 
            Ứng viên nổi bật        
        </h4>
    </header>
    <hr class="top-bottom-margin">
    <?php foreach ($urgent_jobs['jobs_list'] as $urgent_job):?>
    <div class="row">
        <article class="col-md-12">
            <div class="row">
                <div class="col-md-12 aside-content">
                    <!--Content-->
                    <h5 class="no-margin">
                        <a href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $urgent_job['job_id']?>/<?php echo $urgent_job['SEO_title']?>" class="aside-link" title="<?php echo $urgent_job['title']?>">
                            <?php echo $website->limitCharacters($urgent_job['title'],50)?>                     
                        </a>
                        <p class="sub-text">
                            <?php echo $website->limitCharacters($urgent_job['message'],250)?>
                        </p>
                        <p class="sub-text"><strong><?php echo $commonQueries->time_ago($urgent_job['date']);?> </strong></p>
                    </h5>               
                    <hr class="top-bottom-margin">
                </div>    
            </div>
        </article>
    </div>
    <?php endforeach;?>
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/viec-tuyen-dung-gap/">Xem toàn bộ</a></div>    
</div>

<!--Newest candidates-->
<div class="gray-wrap">
    <header class="row">
        <h4 class="col-md-12 aside-header">
            <i class="fa fa-newspaper-o"></i> 
            Ứng viên mới nhất        
        </h4>
    </header>
    <hr class="top-bottom-margin">
    <?php foreach ($urgent_jobs['jobs_list'] as $urgent_job):?>
    <div class="row">
        <article class="col-md-12">
            <div class="row">
                <div class="col-md-12 aside-content">
                    <!--Content-->
                    <h5 class="no-margin">
                        <a href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $urgent_job['job_id']?>/<?php echo $urgent_job['SEO_title']?>" class="aside-link" title="<?php echo $urgent_job['title']?>">
                            <?php echo $website->limitCharacters($urgent_job['title'],50)?>                     
                        </a>
                        <p class="sub-text">
                            <?php echo $website->limitCharacters($urgent_job['message'],250)?>
                        </p>
                        <p class="sub-text"><strong><?php echo $commonQueries->time_ago($urgent_job['date']);?> </strong></p>
                    </h5>               
                    <hr class="top-bottom-margin">
                </div>    
            </div>
        </article>
    </div>
    <?php endforeach;?>
    <div class="text-center"><a class="underline-link" href="<?php echo $FULL_DOMAIN_NAME;?>/viec-tuyen-dung-gap/">Xem toàn bộ</a></div>    
</div>
<?php endif;?>