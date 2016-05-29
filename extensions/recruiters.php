<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db,$commonQueries, $SEO_setting, $FULL_DOMAIN_NAME;   
    
    //Pagination options
    $reload = $FULL_DOMAIN_NAME."/nha-tuyen-dung/?";//Link href
    //Set current page to 1 if empty
    if(!isset($_GET['trang']) || !$commonQueries->isLegal_Number($_GET['trang'])){
        $current_page = 1;
    } else {
        $current_page = filter_input(INPUT_GET,'trang', FILTER_SANITIZE_NUMBER_INT);
    }
    
    // set page limit to 2 results per page. 20 by default
    $db->pageLimit = 5;
    $companies = $db->arraybuilder()->paginate("employers", $current_page);  
    
    
    
    $jobs_by_employers = $commonQueries->jobs_by_employerId(NULL);
    
    if ($jobs_by_employers !== FALSE){
        //get jobs by each employer
        $jobs_by_employer = array();
        foreach ($jobs_by_employers as $key => $value) {
            $jobs_by_employer[] = array(
                $value['company'] => $value['title'],
                'job_id' => $value['job_id'],
                'SEO_title' => $value['SEO_title']
            );
        }
    }    
?>

<?php if($_SESSION['logged_in'] !== TRUE):?>
<div class="row">
    <section class="col-md-12 ">
        <h5>Bạn chưa có tài khoản? Đăng ký tại <a href="<?php echo $FULL_DOMAIN_NAME;?>/mod-vn-employers_registration.html">đây</a></h5>
    </section>
</div>
<?php endif;?>

<div class="row">
    <section class="col-md-12 ">
        <h5><label><strong>Danh sách nhà tuyển dụng nổi bật</strong></label></h5>
    </section>
</div>
<?php foreach ($companies as $company):
    //Count total jobs of employer
    $db->where("employer", $company['username'])->withTotalCount()->get("jobs");    
?>
<div class="recruiter">    
    <!--TOTAL JOBS-->
    <div class="row recruiterTitle">
        <section class="col-md-12">
            <span class="totalJobs">
                <a href="<?php $website->check_SEO_link("jobs_by_companyId",$SEO_setting, $company['id'], $website->seoUrl($company['company']))?>"><?php echo $db->totalCount;?> Việc làm</a>
            </span>
        </section>
    </div>
    
    <div class="row recruiterDetails">
        <!--COMPANY INFO-->
        <section class="col-md-3 col-xs-12">
            <!--<p><a href="<?php $website->check_SEO_link("companyInfo",$SEO_setting, $company['id'], $website->seoUrl($company['company']))?>"><?php echo $company['company']?></a></p>-->
            <a href="<?php $website->check_SEO_link("companyInfo",$SEO_setting, $company['id'], $website->seoUrl($company['company']))?>">
                <img align="left" class="img-right-margin img-responsive" src="http://<?php echo $DOMAIN_NAME?>/images/employers/logo/<?php echo $company['logo']?>">
            </a>
        </section>
        
        <!--ADDRESS-->
        <section class="col-md-4 col-xs-12 recruiterContact">                    
            <strong><p>Địa chỉ:</p></strong>
            <p><?php echo $company['address']?></p>                        
            <figure><img alt="phone icon" src="http://<?php echo $DOMAIN_NAME?>/images/phone_icon.png"><span> <?php echo $company['phone']?></span></figure>
            <p><a rel="nofollow" href="<?php echo $company['website']?>"><?php echo $company['website']?></a></p>                        
        </section>
        
        <!--LATEST JOBS-->
        <?php if($db->totalCount > 0){ //Found jobs?>
        <section class="col-md-5 col-xs-12 min-height-150 recruiterNewestJobs">
            <strong><p>Việc làm mới nhất</p></strong>            
            <ul class="padding-left-15 top-bottom-margin">
                <?php foreach($jobs_by_employer as $key => $job):?>
                    <?php if(!empty($job[$company['company']])):?>
                    <li>
                        <a href="<?php $website->check_SEO_link("details",$SEO_setting, $job['job_id'], $job['SEO_title'])?>" title="" target="_blank">
                            <?php echo $job[$company['company']];?>
                        </a>
                    </li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
            <p><a href="<?php $website->check_SEO_link("jobs_by_companyId",$SEO_setting, $company['id'], $website->seoUrl($company['company']))?>" class="small-font underline-link">Xem toàn bộ</a></p>                                                            
        </section>
        <?php }?>  
    </div>
</div>
<?php endforeach;?>

<!--PAGINATION-->
<div class="row">
    <section class="col-md-12 paginationArea">
        <?php $commonQueries->pagination($reload, $current_page, $db->totalPages, 0);?>
    </section>
</div>