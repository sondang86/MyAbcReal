<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $categories, $salaries,$experience_list, $positions, $locations, $education, $FULL_DOMAIN_NAME, $time_range;
?>

<?php
    //Pagination options    
//    $new_str = preg_replace('/&trang[=][0-9a-zA-Z\W].*/', '', $website->CurrentURL()); //Remove "trang" word and all of the rest after for pagination
    
    //Set current page to 1 if empty & validation page format
    if(!isset($_GET['trang']) || !$commonQueries->isLegal_Number($_GET['trang'])){
        $current_page = 1;
    } else {
        $current_page = filter_input(INPUT_GET,'trang', FILTER_SANITIZE_NUMBER_INT);
    }    
?>

<!--NAVIGATION TAB-->

<div class="row">
    <section class="col-md-10">
        <h4><?php $commonQueries->flash('message');?></h4>        
    </section>
    <section class="col-md-2 navigation-tabs">
        <?php 
            echo LinkTile("home","welcome",$M_DASHBOARD,"","blue");
        ?>
    </section>
</div>

<!--SEARCH FORM-->
<form action="http://<?php echo $DOMAIN_NAME?>/EMPLOYERS/tim-kiem-ung-vien/" name="jobseekers_search" method="GET" id="jobseekers_search">
    <input type="hidden" name="tim_kiem" value="1" />
    <div class="row search-area">
        <div class="filter-panel collapse in" style="height: auto;">
            <div class="panel panel-default">
                <div class="panel-body">
                    <section class="form-inline search-form" role="form">                        
                        <div class="form-group col-md-6">
                            <input type="text" name="q" class="form-control input-sm" id="pref-search" placeholder="Nhập từ khóa muốn tìm" value="<?php if(isset($_GET['q'])){echo $_GET['q'];}?>">
                        </div><!-- form group [search] -->
                        
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="by_category">
                                <option value="">Ngành nghề</option>
                                <?php foreach ($categories as $category) :?>
                                <option value="<?php echo $category['category_id']?>" <?php if(isset($_GET['by_category']) && $_GET['by_category'] == $category['category_id']) echo "selected"?>>
                                    <?php echo $category['category_name_vi']?>
                                </option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->      
                        
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="by_location">
                                <option value="">Địa điểm</option>
                                <?php foreach ($locations as $location) :?>
                                <option value="<?php echo $location['id']?>" <?php if(isset($_GET['by_location']) && $_GET['by_location'] == $location['id']) echo "selected"?>>
                                    <?php echo $location['City']?>
                                </option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->  
                        
                        <div class="form-group col-md-3">
                            <select id="pref-orderby" class="form-control" name="by_education">
                                <option value="">Trình độ học vấn</option>
                                <?php foreach ($education as $value) :?>
                                <option value="<?php echo $value['education_id']?>" <?php if(isset($_GET['by_education']) && $_GET['by_education'] == $value['education_id']) echo "selected"?>>
                                    <?php echo $value['education_name']?>
                                </option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->
                        
                        <div class="form-group col-md-3">
                            <select id="pref-orderby" class="form-control" name="by_expected_position">
                                <option value="">Vị trí mong muốn</option>
                                <?php foreach ($positions as $position) :?>
                                <option value="<?php echo $position['position_id']?>" <?php if(isset($_GET['by_expected_position']) && $_GET['by_expected_position'] == $position['position_id']) echo "selected"?>>
                                    <?php echo $position['position_name']?>
                                </option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->
                        
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="by_experience_level">
                                <option value="">Mức kinh nghiệm</option>
                                <?php foreach ($experience_list as $experience) :?>
                                <option value="<?php echo $experience['experience_id']?>" <?php if(isset($_GET['by_experience_level']) && $_GET['by_experience_level'] == $experience['experience_id']) echo "selected"?>>
                                    <?php echo $experience['name']?>
                                </option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->
                        
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="by_date_updated">
                                <option value="">Hồ sơ cập nhật trong vòng</option>
                                <?php foreach ($time_range as $range) :?>
                                <option value="<?php echo $range['day_number']?>" <?php if(isset($_GET['by_date_updated']) && $_GET['by_date_updated'] == $range['day_number']) echo "selected"?>>
                                    <?php echo $range['time_range_name']?>
                                </option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->                        
                        
                        <div class="form-group col-md-1">
                            <button class="input-group-addon search-button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div> <!-- Search button --> 
                        
                        <input type="hidden" name="trang" value="1" />
                    </section>
                </div>
            </div>
        </div>    
    </div>  
</form>


<?php //Perform search
if (isset($_GET['tim_kiem']) && $_GET['tim_kiem'] == "1"){
    $current_url = preg_replace('/&trang[=][0-9a-zA-Z\W].*/', '', $website->CurrentURL()); //Remove "trang" word and all of the rest after for pagination
    $reload= "$current_url&";//Link href for pagination
   
    //with keywords search
    if (isset($_GET['q'])){$queryString = $commonQueries->addPlustoString(filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING));} else {$queryString = "";}        
    // with category option
    if(isset($_GET['by_category'])){ $by_category = filter_input(INPUT_GET, 'by_category', FILTER_SANITIZE_NUMBER_INT);} else {$by_category="";}
    // with location option
    if(isset($_GET['by_location'])){ $by_location = filter_input(INPUT_GET, 'by_location', FILTER_SANITIZE_NUMBER_INT);} else {$by_location="";} 
    // with education option
    if(isset($_GET['by_education'])){ $by_education = filter_input(INPUT_GET, 'by_education', FILTER_SANITIZE_NUMBER_INT);} else {$by_education="";} 
    // with expected_position option
    if(isset($_GET['by_expected_position'])){ $by_expected_position = filter_input(INPUT_GET, 'by_expected_position', FILTER_SANITIZE_NUMBER_INT);} else {$by_expected_position="";} 
    // with experience_level option
    if(isset($_GET['by_experience_level'])){ $by_experience_level = filter_input(INPUT_GET, 'by_experience_level', FILTER_SANITIZE_NUMBER_INT);} else {$by_experience_level="";} 
    
    // with date_updated option
    if(isset($_GET['by_date_updated'])){ 
        //Get days number
        $by_date_updated = filter_input(INPUT_GET, 'by_date_updated', FILTER_SANITIZE_NUMBER_INT);
        
    } else { //Not found by_date_updated request
        $by_date_updated="";        
    } 
    
    $resumes = $commonQueries->Search_Resumes(TRUE,$queryString,$current_page, $by_category, $by_location, $by_education, $by_expected_position, $by_experience_level, $by_date_updated);
    
    if ($resumes['totalCount'] > 0) {//Search found records?>


<!--LISTING SEARCH RESUMES RESULTS-->        
<div class="row">
    <h4 class="col-md-4">Tìm thấy <?php echo $resumes['totalCount']?> ứng viên</h4>
</div>
<div class="contentArea">
<?php foreach ($resumes['resumes'] as $resume) :
    //Set profile pic to default if user has not been uploaded it yet
    $profile_pic = $commonQueries->setDefault_ifEmpty($resume['profile_pic'], "$FULL_DOMAIN_NAME/images/commons/jobs_portal_logo_demo.jpg","$FULL_DOMAIN_NAME/images/jobseekers/profile_pic/" . $resume['profile_pic']);
?>
    <article class="row joblistArea">
        <!--JOB DETAILS-->
        <header class="col-md-12 joblist">            
            <a href="http://<?php echo $DOMAIN_NAME?>/EMPLOYERS/index.php?category=jobseekers&action=cv_details&id=<?php echo $resume['resume_id']?>" target="_blank" title="Chi tiết hồ sơ ứng viên <?php echo $resume['last_name'] . " " . $resume['first_name'];?>">
                <section class="banner">
                    <img src="<?php echo $profile_pic;?>" width="120" height="110px">
                </section>
                <p title="<?php echo $resume['resume_title']?>" class="desig"><?php echo $resume['resume_title']?></p>
                <p class="company">
                    <span><i class="fa fa-user" aria-hidden="true"></i></span>
                    <span><?php echo $resume['last_name'] . " " . $resume['first_name'];?></span>
                </p>
                
                <p class="company">
                    <span><i class="fa fa-briefcase"></i></span>
                    <span><?php echo $resume['position_name'];?></span>
                </p>
                
                <form class="more">
                    <span><i class="fa fa-list-alt" aria-hidden="true"></i> <?php echo $resume['job_name']?></span>
                    <span class="loc">
                        <i class="fa fa-location-arrow"></i>
                        <span><?php echo $resume['City']?></span>            
                    </span> 
                </form>
                
                <form class="more"> 
                    <i class="fa fa-diamond"></i> <span> Chuyên ngành:</span>
                    <span class="desc"> 
                        <p iclass="skill"><?php echo $resume['category_name_vi']?></p> 
                    </span>  
                </form>
                
                <form class="more"> 
                    <i class="fa fa-money"></i> <span> Mức lương mong muốn:</span>
                    <span class="experience"> 
                        <p><?php echo $resume['salary_range']?></p> 
                    </span>  
                </form>
            </a>
            <span class="featuredjob" title="Ứng viên nổi bật">
                <i class="fa fa-star"></i>
            </span> 
        </header>        
        
        <!--MORE BOTTOM-->        
        <footer class="col-md-12 more-details">           
            <section class="col-md-6 col-xs-6 other_details">
                <!--<span class="salary"><em></em>  Not disclosed </span>--> 
            </section>
            <section class="col-md-6 col-xs-6 rec_details">
                <span> Lần cập nhật cuối: </span> 
                <span><i class="fa fa-clock-o"></i> <?php echo $commonQueries->time_ago($resume['resume_date_updated'])?>  </span>
            </section>
        </footer>    
    </article>
        <?php endforeach;?>
    
    
    
<?php } else { // Not found any matched candidates?>
    <h4>Không tìm thấy ứng viên phù hợp với tiêu chí, vui lòng tìm kiếm với tiêu chí phù hợp</h4>   

    
    
<?php } 
        } else { //Show newest jobseekers CVs by default 
            $reload="$FULL_DOMAIN_NAME/EMPLOYERS/tim-kiem-ung-vien/?";//Link href
            $resumes = $commonQueries->Search_Resumes(FALSE,$queryString="",$current_page);?>
    
    <div class="row">
        <h4 class="col-md-4">Tìm thấy <?php echo $resumes['totalCount']?> ứng viên</h4>
    </div>
    <?php foreach ($resumes['resumes'] as $resume) :
        //Set profile pic to default if user has not been uploaded yet
        $profile_pic = $commonQueries->setDefault_ifEmpty($resume['profile_pic'], "$FULL_DOMAIN_NAME/images/commons/jobs_portal_logo_demo.jpg","$FULL_DOMAIN_NAME/images/jobseekers/profile_pic/" . $resume['profile_pic']);
    ?>            
    <article class="row joblistArea">
        <!--JOB DETAILS-->
        <header class="col-md-12 joblist">            
            <a href="http://<?php echo $DOMAIN_NAME?>/EMPLOYERS/index.php?category=jobseekers&action=cv_details&id=<?php echo $resume['resume_id']?>" target="_blank" title="Chi tiết hồ sơ ứng viên <?php echo $resume['last_name'] . " " . $resume['first_name'];?>">
                <section class="banner">
                    <img src="<?php echo $profile_pic;?>" width="120" height="110px">
                </section>
                <p title="<?php echo $resume['resume_title']?>" class="desig"><?php echo $resume['resume_title']?></p>
                <p class="company">
                    <span><i class="fa fa-user" aria-hidden="true"></i></span>
                    <span><?php echo $resume['last_name'] . " " . $resume['first_name'];?></span>
                </p>
                
                <p class="company">
                    <span><i class="fa fa-briefcase"></i></span>
                    <span><?php echo $resume['position_name'];?></span>
                </p>
                
                <form class="more">
                    <span><i class="fa fa-list-alt" aria-hidden="true"></i> <?php echo $resume['job_name']?></span>
                    <span class="loc">
                        <i class="fa fa-location-arrow"></i>
                        <span><?php echo $resume['City']?></span>            
                    </span> 
                </form>
                
                <form class="more"> 
                    <i class="fa fa-diamond"></i> <span> Chuyên ngành:</span>
                    <span class="desc"> 
                        <p iclass="skill"><?php echo $resume['category_name_vi']?></p> 
                    </span>  
                </form>
                
                <form class="more"> 
                    <i class="fa fa-money"></i> <span> Mức lương mong muốn:</span>
                    <span class="experience"> 
                        <p><?php echo $resume['salary_range']?></p> 
                    </span>  
                </form>
            </a>
            <span class="featuredjob" title="Ứng viên nổi bật">
                <i class="fa fa-star"></i>
            </span> 
        </header>        
        
        <!--MORE BOTTOM-->        
        <footer class="col-md-12 more-details">           
            <section class="col-md-6 col-xs-6 other_details">
                <!--<span class="salary"><em></em>  Not disclosed </span>--> 
            </section>
            <section class="col-md-6 col-xs-6 rec_details">
                <span> Lần cập nhật cuối: </span> 
                <span><i class="fa fa-clock-o"></i> <?php echo $commonQueries->time_ago($resume['resume_date_updated'])?>  </span>
            </section>
        </footer>    
    </article>
            <?php endforeach;?>
    
    
    
    <?php }?>
    
    <!--PAGINATION-->

    <div class="row">
        <section class="col-md-12 paginationArea">
            <?php $commonQueries->pagination($reload, $current_page, $resumes['totalPages'], 0);?>
        </section>
    </div>
</div>

