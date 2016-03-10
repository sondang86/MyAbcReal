<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
?><?php
if(!defined('IN_SCRIPT')) die("");
?>
<?php
$id=$_REQUEST["id"];
$website->ms_i($id);
if($database->SQLCount("jobs","WHERE employer='".$AuthUserName."' AND id=".$id." ") == 0)
{
	die("");
}
global $db, $commonQueries;
$db->where ("id", "$id");
$jobs_by_employer = $db->get("jobs");
?>

<div class="row">
    <div class="col-md-3 col-md-push-9">
        <div class="row">
            <div class="col-md-12 col-sm-6 col-xs-12 top-bottom-margin">
                
            <?php
                    echo LinkTile
                     (
                            "jobs",
                            "my_edit&id=".$id,
                            $MODIFY,
                            "",
                            "lila"
                     );
            ?>
            </div>
            <div class="col-md-12 col-sm-6 col-sm-6 top-bottom-margin">
            <?php	 
                    echo LinkTile
                     (
                            "jobs",
                            "questionnaire&id=".$id,
                            $M_QUESTIONNAIRE." (".$database->SQLCount("questionnaire","WHERE job_id=".$id).")",
                            "",
                            "blue"
                     );
            ?>
            </div>
            <div class="col-md-12 col-sm-6 col-xs-12 top-bottom-margin">    
            <?php
                     echo LinkTile
                     (
                            "jobs",
                            "my_stat&id=".$id,
                            $M_VISITS." (".$database->SQLCount("jobs_stat","WHERE posting_id=".$id).")",
                            "",
                            "gray"
                     );
            ?> 
            </div>    
            <div class="col-md-12 col-sm-6 col-xs-12 top-bottom-margin">
            <?php
                    echo LinkTile
                     (
                            "application_management",
                            "list&Proceed=1&id=".$id,
                            $M_APPLICATIONS." (".$database->SQLCount("apply","WHERE posting_id=".$id).")",
                            "",
                            "yellow"
                     );
            ?>
            </div>
        </div>
    </div>  
    <div class="col-md-9 col-md-pull-3">
        <?php foreach ($jobs_by_employer as $job):?>
        <div class="row top-bottom-margin">
            <div class="col-md-2"><h3>Tiêu đề</h3></div>
            <div class="col-md-10"><?php echo $job['title']?></div>
        </div>

        <div class="row top-bottom-margin">
            <div class="col-md-2"><h4>Chi tiết</h4></div>
            <div class="col-md-10"><?php echo $job['message']?></div>
        </div>
            
        <div class="row top-bottom-margin">
            <div class="col-md-2"><h5>Ngành</h5></div>
            <div class="col-md-10"><?php echo $commonQueries->get_data('categories', 'category_id', $job['job_category'])[0]['category_name_vi']?></div>
        </div>
            
        <div class="row top-bottom-margin">
            <div class="col-md-2">Loại công việc:</div>
            <div class="col-md-10"><?php echo $commonQueries->get_data('job_types', 'id', $job['job_type'])[0]['job_name'];?></div>
        </div>
            
        <div class="row top-bottom-margin">
            <div class="col-md-2">Địa điểm:</div>
            <div class="col-md-10"><?php echo $commonQueries->get_data('locations', 'id', $job['region'])[0]['City'];?></div>
        </div>
            
        <div class="row top-bottom-margin">
            <div class="col-md-2">Mức lương:</div>
            <div class="col-md-10"><?php echo $commonQueries->get_data('salary', 'id', $job['salary'])[0]['salary_range'];?></div>
        </div>
            
        <div class="row top-bottom-margin">
            <div class="col-md-2">Ngày bắt đầu:</div>
            <div class="col-md-10"><?php echo date('Y:m:d',$job['date'])?></div>
        </div>
            
        <div class="row top-bottom-margin">
            <div class="col-md-2">Đang hoạt động:</div>
            <div class="col-md-10"><?php echo $job['status']?></div>
        </div>
        <?php endforeach;?>
    </div>
        
</div>
<div class="row">
    <div class="col-md-12"><a href="index.php?category=jobs&action=my"><?php echo $GO_BACK_TO." <strong>\"".$MY_JOB_ADS."\"</strong>";?></a></div>
</div>