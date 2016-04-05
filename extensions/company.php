<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $SEO_setting;
    $company_id = $commonQueries->check_present_id("id",$SEO_setting);
    $website->ms_i($company_id); //validate first    
    $company_jobs = $commonQueries->jobs_by_employerId($company_id, array(0,5)); //get 5 jobs only
    $db->where("id", $company_id);
    $company_info = $db->get("employers");    
        
    if(!empty($_COOKIE["AuthJ"])){
        $jobseeker_data = explode("~",$_COOKIE["AuthJ"]);
        $jobseeker_email = $jobseeker_data[0];
        $jobseeker_id = $jobseeker_data[3];
    }
        
    $reviews = $db->rawQuery("SELECT count(id) as number,avg(vote) as vote FROM ".$DBprefix."company_reviews WHERE company_id=$company_id");    
//    print_r($reviews);
    //Check if user has already reviewed
    $user_review = $db->where('jobseeker_id', $jobseeker_id)->withTotalCount()->get('company_reviews');
    if ($db->totalCount > 0) {
        $user_reviewed = TRUE;
    } else {
        $user_reviewed = FALSE;
    }
//    
//    print_r($user_review);
    
?>
<div class="page-wrap">
    <div class="row">
        <section class="col-md-8 companyTitle">
            <h2>Prudential</h2>
            <span><?php echo $website->show_stars($reviews[0]['vote']);?></span>
            <span>(<a href="danhgia-prudential-3.html">0 Nhận xét</a>)</span>        
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
                <?php // if(!empty($_COOKIE["AuthJ"])):?>
                <li><a href="#danh-gia" data-toggle="tab">Viết đánh giá</a></li>
                <?php // endif;?>
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
                            <h4>Danh sách việc làm: </h4>
                            <section>
                                <ul>
                                    <?php foreach ($company_jobs as $company_job) :?>
                                    <li><a href="<?php $website->check_SEO_link("details",$SEO_setting,$company_job['job_id'], $website->seoUrl($company_job['title']))?>"><?php echo $company_job['title']?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </section>
                            <p><a href="<?php $website->check_SEO_link("jobs_by_companyId",$SEO_setting,$company_id, $website->seoUrl($company_job['company']))?>">Xem toàn bộ</a></p>
                        </form>
                    </section>                        
                </div>
                
                <!--CONTACT-->
                <div id="lien-he" class="tab-pane fade">
                    <form action="dasdsa" method="post">
                        <section class="row contactForm">
                            <fieldset class="col-md-12">
                                <label>Họ tên (*)</label>
                                <input type="text" name="username" required>
                            </fieldset>
                            <fieldset class="col-md-12">
                                <label>Nội dung (*)</label>
                                <textarea name="content" required></textarea>
                            </fieldset>
                            <fieldset class="col-md-12">
                                <label></label>
                                <input type="submit" name="contact" value="Gửi">
                            </fieldset>
                        </section>                        
                    </form>
                </div>
                    
                <!--REVIEWS-->
                <?php   
                    //Show review form if user logged in and hasn't submitted before yet
                    if(!empty($_COOKIE["AuthJ"]) && ($user_reviewed == FALSE)){ 
                ?>
                <!--Bar rating-->
                <!--https://github.com/antennaio/jquery-bar-rating-->
                <link href="/vieclambanthoigian.com.vn/css/fontawesome-stars.css" rel="stylesheet" type="text/css"/>
                <script src="/vieclambanthoigian.com.vn/js/jquery.barrating.min.js" type="text/javascript"></script>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#reviewSubmit").on('click', function(event) {
                            var anonymous = ($("#anonymous").prop('checked')? '1' : '0'); 
                            var jobseeker_email = $("#jobseeker-email").attr("value");
                            var jobseeker_id = <?php echo $jobseeker_id;?>;
                            var company_id = <?php echo $company_id;?>;
                            //reviewSubmit button clicked, processing data
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
                                success: function() {
//                                    alert('success');
                                    $(".contactForm").hide("1");
                                    $("#reviewed").show("1");
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
                <div id="danh-gia" class="tab-pane fade">
                    <!--<form action="dasdsa" method="post">-->
                    <section class="row contactForm">
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
                    <!--</form>-->                    
                    <section id="reviewed" style="display:none">
                        <h4>Cảm ơn bạn đã viết đánh giá.</h4>
                    </section>    
                </div>
                
                
                 <?php } //User already reviewed
                        elseif(!empty($_COOKIE["AuthJ"]) && ($user_reviewed == TRUE)){ 
                    ?>                    
                <div id="danh-gia" class="tab-pane fade">
                    <!--<form action="dasdsa" method="post">-->
                    <section class="row contactForm">
                        <fieldset class="col-md-12">
                            <h4>Bạn đã đánh giá công ty này.</h4>
                        </fieldset>
                    </section>
                </div>
                    <?php } //User not yet logged in
                            else { 
                    ?>
                <div id="danh-gia" class="tab-pane fade">
                    <!--<form action="dasdsa" method="post">-->
                    <section class="row contactForm">
                        <fieldset class="col-md-12">
                            <h4>Bạn phải đăng nhập mới thực hiện được chức năng này</h4>
                            <p><button data-target="#login-modal" data-toggle="modal" class="login-trigger btn btn-primary custom-back-color" type="button">ĐĂNG NHẬP</button></p>
                        </fieldset>
                    </section>
                </div>                    
                    <?php }?>
            </section>
        </div>
    </div>
    <style>
        .message {
            background-color: #dadada;
            border: 5px solid #ccc;
            border-radius: 3px;
            height: auto;
            margin: 20px 10px;
            max-width: 100%;
            padding: 5px;
            position: relative;            
          }
          
          .list-reviews {
              position: relative;
          }
          
          .tip {
            width: 0px;
            height: 0px;
            position: absolute;
            background: transparent;
            border: 10px solid #ccc;
          }

          .tip-up {
            left: 15px;
            top: -25px;
            border-right-color: transparent;
            border-left-color: transparent;
            border-top-color: transparent;
          }
          
          .message p {
            min-height: 30px;
            border-radius: 3px;
            font-family: Arial;
            font-size: 14px;
            line-height: 1.5;
            color: #797979;
          }
    </style>
    <!--List reviews-->
    <div class="row list-reviews">
        <section class="col-md-12">
            <span><img src="http://www.prometheanworld.me/img/default/userAvatar.png" height="50" width="50"></span>
            <span>Lee of Exeter, NH on March 31, 2016</span>
            <span>Satisfaction Rating</span>
            <span><?php echo $website->show_stars($reviews[0]['vote']);?></span>
        </section>
        <section class="col-md-12 message">
            <span class="tip tip-up"></span>
            <p>DO NOT purchase this list. I signed up for her list during a remodel 3 years ago. I found only amateur and unprofessional workers, despite "good reviews". I agree with many of the other reviews. Once signed up, it is extremely hard to be removed, if not impossible. I paid for a one-time, temporary subscription thru PayPal. 3 YEARS LATER, they are still charging my account! I have contacted them countless times over the past 3 years to be removed from their list (emails, paper mail and payment). They just ignore me and keep charging! I have never had such a hard time with such basic communication with a company. STAY AWAY from this money hungry company.</p>
        </section>
    </div>
    
    <div class="row list-reviews">
        <section class="col-md-12">
            <span><img src="http://www.prometheanworld.me/img/default/userAvatar.png" height="50" width="50"></span>
            <span>Lee of Exeter, NH on March 31, 2016</span>
            <span>Satisfaction Rating</span>
            <span><?php echo $website->show_stars($reviews[0]['vote']);?></span>
        </section>
        <section class="col-md-12 message">
            <span class="tip tip-up"></span>
            <p>DO NOT purchase this list. I signed up for her list during a remodel 3 years ago. I found only amateur and unprofessional workers, despite "good reviews". I agree with many of the other reviews. Once signed up, it is extremely hard to be removed, if not impossible. I paid for a one-time, temporary subscription thru PayPal. 3 YEARS LATER, they are still charging my account! I have contacted them countless times over the past 3 years to be removed from their list (emails, paper mail and payment). They just ignore me and keep charging! I have never had such a hard time with such basic communication with a company. STAY AWAY from this money hungry company.</p>
        </section>
    </div>
    
    <div class="row list-reviews">
        <section class="col-md-12">
            <span><img src="http://www.prometheanworld.me/img/default/userAvatar.png" height="50" width="50"></span>
            <span>Lee of Exeter, NH on March 31, 2016</span>
            <span>Satisfaction Rating</span>
            <span><?php echo $website->show_stars($reviews[0]['vote']);?></span>
        </section>
        <section class="col-md-12 message">
            <span class="tip tip-up"></span>
            <p>DO NOT purchase this list. I signed up for her list during a remodel 3 years ago. I found only amateur and unprofessional workers, despite "good reviews". I agree with many of the other reviews. Once signed up, it is extremely hard to be removed, if not impossible. I paid for a one-time, temporary subscription thru PayPal. 3 YEARS LATER, they are still charging my account! I have contacted them countless times over the past 3 years to be removed from their list (emails, paper mail and payment). They just ignore me and keep charging! I have never had such a hard time with such basic communication with a company. STAY AWAY from this money hungry company.</p>
        </section>
    </div>
    
</div>