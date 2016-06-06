<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $DOMAIN_NAME, $FULL_DOMAIN_NAME;
    
    
    //Page setting
    if(!isset($_GET['trang']) || !$commonQueries->isLegal_Number($_GET['trang'])){
        $current_page = 1;
    } else {
        $current_page = filter_input(INPUT_GET,'trang', FILTER_SANITIZE_NUMBER_INT);
    }     
    $jobseekers_list = $commonQueries->Search_Resumes(FALSE, '', $current_page);
    
    //Pagination options
    if (isset($_GET['page']) && ($_GET['page'] == 'vn_ung-vien')){
        $url = 'ung-vien';
    } else {
        $url = filter_input(INPUT_GET, 'url' ,FILTER_SANITIZE_STRING);   
    }
            
    $reload="http://$DOMAIN_NAME/$url/?";//Link href        
    print_r($_GET);
?>

<!--NAV BAR-->
<div class="row">
    <section class="col-md-12">
        <nav class="navbar navbar-inverse">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li <?php if($url == 'ung-vien-noi-bat' || $url == 'ung-vien'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/ung-vien-noi-bat/">Ứng viên nổi bật</a></li>
                    <li <?php if($url == 'ung-vien-moi-nhat'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/ung-vien-moi-nhat/">Ứng viên mới</a></li>
                </ul>
            </div>
        </nav>
        
    </section>
</div>

<!--LIST-->
<div class="jobsByType_list">    
    <?php foreach ($jobseekers_list['resumes'] as $resume) :
        $profile_pic = $commonQueries->setDefault_ifEmpty($resume['profile_pic'], 'sample.jpg', $resume['profile_pic']);        
    ?>
    <section class="col-sm-6 col-xs-12 jobByType">
        <fieldset>
            <header>
                <figure><img src="<?php echo $FULL_DOMAIN_NAME;?>/images/jobseekers/profile_pic/<?php echo $profile_pic;?>" width="80" height="80"></figure>
                <span>
                    <p><small><a href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-ung-vien/<?php echo $resume['resume_id']?>/<?php echo $website->seoUrl($resume['resume_title'])?>"><?php echo $resume['resume_title']?></a></small></p>
                    <p><small>Ngành nghề: <?php echo $resume['category_name_vi']?></small></p>
                    <p><small>Mức lương: <?php echo $resume['salary_range']?></small></p>
                    <p><small>Loại công việc: <?php echo $resume['job_name']?></small></p>
                </span>
            </header>
            <article>
                <p><small><?php // echo $website->limitCharacters($resume['message'], 100)?></small></p>
            </article>
            
            <footer>
                <a href="http://<?php echo $DOMAIN_NAME;?>/chi-tiet-ung-vien/<?php echo $resume['resume_id']?>/<?php echo $website->seoUrl($resume['resume_title'])?>" class="btn btn-info">Chi tiết</a>
            </footer>
            
        </fieldset>        
    </section>   
    <?php endforeach;?>    
</div>

<!--PAGINATION-->
<div class="row">
    <section class="col-md-12 paginationArea">
        <?php $commonQueries->pagination($reload, $current_page, $jobseekers_list['totalPages'], 0);?>
    </section>
</div>

<?php  
    //SEO optimization
    $website->Title("Danh sách ứng viên tốt nhất được cập nhật liên tục tại vieclambanthoigian.com.vn");
    $website->MetaDescription($website->limitCharacters('Danh sách hồ sơ ứng viên tìm việc chất lượng cao được cập nhật mỗi ngày tại vieclambanthoigian.com.vn. Cơ hội tuyển chọn nhân tài cho nhà tuyển dụng.', 120));
    $website->MetaKeywords("");
?>