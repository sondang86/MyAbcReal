<?php
if(!defined('IN_SCRIPT')) die("");
?>
<br><?php
$input_sanitize = new GUMP;
$posting_id=$_REQUEST["posting_id"];
$website->ms_i($posting_id);

$arrPosting = $database->DataArray("jobs","id=".$posting_id."  AND employer='".$AuthUserName."'");

if(!isset($arrPosting["id"])) die("");
$apply_id=$_REQUEST["apply_id"];
$website->ms_i($apply_id);
$arrPostingApply = $database->DataArray("apply","id=".$apply_id);

if($arrPostingApply["posting_id"]!=$posting_id) die("");

$id = $arrPostingApply["jobseeker"];

if($arrPostingApply["guest"] == "1")
{
	$arrJobseeker = $database->DataArray("jobseekers_guests","id=".$arrPostingApply["guest_id"]);
}
else
{
	$arrJobseeker = $database->DataArray("jobseekers","username='$id'");
}
?>
  <div class="fright">
	<?php
	
	
		echo LinkTile
		 (
			"",
			"",
			$M_GO_BACK,
			"",
			
			"red",
			"small",
			"true",
			"window.history.back"
		 );
?>
</div>
<div class="clear"></div>


<h3>
	<?php echo $DETAILS_JS;?> [<?php echo $id;?>]
</h3>
<br><br><br> 

<?php



if(trim($arrPostingApply["message"])!="")
{
?>
	<strong>
	<i><?php echo $MESSAGE_SENT_JS;?></i></strong>
	<br><br>
	<?php
	echo stripslashes($arrPostingApply["message"]);
	?>
	<br><br>
 
<?php 
} 
?>
        
<br><br>
<strong><i><?php echo $LIST_ATTACHED;?>:</i></strong>

<br>


<!--SonDang modify here-->
                                                <?php
                                                    //Get the current jobseeker data
                                                    $get = $input_sanitize->sanitize($_POST);
                                                    print_r($get);
                                                    $jobseeker_profile = $_GET['apply_id'];
                                                    $jobseeker_data = $database->get_data("jobseeker_resumes", "", "WHERE id='$jobseeker_profile'");
  
                                                ?>
                                                <b><?php echo $M_NAME_CURRENT_POSITION;?></b>						
						<br><br style="line-height:2px">
                                                <input type="text" value="<?php echo $jobseeker_data[0]['name_current_position'];?>" name="js-nameCP" style="width:350px">
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_CURRENT_POSITION;?></b></li>
                                                    <li style="float: right">
                                                        <select name="js-current-position" required>
                                                            <option value="">Please select</option>
                                                            <?php foreach ($database->get_data('positions', '') as $value) :?>
                                                            <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data[0]['current_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_SALARY;?></b></li>						
                                                    <li style="float: right;">
                                                        <select name="js-salary" required>
                                                            <option value="">Please select</option>
                                                            <?php foreach ($database->get_data('salary', '') as $value) :?>
                                                            <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data[0]['salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>

                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_NAME_EXPECTED_POSITION;?></b></li>                                               
                                                    <li style="float: right;">
                                                        <select name="js-expected-position">
                                                            <option value="">Please select</option>
                                                            <?php foreach ($database->get_data('positions', '') as $value) :?>
                                                            <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data[0]['expected_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_NAME_EXPECTED_SALARY;?></b></li>						
                                                    <li style="float: right;">
                                                        <select name="js-expected-salary" required>
                                                            <option value="">Please select</option>
                                                            <?php foreach ($database->get_data('salary', '') as $value) :?>
                                                            <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data[0]['expected_salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_BROWSE_CATEGORY;?></b></li>						
                                                    <li style="float: right;">                                              
                                                    </li>
                                                    <li style="float: right">
                                                        <select name="js-category">
                                                            <?php foreach ($database->get_data('categories') as $value) :?>
                                                            <option value="<?php echo $value['category_name']?>" <?php if($value['category_name'] == $jobseeker_data[0]['job_category']) {echo "selected";}?>><?php echo $value['category_name']?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $WORK_LOCATION;?></b></li>						
                                                    <li style="float: right;">
                                                        <select name="js-location">
                                                            <?php foreach ($database->get_data('locations') as $value) :?>
                                                            <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data[0]['location']) {echo "selected";}?>><?php echo $value['City_en']?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_EDUCATION;?></b></li>						
                                                    <li style="float: right">
                                                        <select name="education_level">
                                                                <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                                                                <?php
                                                                foreach($website->GetParam("arrEducationLevels") as $key=>$value)
                                                                {
                                                                                echo "<option value=\"".$key."\" ".($key==$arrResume["education_level"]?"selected":"").">".$value."</option>";
                                                                }
                                                                ?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_JOB_TYPE;?></b></li>						
                                                    <li style="float: right">
                                                        <select name="js-jobType">
                                                            <?php foreach ($database->get_data('job_types', 'job_name_en') as $value) :?>
                                                            <option value="<?php echo $value?>" ><?php echo $value?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <b><?php echo $M_CAREER_OBJECTIVE;?></b>						
						<br><br style="line-height:2px">
						<textarea name="js-careerObjective" id="jobseeker-experience" rows="5" style="width: 25em"><?php echo $jobseeker_data[0]['career_objective'];?></textarea>
						<br><br>
                                                
                                                <b><?php echo $M_FACEBOOK_URL;?></b>						
						<br><br style="line-height:2px">
						<input type="text" name="js-facebookURL" style="width:350px" value="<?php echo $jobseeker_data[0]['facebook_URL'];?>">
						<br><br>
                                                
                                                <b><?php echo $M_EXPERIENCE;?></b>                                                
                                                <br><br style="line-height:2px">
                                                <textarea name="js-experience" id="jobseeker-experience" rows="5" style="width: 25em"><?php echo $jobseeker_data[0]['experiences'];?></textarea>
						<br><br>
                                                
                                                <b><?php echo $M_YOUR_SKILLS;?></b>						
						<br><br style="line-height:2px">
						<input type="text" name="skills" style="width:350px" value="<?php echo $jobseeker_data[0]['skills'];?>">
						<br><br>
                                                
                                                <b><?php echo $M_FOREIGN_LANGUAGE;?></b>						
						<br><br style="line-height:2px">
                                                <ul class="jobseeker-select">
                                                    <li><label for="js-Language">Select language: </label></li>
                                                    <li>
                                                        <select name="js-language" required>
                                                            <?php foreach ($database->get_data('languages') as $value) :?>
                                                            <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data[0]['language']) {echo "selected";}?>><?php echo $value['name']?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                <ul class="jobseeker-select">
                                                    <li><label for="js-LanguageLevel">Level: </label></li>
                                                    <li class="language-level">
                                                        <select name="js-languageLevel" required>
                                                            <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                                                            <?php foreach ($database->get_data('language_levels', "", "WHERE language_id = " . $database->get_default_language()) as $value) :?>
                                                            <option value="<?php echo $value['level']?>" <?php if($value['level'] == $jobseeker_data[0]['language_level']) {echo "selected";}?>><?php echo $value['level_name']?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>

                                                <br><br>
                                                
                                                <!--###SonDang modify here###-->
                                                
                                                
<?php
    $datatete = "12345";
    echo $datatete;
?>                                                