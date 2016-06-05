<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries,$FULL_DOMAIN_NAME;
        
    $jobseeker_resume = $commonQueries->getJobseekerResume(filter_input(INPUT_GET,'jobseeker_id', FILTER_SANITIZE_NUMBER_INT))['jobseeker_resumes'];
    $jobseeker_profile = $commonQueries->getJobseeker_profile($jobseeker_resume['username']);
    $jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
    $jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);
    $jobseeker_languagues = $commonQueries->getJobseeker_languages($jobseeker_profile['jobseeker_id']);
      
    $next_resume = $commonQueries->get_NextPrev_Record($jobseeker_resume['resume_id'], '>', 'jobseeker_resumes');
    $prev_resume = $commonQueries->get_NextPrev_Record($jobseeker_resume['resume_id'], '<', 'jobseeker_resumes');
        
    //message handling
    if (isset($_POST['submit'])){
        $title = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
        
        $data = array(
            'date'      => time(),
            'user_from' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),  
            'user_to'   => $jobseeker_resume['username'],
            'subject'   => "$title",
            'message'   => "$message"
        );
        
        $id = $db->insert('user_messages', $data);
        if (!$id){
            $commonQueries->flash('message', $commonQueries->messageStyle('danger', "Có lỗi xảy ra, không thể gửi tin nhắn. Vui lòng liên hệ info@vieclambanthoigian.com.vn"));
            $website->redirect($website->CurrentURL());
        } else {
            //Send email notify to jobseeker
            $email_subject  = "Tin nhắn mới từ vieclambanthoigian.com.vn";
            $email_body     = "Chào bạn!\n"
                            . "Bạn có tin nhắn mới trên vieclambanthoigian.com.vn \n\n"
                            . "Dưới đây là nội dung tin nhắn: \n\n"
                            . "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n"
                            . "$title \n\n"
                            . "$message\n\n"
                            . "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n"
                            . "Trân trọng \n\n"
                            . "Vieclambanthoigian.com.vn";
            
            include_once ('include/email_handling.php');
            
            //Redirect back
            $commonQueries->flash('message', $commonQueries->messageStyle('info', "Bạn đã gửi tin nhắn đến ứng viên"));
            $website->redirect($website->CurrentURL()); 
        }
    }
//    
//    echo "<pre>";
//    print_r($jobseeker_profile);
//    echo "</pre>";
?>

<h5><?php $commonQueries->flash('message')?></h5>
<div class="clearfix hidden-xs candidates-nav">
    <a href="<?php echo $FULL_DOMAIN_NAME;?>/vn_ung-vien.html" class="btn btn-gray pull-left">Quay lại danh sách ứng viên</a>
    <div class="pull-right">        
        <?php if($prev_resume['totalCount'] > 0):?>
        <a class="btn btn-gray" href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-ung-vien/<?php echo $prev_resume['record']['id'];?>/<?php echo $website->seoURL($prev_resume['record']['title']);?>" rel="prev">Ứng viên trước đó</a>
        <?php endif;?>
        
        <?php if($next_resume['totalCount'] > 0):?>
        <a class="btn btn-gray" href="<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-ung-vien/<?php echo $next_resume['record']['id'];?>/<?php echo $website->seoURL($next_resume['record']['title']);?>" rel="next">Ứng viên tiếp theo</a>
        <?php endif;?>        
    </div>
</div>
    

<?php
//echo "<pre>";
//print_r($jobseeker_resume);
//echo "</pre>";
?>

<main class="candidates-item candidates-single-item">
    <h5 class="title"><?php echo $jobseeker_resume['title']?></h5>
    <span class="meta">
        <?php echo filter_var($commonQueries->timeCalculation(time(), $jobseeker_profile['dob'],1), FILTER_SANITIZE_NUMBER_INT);?> tuổi - <?php echo $website->limitCharacters($jobseeker_profile['address'], 100)?> </span>

    <ul class="social-icons clearfix">
        <li><a href="<?php echo $jobseeker_resume['facebook_URL']?>" class="btn btn-gray fa fa-facebook" target="_blank"></a></li>
        <li><a href="#" class="btn btn-gray fa fa-twitter"></a></li>
        <li><a href="#" class="btn btn-gray fa fa-google-plus"></a></li>
        <li><a href="#" class="btn btn-gray fa fa-instagram"></a></li>
        <li><a href="#" class="btn btn-gray fa fa-linkedin"></a></li>
    </ul>
    
    <section class="candidate-details-section">
        <H5>Mục tiêu nghề nghiệp</H5>
        <p><?php echo nl2br($jobseeker_resume['career_objective'])?></p>
    </section>
    
    <section class="candidate-details-section">
        <h5>Kinh nghiệm làm việc</h5>
        <p><?php echo nl2br($jobseeker_resume['experiences'])?></p>
    </section>
    
    <section class="candidate-details-section">
        <H5>Kỹ năng, khả năng, tài năng và thành tựu</H5>
        <p><?php echo nl2br($jobseeker_resume['skills'])?></p>
    </section>
    
    <section class="candidate-details-section">
        <h5>Người tham khảo</h5>
        <p><?php echo nl2br($jobseeker_resume['referrers'])?></p>
    </section>
    <hr>
            
        <div class="clearfix">
            
        <a href="#" class="btn btn-default pull-left" data-toggle="modal" data-target="#myModal"><i class="fa fa-envelope-o"></i> Gửi tin nhắn</a>
                
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Gửi tin nhắn cho ứng viên</h4>
                    </div>
                        <form method="POST" action="" class="sky-form">
                            
                            <div class="modal-body">
                                
                            <input type="hidden" name="to" value="<?php echo $jobseeker_resume['resume_id']?>">
                                    
                            <section>
                                <label class="label">E-mail</label>
                                <label class="input">
                                    <i class="icon-append fa fa-envelope-o"></i>
                                    <input type="email" name="email" id="email" required="" aria-required="true">
                                </label>
                            </section>
                                    
                            <section>
                                <label class="label">Tiêu đề</label>
                                <label class="input">
                                    <i class="icon-append fa fa-tag"></i>
                                    <input type="text" name="subject" id="subject" required="" aria-required="true">
                                </label>
                            </section>
                                    
                            <section>
                                <label class="label">Tin nhắn</label>
                                <label class="textarea">
                                    <i class="icon-append fa fa-comment"></i>
                                    <textarea rows="4" name="message" id="message" required="" aria-required="true"></textarea>
                                </label>
                            </section>
                                    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-red" data-dismiss="modal">Đóng</button>
                            <input type="hidden" id="nonce_contact_form" name="nonce_contact_form" value="d64a515304"><input type="hidden" name="_wp_http_referer" value="/candidate/john-doe/">
                            <button type="submit" name="submit" class="btn btn-primary">Gửi</button>
                        </div>
                                
                    </form>
                </div>
            </div>
        </div>
                
        <ul class="social-icons pull-right">
            <li><span>Share</span></li>
            <li><a href="http://www.facebook.com/sharer.php?u=<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-ung-vien/<?php echo $jobseeker_resume['resume_id'];?>/<?php echo $website->seoURL($jobseeker_resume['title']);?>" class="btn btn-gray fa fa-facebook" target="_blank"></a></li>
            <li><a href="http://twitthis.com/twit?url=<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-ung-vien/<?php echo $jobseeker_resume['resume_id'];?>/<?php echo $website->seoURL($jobseeker_resume['title']);?>" class="btn btn-gray fa fa-twitter" target="_blank"></a></li>
            <li><a href="https://plus.google.com/share?url=<?php echo $FULL_DOMAIN_NAME;?>/chi-tiet-ung-vien/<?php echo $jobseeker_resume['resume_id'];?>/<?php echo $website->seoURL($jobseeker_resume['title']);?>" class="btn btn-gray fa fa-google-plus" target="_blank"></a></li>
        </ul>
    </div>
</main>