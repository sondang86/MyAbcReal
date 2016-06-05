<?php if(!defined('IN_SCRIPT')) die("Oops! Nothing here");?>


<!--SUBSCRIPTION DETAILS-->
<form action="" id="credit-selection" class="sky-form" method="POST">
    <header>Đăng ký/thay đổi gói tuyển dụng</header>
    
    <fieldset>					
        <section>
            <span>Địa chỉ email:</span>
            <label class="input">
                <i class="icon-append fa fa-envelope-o"></i>
                <input type="email" placeholder="Địa chỉ email" value="<?php echo $employerInfo['username']?>" readonly>
                <b class="tooltip tooltip-bottom-right">Địa chỉ email của bạn</b>
            </label>
        </section>
        
        <div class="row">
            <section class="col col-6">
                <span>Tên công ty:</span>
                <label class="input">
                    <i class="icon-append fa fa-home"></i>
                    <input type="text" placeholder="Tên công ty" value="<?php echo $employerInfo['company']?>" readonly>
                    <b class="tooltip tooltip-bottom-right">Tên công ty của bạn</b>
                </label>
            </section>
            
            <section class="col col-6">
                <span>Địa chỉ:</span>
                <label class="input">
                    <i class="icon-append fa fa-location-arrow"></i>
                    <input type="text" placeholder="Địa chỉ" value="<?php echo $employerInfo['address']?>" readonly>
                    <b class="tooltip tooltip-bottom-right">Địa chỉ công ty</b>
                </label>
            </section>
        </div>
        
        <div class="row">
            <section class="col col-6">
                <span>Thành phố:</span>
                <label class="select">
                    <select disabled>
                        <option value="0" selected="" disabled="">Thành phố</option>
                        <?php foreach ($locations as $location) :?>
                        <option value="<?php echo $location['id']?>" <?php if(isset($employerInfo['city']) && ($employerInfo['city'] == $location['id'])){echo "selected";}?>><?php echo $location['City']?></option>
                        <?php endforeach;?>
                    </select>
                    <i></i>
                </label>
            </section>
            
            <section class="col col-6">
                <span>Quy mô công ty:</span>
                <label class="select">
                    <select disabled>
                        <option value="0" selected="" disabled="">Quy mô công ty</option>
                        <?php foreach ($company_sizes as $company_size) :?>
                        <option value="<?php echo $company_size['company_size_id']?>" <?php if(isset($employerInfo['company_size']) && ($employerInfo['company_size'] == $company_size['company_size_id'])){echo "selected";}?>>
                            <?php echo $company_size['company_size_name']?>
                        </option>
                        <?php endforeach;?>
                    </select>
                    <i></i>
                </label>
            </section>            
        </div>    
        
        <div class="row">
            <section class="col col-4">
                <span>Website công ty:</span>
                <label class="input">
                    <i class="icon-append fa fa-external-link"></i>
                    <input type="text" placeholder="Website" value="<?php echo $employerInfo['website'];?>" readonly>
                    <b class="tooltip tooltip-bottom-right">Website công ty</b>
                </label>
            </section>
            
            <section class="col col-4">
                <span>Người đại diện:</span>
                <label class="input">
                    <input type="text" placeholder="Người đại diện" required value="<?php echo $employerInfo['contact_person'];?>" readonly>
                </label>
            </section>
            
            <section class="col col-4">
                <span>Số điện thoại:</span>
                <label class="input">
                    <i class="icon-append fa fa-phone"></i>
                    <input type="text" placeholder="Số điện thoại" value="<?php echo $employerInfo['phone'];?>" readonly>
                    <b class="tooltip tooltip-bottom-right">Số điện thoại của bạn</b>
                </label>
            </section>
        </div>
        
        <div class="row">            
            <section class="col col-6">
                <span>Gói đăng ký hiện tại của bạn: </span>
                <label class="input">
                    <i class="icon-append fa fa-registered"></i>
                    <input type="text" placeholder="Gói đăng ký hiện tại" value="<?php echo $current_subscription['name'];?>" readonly>
                    <b class="tooltip tooltip-bottom-right">Gói đăng ký hiện tại của bạn</b>
                </label>
            </section>
            
            <section class="col col-6">
                <span>Gói đăng ký yêu cầu:</span>
                <label class="select">
                    <select name="subscription_request_type">
                        <!--<option value="0" disabled>Gói đăng ký</option>-->
                        <?php foreach ($subscriptions as $subscription) :
                            if ($subscription['id'] == '1'){continue;} //Ignore free subscription
                            if ($current_subscription['current_subscription'] == $subscription['id']){continue;} //Remove current subscription from the list
                        ?>
                        <option value="<?php echo $subscription['id']?>" <?php if($employerInfo['subscription'] == $subscription['id']){echo "selected";}?>><?php echo $subscription['name']?></option>
                        <?php endforeach;?>
                    </select>
                    <i></i>
                </label>
            </section>
            
        </div>
        
    </fieldset>
    
    <fieldset>
        <div class="row">
            <section class="col col-12">
                <span>Lời nhắn:</span>
                <label class="textarea">
                    <i class="icon-append fa fa-comment"></i>
                    <textarea rows="5" type="text" name="employer_message" placeholder="Yêu cầu khác"><?php if (isset($_POST['employer_message'])){echo $_POST['employer_message'];}?></textarea>
                    <b class="tooltip tooltip-bottom-right">Những yêu cầu bạn chưa rõ về gói đăng ký v.v..</b>
                </label>
            </section>
        </div>   
        
        <section class="col col-3 pull-right">
            <label class="label">Vui lòng nhập chính xác mã CAPTCHA dưới đây:</label>
            <label class="input input-captcha">
                <img src="/vieclambanthoigian.com.vn/include/sec_image.php" width="100" height="35" alt="Captcha image" />
                <input type="text" maxlength="6" name="code" id="captcha" required>
            </label>
        </section>
        
    </fieldset>
    <footer>
        <button type="submit" class="button" name="credit_selection_submit">Gửi</button>
        <div class="row">
            <div class="col col-12">
                Lưu ý: <i>Sau khi đăng ký và chuyển khoản thành công, nhân viên của vieclambanthoigian.com.vn sẽ gọi điện để xác thực thông tin và kích hoạt yêu cầu của bạn.</i>
            </div>
        </div>
    </footer>
</form>
<!--SUBSCRIPTION DETAILS-->  
