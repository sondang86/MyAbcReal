<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
?><?php
    if(!defined('IN_SCRIPT')) die("");
    global $companies, $SEO_setting;
?>
<div class="container">
    <div class="row">
        <section class="bxslider companyLogo">
            <?php foreach ($companies as $company) :?>
            <figure class="slide">
                <a href="<?php $website->check_SEO_link("companyInfo", $SEO_setting, $company['id'],$website->seoUrl($company['company']));?>">
                    <img src="http://<?php echo $DOMAIN_NAME;?>/uploaded_images/<?php echo $company['logo']?>.jpg" title="<?php echo $company['company']?>">
                </a>
            </figure>
            <?php endforeach;?>
        </section>
    </div>
</div>