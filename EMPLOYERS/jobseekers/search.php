<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
//if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $categories, $salaries,$experience_list, $positions, $locations, $education;
    
if (isset($_GET['tim_kiem']) && $_GET['tim_kiem'] == "1"){
//    print_r($_GET);
    //with keywords search
    if (isset($_GET['q'])){$queryString = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);} else {$queryString = "";}    
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
    if(isset($_GET['by_date_updated'])){ $by_date_updated = filter_input(INPUT_GET, 'by_date_updated', FILTER_SANITIZE_NUMBER_INT);} else {$by_date_updated="";} 
    
    //perform search
    $jobsInfo_columns = Array (
        $DBprefix."jobseeker_resumes.id as resume_id",$DBprefix."jobseeker_resumes.username",
        $DBprefix."jobseeker_resumes.title as resume_title",$DBprefix."jobseeker_resumes.skills as resume_skills",
        $DBprefix."jobseeker_resumes.date_updated as resume_date_updated",
        $DBprefix."job_experience.name as job_experience_name",$DBprefix."job_experience.name_en as job_experience_name_en",
        $DBprefix."education.education_name as education_name",$DBprefix."education.education_name_en as education_name_en",
        $DBprefix."salary.salary_range",$DBprefix."salary.salary_range_en",
        $DBprefix."categories.category_name",$DBprefix."categories.category_name_vi",
        $DBprefix."job_types.job_name",$DBprefix."job_types.job_name_en",
        $DBprefix."locations.City",$DBprefix."locations.City_en",
    );
    
    $db->join('education', $DBprefix."jobseeker_resumes.education_level =".$DBprefix."education.education_id", "LEFT");
    $db->join('salary', $DBprefix."jobseeker_resumes.expected_salary =".$DBprefix."salary.salary_id", "LEFT");
    $db->join('categories', $DBprefix."jobseeker_resumes.job_category =".$DBprefix."categories.category_id", "LEFT");
    $db->join('job_types', $DBprefix."jobseeker_resumes.job_type =".$DBprefix."job_types.id", "LEFT");
    $db->join('locations', $DBprefix."jobseeker_resumes.location =".$DBprefix."locations.id", "LEFT");
    $db->join('job_experience', $DBprefix."jobseeker_resumes.experience_level =".$DBprefix."job_experience.experience_id", "LEFT");
    $resumes = $db->where('title LIKE "%kỹ%"')
            ->orWhere('skills LIKE "%kỹ%"')
            ->withTotalCount()
            ->get('jobseeker_resumes', NULL, $jobsInfo_columns);
    
    echo "<pre>";
    print_r($resumes);
    echo "</pre>";
}
    
?>
<div class="row">
    <section class="col-md-9"></section>
    <section class="col-md-3 pull-right">
        <?php echo LinkTile("jobseekers","list",$M_BROWSE,"","yellow");?>
    </section>
</div>
    
<form action="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/tim-kiem-ung-vien/" name="jobseekers_search" method="GET" id="jobseekers_search">
    <input type="hidden" name="tim_kiem" value="1" />
    <div class="row">
        <div class="filter-panel collapse in" style="height: auto;">
            <div class="panel panel-default">
                <div class="panel-body">
                    <section class="form-inline search-form" role="form">                        
                        <div class="form-group col-md-6">
                            <input type="text" name="q" class="form-control input-sm" id="pref-search" placeholder="Nhập từ khóa muốn tìm">
                        </div><!-- form group [search] -->
                            
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="by_category">
                                <option value="">Ngành nghề</option>
                                <?php foreach ($categories as $category) :?>
                                <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name_vi']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->      
                            
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="by_location">
                                <option value="">Địa điểm</option>
                                <?php foreach ($locations as $location) :?>
                                <option value="<?php echo $location['id']?>"><?php echo $location['City']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->  
                            
                        <div class="form-group col-md-3">
                            <select id="pref-orderby" class="form-control" name="by_education">
                                <option value="">Trình độ học vấn</option>
                                <?php foreach ($education as $value) :?>
                                <option value="<?php echo $value['education_id']?>"><?php echo $value['education_name']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->
                            
                        <div class="form-group col-md-3">
                            <select id="pref-orderby" class="form-control" name="by_expected_position">
                                <option value="">Vị trí mong muốn</option>
                                <?php foreach ($positions as $position) :?>
                                <option value="<?php echo $position['position_id']?>"><?php echo $position['position_name']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->
                            
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="by_experience_level">
                                <option value="">Mức kinh nghiệm</option>
                                <?php foreach ($experience_list as $experience) :?>
                                <option value="<?php echo $experience['experience_id']?>"><?php echo $experience['name']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->
                            
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="by_date_updated">
                                <option value="">Hồ sơ cập nhật trong vòng</option>
                                <?php foreach ($categories as $category) :?>
                                <option value="<?php echo $category['category_id']?>"><?php echo $category['category_name']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->                        
                            
                        <div class="form-group col-md-1">
                            <button class="input-group-addon search-button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div> <!-- Search button --> 
                            
                    </section>
                </div>
            </div>
        </div>
            
<!--        <button type="button" class="btn btn-primary">
            <span class="glyphicon glyphicon-cog"></span> Tìm kiếm
        </button>-->
    </div>
        
    <!--LISTING SEARCH RESUMES RESULTS-->
    <div class="contentArea">
        <?php foreach ($resumes as $resume) :?>
        <article class="row joblistArea">
            <!--JOB DETAILS-->
            <header class="col-md-12 joblist">            
                <a href="#">
                    <section class="banner">
                        <img src="http://localhost/vieclambanthoigian.com.vn/uploaded_images/48980815.jpg" width="120" height="50">
                    </section>
                    <p title="Shop cần tuyển gấp nhân viên nữ bán quần áo thời trang ," class="desig"><?php echo $resume['resume_title']?></p>
                    <p class="company">
                        <i class="fa fa-briefcase"></i>
                        <span><?php echo $resume['username']?></span>
                    </p>
                        
                    <form class="more">
                        <span class="exp"><i class="fa fa-comments-o"></i> <?php echo $resume['job_name']?></span>
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
                <span class="featuredjob" title="Featured Job">
                    <i class="fa fa-star"></i>
                </span> 
            </header>        
                
            <!--MORE BOTTOM-->        
            <footer class="col-md-12 more-details">           
                <section class="col-md-6 col-xs-6 other_details">
                    <span class="salary"><em></em>  Not disclosed </span> 
                </section>
                <section class="col-md-6 col-xs-6 rec_details">
                    <span> Đăng bởi   <a title="việc làm đăng bởi  sony Ltd " class="rec_name">  <?php echo $resume['username']?> </a></span> 
                    <span><i class="fa fa-clock-o"></i> 4 ngày trước  </span>
                </section>
            </footer>    
        </article>
        <?php endforeach;?>
        
        <!--PAGINATION-->
        <div class="row">
            <section class="col-md-12 paginationArea">
                <div><ul class="pagination"></ul></div>        </section>
        </div>
    </div>
        
</form>    
<style>
    .search-button {
        width: 100%;
        /* border: 1px solid; */
        border-radius: 5px;
        padding: 8px;
        background: #fc205b;
    }
        
    .search-button i {
        color: #ffffff;
    }
        
    .search-form input[type="text"]{
        width: 100%;
        color: grey;
    }
        
    ::-webkit-input-placeholder {
        color: red;
    }
        
    .search-form div select {
        width: 100% !important;
    }
</style>