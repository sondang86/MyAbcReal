<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $website, $categories, $locations;
if(     
    //Display this only in home page
    !isset($_REQUEST["mod"])&&!isset($_REQUEST["page"]) 
    ||( isset($_REQUEST["page"]) &&( $_REQUEST["page"]=="en_Home" 
    || $_REQUEST["page"]=="vn_Trang chủ" ))
)
{
    //Check if user is guests or logged in    
    if(empty($_SESSION["logged_in"])){
        $guest = TRUE;
    } else {
        $guest = FALSE;
    }
    
    
    //Get featured jobs list
    $featured_jobs = $db->rawQuery
	("
            SELECT 
            ".$DBprefix."jobs.id,
            ".$DBprefix."jobs.title,
            ".$DBprefix."jobs.date,
            ".$DBprefix."jobs.salary,
            ".$DBprefix."jobs.applications,
            ".$DBprefix."jobs.region,
            ".$DBprefix."jobs.message,
            ".$DBprefix."employers.company,
            ".$DBprefix."employers.logo
            FROM ".$DBprefix."jobs,".$DBprefix."employers  
            WHERE 
            ".$DBprefix."jobs.employer =  ".$DBprefix."employers.username
            AND ".$DBprefix."jobs.active='YES' 
            AND expires>".time()."  AND ".$DBprefix."jobs.featured=1 ORDER BY RAND()"
            ." LIMIT 0,".$this->GetParam("NUMBER_OF_FEATURED_LISTINGS")."
	");
?>
<!--http://bxslider.com/options-->

<div class="home-panel">
    <section class="container">                
        <div class="outside">
            <p><span id="bx-prev"></span> | <span id="bx-next"></span></p>
        </div>
            
            
        <!--SEARCH FORM-->
        <div class="row">
            <main class="search-form-wrap col-md-<?php if ($guest==TRUE){echo "9";}else{echo "12";}?>">
                <form name="home_form" id="home_form" action="<?php $website->check_SEO_link('search', $SEO_setting);?>"  style="margin-top:0px;margin-bottom:0px;margin-left:0px;margin-right:0px" method="GET"> 
                    <?php if($SEO_setting == 0): //show search module if SEO disabled?>
                    <input type="hidden" name="mod" value="search">
                    <?php endif;?>
                    <div class="text-center">
                        <h4 class="bottom-margin-5"><?php echo $M_SEARCH_FOR_JOBS;?></h4>
                    </div>
                    
                    <!--JOB TITLE-->
                    <div class="col-md-4 form-group group-1">
                        <span class="main-search-label"><br/></span>                    
                        <input type="text" name="q" class="input-job" placeholder="<?php echo $M_KEYWORD;?>">
                    </div>

                    <!--JOB CATEGORY-->
                    <div class="col-md-3 form-group group-2">
                        <span id="label_category" class="main-search-label"><br/></span>                    
                        <select name="category" id="category" class="input-job">
                            <option value=""><?php echo $M_CATEGORY;?></option>
                        <?php foreach ($categories as $category) :?>
                            <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name_vi']?></option>    
                        <?php endforeach;?>
                        </select>
                    </div>

                    <!--LOCATIONS-->
                    <div class="col-md-3 form-group group-3">
                        <span id="label_location" class="main-search-label"><br/></span>                    
                        <select class="input-location" name="location" id="location"  onchange="dropDownChange(this,'location')">
                            <option value=""><?php echo $M_REGION;?></option>	
                        <?php foreach ($locations as $location) :?>
                            <option value="<?php echo $location['id']?>"><?php echo $location['City']?></option>    
                        <?php endforeach;?>
                        </select>
                    </div>

                    <!--SUBMIT-->
                    <div class="col-md-2 no-padding">
                        <span class="main-search-label"><br/></span>
                        <button type="submit" class="btn custom-gradient-2 btn-default btn-green pull-right no-margin"><?php echo $M_SEARCH;?></button>

                    </div>
                    <div class="clearfix"></div>
                </form>
            </main>
            
            <!--QUICK REGISTER-->
            <?php if($guest == TRUE): //SHOW THIS SECTION FOR GUESTS ONLY?>            
            <section class="col-md-3 form-group group-3">
                <fieldset class="quick-register">
                    <a href="#">
                        <input type="button" class="OrangeButton" value="Đăng ký nhanh">
                    </a>
                    <p>Hoặc</p>
                    <a href="http://<?php echo $DOMAIN_NAME;?>/index.php?mod=login">
                        <input type="button" class="BlueButton" value="Đăng nhập">
                    </a>
                </fieldset>
            </section>            
            <?php endif;?>
            
        </div>
    </section> 
    
    <!--BROWSE JOBS-->
    <section class="browser-jobs">
        <div class="container">
            <nav class="row">
                <ul class="col-md-12 nav navbar-nav navbar-left">
                    <li>Browse jobs :</li> 
                    <li class="active"><a href="http://<?php echo $DOMAIN_NAME?>/viec-lam-moi-nhat/"><img src="http://freelancewebdesignerchennai.com/demo/job-portal//images/all-jobs-icon.png"> All jobs</a></li>
                    <li><a href="#"><img src="http://freelancewebdesignerchennai.com/demo/job-portal//images/jobs-by-company-icon.png"> Job by Company</a></li>
                    <li><a href="#"><img src="http://freelancewebdesignerchennai.com/demo/job-portal//images/jobs-by-category-icon.png"> Job by Category</a></li>
                    <li><a href="#"><img src="http://freelancewebdesignerchennai.com/demo/job-portal//images/jobs-by-location-icon.png"> Job by Location</a></li>
                </ul>
            </nav>
        </div>
    </section>
</div>   
<?php }?>