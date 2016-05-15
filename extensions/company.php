<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $SEO_setting;
    $company_id = $commonQueries->check_present_id("id",$SEO_setting);
        
    $website->ms_i($company_id); //validate first    
    $company_jobs = $commonQueries->jobs_by_employerId($company_id, array(0,5)); //get 5 jobs only
    $company_info = $db->where("id", $company_id)->getOne("employers");    
        
    //Default user is not review the company
    $user_reviewed = FALSE;
        
        
    //Is jobseeker profile
    if($_SESSION['user_type'] == "jobseeker"){
        $jobseeker_username = filter_var($_SESSION['username'], FILTER_SANITIZE_STRING);
        $jobseeker_id = $db->where('username', "$jobseeker_username")->getOne('jobseekers', array('id'));
            
            
        $user_review = $db->where('company_id', $company_id)
                ->where('email', "$jobseeker_username")
                ->withTotalCount()->get('company_reviews');        
                    
        //Check if user has already reviewed
        if ($db->totalCount > 0) {
            $user_reviewed = TRUE;
            $user_review_totalCount = $db->totalCount;
        }
    }
        
    $reviews = $db->rawQuery("SELECT count(id) as number,avg(vote) as vote FROM ".$DBprefix."company_reviews WHERE company_id=$company_id");            
        
    //Company reviews
        
    $cols = array(
        $DBprefix."company_reviews.date",$DBprefix."company_reviews.title",$DBprefix."company_reviews.html",
        $DBprefix."company_reviews.company_id",$DBprefix."company_reviews.vote",$DBprefix."company_reviews.jobseeker_id",
        $DBprefix."company_reviews.review_reliable",$DBprefix."company_reviews.review_professional",
        $DBprefix."company_reviews.review_overall",
        $DBprefix."jobseekers.first_name",$DBprefix."jobseekers.last_name",
    );
    $db->join('jobseekers', $DBprefix."company_reviews.jobseeker_id = ".$DBprefix."jobseekers.id");
    $company_reviews = $db->withTotalCount()->where("company_id", $company_id)->get("company_reviews", NULL, $cols);
    $company_reviews_totalCount = $db->totalCount;
    
    //calculate average overall scores
    $cols_overall = array('AVG(review_reliable)', 'AVG(review_professional)', 'AVG(review_overall)');
    $overall_scores = $db->where('company_id', $company_id)->get('company_reviews', NULL, $cols_overall);
?>
    
<div class="page-wrap">
    <div class="row">
        <section class="col-md-8 companyTitle">
            <h2><?php echo $company_info['company']?></h2>
            <span>(<a href="#reviews"><?php echo $company_reviews_totalCount;?> Nhận xét</a>)</span>             
            
            <fieldset class="row">
                <section class="col-xs-4">
                    <p>Độ tin cậy:</p>
                    <p><?php echo $website->show_stars($overall_scores[0]['AVG(review_reliable)']);?></p>
                </section>
                <section class="col-xs-4">
                    <p>Độ chuyên nghiệp:</p>
                    <p><?php echo $website->show_stars($overall_scores[0]['AVG(review_professional)']);?></p>
                </section>
                <section class="col-xs-4">
                    <p>Tổng quan:</p>
                    <p><?php echo $website->show_stars($overall_scores[0]['AVG(review_overall)']);?></p>
                </section>
            </fieldset>
            
        </section> 
        
        <figure class="col-md-4">
            <img src="http://<?php echo $DOMAIN_NAME;?>/images/employers/logo/<?php echo $company_info['logo']?>" width="300" class="img-responsive" alt="Prudential">
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
                            <h4><span>Tên công ty: </span><span><?php echo $company_info['company']?></span></h4>
                            <p><i class="fa fa-location-arrow"></i><span> Địa chỉ: </span><span><?php echo $company_info['address']?></span></p>
                            <p><i class="fa fa-user"></i><span>Người đại diện: </span><span><?php echo $company_info['contact_person']?></span></p>
                            <p><i class="fa fa-sign-in"></i><span> Ngày đăng ký: </span><span><?php echo date("Y-m-d",$company_info['date'])?></span></p>
                            <p><span><?php echo $company_info['company_description']?></span></p>
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
                    
                    $(function(){
                        // Validation
                        $("#danh-gia").validate({					
                            // Rules for form validation
                            rules:{
                                review_name:{
                                    required: true  
                                },
                                review:{
                                    required: true,
                                    minlength: 20
                                },
                                review_reliable:{
                                    required: true
                                },
                                review_professional:{
                                    required: true
                                },
                                review_overall:{
                                    required: true
                                }
                            },

                            // Messages for form validation
                            messages:{
                                review_name: {
                                  required: 'Chưa nhập tên'  
                                },
                                review:{
                                        required: 'Chưa có đánh giá',
                                        minlength: 'Tối thiểu 20 ký tự'
                                },
                                review_reliable:{
                                        required: 'Chưa đánh giá'
                                },
                                review_professional:{
                                        required: 'Chưa đánh giá'
                                },
                                review_overall:{
                                        required: 'Chưa đánh giá'
                                }
                            },

                            // Ajax form submition					
                            submitHandler: function(form){
                                var anonymous = $('#review_anonymous').is(':checked') ? '1' : '0';
                                $.ajax({
                                    type: "POST",
                                    url: "http://<?php echo $DOMAIN_NAME?>/extensions/handleReview.php",
                                    dataType: 'JSON',
                                    data: {
                                        review_name: $("#review_name").val(),
                                        review: $("#review").val(),
                                        review_email: $("#review_email").val(),
                                        review_reliable: $(".review_reliable input[type='radio']:checked").val(),
                                        review_professional: $(".review_professional input[type='radio']:checked").val(),
                                        review_overall: $(".review_overall input[type='radio']:checked").val(),
                                        employer_email: "<?php echo $company_info['username'];?>",
                                        company_id: <?php echo $company_info['id'];?>,
                                        jobseeker_id: <?php echo $jobseeker_id['id'];?>,
                                        anonymous : anonymous,
                                        request_type: 'reviews_employer'
                                    },
                                    success: function(data) { //show thanks message
                                        $("#danh-gia").attr("disabled", true);
                                        $("#reviewed").show("1");
                                        if(data.status == 1){
                                            $.alert(data.message);
                                            $("#danh-gia :input").attr("disabled", true);
                                            $("#form-danh-gia").hide("1");    
                                        } else {
                                            $.alert(data.message);
                                        }
                                    }
                                });
                            },
                            
                            // Do not change code below
                            errorPlacement: function(error, element)
                            {
                                    error.insertAfter(element.parent());
                            }
                        });
                    });		
                </script>
                    
                    
                    
                <!--REVIEW-->
                <form id="danh-gia" class="tab-pane fade sky-form">
                    <div id="form-danh-gia">
                        <header>
                            <p>Đánh giá công ty <?php echo $company_info['company']?></p>
                            <small>Đánh giá khách quan của bạn về công ty, mọi thông tin đánh giá sai lệch sẽ bị xóa và ban nick không cần báo trước</small>
                        </header>

                        <fieldset>	

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-envelope-o"></i>
                                    <input type="email" name="review_email" id="review_email" placeholder="Email" value="<?php echo $jobseeker_username;?>" disabled>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-user"></i>
                                    <input type="text" name="review_name" id="review_name" placeholder="Tên">
                                </label>
                            </section>

                            <section>
                                <label class="label"></label>
                                <label class="textarea">
                                    <i class="icon-append fa fa-comment"></i>
                                    <textarea rows="3" name="review" id="review" placeholder="Nhận xét"></textarea>
                                </label>
                            </section>

                            <section>
                                <div class="rating review_reliable">
                                    <input type="radio" name="review_reliable" value="5" id="reliable-5">
                                    <label for="reliable-5"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_reliable" value="4" id="reliable-4">
                                    <label for="reliable-4"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_reliable" value="3" id="reliable-3">
                                    <label for="reliable-3"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_reliable" value="2" id="reliable-2">
                                    <label for="reliable-2"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_reliable" value="1" id="reliable-1">
                                    <label for="reliable-1"><i class="fa fa-star"></i></label>
                                    Độ tin cậy
                                </div>						

                                <div class="rating review_professional">
                                    <input type="radio" name="review_professional" value="5" id="professional-5">
                                    <label for="professional-5"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_professional" value="4" id="professional-4">
                                    <label for="professional-4"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_professional" value="3" id="professional-3">
                                    <label for="professional-3"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_professional" value="2" id="professional-2">
                                    <label for="professional-2"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_professional" value="1" id="professional-1">
                                    <label for="professional-1"><i class="fa fa-star"></i></label>
                                    Tính chuyên nghiệp
                                </div>				

                                <div class="rating review_overall">
                                    <input type="radio" name="review_overall" class="review_overall" value="5" id="overall-5">
                                    <label for="overall-5"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_overall" class="review_overall" value="4" id="overall-4">
                                    <label for="overall-4"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_overall" class="review_overall" value="3" id="overall-3">
                                    <label for="overall-3"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_overall" class="review_overall" value="2" id="overall-2">
                                    <label for="overall-2"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="review_overall" class="review_overall" value="1" id="overall-1">
                                    <label for="overall-1"><i class="fa fa-star"></i></label>
                                    Tổng thể
                                </div>

                                <div class="review_anonymous clearfix">
                                    <!--<span>Select</span>-->
                                    <label class="checkbox">
                                        <input type="checkbox" name="checkbox" id="review_anonymous" checked><i></i>Ẩn danh                                     
                                    </label>                                
                                </div>
                            </section>
                        </fieldset>
                        <footer>
                            <button type="submit" id="submit" class="button">Gửi đánh giá</button>
                        </footer>  
                    </div>
                    <fieldset id="reviewed" style="display:none">
                        <h4>Cảm ơn bạn đã viết đánh giá.</h4>
                    </fieldset> 
                </form>
                
                <section id="alreadyReviewed" style="display:none">
                    <h4>Bạn đã đánh giá rồi.</h4>
                </section>
                
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
                            elseif ($_SESSION['user_type'] == "guest") { 
                    ?>
                        
                <div id="danh-gia" class="tab-pane fade">
                    
                    <section class="row contactForm" id="reviewForm">
                        <fieldset class="col-md-12">
                            <h4>Bạn phải đăng nhập mới thực hiện được chức năng này</h4>
                            <p><button data-target="#login-modal" data-toggle="modal" class="login-trigger btn btn-primary custom-back-color" type="button">ĐĂNG NHẬP</button></p>
                        </fieldset>
                    </section>
                </div>                    
                    
                    <?php } else { //Employer restricted?>
                        
                <div id="danh-gia" class="tab-pane fade">                    
                    <section class="row contactForm" id="reviewForm">
                        <fieldset class="col-md-12">
                            <h4>Mục này chỉ dành cho người tìm việc</h4>
                        </fieldset>
                    </section>
                </div>
                    
                    <?php }?>
                <!--CONTACT EMPLOYER SCRIPT-->
                <script type="text/javascript">                    
                    $(document).ready(function(){
                        //Contact form submit
                        $("#lien-he").validate({
                            rules:{
                                contact_username:{
                                    required: true
                                },
                                contact_title:{
                                    required: true
                                },
                                contact_company:{
                                    required: true
                                },
                                contact_email:{
                                    required: true
                                },
                                contact_phone:{
                                    required: true
                                },
                                comment:{
                                    required: true
                                }
                                
                            },
                            messages:{
                                contact_username:{
                                    required: 'Vui lòng điền thông tin'
                                },
                                contact_title:{
                                    required: 'Vui lòng điền thông tin'
                                },
                                contact_company:{
                                    required: 'Vui lòng điền thông tin'
                                },
                                contact_email:{
                                    required: 'Vui lòng điền thông tin'
                                },
                                contact_phone:{
                                    required: 'Vui lòng điền thông tin'
                                },
                                comment:{
                                    required: 'Vui lòng điền thông tin'
                                },
                                
                            },
                            submitHandler: function(form) {
                                var employer_email = "<?php echo $company_info['username']?>";
                                $.ajax({
                                    type: "POST",
                                    url: "http://<?php echo $DOMAIN_NAME?>/extensions/handleReview.php",
                                    data: {
                                        contact_username: $("#contact_username").val(),
                                        contact_company: $("#contact_company").val(),
                                        contact_email: $("#contact_email").val(),
                                        contact_phone: $("#contact_phone").val(),
                                        contact_title: $("#contact_title").val(),
                                        contact_comment: $("#contact_comment").val(),
                                        employer_email: employer_email,
                                        request_type: 'contact_employer'
                                    },
                                    success: function() { //show thanks message
                                        $("#contactForm").hide("1");
                                        $("#reviewed").show("1");
                                        $.alert('Đã gửi tin nhắn đến nhà tuyển dụng');
                                    }
                                });
                            }
                        });                        
                    });
                </script>
                    
                    
                <form id="lien-he" class="tab-pane fade">
                    <section class="row sky-form" id="contactForm">
                        <fieldset>					
                            <div class="row">
                                <section class="col col-6">
                                    <label class="input">
                                        <i class="icon-append fa fa-user"></i>
                                        <input type="text" name="contact_username" id="contact_username" placeholder="Tên bạn (*)">
                                    </label>
                                </section>
                                <section class="col col-6">
                                    <label class="input">
                                        <i class="icon-append fa fa-briefcase"></i>
                                        <input type="text" name="contact_company" id="contact_company" placeholder="Công ty">
                                    </label>
                                </section>
                            </div>
                                
                            <div class="row">
                                <section class="col col-6">
                                    <label class="input">
                                        <i class="icon-append fa fa-envelope-o"></i>
                                        <input type="email" name="contact_email" id="contact_email" placeholder="E-mail (*)">
                                    </label>
                                </section>
                                <section class="col col-6">
                                    <label class="input">
                                        <i class="icon-append fa fa-phone"></i>
                                        <input type="tel" name="contact_phone" id="contact_phone" placeholder="Phone">
                                    </label>
                                </section>
                            </div>
                        </fieldset>
                            
                        <fieldset>
                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-comment"></i>
                                    <input type="text" name="contact_title" id="contact_title" placeholder="Tiêu đề (*)">
                                </label>
                            </section>
                                
                            <section>
                                <label class="textarea">
                                    <i class="icon-append fa fa-comment"></i>
                                    <textarea rows="5" name="contact_comment" id="contact_comment" placeholder="Lời nhắn (*)"></textarea>
                                </label>
                            </section>					
                        </fieldset>
                        <footer>
                            <button type="submit" id="submit" class="button">Gửi</button>
                            <div class="progress"></div>
                        </footer>				
                        <div class="message">
                            <i class="fa fa-check"></i>
                            <p>Thanks for your order!<br>We'll contact you very soon.</p>
                        </div>
                    </section>
                </form>	    
                    
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
            <section class="col-md-6 col-xs-12 review-user">
                <img src="http://www.prometheanworld.me/img/default/userAvatar.png" height="50" width="50">
                <p><?php echo $company_review['first_name'] . " " . $company_review['last_name']?> vào lúc <?php echo date("M d, Y",$company_review['date'])?></p>
                <p><label><?php echo $company_review['title']?></label> :</p>
            </section>
            <section class="col-md-2 col-sm-4 col-xs-4 satisfaction-rating">
                <p>Độ tin cậy:</p>
                <p><?php echo $website->show_stars($company_review['review_reliable']);?></p>
            </section>
            <section class="col-md-2 col-sm-4 col-xs-4 satisfaction-rating">
                <p>Độ chuyên nghiệp:</p>
                <p><?php echo $website->show_stars($company_review['review_professional']);?></p>
            </section>
            <section class="col-md-2 col-sm-4 col-xs-4 satisfaction-rating">
                <p>Tổng quan:</p>
                <p><?php echo $website->show_stars($company_review['review_overall']);?></p>
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
<script>
    $(function(){
        $("#lien-he").validate({
            rules:{
                contact_title:{
                    required: true
                }
            },
            messages:{
                contact_title:{
                    required: 'sadajdlkas'
                }
            }
        });
    });    
</script>
<!--Bar rating-->
<!--https://github.com/antennaio/jquery-bar-rating-->
<link href="/vieclambanthoigian.com.vn/css/fontawesome-stars.css" rel="stylesheet" type="text/css"/>
<script src="/vieclambanthoigian.com.vn/js/jquery.barrating.min.js" type="text/javascript"></script>