<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db;
$db->where('username', "$AuthUserName");
$jobseekers = $db->get("jobseekers");

if(isset($_REQUEST["bn"]))
{
	if($_REQUEST["bn"]=="s")
	{
		$o = str_replace("b-","",str_replace("box-","",$_REQUEST["o"]));
		$n = str_replace("b-","",str_replace("box-","",$_REQUEST["n"]));
		$temp_value = $AdminUser["box_".$o];
				
		$database->SQLUpdate("jobseekers",array("box_".$o),array($AdminUser["box_".$n]),"username='".$AuthUserName."'");
		$database->SQLUpdate("jobseekers",array("box_".$n),array($temp_value),"username='".$AuthUserName."'");
		$AdminUser=$database->DataArray("jobseekers","username='".$AuthUserName."'");
	}
	else
	if($_REQUEST["bn"]=="a")
	{
		$o = str_replace("b-","",str_replace("box-","",$_REQUEST["o"]));
		$n = strip_tags(stripslashes($_REQUEST["n"]));
		$current_value = $AdminUser["box_".$o];
		$p_items = explode("#",$current_value);
		$passed_value =  explode("-",$n);	
		
		if(trim($passed_value[0])!=""&&trim($passed_value[1])!="")
		{
			$new_value = $passed_value[0]."#".$passed_value[1]."#".$p_items[2];
			$database->SQLUpdate("jobseekers",array("box_".$o),array($new_value),"username='".$AuthUserName."'");
			$AdminUser=$database->DataArray("jobseekers","username='".$AuthUserName."'");
		}
	}
	
}
?>

<div class="row">
	<div class="col-md-3 welcome-left-block">
	
		<span class="large-font">
		<?php echo $M_WELCOME;?> <?php echo $jobseekers[0]["first_name"];?>,
		</span>
		<br/><br/>
		
		<?php
		
			$last_login = $database->DataArray_Query("SELECT max(date) max_date FROM ".$DBprefix."login_log WHERE username='".$AuthUserName."' AND action='login' AND date<(SELECT max(date) max_date FROM ".$DBprefix."login_log WHERE username='".$AuthUserName."' AND action='login')");
			$last_date=date("F j, Y",$last_login["max_date"]);
			$login_stat=str_replace("{show_date}","<a href=\"".CreateLink("home","connections")."\">".$last_date."</a>",$M_LOGIN_STAT_MESSAGE);
			
			$strServerName=$_SERVER["SERVER_NAME"];

			
			if(
			
				trim($arrUser["cv"])==""
				&&
				$database->SQLCount("jobseeker_resumes","WHERE username='".$AuthUserName."'")==0
			  )
			
			{
			?>
				<br/>
				<img src="../images/warning.png"/>
				<a href="index.php?category=cv&action=description"><?php echo $M_STILL_DIDNT_CREATE_RESUME;?></a>
				<br/>
			<?php
			}
		
			
			$new_messages = $database->SQLCount("user_messages","WHERE user_to='".$AuthUserName."'");
			
			
			if($new_messages>0)
			{
			?>
				<br/><br/>
				<img src="../images/warning.png"/>
				<span class="home-warning-text">
					<a href="index.php?category=home&action=received"><?php echo $new_messages;?> <?php echo $M_NEW_MESSAGES;?></a>
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
		?>
		<br/><br/>
	</div>
    <div id="home-links-area" class="col-md-9">
        
        <div class="row" style="padding:10px">
            
            <section class="col-md-4 col-sm-6 col-xs-12 t-padding ui-droppable" id="box-1">
                <div class="tile-p ui-draggable" id="b-1" style="position: relative;">
                    <a class="home-tile box-1-back" href="index.php?category=profile&amp;action=edit"><img class="pull-right" src="images/icons/edit.png">
                        <h3 class="h3-tile">Sửa thông tin cá nhân</h3>
                    </a>					
                </div>
            </section>
                
            <section class="col-md-4 col-sm-6 col-xs-12 t-padding ui-droppable" id="box-2">
                <div class="tile-p ui-draggable" id="b-2" style="position: relative;">
                    <a class="home-tile box-2-back" href="index.php?category=cv&amp;action=resume_creator"><img class="pull-right" src="images/icons/job_preferences.png"><h3 class="h3-tile">Tạo/Sửa CV</h3></a></div>
            </section>
                            
            <section class="col-md-4 col-sm-6 col-xs-12 t-padding ui-droppable" id="box-4">
                <div class="tile-p ui-draggable" id="b-4" style="position: relative;">
                    <a class="home-tile box-4-back" href="index.php?category=home&amp;action=apply">
                        <img class="pull-right" src="images/icons/apply.png"><h3 class="h3-tile">Lịch sử công việc</h3>
                    </a>
                </div>
            </section>                
            
        </div>
            
    </div>
</div>
<div class="clear"></div>
<br/>
<br/>

<div class="row">
 <div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong><?php echo $M_YOUR_JOB_APPLICATIONS_HISTORY;?></strong>
		</div>
		
		<div class="panel-body">
			<div class="list-group">
			<?php
                            $applications_list = $db->rawQuery(
                                "SELECT 
                                        a.id as app_id,
                                        a.date,

                                        a.jobseeker,
                                        a.message,
                                        a.status,
                                        a.employer_reply,
                                        b.title
                                FROM
                                ".$DBprefix."apply a
                                RIGHT JOIN ".$DBprefix."jobs b ON
                                (a.posting_id = b.id)
                                WHERE a.jobseeker='".$AuthUserName."' 
                                ORDER BY a.id DESC
                                LIMIT 0,3"
                            );
                            
                            

                            $applications = $database->Query
                            (
                                    "SELECT 
                                            a.id as app_id,
                                            a.date,

                                            a.jobseeker,
                                            a.message,
                                            a.status,
                                            a.employer_reply,
                                            b.title
                                    FROM
                                    ".$DBprefix."apply a
                                    RIGHT JOIN ".$DBprefix."jobs b ON
                                    (a.posting_id = b.id)
                                    WHERE a.jobseeker='".$AuthUserName."' 
                                    ORDER BY a.id DESC
                                    LIMIT 0,3"
                            );

			$i_app_counter=0;
			foreach($applications_list as $application)
			{
				$i_app_counter++;
			?>
			
				<a  href="index.php?category=home&folder=apply&page=edit&apply_id=<?php echo $application["app_id"];?>" class="list-group-item no-decoration <?php if($i_app_counter%2==0) echo 'alt-back';?>" >
					<div class="row">
						<div class="col-md-2">
							<strong><?php echo date($website->GetParam("DATE_HOUR_FORMAT"),$application["date"]);?></strong>
						</div>
						<div class="col-md-4">
							<?php echo $M_JOB.": <strong>".stripslashes($application["title"])."</strong>";?>
													
						</div>
						<div class="col-md-3">
							<?php 
							$arrst=array($PENDING_VALIDATION,$M_APPROVED,$M_REJECTED);
							echo $STATUS.": <strong>".$arrst[$application['status']]."</strong>";
							
							?>
													
						</div>
						<div class="col-md-3">
							<?php echo $M_EMPLOYER_REPLY.": <strong>";
							
							if(trim($application["employer_reply"])=="")
							{
								echo "[".$M_NA."]";
							}
							else
							{
								echo stripslashes($application["employer_reply"]);
							}
							?>
							</strong>
						</div>
						
					</div>
				</a>
			<?php
			}
			?>
			</div>
			
			<a href="<?php echo CreateLink("home","apply");?>" class="btn btn-default btn-block alt-back"><?php echo $M_SEE_ALL;?></a>
		</div>
	</div>
</div>
</div>
