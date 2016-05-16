<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db,$companies, $commonQueries, $SEO_setting;    
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