<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries;
    
    $company_jobs = $commonQueries->jobs_by_employerId(4, array(0,5));
   
    $db->where("id", 4);
    $company_info = $db->get("employers");
    print_r($company_jobs);
?>
<div class="page-wrap">
    <div class="row">
        <section class="col-md-8">
            <h2>Prudential</h2>
            <img src="http://localhost/vieclambanthoigian.com.vn/images/empty-star.gif" width="13" height="12" alt="">
            <img src="http://localhost/vieclambanthoigian.com.vn/images/empty-star.gif" width="13" height="12" alt="">
            <img src="http://localhost/vieclambanthoigian.com.vn/images/empty-star.gif" width="13" height="12" alt="">
            <img src="http://localhost/vieclambanthoigian.com.vn/images/empty-star.gif" width="13" height="12" alt="">
            <img src="http://localhost/vieclambanthoigian.com.vn/images/empty-star.gif" width="13" height="12" alt=""> 
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
                    <a href="#chi-tiet" data-toggle="tab">Chi tiết</a>
                </li>                
                <li><a href="#viec-lam" data-toggle="tab">Việc làm</a></li>
                <li><a href="#lien-he" data-toggle="tab">Liên Hệ</a></li>
                <li><a href="http://localhost/vieclambanthoigian.com.vn/index.php?write=1&amp;mod=reviews&amp;id=3&amp;lang=vn">Viết đánh giá</a></li>
            </ul>
        </section>
        <div class="col-md-12">
            <section class="tab-content">
                <!--COMPANY DETAILS-->
                <div id="chi-tiet" class="tab-pane fade in active">
                    <div class="row same_height">
                        <div class="col-md-12">
                            <p><span>Tên công ty: </span><span><?php echo $company_info[0]['company']?></span></p>
                            <p><span>Địa chỉ: </span><span><?php echo $company_info[0]['address']?></span></p>
                            <p><span>Đại diện: </span><span><?php echo $company_info[0]['contact_person']?></span></p>
                            <p><span>Ngày đăng ký: </span><span><?php echo $company_info[0]['contact_person']?></span></p>
                            <p><span>Đại diện: </span><span><?php echo $company_info[0]['contact_person']?></span></p>
                        </div>
                    </div>                        
                </div>
                
                <!--JOBS-->
                <div id="viec-lam" class="tab-pane fade">
                    <div class="row same_height">
                        <div class="col-md-12">
                            <h4>Danh sách việc làm: </h4>
                            <section>
                                <ul>
                                    <?php foreach ($company_jobs as $company_job) :?>
                                    <li><a href="#"><?php echo $company_job['title']?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </section>
                            <p><a href="#">Xem toàn bộ</a></p>
                        </div>
                    </div>                        
                </div>
                
                <!--CONTACT-->
                <div id="lien-he" class="tab-pane fade">
                    <div class="row same_height">
                        <div class="col-md-12">
                            cccccccccccccc
                        </div>
                    </div>                        
                </div>
            </section>
        </div>
    </div>
        
        
    <div class="clear"></div>
    <br>
</div>