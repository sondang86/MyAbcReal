<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
?>
<div class="fright">

	<?php
		echo LinkTile
		 (
			"cv",
			"description",
			$M_GO_BACK,
			"",
			"red"
		 );
	
	?>

</div>
<div class="clear"></div>		
		<?php
		if(isset($_POST["ProceedSaveResume"]))
		{
			if($database->SQLCount("jobseeker_resumes","WHERE username='".$AuthUserName."'") == 0)
			{
				$database->SQLInsert("jobseeker_resumes",array("username"),array($AuthUserName));
			}

			$database->SQLUpdate
				(
					"jobseeker_resumes",
					
					array
					(
                                                                "name_current_position",
                                                                "current_position", 
                                                                "salary", 
                                                                "expected_position", 
                                                                "expected_salary", 
                                                                "job_category", 
                                                                "location", 
                                                                "education_level",
                                                                "job_type",
                                                                "career_objective",
                                                                "facebook_URL",
                                                                "experiences",
                                                                "skills",
                                                                "language",
                                                                "language_level"                                                                 
					)
					,
					array
					(
                                                                get_param("js-nameCP"),
                                                                get_param("js-current-position"),
                                                                get_param("js-salary"),
                                                                get_param("js-expected-position"),
                                                                get_param("js-expected-salary"),
                                                                get_param("js-category"),
                                                                get_param("js-location"),
                                                                get_param("education_level"),
                                                                get_param("js-jobType"),
                                                                get_param("js-careerObjective"),
                                                                get_param("js-facebookURL"),
                                                                get_param("js-experience"),
                                                                get_param("skills"),
                                                                get_param("js-Language"),
                                                                get_param("js-LanguageLevel")
					),
					"username='".$AuthUserName."'"
				);
		}
                
		$arrResume = $database->DataArray("jobseeker_resumes","username='".$AuthUserName."'");
		
		
		?>
		
						<br>
						
						<form action="index.php" method="post">
						<input type="hidden" name="ProceedSaveResume" value="1">
						<input type="hidden" name="action" value="<?php echo $action;?>">
						<input type="hidden" name="category" value="<?php echo $category;?>">
					
						
						<span style="font-size:14px;font-weight:400">
							<i><b><?php echo $M_PERSONAL_INFORMATION;?></b></i>
						</span>
						
						<br><br><br>
						
						<?php echo $M_VIEW_MODIFY_PROFILE;?>
						<a href="index.php?category=profile&action=edit"><?php echo $M_PROFILE_MODIFY;?></a> 
						<?php echo $M_PAGE;?>!
						
						<br><br><br>
						<span style="font-size:14px;font-weight:400">
							<i><b><?php echo $M_WORK_HISTORY;?></b></i>
						</span>
						<br><br>
						<font size=1><i><?php echo $M_WORK_HISTORY_EXPL;?> </i></font>
						
						<br><br>
						
                                                <!--SonDang modify here-->
                                                
                                                <b><?php echo $M_NAME_CURRENT_POSITION;?></b>						
						<br><br style="line-height:2px">
						<input type="text" name="js-nameCP" style="width:350px">
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_CURRENT_POSITION;?></b></li>
                                                    <li style="float: right">
                                                        <select name="js-current-position" required>
                                                            <option value="">Please select</option>
                                                            <option value="1">Manager</option>
                                                            <option value="2">Staff</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_SALARY;?></b></li>						
                                                    <li style="float: right;">
                                                        <select name="js-salary" required>
                                                            <option value="">Please select</option>
                                                            <option value="1">50-150 USD</option>
                                                            <option value="2">150-350 USD</option>
                                                            <option value="3">350-500 USD</option>
                                                            <option value="4">500-1000 USD</option>
                                                            <option value="5">Above 1000 USD</option>
                                                            <option value="6">Other</option>
                                                        </select>
                                                    </li>
                                                </ul>

                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_NAME_EXPECTED_POSITION;?></b></li>                                               
                                                    <li style="float: right;">
                                                        <select name="js-expected-position">
                                                            <option value="">Please select</option>
                                                            <option value="1">Manager</option>
                                                            <option value="2">Staff</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_NAME_EXPECTED_SALARY;?></b></li>						
                                                    <li style="float: right;">
                                                        <select name="js-expected-salary" required>
                                                            <option value="">Please select</option>
                                                            <option value="1">50-150 USD</option>
                                                            <option value="2">150-350 USD</option>
                                                            <option value="3">350-500 USD</option>
                                                            <option value="4">500-1000 USD</option>
                                                            <option value="5">Above 1000 USD</option>
                                                            <option value="6">Negotiate</option>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $M_BROWSE_CATEGORY;?></b></li>						
                                                    <li style="float: right;">                                              
                                                    </li>
                                                    <li style="float: right">
                                                        <select name="js-categories">
                                                            <?php foreach ($database->get_data('categories', 'category_name') as $value) :?>
                                                            <option value="<?php echo $value?>"><?php echo $value?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <ul class="jobseeker-select">
                                                    <li><b><?php echo $WORK_LOCATION;?></b></li>						
                                                    <li style="float: right;">
                                                        <select name="js-categories">
                                                            <?php foreach ($database->get_data('locations', 'City_en') as $value) :?>
                                                            <option value="<?php echo $value?>"><?php echo $value?></option>
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
                                                        <select name="js-categories">
                                                            <?php foreach ($database->get_data('job_types', 'job_name_en') as $value) :?>
                                                            <option value="<?php echo $value?>"><?php echo $value?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>
                                                
                                                <b><?php echo $M_CAREER_OBJECTIVE;?></b>						
						<br><br style="line-height:2px">
						<textarea name="js-careerObjective" id="jobseeker-experience" rows="5" style="width: 25em"></textarea>
						<br><br>
                                                
                                                <b><?php echo $M_FACEBOOK_URL;?></b>						
						<br><br style="line-height:2px">
						<input type="text" name="js-facebookURL" style="width:350px">
						<br><br>
                                                
                                                <b><?php echo $M_EXPERIENCE;?></b>                                                
                                                <br><br style="line-height:2px">
                                                <textarea name="js-experience" id="jobseeker-experience" rows="5" style="width: 25em"></textarea>
						<br><br>
                                                
                                                <b><?php echo $M_YOUR_SKILLS;?></b>						
						<br><br style="line-height:2px">
						<input type="text" name="skills" style="width:350px">
						<br><br>
                                                
                                                <b><?php echo $M_FOREIGN_LANGUAGE;?></b>						
						<br><br style="line-height:2px">
                                                <ul class="jobseeker-select">
                                                    <li><label for="js-Language">Select language: </label></li>
                                                    <li>
                                                        <select name="js-language" required>
                                                            <?php foreach ($database->get_data('languages', 'name') as $value) :?>
                                                            <option value="<?php echo $value?>"><?php echo $value?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                    <li><label for="js-LanguageLevel">Level: </label></li>
                                                    <li class="language-level">
                                                        <select name="js-languageLevel" required>
                                                            <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                                                            <?php foreach ($database->get_data('language_levels', 'level_name') as $value) :?>
                                                            <option value="<?php echo $value?>"><?php echo $value?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </li>
                                                </ul>

                                                <br><br>
                                                
                                                <!--###SonDang modify here###-->
                                                
                                                <input type="submit" value=" <?php echo $SAUVEGARDER;?> " class="btn btn-primary">

						
						</form>
		
		<?php
		
		?>