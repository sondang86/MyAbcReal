<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db,$categories, $categories_subs,$commonQueries,  $SEO_setting;
    $job_id = $commonQueries->check_present_id($_GET, $SEO_setting, 3);
    $job_details = $commonQueries->job_by_id("jobs", "id", $job_id, NULL, array('id', 'date'));

?>    
<a id="go_back_button" class="btn btn-default btn-xs pull-right no-decoration margin-bottom-5" href="javascript:GoBack()">Quay lại</a>    
<article class="job-details-wrap">
    <!--JOB's TITLE-->
    <div class="row">
        <h2 class="no-margin col-md-10">Nhà hàng Army (chuyên món ăn Việt Nam), Đc: 17B Lý Nam Đế, Hoàn Kiếm, Hà Nội cần tuyển gấp:</h2>
        <section class="social-share">
            <ul class="col-md-2">
                <li><a rel="nofollow" href="https://www.linkedin.com/shareArticle?mini=true&amp;title=Nh%C3%A0+h%C3%A0ng+Army+%28chuy%C3%AAn+m%C3%B3n+%C4%83n+Vi%E1%BB%87t+Nam%29%2C+%C4%90c%3A+17B+L%C3%BD+Nam+%C4%90%E1%BA%BF%2C+Ho%C3%A0n+Ki%E1%BA%BFm%2C+H%C3%A0+N%E1%BB%99i+c%E1%BA%A7n+tuy%E1%BB%83n+g%E1%BA%A5p%3A&amp;url=http://localhost/vieclambanthoigian.com.vn/nghenghiep-nh�-h�ng-army-chuy��n-m��n-��n-viet-76.html" target="_blank"><img src="http://localhost/vieclambanthoigian.com.vn/images/linkedin.gif" width="18" height="18"></a></li>
                <li><a rel="nofollow" href="http://plus.google.com/share?url=http://localhost/vieclambanthoigian.com.vn/nghenghiep-nh�-h�ng-army-chuy��n-m��n-��n-viet-76.html" target="_blank"><img src="http://localhost/vieclambanthoigian.com.vn/images/googleplus.gif" width="18" height="18"></a></li>
                <li><a rel="nofollow" href="http://www.twitter.com/intent/tweet?text=Nh%C3%A0+h%C3%A0ng+Army+%28chuy%C3%AAn+m%C3%B3n+%C4%83n+Vi%E1%BB%87t+Nam%29%2C+%C4%90c%3A+17B+L%C3%BD+Nam+%C4%90%E1%BA%BF%2C+Ho%C3%A0n+Ki%E1%BA%BFm%2C+H%C3%A0+N%E1%BB%99i+c%E1%BA%A7n+tuy%E1%BB%83n+g%E1%BA%A5p%3A&amp;url=http://localhost/vieclambanthoigian.com.vn/nghenghiep-nh�-h�ng-army-chuy��n-m��n-��n-viet-76.html" target="_blank"><img src="http://localhost/vieclambanthoigian.com.vn/images/twitter.gif" width="18" height="18"></a></li>
                <li><a rel="nofollow" href="http://www.facebook.com/sharer.php?u=http://localhost/vieclambanthoigian.com.vn/nghenghiep-nh�-h�ng-army-chuy��n-m��n-��n-viet-76.html" target="_blank"><img src="http://localhost/vieclambanthoigian.com.vn/images/facebook.gif" width="18" height="18"></a></li>
            </ul>
        </section>
    </div>
        
    <!--JOB DETAILS-->
    <div class="job-details-info">            
        <section class="col-md-6">
            <p><label>Thành phố: </label><span>Bien Hoa</span></p>
            <p><label>Đăng vào lúc: </label><span>12/04/16 19:40</span></p>
            <p><label>Số đơn xin việc đã nộp: </label><span>0</span></p> 				
        </section>
        <section class="col-md-6">                    
            <p>
                <label>Loại công việc:</label>
                <span>Toàn thời gian</span> 
            </p>
            <p>
                <label>Mức lương:</label>
                <span><strong>3</strong></span>
            </p>
        </section>
    </div>
        
    <!--JOB DESCRIPTION-->
    <section class="row">
        <article class="col-md-8">
            <br>1. 04 Nữ Nhân viên bàn: không yêu cầu kinh nghiệm. <br>
            + Lương KĐ 5 triệu/tháng; thời gian làm việc: sáng 08h00 - 14h00, chiều 16h00 - 22h00 (nghỉ trưa 14h00 - 16h00). <br>
            + Lương KĐ 2,5 - 3 triệu/tháng. Làm 1 trong ca: từ 8h00 - 15h00 hoặc từ 15h00 - 22h00.<br>
            2. 02 Nam nhân viên bưng bê: không yêu cầu kinh nghiệm. <br>
            Lương KĐ 4 triệu/tháng; thời gian làm việc: sáng 08h00 - 14h00, chiều 16h00 - 22h00 (nghỉ trưa 14h00 - 16h00).<br>
            3. 02 Nhân viên bar, thu ngân: nam hoặc nữ. Lương KĐ 2,5 - 3 triệu/tháng; thời gian làm việc: 1 trong 2 ca. Ca sáng 08h00 - 15h00 hoặc ca chiều 15h00 - 22h00.<br>
            Quyền lợi: tháng làm 28 ngày, bao ăn. <br>
            Liên hệ: anh Lưu, ĐT: 0903214200.<br>
            Hs gom co: Bản phô tô Chứng minh thư hoặc bằng lái xe hoặc sơ yếu lý lịch là được		
        </article>
        <aside class="col-md-4 text-center">
            <a href="http://localhost/vieclambanthoigian.com.vn/viec-lam-cung-cong-ty/5/pepsi">
                <img class="logo-border img-responsive" src="http://localhost/vieclambanthoigian.com.vn/thumbnails/56826869.jpg" alt="pepsi">
            </a>
            <div class="clearfix underline-link"></div>
            <a href="http://localhost/vieclambanthoigian.com.vn/viec-lam-cung-cong-ty/5/pepsi" class="sub-text underline-link">Việc làm khác từ pepsi</a>
            <br>
            <a href="http://localhost/vieclambanthoigian.com.vn/thong-tin-cong-ty/5/pepsi" class="sub-text underline-link">Thông tin công ty</a>
        </aside>
    </section>
        
    <!--JOB APPLY NAV-->
    <footer class="row">
        <figure class="col-md-12">
            <section class="pull-right">
                <a href="http://localhost/vieclambanthoigian.com.vn/nop-ho-so/75/tuyen-8-10-shipper-khu-vuc-noi-thanh-ha-noi">
                    <input type="submit" class="btn btn-default custom-gradient btn-green" value=" Nộp hồ sơ ">
                </a>
            </section>
            <img src="http://localhost/vieclambanthoigian.com.vn/images/email-small-icon.png">
            <a href="#" class="small-link gray-link" data-toggle="collapse" data-target=".email-collapse">Gửi email</a>
                
            <img class="l-margin-20" src="http://localhost/vieclambanthoigian.com.vn/images/save-small-icon.png" height="12">
            <a class="small-link gray-link" href="javascript:SaveListing(75)" id="save_75">Lưu việc làm này</a>                    
        </figure>
    </footer>
        
</article>