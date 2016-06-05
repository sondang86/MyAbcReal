<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $FULL_DOMAIN_NAME;
      
    //Page 
    if(!isset($_GET['trang']) || !$commonQueries->isLegal_Number($_GET['trang'])){
        $current_page = 1;
    } else {
        $current_page = filter_input(INPUT_GET,'trang', FILTER_SANITIZE_NUMBER_INT);
    }
    
    //Job type name, default is full time jobs
    if (isset($_GET['name'])){
        $column = filter_input(INPUT_GET,'name', FILTER_SANITIZE_STRING);
    } else {
        $column = "job_type";
    }
    
    //Job type value, default is 0 (high salary jobs)
    if (isset($_GET['job_type'])){
        $job_type = filter_input(INPUT_GET,'job_type', FILTER_SANITIZE_NUMBER_INT);
    } else {
        $job_type = '0';
    }    
   
    //Pagination options
    $url = $website->getURL_segment($website->CurrentURL(), 2);
    $reload="$FULL_DOMAIN_NAME/$url/?";//Link href        
    $jobs_by_type = $commonQueries->jobs_by_type_pagination("$column", $job_type, "$current_page", 20);
    
    //Jobs by high salary
    if ($job_type == '0'){
        $jobs_by_type = $commonQueries->jobs_by_type_pagination("salary", '3', "$current_page", 20, ">=");
    }    
?>
<div class="row">
    <section class="col-md-12">
        <h4>Tìm việc theo tính chất</h4>
    </section>
</div>

<?php if ($jobs_by_type['totalCount'] !== '0'): //Show records?>

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
                    <li <?php if($job_type == '0'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-luong-cao/">Việc làm lương cao</a></li>
                    <li <?php if($job_type == '1'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-toan-thoi-gian/">Việc làm toàn thời gian</a></li>
                    <li <?php if($job_type == '3'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-part-time/">Việc làm bán thời gian</a></li> 
                    <li <?php if($job_type == '6'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-freelancer">Việc freelancer</a></li>
                </ul>
            </div>
        </nav>

    </section>
</div>

<!--LIST-->
<div class="jobsByType_list">    
    <?php foreach ($jobs_by_type['jobs_list'] as $job) :
          $profile_pic = $commonQueries->setDefault_ifEmpty($job['company_logo'], 'sample.jpg', $job['company_logo']);
    ?>
    <section class="col-sm-6 col-xs-12 jobByType">
        <fieldset>
            <header>
                <figure>
                    <img src="<?php echo $FULL_DOMAIN_NAME;?>/images/employers/logo/<?php echo $profile_pic;?>" width="80" height="80">
                    <?php if($job['subscription'] > 1):?>
                    <label><small><i class="fa fa-check-square-o" aria-hidden="true"></i> NTD đã xác thực</small></label>
                <?php endif;?>
                </figure>
                <span>
                    <p><small><a href="http://<?php echo $DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $job['job_id']?>/<?php echo $job['SEO_title']?>"><?php echo $job['title']?></a></small></p>
                    <p><small>Ngành nghề: <?php echo $job['category_name_vi']?></small></p>
                    <p><small>Mức lương: <?php echo $job['salary_range']?></small></p>
                    <p><small>Loại công việc: <?php echo $job['job_name']?></small></p>
                </span>
            </header>
            <article>
                <p><small><?php echo $website->limitCharacters($job['message'], 100)?></small></p>
            </article>
            
            <footer>
                <a href="http://<?php echo $DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $job['job_id']?>/<?php echo $job['SEO_title']?>" class="btn btn-info">Chi tiết</a>
            </footer>
        </fieldset>        
    </section>   
    <?php endforeach;?>    
</div>


<!--PAGINATION-->
<div class="row">
    <section class="col-md-12 paginationArea">
        <?php $commonQueries->pagination($reload, $current_page, $db->totalPages, 0);?>
    </section>
</div>

<?php else : // Not found data?>
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
                    <li <?php if($job_type == '0'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-luong-cao">Việc làm lương cao</a></li>
                    <li <?php if($job_type == '1'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-toan-thoi-gian/">Việc làm toàn thời gian</a></li>
                    <li <?php if($job_type == '3'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-part-time/">Việc làm bán thời gian</a></li> 
                    <li <?php if($job_type == '6'){echo 'class="active"';}?>><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-freelancer">Việc freelancer</a></li>
                </ul>
            </div>
        </nav>
        <h5>Không tìm thấy kết quả nào</h5>
    </section>
</div>
<?php endif;?>

<!--Add active class on click-->
<script>
    $(".nav a").on("click", function(){
        $(".nav").find(".active").removeClass("active");
        $(this).parent().addClass("active");
    });
</script>