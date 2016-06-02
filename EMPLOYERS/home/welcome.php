<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
    
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $FULL_DOMAIN_NAME;

$jobs_by_employer_columns = array(
    $DBprefix."jobs.id as jobId",$DBprefix."jobs.title",
    $DBprefix."jobs.applications",$DBprefix."jobs.date",
    $DBprefix."job_statistics.views_count",
);
$db->join('job_statistics', $DBprefix."jobs.id = " . $DBprefix."job_statistics.job_id", "LEFT");
$jobs_by_employer = $db->where("employer", "$AuthUserName")->orderBy('date', 'DESC')->get("jobs", NULL, $jobs_by_employer_columns);

?>
    
    <div class="col-md-3 welcome-left-block">
            
    <span class="large-font">
		<?php echo $M_WELCOME;?> <?php echo $LoginInfo["contact_person"];?>,
    </span>
                    
                    
		<?php
                    
			$last_login = $database->DataArray_Query("SELECT max(date) max_date FROM ".$DBprefix."login_log WHERE username='".$AuthUserName."' AND action='login' AND date<(SELECT max(date) max_date FROM ".$DBprefix."login_log WHERE username='".$AuthUserName."' AND action='login')");
			$last_date=date("F j, Y",$last_login["max_date"]);
			//$login_stat=str_replace("{show_date}","<a href=\"".CreateLink("home","connections")."\">".$last_date."</a>",$M_LOGIN_STAT_MESSAGE);
			$login_stat="";
			$strServerName=$_SERVER["SERVER_NAME"];
                            
                            
                            
			$new_messages = $database->SQLCount("user_messages","WHERE user_to='".$AuthUserName."'");
                            
                            
			if($new_messages>0)
			{
			?>
    <br/><br/>
    <img src="http://<?php echo $DOMAIN_NAME;?>/images/warning.png"/>
    <span class="home-warning-text">
        <a href="http://<?php echo $DOMAIN_NAME;?>/EMPLOYERS/index.php?category=home&action=received"><?php echo $new_messages;?> <?php echo $M_NEW_MESSAGES;?></a>
    </span>
			<?php
			}
			else
			{
			?>
    <br/><br/>
    <span class="home-warning-text">
					<?php echo $ANY_MESSAGES;?>
    </span>
                                    
			<?php
			}
                            
                            
			if($website->GetParam("CHARGE_TYPE") == 1)
			{
			?>
    <br/><br/>
				<?php echo $M_CURRENTLY_YOU_HAVE;?>:<br/>
    <a class="underline-link" href="index.php?category=home&action=credits"><strong><?php if($arrUser["subscription"]==0) echo "0";else echo "1";?></strong></a> <?php echo $M_SUBSCRIPTION;?>, 
				<?php 
				if($arrUser["subscription"]>0)
				{
				?>
    <br/><?php echo $REMAINING_ADS;?>: 
    <strong>
					<?php
					$arrSubscription = $database->DataArray("subscriptions","id=".$arrUser["subscription"]);
                                            
					echo ($arrSubscription["listings"]-$database->SQLCount("jobs","WHERE employer='".$AuthUserName."'"));
					?>
    </strong>
					<?php
				}
				?>
			<?php
			}
			else
			if($website->GetParam("CHARGE_TYPE")==2)
			{
			?>
    <br/><br/>
				<?php echo $M_CURRENTLY_YOU_HAVE.': <a class="red-font underline-link" href="index.php?category=home&action=credits">'.$AdminUser["credits"].' '.$M_CREDITS.'<a>';?>
			<?php
			}
		?>
    <br/><br/>
</div>

<div id="home-links-area" class="col-md-9">    
    <div class="row" style="padding:10px">
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-1">
            <div class="tile-p" id="b-1">
                <a class="home-tile box-1-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/index.php?category=jobs&amp;action=add"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/add.png">
                    <h3 class="h3-tile">Đăng việc mới</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-2">
            <div class="tile-p" id="b-2">
                <a class="home-tile box-2-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/index.php?category=jobs&amp;action=my"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/my.png">
                    <h3 class="h3-tile">Danh sách công việc</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-3">
            <div class="tile-p" id="b-3">
                <a class="home-tile box-3-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/index.php?category=application_management&amp;action=list"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/list.png">
                    <h3 class="h3-tile">Đơn xin việc</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-4">
            <div class="tile-p" id="b-4">
                <a class="home-tile box-4-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/index.php?category=home&amp;action=received"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/received.png">
                    <h3 class="h3-tile">Tin nhắn</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-5">
            <div class="tile-p" id="b-5">
                <a class="home-tile box-5-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/index.php?category=profile&amp;action=edit"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/edit.png">
                    <h3 class="h3-tile">Chỉnh sửa thông tin cá nhân</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-6">
            <div class="tile-p" id="b-6">
                <a class="home-tile box-6-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/tim-kiem-ung-vien/"><img class="pull-right" src="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/images/icons/logo.png">
                    <h3 class="h3-tile">Tìm kiếm ứng viên</h3>
                </a>            
            </div>
        </div>
    </div>
        
</div>

<style>
    .title-panel {
        padding-bottom: 30px;
    }
</style>
            
<div class="row">
    <p class="col-md-12">
        <strong><?php echo $M_YOUR_LATEST_JOBS;?></strong>
    </p>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row panel panel-default">
            <section class="panel-heading title-panel">                
                <div class="col-md-2">
                    <p>Ngày</p>            
                </div>
                
                <div class="col-md-2">
                    <p>Tiêu đề</p>            
                </div>

            </section>
                    
            <div class="panel-body">
                <div class="list-group">
                    
                    <?php foreach ($jobs_by_employer as $job) :?>                            
                    <a  href="index.php?category=jobs&action=details&id=<?php echo $job["jobId"];?>" class="list-group-item no-decoration" >
                        <div class="row">
                            <div class="col-md-2">
                            <?php echo date('d/m/Y',$job["date"]);?>
                            </div>
                            <div class="col-md-6">
                                <strong><?php echo stripslashes($job["title"]);?></strong>
                            </div>
                            <div class="col-md-2 italic">
                                <?php echo $job['applications']?> đơn xin việc                                                           
                            </div>
                            <div class="col-md-2 italic">
                                <?php if($job['views_count'] == NULL){echo "0";} else { echo $job['views_count'];}?> lượt xem  			
                            </div>
                                                    
                        </div>
                    </a>
                    <?php endforeach;?>
                    
                </div>
                            
                <a href="<?php echo CreateLink("jobs","my");?>" class="btn btn-default btn-block alt-back"><?php echo $M_SEE_ALL;?></a>
            </div>
        </div>
    </div>
</div>