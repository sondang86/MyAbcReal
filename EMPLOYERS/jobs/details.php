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
        <section class="row top-bottom-margin">
            <label class="col-md-2">Tiêu đề</label>
            <div class="col-md-10"><?php echo $job['title']?></div>
        </section>

        <section class="row top-bottom-margin">
            <label class="col-md-2">Chi tiết</label>
            <article class="col-md-10"><?php echo nl2br($job['message'])?></article>
        </section>
            
        <section class="row top-bottom-margin">
            <label class="col-md-2">Ngành</label>
            <article class="col-md-10"><?php echo $commonQueries->get_data('categories', 'category_id', $job['job_category'])[0]['category_name_vi']?></article>
        </section>
            
        <section class="row top-bottom-margin">
            <label class="col-md-2">Loại công việc:</label>
            <article class="col-md-10"><?php echo $commonQueries->get_data('job_types', 'id', $job['job_type'])[0]['job_name'];?></article>
        </section>
            
        <section class="row top-bottom-margin">
            <label class="col-md-2">Địa điểm:</label>
            <article class="col-md-10"><?php echo $commonQueries->get_data('locations', 'id', $job['region'])[0]['City'];?></article>
        </section>
            
        <section class="row top-bottom-margin">
            <label class="col-md-2">Mức lương:</label>
            <article class="col-md-10"><?php echo $commonQueries->get_data('salary', 'id', $job['salary'])[0]['salary_range'];?></article>
        </section>
            
        <section class="row top-bottom-margin">
            <label class="col-md-2">Ngày bắt đầu:</label>
            <article class="col-md-10"><?php echo date('Y:m:d',$job['date'])?></article>
        </section>
            
        <section class="row top-bottom-margin">
            <label class="col-md-2">Đang hoạt động:</label>
            <article class="col-md-10"><?php echo $job['status']?></article>
        </section>
        <?php endforeach;?>
    </div>
        
</div>
<div class="row">
    <div class="col-md-12"><button><a href="index.php?category=jobs&action=my"><?php echo $GO_BACK_TO." <strong>\"".$MY_JOB_ADS."\"</strong>";?></a></button></div>
</div>