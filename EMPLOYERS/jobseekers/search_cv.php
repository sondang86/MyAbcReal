<?php
global $db;
if (is_numeric($_GET['id'])){
    $jobseeker_data = $db->where('id', $db->escape($_GET['id']))->get('jobseeker_resumes');
} else {
    die;
}


$id=$_REQUEST["id"];
$website->ms_i($id);

if($database->SQLCount("jobseekers","WHERE id=".$id." AND profile_public=1") < 1)
{
	die("");
}

$show_cv = true;

if($website->GetParam("CHARGE_TYPE") == 3&&!isset($_REQUEST["rsm"]))
{
	$show_cv = false;
?>
<h4>Please click on the icon below to purchase this resume</h4>

	<?php
			if(trim($website->GetParam("PAYPAL_ID"))!="")
			{
			?>
<br/><br/>				
<form id="paypal_form" name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo $website->GetParam("PAYPAL_ID");?>">
    <input type="hidden" name="currency_code" value="<?php echo $website->GetParam("CURRENCY_CODE");?>">
    <input type="hidden" name="item_name" value="<?php echo "Payment for CV #".$id." on ".$DOMAIN_NAME;?> ">
    <input type="hidden" name="item_number" value="<?php echo $id;?>">
    <input type="hidden" name="amount" value="<?php echo number_format($website->params[712], 2, '.', '');?>">
    <input type="hidden" name="cancel_return" value="<?php echo "http://".$DOMAIN_NAME."/EMPLOYERS/index.php?category=jobseekers&action=search";?>">
    
    <input type="hidden" name="return" value="<?php echo "http://".$DOMAIN_NAME."/EMPLOYERS/index.php?category=jobseekers&folder=search&page=cv&rsm=".md5($DOMAIN_NAME.$id)."&id=".$id;?>">
    <input type="image"  src="../images/paypal.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>
<script>
    document.getElementById("paypal_form").submit();
</script>
<br><br><br>
			<?php
			}
			?>

<?php
}
else

if($website->GetParam("CHARGE_TYPE") == 1&&$arrUser["subscription"]==0)
{
	$show_cv = false;
?>
<br><br>
<a class="underline-link" href="index.php?category=home&action=credits"><?php echo $M_NEED_SUBSCRIPTION_RESUMES;?></a>
<?php
}
else	
if($website->GetParam("CHARGE_TYPE") == 2&&aParameter(704)>$arrUser["credits"])
{
	$show_cv = false;
?>
<br><br>
<a class="underline-link" href="index.php?category=home&action=credits"><?php echo $M_NOT_ENOUGH_CREDITS_TO_VIEW_RESUME;?></a>
<?php
}
else
{

	if($website->GetParam("CHARGE_TYPE") == 2)
	{
		$database->SQLUpdate_SingleValue
		(
			"employers",
			"username",
			"'".$AuthUserName."'",
			"credits",
			$arrUser["credits"]-aParameter(704)
		);	
	}
	
	if($website->GetParam("CHARGE_TYPE") == 3&&!isset($_REQUEST["rsm"]))
	{
		if($_REQUEST["rsm"]!=md5($DOMAIN_NAME.$id)) die("Access denied");
	}

				$arrSeeker = $database->DataArray("jobseekers","id=".$id);
				
				$database->SQLInsert
				(
					"jobseekers_stat",
					array("date","jobseeker","ip","employer"),
					array(time(),$arrSeeker["username"],$_SERVER["REMOTE_ADDR"],$AuthUserName)
				);
				
				$arrResume = $database->DataArray("jobseeker_resumes","username='".$arrSeeker["username"]."'");
						
}

if($show_cv)		
{
	$arrSeeker = $arrJobseeker = $database->DataArray("jobseekers","id=".$id);
	$arrResume = $database->DataArray("jobseeker_resumes","username='".$arrSeeker["username"]."'");
	
?>



<div class="fright">
	<?php
	
	
	echo LinkTile
		 (
			"",
			"",
			$M_SAVE_RESUME_AS_PDF,
			"",
			
			"green",
			"small",
			"true",
			"SubmitForm"
		 );
		 
		echo LinkTile
		 (
			"jobseekers",
			"list_message-id=".$id,
			$SEND_MESSAGE,
			"",
			
			"yellow",
			"small"
		 );
		 
		 
	echo LinkTile
	 (
		"jobseekers",
		"search",
		$M_GO_BACK,
		"",
		"red"
	 );
?>
</div>
<div class="clear"></div>
<br/>
<form id="html_form" action="pdf/resume.php" method="post">
    <input id="html_field" type="hidden" name="html" value="">
</form>

<h3>
		<?php echo $CV_OF;?> <?php echo $arrSeeker["first_name"];?> <?php echo $arrSeeker["last_name"];?>
</h3>


<?php
if($database->SQLCount("files","WHERE user='".$arrSeeker["username"]."'  AND is_resume=1 ","file_id") == 0)
{

}
else
{

?>

<div class="pull-right">
    <i><b><?php echo $M_UPLOADED_RESUMES_JOBSEEKER;?>:</b></i>
    
    <br><br>
    
<?php
	$JobseekerFiles=$database->DataTable("files","WHERE user='".$arrSeeker["username"]."' AND is_resume=1");
	
	while($js_file = $database->fetch_array($JobseekerFiles))
	{
		$file_show_link = "../file.php?id=".$js_file["file_id"];
		foreach($website->GetParam("ACCEPTED_FILE_TYPES") as $c_file_type)
		{	
			if(file_exists("../user_files/".$js_file["file_id"].".".$c_file_type[1]))
			{
				$file_show_link = "../user_files/".$js_file["file_id"].".".$c_file_type[1];
				break;
			}
		}
	?>
    
    <a target="_blank" href="<?php echo $file_show_link;?>"><b><?php echo $js_file["file_name"];?></b></a>
    <br>
    <i style="font-size;10px"><?php echo $js_file["description"];?></i>
    <br><br>
	<?php
	}
	

						
}
?>
    
    
</div>



<script>
		
    function SubmitForm()
    {
			
        document.getElementById("html_field").value=document.getElementById("resume_content").innerHTML;	
        document.getElementById("html_form").submit();
    }
		
</script>


<div class="clear"></div><br>



<div id="resume_content">
    
    
    <span style="font-size:14px;font-weight:400">
        <i><b><?php echo $M_PERSONAL_INFORMATION;?></b></i>
    </span>
    
    <br><br>
    
    
		<?php
$MessageTDLength = 140;

$_REQUEST["HideSubmit"] = true;

	AddEditForm
	(
	array(
	
	" <i>".$FIRST_NAME.":</i>",
	" <i>".$LAST_NAME.":</i>",
	" <i>".$M_ADDRESS.":</i>",
	" <i>".$TELEPHONE.":</i>",
	" <i>".$M_MOBILE.":</i>",
	" <i>".$M_EMAIL.":</i>",
	" <i>".$M_DOB.":</i>",
	
	" <i>".$M_PICTURE.":</i>"),
	array("first_name","last_name","address","phone",
	"mobile","username","dob","logo"),
	array("profile_public","title","first_name","last_name","address","phone",
	"mobile","username","dob","logo"),
	array("textbox_30","textbox_30","textarea_50_4","textbox_30",
	"textbox_30","textbox_30","textbox_30","textbox_30"),
	"jobseekers",
	"id",
	$id,
	""
	);

?>
    
    <table summary="" border="0" width="100%">
        <tr>
            <td>
                
                
<?php	


if($arrJobseeker["experience"]!=0)
{
?>
        <tr height="32">
            <td >
                <i><?php echo $M_EXPERIENCE;?>:</i>
            </td>
            <td><strong><?php echo $website->show_value("arrExperienceLevels",$arrJobseeker["experience"]);?></strong></td>
        </tr>
<?php
}

if($arrJobseeker["availability"]!=0)
{
?>
        <tr height="32">
            <td width="<?php echo $MessageTDLength;?>">
                <i><?php echo $M_AVAILABILITY;?>:</i>
            </td>
            <td><strong><?php echo $website->show_value("arrAvailabilityTypes",$arrJobseeker["availability"]);?></strong></td>
        </tr>
<?php
}

if($arrJobseeker["job_type"]!=0)
{
	
?>
        <tr height="32">
            <td width="<?php echo $MessageTDLength;?>">
                <i><?php echo $M_JOB_TYPE;?>:</i>
            </td>
            <td><strong><?php echo $website->show_value("arrJobTypes",$arrJobseeker["job_type"]);?></strong></td>
        </tr>
<?php
}


if(trim($arrJobseeker["jobseeker_fields"]) != "")
{

	$arrEmployerFields = array();

	if(is_array(unserialize($arrJobseeker["jobseeker_fields"])))
	{
		$arrEmployerFields = unserialize($arrJobseeker["jobseeker_fields"]);
	}

	$bFirst = true;
	while (list($key, $val) = each($arrEmployerFields)) 
	{

	?>
        <tr height="32">
            <td width="<?php echo $MessageTDLength;?>"><i><?php str_show($key);?>:</i></td>
            <td><?php str_show($val);?></td>
        </tr>
	<?php

	}
}

?>
        </td>
        </tr>
    </table>
    
    
    
    
<?php 
    }

?>
    
    <div class="jobseeker-cv">    
        <div class="jobseeker-main">
            <div class="row jobseeker-mainTitle">
                <div class="col-md-6 col-sm-6 col-xs-12 cv-details">
                    <label>
                        <span><b><?php echo $M_CURRENT_POSITION;?></b></span>
                        <aside>
                        <?php echo $database->get_data('positions', 'position_name', "WHERE position_id =". $jobseeker_data[0]['current_position'])[0]?>                
                        </aside>
                    </label>
                    
                    <label>
                        <span><b><?php echo $M_SALARY;?></b></span>						
                        <aside>
                        <?php echo $database->get_data('salary', 'salary_range', "WHERE salary_id =". $jobseeker_data[0]['salary'])[0]?>                
                        </aside>
                    </label>
                    
                    <label>
                        <span><b><?php echo $M_NAME_EXPECTED_POSITION;?></b></span>                                               
                        <aside>
                        <?php echo $database->get_data('positions', 'position_name', "WHERE position_id =". $jobseeker_data[0]['expected_position'])[0]?>                
                        </aside>
                    </label>
                    <label>
                        <span><b><?php echo $M_NAME_EXPECTED_SALARY;?></b></span>						
                        <aside>
                        <?php echo $database->get_data('salary', 'salary_range', "WHERE salary_id =". $jobseeker_data[0]['expected_salary'])[0]?> 
                        </aside>
                    </label>
                    <label>
                        <span><b><?php echo $M_FOREIGN_LANGUAGE;?>/Level: </b></span>
                        <aside><?php echo $database->get_data('languages', 'name', "WHERE id =". $jobseeker_data[0]['language'])[0]?>/<?php echo $database->get_data('language_levels', 'level_name', "WHERE id =". $jobseeker_data[0]['language_level'])[0]?></aside>
                    </label>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 cv-details">
                    <label>
                        <span><b><?php echo $M_BROWSE_CATEGORY;?></b></span>						
                        <aside>
                        <?php echo $database->get_data('categories', 'category_name', "WHERE category_id =". $jobseeker_data[0]['job_category'])[0]?>
                        </aside>
                    </label>
                    
                    <label>
                        <span><b><?php echo $WORK_LOCATION;?></b></span>						
                        <aside>
                        <?php echo $database->get_data('locations', 'City_en', "WHERE id =". $jobseeker_data[0]['location'])[0]?>
                        </aside>
                    </label>
                    
                    <label>
                        <span><b><?php echo $M_EDUCATION;?></b></span>						
                        <aside>
                        <?php echo $database->get_data('education', 'education_name', "WHERE id =". $jobseeker_data[0]['education_level'])[0]?>
                        </aside>
                    </label>
                    
                    <label>
                        <span><b><?php echo $M_JOB_TYPE;?></b></span>						
                        <aside>
                        <?php echo $database->get_data('job_types', 'job_name', "WHERE id =". $jobseeker_data[0]['job_type'])[0]?>
                        </aside>
                    </label>
                </div>
            </div>
            <div class="jobseeker-messageArea" name="js-careerObjective" class="jobseeker-messageArea" rows="5" style="width: 100%">
                <div class="jobseeker-title"><h4><?php echo $M_CAREER_OBJECTIVE;?></h4></div>
                <?php echo $jobseeker_data[0]['career_objective'];?>
            </div>
            <br><br>
            <div class="cv-details">
                <label>
                    <span style="width:100px; margin-top: 7px;"><b><?php echo $M_FACEBOOK_URL;?></b></span>
                    <aside style="width:200px; float: left; text-align: left;"><input type="text" name="js-facebookURL" style="width:350px" value="<?php echo $jobseeker_data[0]['facebook_URL'];?>" readonly="readonly"></aside>
                </label>
            </div>
            <div class="jobseeker-messageArea" rows="5" style="width: 100%">
                <div class="jobseeker-title"><h4><?php echo $M_EXPERIENCE;?></h4></div>
                <?php echo $jobseeker_data[0]['experiences'];?>
            </div>
            <br><br>
            
            <div class="jobseeker-messageArea" style="width:100%">
                <div class="jobseeker-title"><h4><?php echo $M_YOUR_SKILLS;?></h4></div>
                <?php echo $jobseeker_data[0]['skills'];?>
            </div>
            <br><br>
            <strong><i><?php echo $LIST_ATTACHED;?>:</i></strong>
        </div>    
        
    </div>       
        
<?php
    $datatete = "12345";
    echo $datatete;
?>        