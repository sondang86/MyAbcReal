<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $commonQueries_Employers;

    $current_subscription = $commonQueries_Employers->getCurrent_Subscriptions($AuthUserName);
    
    print_r($current_subscription);
?>


<div class="row">
    <section class="col-md-10">
        <h4><?php $commonQueries->flash('message');?></h4>
        <p></p>
        <h5></h5>            
    </section>
        
    <section class="col-md-2 navigation-tabs">
        <?php echo LinkTile("home","welcome",$M_DASHBOARD,"","blue");?>
    </section>
</div>
    
<div class="row subscription-main">
    <div class="col-xs-12">
        <h4>Bạn đang dùng gói <?php echo $current_subscription['name']?></h4>
        <h5>Để đăng ký gói dịch vụ mới, bạn vui lòng chọn gói đăng ký rồi làm theo form hướng dẫn. Nếu bạn chưa chọn bất cứ đăng ký nào thì mặc định sẽ là gói miễn phí</h5>
        <h5>Vui lòng gọi hotline 0984363189 nếu bạn có bất kỳ vướng mắc gì</h5>
    </div>
        
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="db-wrapper">
            <section class="db-pricing-seven">
                <ul>
                    <li class="price">
                        <i class="glyphicon glyphicon-qrcode"></i>
                        Miễn phí - Free
                    </li>
                        
                    <li>Đăng tối đa 3 tin</li>
                    <li>Xem 100 hồ sơ ứng viên/ ngày</li>
                    <li>Sửa, đóng, làm mới tin sau 3 ngày</li>
                    <li>Tin hiển thị tại tối đa 1 ngành nghề.</li>  
                </ul>
                <div class="pricing-footer">
                    
                    <!--<a href="#" class="btn btn-default btn-lg">CHỌN <i class="glyphicon glyphicon-play-circle"></i></a>-->
                </div>
            </section>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="db-wrapper">
            <section class="db-pricing-seven">
                <ul>
                    <li class="price">
                        <i class="glyphicon glyphicon-indent-right"></i>
                        Gói cơ bản - Basic
                    </li>
                        
                    <li>Đăng tin không giới hạn</li>
                    <li>Xem 100 hồ sơ ứng viên/ ngày</li>
                    <li>Sửa, đóng, làm mới tin</li>
                    <li>Tài khoản đóng dấu "Tài khoản xác thực", tin đóng dấu tin xác thực nhằm gia tăng sự tin tưởng của ứng viên</li>
                    <li>Tin hiển thị tại tối đa 3 ngành nghề.</li>                    
                </ul>
                
                <div class="pricing-footer">                    
                    <a href="index.php?category=home&action=credit_selection&type=2" class="btn btn-default btn-lg">CHỌN<i class="glyphicon glyphicon-play-circle"></i></a>
                </div>
            </section>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="db-wrapper">
            <section class="db-pricing-seven">
                <ul>
                    <li class="price">
                        <i class="glyphicon glyphicon-list-alt"></i>
                        ULTIMATE - 99 $
                    </li>
                        
                    <li>Tin hiển thị tại box "Tuyển dụng tiêu điểm" tại trang ngành đăng tuyển (hiển thị logo công ty kèm theo)</li>
                    <li>Hiển thị cố định tại box tiêu điểm ở tất cả các kết quả tìm kiếm liên quan trên hệ thống</li>
                    <li>Xác thực tài khoản tương đương thời gian sử dụng</li>
                    <li>Tin hiển thị nổi bật trong danh sách tin ngành nghề (màu xanh)</li>
                    <li>Tin được đóng dấu “Tin tiêu điểm” thu hút ứng viên</li>
                    <li>Tin hiển thị tối đa trong 5 ngành nghề (3 ngành nghề khách chọn, 2 ngành nghề CSKH hỗ trợ)</li>
                    <li>Quảng cáo CPM hiển thị luân phiên trong box Tuyển dụng nhanh ở tất cả các việc làm chi tiết</li>
                </ul>
                
                <div class="pricing-footer">                    
                    <a href="index.php?category=home&action=credit_selection&type=3" class="btn btn-default btn-lg">CHỌN <i class="glyphicon glyphicon-play-circle"></i></a>
                </div>
            </section>
        </div>
    </div>        
</div>

<style>    
    .db-pricing-seven:hover {
        margin-top: 5px;
        -moz-transition: margin-top 0.25s ease 0s;
        -ms-transition: margin-top 0.25s ease 0s;
        transition: margin-top 0.25s ease 0s;
    }
        
    .db-pricing-seven {
        margin-bottom: 30px;
        margin-top: 30px;
        text-align: center;
        border: 1px solid #C5C2C2;
        background-color: #EDEDED;
    }
        
    .db-pricing-seven ul {
        list-style: none;
        margin: 0;
        text-align: center;
        padding-left: 0px;
    }
        
    .db-pricing-seven ul li.price {
        background-color: #fff;
        padding: 40px 20px 20px 20px;
        font-size: 20px;
        font-weight: 900;
        color: #353c3e;
        font-weight: 900;
    }
        
    .db-pricing-seven ul li {
        border-bottom: solid 1px #D8D8D8;
        padding-top: 20px;
        padding-bottom: 20px;
        cursor: pointer;
        text-transform: uppercase;
    }
        
    .db-pricing-seven .pricing-footer {
        padding: 20px;
    }
    
    .subscription-main {
        background-color: #f1f4f4;
    }
        
</style>