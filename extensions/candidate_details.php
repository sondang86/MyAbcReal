<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries,$FULL_DOMAIN_NAME;
        
    $jobseeker_resume = $commonQueries->getJobseekerResume(filter_input(INPUT_GET,'jobseeker_id', FILTER_SANITIZE_NUMBER_INT))['jobseeker_resumes'];
    $jobseeker_profile = $commonQueries->getJobseeker_profile($jobseeker_resume['username']);
    $jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
    $jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);
    $jobseeker_languagues = $commonQueries->getJobseeker_languages($jobseeker_profile['jobseeker_id']);
      
        
//    echo "<pre>";
//    print_r($jobseeker_languagues);
//    echo "</pre>";
?>

<div class="clearfix hidden-xs candidates-nav">
    <a href="http://431.da1.myftpupload.com/candidate-listing" class="btn btn-gray pull-left">Back to Listings</a>
    <div class="pull-right">
        <a class="btn btn-gray" href="http://431.da1.myftpupload.com/candidate/mike-doe/" rel="next">Next</a>
    </div>
</div>
    
<main class="candidates-item candidates-single-item">
    <h5 class="title"><?php echo $jobseeker_profile['first_name']?></h5>
    <span class="meta">
        27 Years Old - 																				Victoria, Australia									</span>

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
                        <form method="post" action="" class="sky-form">
                            
                            <div class="modal-body">
                                
                            <input type="hidden" name="to" value="">
                                    
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
                            <button type="submit" class="btn btn-primary">Gửi</button>
                        </div>
                                
                    </form>
                </div>
            </div>
        </div>
                
        <ul class="social-icons pull-right">
            <li><span>Share</span></li>
            <li><a href="http://www.facebook.com/sharer.php?u=http://431.da1.myftpupload.com/candidate/john-doe/" class="btn btn-gray fa fa-facebook" target="_blank"></a></li>
            <li><a href="http://twitthis.com/twit?url=http://431.da1.myftpupload.com/candidate/john-doe/" class="btn btn-gray fa fa-twitter" target="_blank"></a></li>
            <li><a href="https://plus.google.com/share?url=http://431.da1.myftpupload.com/candidate/john-doe/" class="btn btn-gray fa fa-google-plus" target="_blank"></a></li>
        </ul>
    </div>
</main>