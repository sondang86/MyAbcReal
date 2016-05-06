<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $SEO_setting;
    $company_id = $commonQueries->check_present_id("id",$SEO_setting);
    
    $website->ms_i($company_id); //validate first    
    $company_jobs = $commonQueries->jobs_by_employerId($company_id, array(0,5)); //get 5 jobs only
    $company_info = $db->where("id", $company_id)->get("employers");    
        
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    
    //if user is logged in, store data
    if($_SESSION['user_type'] == "jobseeker"){
        $jobseeker_username = filter_var($_SESSION['username'], FILTER_SANITIZE_STRING);
        
        $user_review = $db->where('company_id', $company_id)
                ->where('email', "$jobseeker_username")
                ->withTotalCount()->get('company_reviews');        
        
        //Check if user has already reviewed
        if ($db->totalCount > 0) {
            $user_reviewed = TRUE;
            $user_review_totalCount = $db->totalCount;
        } else {
            $user_reviewed = FALSE;
        }
    }
        
    $reviews = $db->rawQuery("SELECT count(id) as number,avg(vote) as vote FROM ".$DBprefix."company_reviews WHERE company_id=$company_id");    

        
    
    //Retrieve company reviews
    $company_reviews = $db->withTotalCount()->where("company_id", $company_id)->get("company_reviews");
    $company_reviews_totalCount = $db->totalCount;
        
        
?>
<!--Bar rating-->
<!--https://github.com/antennaio/jquery-bar-rating-->
<link href="/vieclambanthoigian.com.vn/css/fontawesome-stars.css" rel="stylesheet" type="text/css"/>
<script src="/vieclambanthoigian.com.vn/js/jquery.barrating.min.js" type="text/javascript"></script>

<div class="page-wrap">
    <div class="row">
        <section class="col-md-8 companyTitle">
            <h2>Prudential</h2>
            <span><?php echo $website->show_stars($reviews[0]['vote']);?></span>
            <span>(<a href="#reviews"><?php echo $company_reviews_totalCount;?> Nhận xét</a>)</span>        
        </section>        
        <figure class="col-md-4">
            <img src="http://localhost/vieclambanthoigian.com.vn/uploaded_images/90945453.jpg" width="300" class="img-responsive" alt="Prudential">
        </figure>
    </div>     
    <div class="tabbable row">
        <section class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#chi-tiet" data-toggle="tab">Thông tin công ty</a>
                </li>                
                <li><a href="#viec-lam" data-toggle="tab">Việc làm</a></li>
                <li><a href="#lien-he" data-toggle="tab">Liên Hệ</a></li>
                <li><a href="#danh-gia" data-toggle="tab">Viết đánh giá</a></li>
            </ul>
        </section>
            
        <div class="col-md-12">
            <section class="tab-content">
                <!--COMPANY DETAILS-->
                <div id="chi-tiet" class="tab-pane fade in active">
                    <section class="row same_height">
                        <form class="col-md-12">
                            <h4><span>Công ty: </span><span><?php echo $company_info[0]['company']?></span></h4>
                            <p><i class="fa fa-location-arrow"></i><span> Địa chỉ: </span><span><?php echo $company_info[0]['address']?></span></p>
                            <p><i class="fa fa-user"></i><span> Đại diện: </span><span><?php echo $company_info[0]['contact_person']?></span></p>
                            <p><i class="fa fa-sign-in"></i><span> Ngày đăng ký: </span><span><?php echo date("Y-m-d",$company_info[0]['date'])?></span></p>
                            <p><span><?php echo $company_info[0]['company_description']?></span></p>
                        </form>
                    </section>                        
                </div>
                
                <!--JOBS-->
                <div id="viec-lam" class="tab-pane fade">
                    <section class="row same_height">
                        <form class="col-md-12">
                            <?php if($company_jobs !== FALSE) { //Found jobs?>
                            <h4>Danh sách việc làm: </h4>
                            <section>
                                <ul>
                                    <?php foreach ($company_jobs as $company_job) :?>
                                    <li><a href="<?php $website->check_SEO_link("details",$SEO_setting,$company_job['job_id'], $website->seoUrl($company_job['title']))?>"><?php echo $company_job['title']?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </section>
                            <p><a href="<?php $website->check_SEO_link("jobs_by_companyId",$SEO_setting,$company_id, $website->seoUrl($company_job['company']))?>">Xem toàn bộ</a></p>
                            
                            <?php } else { //Not found any jobs?>
                            <h4>Chưa có việc làm nào</h4>                                
                            <?php }?>
                        </form>
                    </section>                        
                </div>
                    
                <!--REVIEWS-->
                <?php   
                    //Show review form if user logged in (jobseeker) and hasn't submitted before yet
                    if(($_SESSION['user_type'] == "jobseeker") && ($user_reviewed == FALSE)){ 
                ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#reviewSubmit").on('click', function(event) { //reviewSubmit button clicked, processing data
                            var anonymous = ($("#anonymous").prop('checked')? '1' : '0'); 
                            var jobseeker_email = $("#jobseeker-email").attr("value");
                            var jobseeker_id = <?php echo $jobseeker_id;?>;
                            var company_id = <?php echo $company_id;?>;                            
                            $.ajax({
                                type: "POST",
                                url: "http://<?php echo $DOMAIN_NAME?>/extensions/handleReview.php",
                                data: {
                                    rating: $(".br-current-rating").text(),
                                    title: $("#review-title").val(),
                                    review: $("#review-content").val(),
                                    anonymous: anonymous,
                                    jobseeker_email: jobseeker_email,
                                    jobseeker_id: jobseeker_id,
                                    company_id: company_id
                                },
                                success: function(response) { 
                                    if (response == 'already reviewed'){
                                        $("#reviewForm").hide("1");
                                        $("#alreadyReviewed").show("1");
                                    } else { //show thanks message
                                        $("#reviewForm").hide("1");
                                        $("#reviewed").show("1");
                                    }
                                }
                            });
                            event.preventDefault();
                        });
                    });                   
                    
                    //Bar rating stars
                    $(function() {
                        $('#star-rating').barrating({
                            theme: 'fontawesome-stars',
                            initialRating: 3, //Default value
                            showSelectedRating: true,
                            onSelect: function(value, text, event) {
                                if (typeof(event) !== 'undefined') {
                                    // rating was selected by a user
                                    var value = $('.br-current-rating').text();
                                    //                              alert(value);                              
                                } 
                            }                         
                        });
                    });                    
                </script>
                
                <!--REVIEW-->
                <div id="danh-gia" class="tab-pane fade">
                    <section class="row contactForm" id="reviewForm">
                        <fieldset class="col-md-12">
                            <label>Đánh giá: </label>
                            <select id="star-rating">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </fieldset>
                        <fieldset class="col-md-12">
                            <label>Email: </label>
                            <input type="text" name="email" id="jobseeker-email" value="<?php echo $jobseeker_email;?>" disabled>
                        </fieldset>
                        <fieldset class="col-md-12">
                            <label>Tiêu đề: </label>
                            <input type="text" name="title" id="review-title">
                        </fieldset>
                        <fieldset class="col-md-12">
                            <label>Review của bạn về công ty (tối thiểu 30 ký tự): (*)</label>
                            <textarea name="review-content" id="review-content" minlength="30"></textarea>
                        </fieldset>
                        <fieldset class="col-md-12">
                            <label>Ẩn danh</label>
                            <input type="checkbox" name="anonymous" id="anonymous" checked="checked">
                        </fieldset>
                        <fieldset class="col-md-12">
                            <label></label>
                            <input type="submit" name="reviewSubmit" id="reviewSubmit" value="Gửi">
                        </fieldset>
                    </section>                        
                        
                    <section id="reviewed" style="display:none">
                        <h4>Cảm ơn bạn đã viết đánh giá.</h4>
                    </section> 
                    <section id="alreadyReviewed" style="display:none">
                        <h4>Bạn đã đánh giá rồi.</h4>
                    </section>
                </div>
                 <?php } //User already reviewed
                        elseif(!empty($_SESSION['username']) && ($user_reviewed == TRUE)){ 
                    ?>                    
                <div id="danh-gia" class="tab-pane fade">
                    <section class="row contactForm" id="reviewForm">
                        <fieldset class="col-md-12">
                            <h4>Bạn đã đánh giá công ty này.</h4>
                        </fieldset>
                    </section>
                </div>
                    <?php } //User not yet logged in
                            else { 
                    ?>
                <div id="danh-gia" class="tab-pane fade">
                    
                    <section class="row contactForm" id="reviewForm">
                        <fieldset class="col-md-12">
                            <h4>Bạn phải đăng nhập mới thực hiện được chức năng này</h4>
                            <p><button data-target="#login-modal" data-toggle="modal" class="login-trigger btn btn-primary custom-back-color" type="button">ĐĂNG NHẬP</button></p>
                        </fieldset>
                    </section>
                </div>                    
                    <?php }?>
                
                
                <!--CONTACT-->
                <script type="text/javascript">                    
                    $(document).ready(function(){
                        //Contact form submit
                        $("#contactSubmit").on('click', function(event){
                            var employer_email = "<?php echo $company_info[0]['username']?>";
                            //Send data to process
                            $.ajax({
                                type: "POST",
                                    url: "http://<?php echo $DOMAIN_NAME?>/extensions/handleReview.php",
                                    data: {
                                        title: $("#contactTitle").val(),
                                        content: $("#contactContent").val(),
                                        email: $("#contactEmail").val(),
                                        employer_email: employer_email
                                },
                                success: function() { //show thanks message
                                    $("#contactForm").hide("1");
                                    $("#reviewed").show("1");
                                }
                            });
                            event.preventDefault();
                        });
                    });
                </script>
                <div id="lien-he" class="tab-pane fade">
                    <section class="row contactForm" id="contactForm">
                        <fieldset class="col-md-12">
                            <label>Tiêu đề (*)</label>
                            <input type="text" name="contactTitle" id="contactTitle" required>
                        </fieldset>
                        
                        <fieldset class="col-md-12">
                            <label>Nội dung (*)</label>
                            <textarea name="contactContent" id="contactContent" required></textarea>
                        </fieldset>
                        
                        <fieldset class="col-md-12">
                            <label>Email của bạn (*)</label>
                            <input type="text" name="username" id="contactEmail" value="<?php if(!empty($jobseeker_email)){echo $jobseeker_email;}?>" <?php if(!empty($jobseeker_email)){echo "disabled";}?> required>
                        </fieldset>
                        
                        <fieldset class="col-md-12">
                            <label></label>
                            <input type="submit" name="contact" id="contactSubmit" value="Gửi">
                        </fieldset>
                    </section>
                    <section id="reviewed" style="display:none">
                        <h4>Bạn đã gửi tin nhắn thành công đến nhà tuyển dụng.</h4>
                    </section>
                </div>
                
            </section>
        </div>
    </div>
    
    <!--List reviews-->
    <h4 id="reviews">Đánh giá: </h4>    
    <?php if($company_reviews_totalCount !== "0"){ //List reviews
        foreach ($company_reviews as $company_review) :
    ?>
    <div class="list-reviews hvr-underline-reveal">        
        <form class="row">
            <section class="col-md-8 col-xs-12 review-user">
                <img src="http://www.prometheanworld.me/img/default/userAvatar.png" height="50" width="50">
                <p>Lee of Exeter vào <?php echo date("M d, Y",$company_review['date'])?></p>
                <p><label><?php echo $company_review['title']?></label> :</p>
            </section>
            <section class="col-md-4 col-xs-12 satisfaction-rating">
                <p>Độ hài lòng:</p>
                <p><?php echo $website->show_stars($company_review['vote']);?></p>
            </section>
        </form>
        
        <form class="row message">
            <span class="tip tip-up"></span>
            <p class="col-md-12"><?php echo $company_review['html']?></p>
        </form>
    </div>
    <?php endforeach; 
        } else {//No records?> 
    <h4>Hiện chưa có đánh giá nào về công ty này</h4>  
    <?php }?>
</div>