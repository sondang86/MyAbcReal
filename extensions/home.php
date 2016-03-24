<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db,$categories, $categories_subs,$commonQueries;
?>
<ul class="nav nav-tabs">
    <li class="active"><a href="#by_category"><?php echo $M_BROWSE_CATEGORY;?></a></li>
    <li><a href="#by_location"><?php echo $M_JOBS_BY_LOCATION;?></a></li>
    <li><a href="#by_company"><?php echo $M_JOBS_BY_COMPANY;?></a></li>
</ul>

<!--CATEGORIES-->
<div class="tab-content padding-5">
    <div id="by_category" class="tab-pane fade in active">
        <div class="row">
            <?php foreach ($categories as $catkey => $category) :?>
            <span class="col-md-4 main-category">
                <!--MAIN CATEGORIES-->
                <strong><a href="#"><?php echo $category['category_name_vi']?></a>(<?php echo $commonQueries->countRecords("job_category", $category['category_id'], "jobs")->count?>)</strong>

                <!--LIST SUB CATEGORIES-->
                <?php foreach ($categories_subs as $subkey => $category_subs) :
                        if ($category_subs['main_category_id'] == $category['category_id']){
                            echo "<a href='#'><em><small>". $category_subs['sub_category_name_vn'] . "</small></em></a>";
                        }
                    ?>                    
                <?php endforeach;?>
            </span>
            <?php endforeach;?>
        </div>
    </div> 
    
    <div id="by_location" class="tab-pane fade padding-5">
        <div class="row">
            <p>đạká</p>
            <p>test</p>
        </div>
    </div>        
    
    <div id="by_company" class="tab-pane fade padding-5">
        <div class="row">
            <p>đạká</p>
            <p>test</p>
        </div>
    </div>
</div>
<!--CATEGORIES--> 

<!--job tabs js-->
<script>
    $(document).ready(function(){
        $(".nav-tabs a").click(function(){
            $(this).tab('show');
        });
    });
</script>

