<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
?><?php
    if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
    global $db, $SEO_setting,$FULL_DOMAIN_NAME;
    
    $companies = $db->where('subscription', '1', ">")->get('employers');
?>
<style>
    .bx-wrapper {
        max-width: 100% !important;
    }
    
    .slider {
        position: relative;
        display: block;
    }
    
    .slider section.title {
        position: absolute;
        top: 25px;
    }
</style>

<div class="container hide-sm ">
    <div class="row slider">
        <section class="title"><label>Nhà tuyển dụng nổi bật: </label></section>
        <section class="bxslider companyLogo">
            <?php foreach ($companies as $company) :
                if($company['logo'] == ""){ 
                continue; //Don't display missing logo companies
            }?>
            <figure class="slide">
                <a href="<?php $website->check_SEO_link("companyInfo", $SEO_setting, $company['id'],$website->seoUrl($company['company']));?>">
                    <img src="<?php echo $FULL_DOMAIN_NAME;?>/images/employers/logo/<?php echo $company['logo']?>" title="<?php echo $company['company']?>" width="150" height="100">
                </a>
            </figure>
            <?php endforeach;?>
        </section>        
    </div>
</div>