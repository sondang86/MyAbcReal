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
    
<h3><?php echo $M_JOB_DETAILS;?></h3>
    
<?php  
    
//URL security check 
if (!isset($_GET['apply_id'])){
    $website->redirect('index.php');
} else {
    $apply_id=$_REQUEST["apply_id"];
}
$website->ms_i($apply_id);
$application = $db->where('id', $apply_id)->withTotalCount()->getOne('apply');

if ($db->totalCount == "0"){ //not found any records?>
<div class="row">
    <section class="col-md-12">
        <h4>Không tìm thấy công việc này</h4>
    </section>
</div>
<?php } else {
$Posting =   $db->join("categories", "jobsportal_jobs.job_category=jobsportal_categories.category_id", "LEFT")
                ->join("locations", "jobsportal_jobs.region=jobsportal_locations.id", "LEFT")
                ->join("salary", "jobsportal_jobs.salary=jobsportal_salary.salary_id", "LEFT")
                ->where('jobsportal_jobs.id', $application['posting_id'])->getOne('jobs');
                
$employer = $db->where('username', $Posting['employer'])->getOne('employers');
    
?>
    
<div class="row jobseeker-cv">
    <div class="col-md-12 info-description row">
        <section class="col-md-6">
            <p>
                <label><?php echo $JOB_TITLE?></label>
                <span><?php echo stripslashes($Posting["title"])?></span>
            </p>

            <p>
                <label><?php echo $M_JOB_TYPE?>: </label>
                <span><?php echo stripslashes($Posting["category_name_vi"])?></span>
            </p>   

            <p>
                <label><?php echo $M_REGION?>: </label>
                <span><?php echo stripslashes($Posting["City"])?></span>
            </p> 

            <p>
                <label><?php echo $M_SALARY?>: </label>
                <span><?php echo stripslashes($Posting["salary_range"])?></span>
            </p>
        </section>    
        <section class="col-md-6">
            <p>
                <label><?php echo $M_DATE_AVAILABLE?>: </label>
                <span><?php echo date('Y-m-d',stripslashes($Posting["date"]))?></span>
            </p>

            <p>
                <label><?php echo $M_COMPANY?>: </label>
                <span><?php echo (trim($employer["company"])!=""?stripslashes($employer["company"]):"[n/a]")?></span>
            </p>

            <p>
                <label><?php echo $COMPANY_DESCRIPTION?>: </label>
                <span><?php echo stripslashes(trim($employer["company_description"])!=""?$employer["company_description"]:"[n/a]")?></span>
            </p>

            <p>
                <label><?php echo $COMPANY_WEBSITE?>: </label>
                <span><a href="<?php echo (trim($employer["website"])!=""?$employer["website"]:"[n/a]")?>"><?php echo (trim($employer["website"])!=""?$employer["website"]:"[n/a]")?></a></span>
            </p>
        </section>
    </div>
      
    <h4><?php echo $JOB_DESCRIPTION?></h4>
    <section class="col-md-12 info-description">        
        <p><?php echo nl2br($Posting["message"])?></p>
    </section>
    
    <h4><?php echo $REQUIRES_DESCRIPTION?></h4>
    <section class="col-md-12 info-description">        
        <p><?php echo nl2br($Posting["requires_description"])?></p>
    </section>
    
    <h4><?php echo $BENEFITS_DESCRIPTION?></h4>
    <section class="col-md-12 info-description">        
        <p><?php echo nl2br($Posting["benefits_description"])?></p>
    </section>
    
    <h4><?php echo $PROFILECV_DESCRIPTION?></h4>
    <section class="col-md-12 info-description">        
        <p><?php echo nl2br($Posting["profileCV_description"])?></p>
    </section>
</div>
<?php }?>