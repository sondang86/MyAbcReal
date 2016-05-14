<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net

if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries,$positions,$salaries,$categories,$education,$job_types,$location,$language_levels,$languages,$skills, $locations;
    
//Get the current jobseeker data
$jobseeker_data = $db->where('username', "$AuthUserName")->getOne('jobseeker_resumes');
    
//Get the jobseeker profile data
$jobseeker_profile = $commonQueries->getJobseeker_profile($AuthUserName);
$jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
$jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);

$db->where('resume_id',$jobseeker_data['id']);
$jobseeker_languages = $db->withTotalCount()->get('jobseeker_languages');
$jobseeker_languages_count = $db->totalCount;    

    
if(isset($_POST["ProceedSaveResume"])){
   

    //Pre-create resume if empty
    $db->where('username', "$AuthUserName")->withTotalCount()->getOne('jobseeker_resumes');
    if ($db->totalCount == "0"){
        $db->insert('jobseeker_resumes', array('username' => "$AuthUserName"));
    }

    //Locations update
    if(isset($_POST['preferred_locations'])){
        //If user records exists, delete them
            
        $db->where('jobseeker_id', $jobseeker_profile['jobseeker_id'])->withTotalCount()->get('jobseeker_locations');
        if($db->totalCount !== "0"){            
            $db->where('jobseeker_id', $jobseeker_profile['jobseeker_id']);
            if($db->delete('jobseeker_locations')){
                echo 'successfully deleted';
            } else {
                echo "There was a problem when deleting locations";die;
            }            
        }
            
        //Insert records
        foreach ($_POST['preferred_locations'] as $preferred_location) {
            $location_data = array(
                'location_id'   => $preferred_location,
                'jobseeker_id'  => $jobseeker_profile['jobseeker_id']
            );
                
            $id = $db->insert ('jobseeker_locations', $location_data);
            if($id){
                echo 'records were created. Id=' . $id;
            } else {
                echo 'problem while creating records';die;
            }
        }   
    }
        
        
    //categories update
    if(isset($_POST['preferred_categories'])){
        //If user records exists, delete them
        $db->where('jobseeker_id', $jobseeker_profile['jobseeker_id'])->withTotalCount()->get('jobseeker_categories');
        if($db->totalCount !== "0"){
            $db->where('jobseeker_id', $jobseeker_profile['jobseeker_id']);
            if($db->delete('jobseeker_categories')){
                echo 'successfully deleted';
            } else { //Problems when deleting
                echo "There was a problem when deleting categories";die;
            }            
        }
            
        //Insert again
        foreach ($_POST['preferred_categories'] as $preferred_category) {
            $category_data = array(
                'category_id'   => $preferred_category,
                'jobseeker_id'  => $jobseeker_profile['jobseeker_id']
            );
                
            $id = $db->insert ('jobseeker_categories', $category_data);
            if($id){
                echo 'records were created. Id=' . $id;
            } else {
                echo 'problem while creating records';die;
            }
        }
    }
    
    $commonQueries->flash('message',$commonQueries->messageStyle('info',"Cập nhật thành công"));
    $website->redirect("index.php?category=cv&action=resume_creator");    
     
} 
?>


<nav class="row">
    <section class="col-md-9"><h4><?php $commonQueries->flash('message');?></h4></section>
    <section class="col-md-3 navmenu">
        <?php echo LinkTile("profile","current",$M_GO_BACK,"","red");?>                                                    
    </section>
</nav>        

<!--SonDang modify here-->
<div class="row"> 
    
    <form action="" method="post" class="col-md-12" id="js_form">        
        <input type="hidden" name="ProceedSaveResume" value="1">
        <input type="hidden" name="action" value="<?php echo $action;?>">
        <input type="hidden" name="category" value="<?php echo $category;?>">
        <div class="jobseeker-cv">
            <section class="col-md-2 profilePic">
                <?php if (!empty($jobseeker_profile['profile_pic'])){?>
                <figure><img src="http://<?php echo $DOMAIN_NAME?>/images/jobseekers/profile_pic/<?php echo $jobseeker_profile['profile_pic'];?>" id="preview" alt="Ảnh cá nhân hiện tại" class="img-responsive" width="150" height="200"></figure>
                <?php } else {?>
                <figure><img src="http://<?php echo $DOMAIN_NAME?>/images/jobseekers/profile_pic/avatar_nam.jpg" id="preview" alt="Ảnh cá nhân hiện tại" class="img-responsive" width="150" height="200"></figure>
                <?php }?>
            </section>
            <section class="col-md-10 JS_profile">
                <p><label>Họ và Tên: </label> <span><?php echo $jobseeker_profile['first_name']?> <?php echo $jobseeker_profile['last_name']?></span></p>
                <p><label>Địa chỉ: </label> <span><?php echo $jobseeker_profile['address']?></span></p>
                <p><label>Email: </label> <span><?php echo $jobseeker_profile['username']?></span></p>
                <p><label>Số điện thoại: </label> <span><?php echo $jobseeker_profile['phone']?></span></p>
                <p><label>Tình trạng hôn nhân: </label> <span><?php echo $jobseeker_profile['marital_status_name']?></span></p>
                <p><label>Giới tính: </label> <span><?php echo $jobseeker_profile['gender_name']?></span></p>
                <a href="index.php?category=profile&action=current">Sửa thông tin cá nhân</a>
            </section>
            
            
            <!--EXPECTED AREA 1-->
            <div class="col-md-12 jobseeker-main">
                <div class="row modify">
                    <section class="col-md-11"></section>
                    <section class="col-md-1" style="text-align: right;">
                        <a id="modify-expected-areas" href="#" title="Sửa mục này">
                            <i aria-hidden="true" class="fa fa-pencil-square-o fa-2x"></i>                        
                        </a>
                    </section>
                </div>                
                
                
                <fieldset class="row expected_area">
                    <div class="jobseeker-title row">
                        <section class="col-md-6 col-xs-12 js-forms">
                            <label>
                                <span><b>Tiêu đề hồ sơ (*): </b></span>
                                <input type="text" name="js-title" title="" value="<?php echo $jobseeker_data['title']?>" required>
                                <p><label>Ví dụ: Giám đốc kinh doanh, nhân viên marketing, nhân viên bán hàng v.v...</label></p>
                            </label>
                        </section>
                    </div>
                    
                    <div class="row jobseeker-mainTitle">
                        <div class="col-md-6 col-xs-12 js-forms">
                            
                            <!--CURRENT POSITION-->
                            <label>
                                <span><b><?php echo $M_CURRENT_POSITION;?></b></span>                            
                                <select name="js-current-position" required class="form-control">
                                    <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                                    <?php foreach ($positions as $value) :?>
                                    <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data['current_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                                    <?php endforeach;?>
                                </select>
                            </label>
                            
                            <!--CURRENT SALARY-->
                            <label>
                                <span><b><?php echo $M_SALARY;?></b></span>						
                                <select name="js-salary" required class="form-control">
                                    <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                                <?php foreach ($salaries as $value) :?>
                                    <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data['salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                                <?php endforeach;?>
                                </select>            
                            </label>
                            
                            <!--EXPECTED POSITION-->
                            <label>
                                <span><b><?php echo $M_NAME_EXPECTED_POSITION;?></b></span>                                               
                                <select name="js-expected-position" class="form-control">
                                    <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                                <?php foreach ($positions as $value) :?>
                                    <option value="<?php echo $value['position_id']?>" <?php if($value['position_id'] == $jobseeker_data['expected_position']) {echo "selected";}?>><?php echo $value['position_name']?></option>
                                <?php endforeach;?>
                                </select>
                            </label>
                            
                            <!--EXPECTED SALARY-->
                            <label>
                                <span><b><?php echo $M_NAME_EXPECTED_SALARY;?></b></span>						
                                <select name="js-expected-salary" required class="form-control">
                                    <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                                <?php foreach ($salaries as $value) :?>
                                    <option value="<?php echo $value['salary_id']?>" <?php if($value['salary_id'] == $jobseeker_data['expected_salary']) {echo "selected";}?>><?php echo $value['salary_range']?></option>
                                <?php endforeach;?>
                                </select>
                            </label>
                            
                        </div>
                        
                        <div class="col-md-6 col-xs-12 js-forms">
                            <label>
                                <span><b><?php echo $M_JOB_TYPE;?></b></span>						
                                <select name="js-jobType" class="form-control">                            
                            <?php foreach ($job_types as $value) :?>
                                    <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data['job_type']) {echo "selected";}?>><?php echo $value['job_name']?></option>
                            <?php endforeach;?>
                                </select>
                            </label>
                            
                            
                            <!--BROWSE CATEGORY-->
                            <label>
                                <span><b><?php echo $M_BROWSE_CATEGORY;?></b></span>						
                                <select name="js-category" class="form-control">
                            <?php foreach ($categories as $value) :?>
                                    <option value="<?php echo $value['category_id']?>" <?php if($value['category_id'] == $jobseeker_data['job_category']) {echo "selected";}?>><?php echo $value['category_name_vi']?></option>
                            <?php endforeach;?>
                                </select>
                            </label>
                            
                            <!--DESIRE WORK LOCATION-->
                            <label>
                                <span><b><?php echo $WORK_LOCATION;?></b></span>						
                                <select name="js-location" class="form-control">
                            <?php foreach ($locations as $value) :?>
                                    <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_data['location']) {echo "selected";}?>><?php echo $value['City']?></option>
                            <?php endforeach;?>
                                </select>
                            </label>
                            
                            <!--EDUCATION-->
                            <label>
                                <span><b><?php echo $M_EDUCATION;?></b></span>						
                                <select name="education_level" class="form-control">
                                    <option value=""><?php echo $M_PLEASE_SELECT;?></option>
                                <?php foreach ($education as $value) :?>
                                    <option value="<?php echo $value['education_id']?>" <?php if($value['education_id'] == $jobseeker_data['education_level']) {echo "selected";}?>><?php echo $value['education_name']?></option>
                                <?php endforeach;?>
                                </select>
                            </label>
                        </div>
                    </div>
                </fieldset>
                
                <div class="row navigation-tabs">
                    <section class="col-md-6"></section>
                    <section style="text-align: right; display: none;" id="expected_area" class="col-md-6 save_cancel">
                        <span><button class="button btn btn-primary btn-sm" type="submit" id="save_expected_area">Lưu</button></span>
                        <span><button class="button btn btn-warning btn-sm" id="cancel_expected_area">Hủy</button></span>
                    </section>
                </div>
                
                <!--END OF EXPECTED AREA 1-->
                
                <!--EXPECTED AREA 1 SCRIPT-->
                <script>
                    //Disabled select options by default
                    $(document).ready(function(){
                        $(".js-forms label select,.js-forms label input,.js-forms input, .jobseeker-messageArea textarea, .js-radioBoxes span input").attr('disabled', true);
                    });

                    //Show the save & cancel options
                    $(document).on("click", "#modify-expected-areas", function(e){
                        $("#expected_area").show("slow");
                        $(".js-forms label select, .js-forms label input").attr("disabled", false);
                        e.preventDefault();
                    });

                    //disable language form input if user click cancel
                    $(document).on("click", "#cancel_expected_area", function(e){
                        $("#expected_area").hide("slow");
                        $(".js-forms label select, .js-forms label input").attr("disabled", true);
                        e.preventDefault();
                    });
                
                    //Save data to db
                    $(document).on("click", "#save_expected_area", function(e){
                        var resume_id       = <?php echo $jobseeker_data['id'] ?>;
                        var username        = "<?php echo $AuthUserName?>";
                        var expected_areas  = $('.expected_area').serializeObject();

                        e.preventDefault();
                        //                    console.log(expected_areas);        

                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "http://<?php echo $DOMAIN_NAME;?>/jobseekers/extensions/save_updates.php",
                            data: { 
                                data: expected_areas ,
                                resume_id: resume_id,
                                username: username,
                                request_type: "save_expected_area1"
                            },
                            success:  function(data){
                                if(data.status == "1"){ //success, we will disable button
                                    $("#expected_area").hide("slow");
                                    $(".js-forms label select, .js-forms label input").attr("disabled", true);
                                    $.alert({
                                        title: 'Thông báo!',
                                        content: 'Lưu thay đổi thành công!'
                                    });
                                } else {
                                    $.alert({
                                        title: 'Có lỗi xảy ra!',
                                        content: 'Có lỗi xảy ra khi lưu thay đổi, vui lòng liên hệ info@vieclambanthoigian.com.vn!'
                                    });
                                };
                            }
                        });
                    });
                </script>
                
                
                
                <div class="row edit_language modify">
                    <section class="col-md-11"><h4>Ngoại ngữ: </h4></section>
                    <section class="col-md-1" style="text-align: right;">
                        <a href="#" id="modify-language" title="Sửa mục này">
                            <i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>                        
                        </a>                    
                    </section>
                </div>
                
                <!--LANGUAGES AREA-->
                <div class='wrapper'>
                <?php if ($jobseeker_languages_count == "0") { //No languages found, display default selection tab?>
                    
                    <fieldset class="row language-form">
                        
                        <section class="col-md-4 col-sm-12 js_language">
                            <span>Trình độ</span>
                            <select name="js_language" class="form-control">
                                <?php foreach ($languages as $value) :?>
                                <option value="<?php echo $value['id']?>"><?php echo $value['language_name']?></option>
                                <?php endforeach;?>
                            </select>
                        </section>
                        
                        <section class="col-md-6 col-sm-11 js_language_level">
                            <span>Trình độ</span>
                            <select name="js_language_level" class="form-control">
                                <?php foreach ($language_levels as $value) :?>
                                <option value="<?php echo $value['level']?>"><?php echo $value['level_name']?></option>
                                <?php endforeach;?>
                            </select>
                            <span><button type="button" class="delete button btn btn-danger btn-sm">X</button></span>
                        </section>
                        
                    </fieldset>
                    
                <?php } else {                        
                        
                    foreach ($jobseeker_languages as $jobseeker_language) :?>
                    <fieldset class="row language-form">
                        
                        <section class="col-md-5 col-xs-12">
                            <span>Ngôn ngữ: </span>
                            <select name="js_language" required class="form-control">
                                <?php foreach ($languages as $value) :?>
                                <option value="<?php echo $value['id']?>" <?php if($value['id'] == $jobseeker_language['language_id']) {echo "selected";}?>><?php echo $value['language_name']?></option>
                                <?php endforeach;?>
                            </select>
                        </section>
                        
                        <section class="col-md-7 col-xs-12">
                            <span>Trình độ</span>
                            <select name="js_language_level" required class="form-control">
                                <?php foreach ($language_levels as $value) :?>
                                <option value="<?php echo $value['level']?>" <?php if($value['level'] == $jobseeker_language['level_id']) {echo "selected";}?>><?php echo $value['level_name']?></option>
                                <?php endforeach;?>
                            </select>
                            <span><button type="button" class="delete button btn btn-danger btn-sm">X</button></span>
                        </section>
                        
                        <input type="hidden" name="language_keyId[]" value="<?php echo $jobseeker_language['id']?>">
                        
                    </fieldset>
                    <?php endforeach; }?>
                </div>
                
                
                <!--ADD MORE LANGUAGE BUTTON-->
                <div class="row addbutton">
                    <section class="col-md-6">
                        <input id="addbutton" type="button" value="Thêm ngoại ngữ" class="button btn btn-primary btn-sm"/>
                    </section>
                    <section class="col-md-6 save_cancel" id="language_choice" style="display: none; text-align: right">
                        <span><button id="save_language" type="submit" class="button btn btn-primary btn-sm">Lưu</button></span>
                        <span><button id="cancel_language" class="button btn btn-warning btn-sm">Hủy</button></span>
                    </section>
                </div>
                <!--END LANGUAGES AREA-->
                
                <script>   
                    /** Add/Remove languages*/    
                    var Count = $('.language-form').length; //total current language tabs

                    //Hide add more language button
                    if (Count > 2) {
                        $("#addbutton").hide("slow");                           
                    }


                    function add_element(Count){
                        if (Count > 0) {
                            $('#removebutton').show("slow");
                        }
                        //Clone select options
                        $('.language-form:first').clone().find('option').prop('selected', false).end().appendTo('.wrapper');

                        //Show add button while < 3 elements only
                        if (Count > 2){
                            $("#addbutton").hide("slow");
                        } 
                    };

                    //Add language
                    $(document).on('click',"#addbutton", function(){
                        Count++; // + 1 tab
                        add_element(Count);
                    });

                    //Remove selected tab
                    $(document).on('click', ".delete", function(e){
                        //Prevent delete the last tab
                        if (Count > "1") {
                            //Delete slowly
                            $(this).parents(".language-form").fadeOut(100, function(){
                                $(this).remove();
                            });
                            Count--;

                            //Show back add button
                            if(Count <= "2"){
                                $("#addbutton").show("slow");
                            }
                        } else {
                            $.alert('Phải có it nhất 1 ngôn ngữ','Thông báo');
                        }

                        //Prevent form click
                        e.preventDefault();
                    });

                    //Language modify
                    //Disable select forms & buttons by default
                    $(document).ready(function(){
                        $(".language-form section select").attr("disabled", true); //Disable selection by default
                    });

                    //Show the save & cancel options
                    $(document).on("click", "#modify-language", function(e){
                        $("#language_choice").show("slow");
                        $(".language-form section select").attr("disabled", false);
                        e.preventDefault();
                    });

                    //disable language form input if user click cancel
                    $(document).on("click", "#cancel_language", function(e){
                        $("#language_choice").hide("slow");
                        $(".language-form section select").attr("disabled", true);
                        e.preventDefault();
                    });

                    //Save data to db
                    $(document).on("click", "#save_language", function(e){
                        var resume_id = <?php echo $jobseeker_data['id'] ?>;
                        var jobseeker_id = <?php echo $jobseeker_profile['jobseeker_id']?>;
                        var languages_selected = $('.language-form').serializeObject();

                        e.preventDefault();

                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "http://<?php echo $DOMAIN_NAME;?>/jobseekers/extensions/save_updates.php",
                            data: { 
                                languages: languages_selected ,
                                resume_id: resume_id, 
                                jobseeker_id: jobseeker_id,
                                request_type: "language_update"
                            },
                            success:  function(data){
                                if(data.status == "1"){ //success, we will disable button
                                    $("#language_choice").hide("slow");
                                    $(".language-form section select").attr("disabled", true);
                                    $.alert({
                                        title: 'Thông báo!',
                                        content: 'Lưu thay đổi thành công!'
                                    });
                                } else {
                                    $.alert({
                                        title: 'Có lỗi xảy ra!',
                                        content: 'Có lỗi xảy ra khi lưu thay đổi, vui lòng liên hệ info@vieclambanthoigian.com.vn!'
                                    });
                                };
                            }
                        });
                    });

                    /** Languages area*/
                </script>                
                
                
                <!--EXPECTED AREA 2-->
                
                <div class="row modify">
                    <section class="col-md-11"></section>
                    <section class="col-md-1" style="text-align: right;">
                        <a id="modify-expected-areas2" href="#" title="Sửa mục này">
                            <i aria-hidden="true" class="fa fa-pencil-square-o fa-2x"></i>                        
                        </a>
                    </section>
                </div>  
                <fieldset class="row expected_area2">    
                    <!--CAREER OBJECTIVE-->
                    <div class="jobseeker-messageArea" name="js-careerObjective" class="jobseeker-messageArea" rows="5" style="width: 100%">
                        <div class="jobseeker-title"><h4><?php echo $M_CAREER_OBJECTIVE;?></h4></div>
                        <p><label>Gợi ý: Mục tiêu ngắn hạn của bạn trong một vài năm tới, Mục tiêu dài hạn trong 5-10 năm tới</label></p>
                        <p><label>Ví dụ: </label></p>
                        <p>- Mong muốn được làm việc trong môi trường năng động, chuyên nghiệp v.v...</p>
                        <p>- Công ty tạo điều kiện tốt cho việc học tập nâng cao trình độ v.v...</p>
                        <p>- Công việc ổn định lâu dài, có cơ hội thăng tiến cao v.v...</p>
                        <p>- Mong muốn được đóng góp cho công ty </p>
                        <textarea name="js-careerObjective" id="jobseeker-experience" rows="5" style="width: 100%"><?php echo $jobseeker_data['career_objective'];?></textarea>
                    </div>
                    
                    <!--EXPERIENCE-->
                    <div class="jobseeker-messageArea" rows="5" style="width: 100%">
                        <div class="jobseeker-title">
                            <h4><?php echo $M_EXPERIENCE;?></h4>
                            <p><label> Liệt kê các kinh nghiệm công việc từ thời gian gần nhất trở về trước.</label></p>
                            <p><label> Kinh nghiệm có thể trong công việc hoặc các hoạt động đoàn thể.</label></p>
                        </div>
                        <textarea name="js-experience" id="jobseeker-experience" rows="5" style="width: 100%"><?php echo $jobseeker_data['experiences'];?></textarea>
                    </div>
                    
                    <!--SKILLS-->
                    <div class="jobseeker-messageArea" style="width:100%">
                        <div class="jobseeker-title"><h4><?php echo $M_YOUR_SKILLS;?></h4></div>
                        <p><label>Gợi ý: Liệt kê những kỹ năng, khả năng nổi bật của bạn</label></p>
                        <p><label>Ví dụ: </label></p>
                        <p>- Kỹ năng liên quan chuyên môn </p>
                        <p>- Kỹ năng về ngoại ngữ: tiếng Anh, Pháp, Trung v.v... </p>
                        <p>- Kỹ năng về tin học: tin học văn phòng, ngôn ngữ lập trình, quản trị điều hành mạng v.v... </p>
                        <p>- Kỹ năng giao tiếp, thuyết phục khách hàng v.v... </p>
                        <p>- Khả năng nắm bắt công việc, làm việc theo nhóm, nghiên cứu tài liệu v.v... </p>
                        <p>- Khả năng hòa nhập, thích nghi với môi trường, khả năng tư duy, thuyết trình v.v... </p>
                        <p>- Mô tả phải có ít nhất 50 ký tự trở lên</p>
                        <textarea name="skills" style="width:100%" rows="5" ><?php echo $jobseeker_data['skills'];?></textarea>
                    </div>
                    
                    <!--REFERENCES-->
                    <div class="jobseeker-messageArea" style="width:100%">
                        <div class="jobseeker-title"><h4>Thông tin người tham khảo</h4></div>
                        <p><label>Gợi ý: Liệt kê những người tham khảo (quản lý của bạn, giám đốc, tên công ty, số điện thoại, email v.v..)</label></p>
                        <textarea name="referrers" style="width:100%" rows="5" ><?php echo $jobseeker_data['referrers'];?></textarea>
                    </div>
                    
                    
                    <!--<strong><i><?php echo $LIST_ATTACHED;?>:</i></strong>-->                  
                    
                    <!--IT SKILLS-->
                    <div class="row">
                        <label class="col-md-12 js-forms">
                            <span><b>Tin học văn phòng : </b></span>
                            <section class="js-radioBoxes">
                            <?php foreach ($skills as $skill) :?>
                                <span><input type="radio" name="js-IT_skill" value="<?php echo $skill['skill_id']?>" required <?php if($skill['skill_id'] == $jobseeker_data['IT_skills']){echo "checked";}?>><?php echo $skill['name']?></span>
                            <?php endforeach;?>
                            </section>
                        </label>
                    </div>
                    
                    <!--GROUP SKILL-->
                    <section class="row">
                        <label class="col-md-12 js-forms">
                            <span><b>Khả năng làm việc nhóm: </b></span>
                            <section class="js-radioBoxes">
                            <?php foreach ($skills as $skill) :?>
                                <span><input type="radio" name="js-group_skill" value="<?php echo $skill['skill_id']?>" required <?php if($skill['skill_id'] == $jobseeker_data['group_skills']){echo "checked";}?>><?php echo $skill['name']?></span>
                            <?php endforeach;?>
                            </section>
                        </label>
                    </section>
                    
                    <!--PRESSURE SKILL-->
                    <section class="row">
                        <label class="col-md-12 js-forms">
                            <span><b>Khả năng chịu áp lực: </b></span>
                            <section class="js-radioBoxes">
                            <?php foreach ($skills as $skill) :?>
                                <span><input type="radio" name="js-pressure_skill" value="<?php echo $skill['skill_id']?>" required <?php if($skill['skill_id'] == $jobseeker_data['pressure_skill']){echo "checked";}?>><?php echo $skill['name']?></span>
                            <?php endforeach;?>
                            </section>
                        </label>
                    </section>
                    
                    <!--FACEBOOK ADDRESS-->
                    <section class="row">
                        <label class="col-md-12 js-forms">
                            <span><b><?php echo $M_FACEBOOK_URL;?>: </b></span>
                            <input type="text" name="js-facebookURL" value="<?php echo $jobseeker_data['facebook_URL'];?>">
                        </label>
                    </section>
                    
                    <div class="row navigation-tabs">
                        <section class="col-md-6"></section>
                        <section style="text-align: right; display: none;" id="expected_area2" class="col-md-6 save_cancel">
                            <span><button class="button btn btn-primary btn-sm" type="submit" id="save_expected_area2">Lưu</button></span>
                            <span><button class="button btn btn-warning btn-sm" id="cancel_expected_area2">Hủy</button></span>
                        </section>
                    </div>
                </fieldset>
                
                <!--EXPECTED AREA 2 SCRIPT-->
                <script>
                    //Show the save & cancel options & enable modify
                    $(document).on("click", "#modify-expected-areas2", function(e){
                        $("#expected_area2").show("slow");
                        $(".jobseeker-messageArea textarea, .js-radioBoxes span input, .js-forms input").attr("disabled", false);
                        e.preventDefault();
                    });

                    //disable language form input if user click cancel
                    $(document).on("click", "#cancel_expected_area2", function(e){
                        $("#expected_area2").hide("slow");
                        $(".jobseeker-messageArea textarea, .js-radioBoxes span input, .js-forms input").attr("disabled", true);
                        e.preventDefault();
                    });

                    //Save data to db
                    $(document).on("click", "#save_expected_area2", function(e){
                        var resume_id       = <?php echo $jobseeker_data['id'] ?>;
                        var username        = "<?php echo $AuthUserName?>";
                        var expected_areas2  = $('.expected_area2').serializeObject();

                        e.preventDefault();
                        //                        console.log(expected_areas2);        

                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "http://<?php echo $DOMAIN_NAME;?>/jobseekers/extensions/save_updates.php",
                            data: { 
                                data: expected_areas2 ,
                                resume_id: resume_id,
                                username: username,
                                request_type: "save_expected_area2"
                            },
                            success:  function(data){
                                if(data.status == "1"){ //success, we will disable button
                                    $("#expected_area2").hide("slow");
                                    $(".jobseeker-messageArea textarea, .js-radioBoxes span input, .js-forms input").attr("disabled", true);
                                    $.alert({
                                        title: 'Thông báo!',
                                        content: 'Lưu thay đổi thành công!'
                                    });
                                } else {
                                    $.alert({
                                        title: 'Có lỗi xảy ra!',
                                        content: 'Có lỗi xảy ra khi lưu thay đổi, vui lòng liên hệ info@vieclambanthoigian.com.vn!'
                                    });
                                };
                            }
                        });
                    });
                </script>   
                
                
                
                <!--DESIRED LOCATIONS-->
                <div class="row top-bottom-margin desires-section">
                    <span class="col-md-3"><h5>Nơi làm việc mong muốn</h5></span>
                    <section class="col-md-4 ListDesires-section">
                <?php foreach ($jobseeker_locations as $jobseeker_location) :?>
                        <span class="desire-tag"><?php echo $jobseeker_location['City'] ?>,</span>
                <?php endforeach;?>
                    </section>
                    <section class="col-md-5 select2-searchBox">
                        <select name="preferred_locations[]" id="preferred_locations" multiple="multiple">
                    <?php foreach ($locations as $location) :?>
                            <option value="<?php echo $location['id']?>"><?php echo $location['City']?></option>
                    <?php endforeach; ?>
                        </select>
                    </section>
                </div>
                <!--DESIRED LOCATIONS-->
                
                <!--DESIRED CATEGORIES-->
                <div class="row top-bottom-margin desires-section">
                    <span class="col-md-3"><h5>Ngành nghề mong muốn</h5></span>
                    <section class="col-md-4 ListDesires-section">
                <?php foreach ($jobseeker_categories as $jobseeker_category) :?>
                        <span class="desire-tag"><?php echo $jobseeker_category['category_name_vi'] ?>,</span>
                <?php endforeach;?>
                    </section>
                    <section class="col-md-5 select2-searchBox">
                        <select name="preferred_categories[]" id="preferred_categories" multiple="multiple">
                    <?php foreach ($categories as $value) :?>
                            <option value="<?php echo $value['id']?>"><?php echo $value['category_name_vi']?></option>
                    <?php endforeach; ?>
                        </select>
                    </section>
                </div>
                <!--DESIRED CATEGORIES-->
                
                <input type="hidden" name="jobseeker_id" value="<?php echo $jobseeker_profile['jobseeker_id'] //JOBSEEKER ID?>">
                <input type="hidden" name="resume_id" value="<?php echo $jobseeker_data['id']; //RESUME ID?>">
            </div>  
            <div class="row">
                <section class="col-md-3 pull-right"><input type="submit" value=" <?php echo $SAUVEGARDER;?> " class="btn btn-primary"></section>
            </div>
        </div>
        
    </form>
</div>
<!--###SonDang modify here###-->
