<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db;
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
                                                    <?php
                                                        
                                                ?>
                                                    
</div>
<?php
    $job_types = $db->get_data('job_types');
?>

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
                                                                "language_level",
                                                                "language_1",
                                                                "language_1_level",
                                                                "language_2",
                                                                "language_2_level",
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
                                                                get_param("js-language"),
                                                                get_param("js-languageLevel"),
                                                                get_param("js-language1"),
                                                                get_param("js-languageLevel1"),
                                                                get_param("js-language2"),
                                                                get_param("js-languageLevel2"),
					),
					"username='".$AuthUserName."'"
				);
		}
                    
		$arrResume = $database->DataArray("jobseeker_resumes","username='".$AuthUserName."'");
                    
		//Get the current jobseeker data
                $jobseeker_data = $database->get_data("jobseeker_resumes", "", "WHERE username = '$AuthUserName'");
		?>
                    
           
<!--SonDang modify here-->
<form action="index.php" method="post">
    <input type="hidden" name="ProceedSaveResume" value="1">
    <input type="hidden" name="action" value="<?php echo $action;?>">
    <input type="hidden" name="category" value="<?php echo $category;?>">
    <div class="jobseeker-cv">    
        <div class="jobseeker-main">
            <div class="row jobseeker-mainTitle">
                <div class="col-md-6 col-sm-6 col-xs-12 js-forms">
                    <label>
                        <span><b><?php echo $M_CURRENT_POSITION;?></b></span>                            
                        <select name="js-current-position" required>
                            <option value="">Please select</option>
                                <?php foreach ($database->get_data('positions', '') as $value) :?>
                            <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data[0]['current_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                                <?php endforeach;?>
                        </select>
                    </label>
                    <label>
                        <span><b><?php echo $M_SALARY;?></b></span>						
                        <select name="js-salary" required>
                            <option value="">Please select</option>
                            <?php foreach ($database->get_data('salary', '') as $value) :?>
                            <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data[0]['salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                            <?php endforeach;?>
                        </select>            
                    </label>
                    <label>
                        <span><b><?php echo $M_NAME_EXPECTED_POSITION;?></b></span>                                               
                        <select name="js-expected-position">
                            <option value="">Please select</option>
                            <?php foreach ($database->get_data('positions', '') as $value) :?>
                            <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data[0]['expected_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                            <?php endforeach;?>
                        </select>
                    </label>
                    <label>
                        <span><b><?php echo $M_NAME_EXPECTED_SALARY;?></b></span>						
                        <select name="js-expected-salary" required>
                            <option value="">Please select</option>
                            <?php foreach ($database->get_data('salary', '') as $value) :?>
                            <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data[0]['expected_salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                            <?php endforeach;?>
                        </select>
                    </label>
                    <label>
                        <span><b><?php echo $M_BROWSE_CATEGORY;?></b></span>						
                        <select name="js-category">
                        <?php foreach ($database->get_data('categories') as $value) :?>
                            <option value="<?php echo $value['category_id']?>" <?php if($value['category_name'] == $jobseeker_data[0]['job_category']) {echo "selected";}?>><?php echo $value['category_name']?></option>
                        <?php endforeach;?>
                        </select>
                    </label>
                        
                    <label>
                        <span><b><?php echo $WORK_LOCATION;?></b></span>						
                        <select name="js-location">
                        <?php foreach ($database->get_data('locations') as $value) :?>
                            <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data[0]['location']) {echo "selected";}?>><?php echo $value['City_en']?></option>
                        <?php endforeach;?>
                        </select>
                    </label>                    
                    <label>
                        <span><b><?php echo $M_EDUCATION;?></b></span>						
                        <select name="education_level">
                            <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                        <?php
                        foreach($website->GetParam("arrEducationLevels") as $key=>$value)
                        {
                                        echo "<option value=\"".$key."\" ".($key==$arrResume["education_level"]?"selected":"").">".$value."</option>";
                        }
                        ?>
                        </select>
                    </label>
                    <!--<div class="formWrapper">-->
                        
                    <!--</div>-->
                </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 js-forms">
                    
                    <!--Language forms-->
                    <label>
                        <span><b><?php echo $M_JOB_TYPE;?></b></span>						
                        <select name="js-jobType">                            
                        <?php foreach ($database->get_data('job_types') as $value) :?>
                            <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data[0]['job_type']) {echo "selected";}?>><?php echo $value['job_name']?></option>
                        <?php endforeach;?>
                        </select>
                    </label>
                    <label>
                        <span><label for="js-Language"><?php echo $M_FOREIGN_LANGUAGE;?>: </label></span>
                        <select name="js-language" required>
                            <option value="">Select language</option>
                        <?php foreach ($database->get_data('languages') as $value) :?>
                            <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data[0]['language']) {echo "selected";}?>><?php echo $value['name']?></option>
                        <?php endforeach;?>
                        </select>
                        <span></span>
                        <select name="js-languageLevel" required>
                            <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($database->get_data('language_levels', "", "WHERE language_id = " . $database->get_default_language()) as $value) :?>
                            <option value="<?php echo $value['level']?>" <?php if($value['level'] == $jobseeker_data[0]['language_level']) {echo "selected";}?>><?php echo $value['level_name']?></option>
                            <?php endforeach;?>
                        </select>
                    </label>
                    <label>
                        <span><label for="js-Language"><?php echo $M_FOREIGN_LANGUAGE;?>: </label></span>
                        <select name="js-language1">
                            <option value="">Select language</option>
                        <?php foreach ($database->get_data('languages') as $value) :?>
                            <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data[0]['language_1']) {echo "selected";}?>><?php echo $value['name']?></option>
                        <?php endforeach;?>
                        </select>
                        <span></span>
                        <select name="js-languageLevel1">
                            <option value="">Select level</option>
                            <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($database->get_data('language_levels', "", "WHERE language_id = " . $database->get_default_language()) as $value) :?>
                            <option value="<?php echo $value['level']?>" <?php if($value['level'] == $jobseeker_data[0]['language_1_level']) {echo "selected";}?>><?php echo $value['level_name']?></option>
                            <?php endforeach;?>
                        </select>
                    </label>
                    <label>
                        <span><label for="js-Language"><?php echo $M_FOREIGN_LANGUAGE;?>: </label></span>
                        <select name="js-language2">
                            <option value="">Select language</option>
                        <?php foreach ($database->get_data('languages') as $value) :?>
                            <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data[0]['language_2']) {echo "selected";}?>><?php echo $value['name']?></option>
                        <?php endforeach;?>
                        </select>
                        <span></span>
                        <select name="js-languageLevel2">
                            <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                            <?php foreach ($database->get_data('language_levels', "", "WHERE language_id = " . $database->get_default_language()) as $value) :?>
                            <option value="<?php echo $value['level']?>" <?php if($value['level'] == $jobseeker_data[0]['language_2_level']) {echo "selected";}?>><?php echo $value['level_name']?></option>
                            <?php endforeach;?>
                        </select>
                    </label>
                    <!--Language forms-->
                    
                </div>
            </div>
            <div class="jobseeker-messageArea" name="js-careerObjective" class="jobseeker-messageArea" rows="5" style="width: 100%">
                <div class="jobseeker-title"><h4><?php echo $M_CAREER_OBJECTIVE;?></h4></div>
                <textarea name="js-careerObjective" id="jobseeker-experience" rows="5" style="width: 100%"><?php echo $jobseeker_data[0]['career_objective'];?></textarea>
            </div>
            
            <label class="js-forms" style="width: 400px;">
                <span><b><?php echo $M_FACEBOOK_URL;?>: </b></span>
                <input type="text" name="js-facebookURL" value="<?php echo $jobseeker_data[0]['facebook_URL'];?>">
            </label>
            
            <div class="jobseeker-messageArea" rows="5" style="width: 100%">
                <div class="jobseeker-title"><h4><?php echo $M_EXPERIENCE;?></h4></div>
                <textarea name="js-experience" id="jobseeker-experience" rows="5" style="width: 100%"><?php echo $jobseeker_data[0]['experiences'];?></textarea>
            </div>
            <br><br>
            
            <div class="jobseeker-messageArea" style="width:100%">
                <div class="jobseeker-title"><h4><?php echo $M_YOUR_SKILLS;?></h4></div>
                <textarea name="skills" style="width:100%" rows="5" ><?php echo $jobseeker_data[0]['skills'];?></textarea>
            </div>
            <br><br>
            <strong><i><?php echo $LIST_ATTACHED;?>:</i></strong> 
                
                
        </div>    
        
    </div>
    <input type="submit" value=" <?php echo $SAUVEGARDER;?> " class="btn btn-primary">
</form>
<!--###SonDang modify here###-->