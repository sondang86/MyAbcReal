<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries,$FULL_DOMAIN_NAME;
        
    $jobseeker_resume = $commonQueries->getJobseekerResume(filter_input(INPUT_GET,'jobseeker_id', FILTER_SANITIZE_NUMBER_INT))['jobseeker_resumes'];
    $jobseeker_profile = $commonQueries->getJobseeker_profile($jobseeker_resume['username']);
    $jobseeker_categories = $commonQueries->getJobseeker_categories($jobseeker_profile['jobseeker_id']);
    $jobseeker_locations = $commonQueries->getJobseeker_locations($jobseeker_profile['jobseeker_id']);
    $jobseeker_languagues = $commonQueries->getJobseeker_languages($jobseeker_profile['jobseeker_id']);
        
        
        
//    echo "<pre>";
//    print_r($jobseeker_resume);
//    echo "</pre>";
?>

<div class="clearfix hidden-xs candidates-nav">
    <a href="http://431.da1.myftpupload.com/candidate-listing" class="btn btn-gray pull-left">Back to Listings</a>
    <div class="pull-right">
        <a class="btn btn-gray" href="http://431.da1.myftpupload.com/candidate/mike-doe/" rel="next">Next</a>
    </div>
</div>
    
<main class="candidates-item candidates-single-item">
    <h6 class="title"><a href="http://431.da1.myftpupload.com/candidate/john-doe/">John Doe</a></h6>
    <span class="meta">
        27 Years Old - 																				Victoria, Australia									</span>
    
    <!-- <ul class="top-btns">
            <li><a href="#" class="btn btn-gray fa fa-star"></a></li>
    </ul> -->
            
    <ul class="social-icons clearfix">
        <li><a href="http://fb.com" class="btn btn-gray fa fa-facebook"></a></li>										<li><a href="http://fb.com" class="btn btn-gray fa fa-twitter"></a></li>										<li><a href="http://fb.com" class="btn btn-gray fa fa-google-plus"></a></li>																				<li><a href="http://fb.com" class="btn btn-gray fa fa-instagram"></a></li>										<li><a href="http://fb.com" class="btn btn-gray fa fa-linkedin"></a></li>									</ul>
    
    <p>
    </p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit, maxime, excepturi, mollitia, voluptatibus similique aliquid a dolores autem laudantium sapiente ad enim ipsa modi laborum accusantium deleniti neque architecto vitae.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea, nihil, dolores, culpa ullam vero ipsum placeat accusamus nemo ipsa cupiditate id molestiae consectetur quae pariatur repudiandae vel ex quaerat nam iusto aliquid laborum quia adipisci aut ut impedit obcaecati nisi deleniti tempore maxime sequi fugit reiciendis libero quo. Rerum, assumenda.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem, at nemo inventore temporibus corporis suscipit.</p>
    <p></p>
            
        <ul class="list-unstyled">
            
            
            
            
    </ul>
            
            
            
    <hr>
            
        <div class="clearfix">
            
        <a href="#" class="btn btn-default pull-left" data-toggle="modal" data-target="#myModal"><i class="fa fa-envelope-o"></i> Contact Me</a>
                
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Send Us a Message</h4>
                    </div>
                        <form method="post" action="" class="ng-pristine ng-valid">
                            
                            <div class="modal-body">
                                
                            <input type="hidden" name="to" value="">
                                    
                            <div class="form-group">
                                <label for="from">Your Email address</label>
                                <input type="text" id="form" name="from" class="form-control" placeholder="Enter your email">
                            </div>
                                    
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter your subject ">
                            </div>
                                    
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea name="message" id="message" rows="5" placeholder="Enter your message..."></textarea>
                            </div>
                                    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-red" data-dismiss="modal">Close</button>
                            <input type="hidden" id="nonce_contact_form" name="nonce_contact_form" value="d64a515304"><input type="hidden" name="_wp_http_referer" value="/candidate/john-doe/">											        <button type="submit" class="btn btn-default">Submit</button>
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