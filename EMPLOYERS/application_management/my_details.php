<?php
if(!defined('IN_SCRIPT')) die("");
?>
<br><?php
$data_sanitize = new GUMP;
$GET = $data_sanitize->sanitize($_GET);
global $db;
    
$posting_id=$_REQUEST["posting_id"];
$website->ms_i($posting_id);
    
$arrPosting = $database->DataArray("jobs","id=".$posting_id."  AND employer='".$AuthUserName."'");
    
if(!isset($arrPosting["id"])) {
    die("");
};

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

//Questionnaire data
$answers=$db->rawQuery
	("
            SELECT 
            ".$DBprefix."questionnaire_answers.answer,
            ".$DBprefix."questionnaire.question
            FROM
            ".$DBprefix."questionnaire_answers,
            ".$DBprefix."questionnaire
            WHERE
            ".$DBprefix."questionnaire_answers.question_id=
            ".$DBprefix."questionnaire.id
            AND
            ".$DBprefix."questionnaire_answers.app_id=".$apply_id."
            AND 
            ".$DBprefix."questionnaire.employer='".$AuthUserName."'
	");
?>
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12 fright">
        <?php
            echo LinkTile("","",$M_GO_BACK,"","red","small","true","window.history.back");
            
            //Get the current jobseeker data
            $jobseeker_profile = $GET['apply_id'];
            $jobseeker_data = $database->get_data("jobseeker_resumes", "", "WHERE username='$id'");
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="cv-title">
            <h4>
            <?php echo $DETAILS_JS;?> : <?php echo $id;?>
            </h4>
        </div>

        <ul class="jobseeker-select">
            <li><h5><?php echo $M_NAME_CURRENT_POSITION;?> : </h5></li>
            <li><h5><?php echo $jobseeker_data[0]['name_current_position'];?></h5></li>
        </ul>    
        <div class="jobseeker-messageArea">
    <?php
    if(trim($arrPostingApply["message"])!="")
        {
        ?>
            <div class="jobseeker-title">
                <h4>
                    <strong><i><?php echo $MESSAGE_SENT_JS;?></i></strong>
                </h4>
            </div>
            <div class="jobseeker-message">
            <?php
                echo stripslashes($arrPostingApply["message"]);
            ?>
            </div>
    <?php 
        } 
    ?>
        </div>
    </div>
</div>

<!--Questionnaire-->

<div class="questionnaire">
    <div class="form-group-title">
        <label for="maxOfInterval" class="control-label">Câu hỏi: </label>
    </div>
    <div class="row questionnaire-form">
    <?php foreach ($answers as $answer) :?>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="maxOfInterval" class="control-label"><?php echo $answer['question']?></label>
                <aside>
                    <p>Trả lời:</p> <?php echo $answer['answer']?>
                </aside>
            </div>
        </div>
    <?php endforeach;?>
    </div>
</div>


<!--SonDang modify here-->
<div class="jobseeker-cv">    
    <div class="jobseeker-main">
        
        <!--MAIN-->
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
        
        <!--CAREER OBJECTIVE-->
        <div class="jobseeker-messageArea" name="js-careerObjective" class="jobseeker-messageArea" rows="5" style="width: 100%">
            <div class="jobseeker-title"><h4><?php echo $M_CAREER_OBJECTIVE;?></h4></div>
                <?php echo $jobseeker_data[0]['career_objective'];?>
        </div>

        <!--FACEBOOK URL-->
        <div class="cv-details">
            <label>
                <span style="width:100px; margin-top: 7px;"><b><?php echo $M_FACEBOOK_URL;?></b></span>
                <aside style="width:200px; float: left; text-align: left;"><input type="text" name="js-facebookURL" style="width:350px" value="<?php echo $jobseeker_data[0]['facebook_URL'];?>" readonly="readonly"></aside>
            </label>
        </div>
        
        <!--EXPERIENCE AREA-->
        <div class="jobseeker-messageArea" rows="5" style="width: 100%">
            <div class="jobseeker-title"><h4><?php echo $M_EXPERIENCE;?></h4></div>
                <?php echo $jobseeker_data[0]['experiences'];?>
        </div>
        
        <!--SKILLS AREA-->
        <div class="jobseeker-messageArea" style="width:100%">
            <div class="jobseeker-title"><h4><?php echo $M_YOUR_SKILLS;?></h4></div>
                <?php echo $jobseeker_data[0]['skills'];?>
        </div>

        <!--LIST ATTACHED FILES-->
        <div class="row">
            <div class="col-md-12">
                <section class="attached-section">
                    <label><i><?php echo $LIST_ATTACHED;?>:</i></label>
                    <?php
                            $userFiles = $database->DataTable("files","WHERE user='$id'");

                            while($js_file = $database->fetch_array($userFiles))
                            {
                                    $file_show_link = "";
                                    foreach($website->GetParam("ACCEPTED_FILE_TYPES") as $c_file_type)
                                    {	
                                            if(file_exists("../user_files/".$js_file["file_id"].".".$c_file_type[1]))
                                            {
                                                    $file_show_link = "../user_files/".$js_file["file_id"].".".$c_file_type[1];
                                                    break;
                                            }
                                    }

                                    if(trim($file_show_link)=="") continue;
                        ?>
                    <p>
                        <a target="_blank" href="<?php echo $file_show_link;?>"><b><?php echo $js_file["file_name"];?></b></a>
                        <br>
                        <i style="font-size:10px"><?php echo $js_file["description"];?></i>
                        <br><br>
                    </p>
                    <?php }?>	
                </section>
                </div>
        </div>
    </div>    
    
</div>
<!--###SonDang modify here###-->
