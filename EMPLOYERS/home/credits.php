<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
?>
<script src="../js/jquery.modal.js" type="text/javascript"></script>

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
        <h4>Bạn đang sử dụng gói tuyển dụng Free</h4>
        <h5>Để đăng ký gói dịch vụ mới, bạn vui lòng chọn gói đăng ký rồi làm theo form hướng dẫn nhoa</h5>
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
                    
                    <a href="#sky-form" class="btn btn-default btn-lg modal-opener">CHỌN <i class="glyphicon glyphicon-play-circle"></i></a>
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
                    
                    <a href="#sky-form" class="btn btn-default btn-lg modal-opener">CHỌN<i class="glyphicon glyphicon-play-circle"></i></a>
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
                    
                    <a href="#sky-form" class="btn btn-default btn-lg modal-opener">CHỌN <i class="glyphicon glyphicon-play-circle"></i></a>
                </div>
            </section>
        </div>
    </div>        
</div>
    
<form action="" id="sky-form" class="sky-form sky-form-modal">
    <header>Login form</header>
        
    <fieldset>					
        <section>
            <div class="row">
                <label class="label col col-4">E-mail</label>
                <div class="col col-8">
                    <label class="input">
                        <i class="icon-append fa fa-user"></i>
                        <input type="email" name="email" id="email">
                    </label>
                </div>
            </div>
        </section>
            
        <section>
            <div class="row">
                <label class="label col col-4">Password</label>
                <div class="col col-8">
                    <label class="input">
                        <i class="icon-append fa fa-lock"></i>
                        <input type="password" name="password" id="password">
                    </label>
                    <div class="note"><a href="#">Forgot password?</a></div>
                </div>
            </div>
        </section>
            
        <section>
            <div class="row">
                <div class="col col-4"></div>
                <div class="col col-8">
                    <label class="checkbox"><input type="checkbox" name="checkbox-inline" checked><i></i>Keep me logged in</label>
                </div>
            </div>
        </section>
    </fieldset>
    <footer>
        <button type="submit" class="button">Log in</button>
        <a href="#" class="button button-secondary modal-closer">Close</a>
    </footer>
</form>
    
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