<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db;
?>
<div class="fright">
    <?php echo LinkTile("home","apply",$M_GO_BACK,"","red");?>
</div>
<div class="clear"></div>
<h3>
	<?php echo $M_JOB_DETAILS;?>
</h3>
<br/>

<?php  

$id=$_REQUEST["id"];
$website->ms_i($id);
$application = $db->where('id', $id)->get('apply');
$Posting =  $db->join("categories", "jobsportal_jobs.job_category=jobsportal_categories.category_id", "LEFT")
            ->join("locations", "jobsportal_jobs.region=jobsportal_locations.id", "LEFT")
            ->join("salary", "jobsportal_jobs.salary=jobsportal_salary.salary_id", "LEFT")
            ->where('jobsportal_jobs.id', $application[0]['posting_id'])->get('jobs');
$employer = $db->where('username', $Posting[0]['employer'])->get('employers');

//echo "<pre>";
//print_r($Posting);
//echo "</pre>";
//$bad_string = "Hi! <script>alert('you are here');</script> It's a good day!";
//$goodstring = $db->cleanData($bad_string);
//print_r($goodstring);

?>

<div class="row">
    <section class="col-md-12 info-section">
        <label><?php echo $JOB_TITLE?></label>
        <p><?php echo stripslashes($Posting[0]["title"])?></p>
    </section>
</div>

<div class="row">
    <section class="col-md-12 info-section">
        <label><?php echo $DESCRIPTION?></label>
        <p><?php echo stripslashes($Posting[0]["message"])?></p>
    </section>
</div>

<div class="row">
    <section class="col-md-12 info-section">
        <label><?php echo $M_JOB_TYPE?>: </label>
        <span><?php echo stripslashes($Posting[0]["category_name_vi"])?></span>
    </section>
</div>

<div class="row">
    <section class="col-md-12 info-section">
        <label><?php echo $M_REGION?>: </label>
        <span><?php echo stripslashes($Posting[0]["City"])?></span>
    </section>
</div>

<div class="row">
    <section class="col-md-12 info-section">
        <label><?php echo $M_SALARY?>: </label>
        <span><?php echo stripslashes($Posting[0]["salary_range"])?> USD</span>
    </section>
</div>

<div class="row">
    <section class="col-md-12 info-section">
        <label><?php echo $M_DATE_AVAILABLE?>: </label>
        <span><?php echo date('Y-m-d',stripslashes($Posting[0]["date"]))?></span>
    </section>
</div>

<div class="row">
    <section class="col-md-12 info-section">
        <label><?php echo $M_COMPANY?>: </label>
        <span><?php echo (trim($employer[0]["company"])!=""?stripslashes($employer[0]["company"]):"[n/a]")?></span>
    </section>
</div>

<div class="row">
    <section class="col-md-12 info-section">
        <label><?php echo $COMPANY_DESCRIPTION?>: </label>
        <p><?php echo stripslashes(trim($employer[0]["company_description"])!=""?$employer[0]["company_description"]:"[n/a]")?></p>
    </section>
</div>

<div class="row">
    <section class="col-md-12 info-section">
        <label><?php echo $COMPANY_WEBSITE?>: </label>
        <span><a href="<?php echo (trim($employer[0]["website"])!=""?$employer[0]["website"]:"[n/a]")?>"><?php echo (trim($employer[0]["website"])!=""?$employer[0]["website"]:"[n/a]")?></a></span>
    </section>
</div>		