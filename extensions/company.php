<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $SEO_setting;
    $company_id = $commonQueries->check_present_id($_GET['id'],$SEO_setting);
    $company_jobs = $commonQueries->jobs_by_employerId(4, array(0,5));
    $reviews = $db->rawQuery("SELECT count(id) as number,avg(vote) as vote FROM ".$DBprefix."company_reviews WHERE company_id=3");    
    $db->where("id", 3);
    $company_info = $db->get("employers");
    
//    print_r($company_info);

?>
<div class="page-wrap">
    <div class="row">
        <section class="col-md-8 companyTitle">
            <h2>Prudential</h2>
            <span><?php echo $website->show_stars($reviews[0]['vote']);?></span>
            <span style="position:relative;left:10px">(<a href="danhgia-prudential-3.html">0 Nhận xét</a>)</span>        
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
                <li><a href="<?php $website->check_SEO_link("reviews",$SEO_setting,$company_id, $website->seoUrl($company_info[0]['company']))?>">Viết đánh giá</a></li>
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
                                    <li><a href="#"><?php echo $company_job['title']?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </section>
                            <p><a href="#">Xem toàn bộ</a></p>
                        </form>
                    </section>                        
                </div>
                    
                <!--CONTACT-->
                <div id="lien-he" class="tab-pane fade">
                    <form action="dasdsa" method="post">
                        <section class="row contactForm">
                            <fieldset class="col-md-12">
                                <label>Họ tên</label>
                                <input type="text" name="username" disabled>
                            </fieldset>
                            <fieldset class="col-md-12">
                                <label>Nội dung</label>
                                <textarea name="content" required></textarea>
                            </fieldset>
                            <fieldset class="col-md-12">
                                <label></label>
                                <input type="submit" name="contact" value="Gửi">
                            </fieldset>
                        </section>                        
                    </form>
                </div>
                    
            </section>
        </div>
    </div>
        
        
    <div class="clear"></div>
    <br>
</div>