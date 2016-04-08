<?php
if(!defined('IN_SCRIPT')) {die("");}
global $db, $SEO_setting;
?>

<h3 class="no-margin"><?php echo $M_CURRENT_SAVED;?></h3>
<hr/>
<br/>
<section>
<?php

$RESULTS_PER_PAGE = $website->GetParam("RESULTS_PER_PAGE");
$NUMBER_OF_CATEGORIES_PER_ROW = $website->GetParam("NUMBER_OF_CATEGORIES_PER_ROW");

$skip_query=false;



if(!isset($_COOKIE["saved_listings"])||$_COOKIE["saved_listings"]==""||$_COOKIE["saved_listings"]==",")
{
	$skip_query=true;
}

if(!$skip_query)
{
	$saved_jobs = $db->withTotalCount()->rawQuery
	("
            SELECT 
            ".$DBprefix."jobs.id as job_id,".$DBprefix."jobs.SEO_title,
            ".$DBprefix."jobs.title,".$DBprefix."jobs.date,
            ".$DBprefix."jobs.salary,".$DBprefix."jobs.applications,
            ".$DBprefix."jobs.region,".$DBprefix."jobs.message,
            ".$DBprefix."employers.company,".$DBprefix."employers.logo,
            ".$DBprefix."salary.salary_range,".$DBprefix."salary.salary_range_en,
            ".$DBprefix."locations.City,".$DBprefix."locations.City_en
            FROM ".$DBprefix."jobs,".$DBprefix."employers,".$DBprefix."locations,".$DBprefix."salary  
            WHERE 
            ".$DBprefix."jobs.employer =  ".$DBprefix."employers.username".
            " AND ".$DBprefix."jobs.salary = ".$DBprefix."salary.salary_id".
            " AND ".$DBprefix."jobs.region = ".$DBprefix."locations.id".
            " AND ".$DBprefix."jobs.id in (".rtrim(filter_input(INPUT_COOKIE, 'saved_listings'),",").")
	");
	
}	


if($skip_query || $db->totalCount == 0) // No saved jobs 
{
	echo "<br/><br/><i>".$M_STILL_NO_SAVED."</i><br/><br/><br/><br/><br/>";
}
else { //Show saved jobs 
    
	$iTotResults = 0;
	if(!isset($_REQUEST["num"])){
            $num = 0;
	}
	else{
            $website->ms_i($_REQUEST["num"]);
            $num = $_REQUEST["num"] - 1;
	}
	$i_listings_counter = 0;        
	$_REQUEST["is_saved_page"]=true;
        
	foreach ($saved_jobs as $saved_job){		
            if($iTotResults>=$num*$RESULTS_PER_PAGE&&$iTotResults<($num+1)*$RESULTS_PER_PAGE){?>
                <div class="row category-details">
                    <section class="col-md-9">
                        <h4><?php echo $saved_job['title']?></h4>
                        <p>Lương: <?php echo $saved_job['salary_range']?></p>
                        <p>Địa điểm: <?php echo $saved_job['City']?></p>
                        <p>Thời gian đăng: <?php echo date('Y-m-d',$saved_job['date'])?></p>
                        <p><?php echo $saved_job['applications']?> Đơn xin việc</p>
                        <body>
                            <p><?php echo $saved_job['message']?></p>
                        </body>
                        <footer>
                            <a href="<?php echo $website->check_SEO_link("apply_job", $SEO_setting, $saved_job["job_id"], $saved_job["SEO_title"]);?>" class="job-details-link underline-link r-margin-15">Nộp hồ sơ</a>
                            <a href="<?php echo $website->check_SEO_link("details", $SEO_setting, $saved_job["job_id"], $saved_job["SEO_title"]);?>" class="job-details-link underline-link">Chi tiết công việc</a>
                        </footer>    
                    </section>
                    <figure class="col-md-3">
                        <p><a href="javascript:DeleteSavedListing(<?php echo $saved_job['job_id']?>)" id="save_<?php echo $saved_job['job_id']?>">Xóa bỏ</a></p>
                        <img src="http://<?php echo $DOMAIN_NAME?>/uploaded_images/<?php echo $saved_job['logo']?>.jpg" width="200" height="150" title="Ảnh">
                    </figure>
                </div>
                
        <?php } 
            $iTotResults++;
	}
	?>
	

	
<?php }?>
</section>
<?php
$website->Title($M_SAVED_LISTINGS);
$website->MetaDescription("");
$website->MetaKeywords("");

?>