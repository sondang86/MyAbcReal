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

if($show_cv)		
{
	$arrSeeker = $arrJobseeker = $database->DataArray("jobseekers","id=".$id);
	$arrResume = $database->DataArray("jobseeker_resumes","username='".$arrSeeker["username"]."'");
	
?>
<div class="row">
    <div class="col-md-6">
        <h4>
            <?php echo $CV_OF;?> <?php echo $arrSeeker["first_name"];?> <?php echo $arrSeeker["last_name"];?>
        </h4>
    </div>
</div>

<div class="row">
    <div class="col-md-3 col-sm-12">
        <div class="menu-navigation">
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
    </div>
    <div class="col-md-9 col-sm-12">
        <div class="user-form">
            <label>
                <span>Tên: </span>
                <aside><?php echo $arrSeeker['first_name'] . " " . $arrSeeker['last_name']?></aside>
            </label>
            <label>
                <span>Địa chỉ: </span>
                <aside><?php echo $arrSeeker['address']?>dasdasd</aside>
            </label>
            <label>
                <span>Điện thoại: </span>
                <aside><?php echo $arrSeeker['phone']?>dsadadad</aside>
            </label>
            <label>
                <span>Email: </span>
                <aside><?php echo $arrSeeker['username']?></aside>
            </label>
            <label>
                <span>Ngày sinh: </span>
                <aside><?php echo date('d/M/Y' ,$arrSeeker['date'])?></aside>
            </label>
        </div>
    </div>    
</div>    

<form id="html_form" action="pdf/resume.php" method="post">
    <input id="html_field" type="hidden" name="html" value="">
</form>



<?php
if($database->SQLCount("files","WHERE user='".$arrSeeker["username"]."'  AND is_resume=1 ","file_id") == 0){} else {}?>
    
    
<?php } ?>
    
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
