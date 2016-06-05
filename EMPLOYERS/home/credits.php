<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $commonQueries_Employers, $FULL_DOMAIN_NAME;
    $current_subscription = $commonQueries_Employers->getCurrent_Subscriptions($AuthUserName);
    
?>

<div class="row">
    <section class="col-md-10">
        <h4><?php $commonQueries->flash('message');?></h4>
        <p></p>
        <h5></h5>            
    </section>
        
    <section class="col-md-2 navigation-tabs">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/", 'Về trang chính', 'blue');?>
    </section>
</div>
    
<div class="row subscription-main">
    <div class="col-xs-12">
        <?php if($current_subscription['current_subscription'] == 1):?>
        <h4>Bạn đang dùng gói <?php echo $current_subscription['name']?></h4>
        
        <?php else :?>
        <section class="note">Bạn đang dùng gói 
                <strong><?php echo $current_subscription['name'];?></strong>
                từ ngày <?php echo date('d-m-Y', $employerInfo['subscription_date']);?>, hết hạn vào <?php echo date('d-m-Y',$employerInfo['subscription_date_end']);?>. Sau khi hết hạn, tài khoản của bạn sẽ tự động trở về gói miễn phí
        </section>
        <?php endif;?>
        
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
                        
                    <li>Đăng tối đa 5 tin tuyển dụng</li>
                    <li>Có thể xóa tin sau 3 ngày</li>
                    <!--<li>Tin hiển thị tại tối đa 1 ngành nghề.</li>-->
                    <li>Số lượng đăng việc hấp dẫn 1</li>   
                    <li>Số lượng đăng việc tuyển gấp 0</li>
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
                        Tuyển dụng cơ bản - 300k/tháng
                    </li>
                        
                    <li>Có thể đăng tối đa 20 tin tuyển dụng</li>
                    <li>Có thể xóa tin đã đăng sau 1 ngày</li>
                    <li>Tài khoản đóng dấu "Tài khoản xác thực", tin đóng dấu tin xác thực nhằm gia tăng sự tin tưởng của ứng viên</li>
                    <!--<li>Tin hiển thị tại tối đa 2 ngành nghề.</li>-->
                    <li>Có thể xem email và số điện thoại của ứng viên</li>
                    <li>Số lượng đăng việc hấp dẫn 5</li>   
                    <li>Số lượng đăng việc tuyển gấp 3</li>
                </ul>
                
                <div class="pricing-footer">                    
                    <a href="<?php echo "$FULL_DOMAIN_NAME/EMPLOYERS/dang-ky-dich-vu/"?>" class="btn btn-default btn-lg">CHỌN<i class="glyphicon glyphicon-play-circle"></i></a>
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
                        Tuyển dụng tối ưu - 500k/tháng
                    </li>

                    <li>Có thể đăng tối đa 40 tin tuyển dụng</li>
                    <li>Xóa tin ngay lập tức</li>
                    <li>Tài khoản đóng dấu "Tài khoản xác thực", tin đóng dấu tin xác thực nhằm gia tăng sự tin tưởng của ứng viên</li>
                    <!--<li>Tin hiển thị tại tối đa 4 ngành nghề.</li>-->  
                    <li>Có thể xem email và số điện thoại của ứng viên</li>
                    <li>Số lượng đăng việc hấp dẫn 12</li>   
                    <li>Số lượng đăng việc tuyển gấp 6</li>
                </ul>

                <div class="pricing-footer">                    
                    <a href="<?php echo "$FULL_DOMAIN_NAME/EMPLOYERS/dang-ky-dich-vu/"?>" class="btn btn-default btn-lg">CHỌN<i class="glyphicon glyphicon-play-circle"></i></a>
                </div>
            </section>
        </div>
    </div>
</div>
