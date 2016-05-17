<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries, $locations, $gender, $employer_data;
    $subscriptions = $db->get('subscriptions');
    $company_sizes = $db->get('employers_company_size');
   
if (isset($_POST['submit'])){
    //Insert on duplicate update    
    $data = array(
        'employer_id'           => $employer_data['id'],
        'subscription_request_type'   => $_POST['subscription_request_type'],
        'date'                  => time(),
        'employer_message'      => $_POST['employer_message'],
        'is_processed'          => 0, //Default is not processed, until admin approved 
    );
    
    $updateColumns = Array ("subscription_request_type", "date", "employer_message", "employer_id");
    $db->onDuplicate($updateColumns);
    $id = $db->insert ('subscription_employer_request', $data);
    
    if (!$id){
        echo 'problem';die;
    }
    
    //Succeed, back to question page
    $commonQueries->flash('message', $commonQueries->messageStyle('info', "Yêu cầu của bạn đang được xử lý..."));
    $website->redirect("index.php?category=home&action=credit_selection");    
}  
    
//If credit id not exists, redirect back to credits area with message
    
//Show register form

?>

<h5><?php $commonQueries->flash('message')?></h5>

<form action="" id="credit-selection" class="sky-form" method="POST">
    <header>Chi tiết đăng ký gói tuyển dụng - Basic</header>    
    <fieldset>					
        <section>
            <label class="input">
                <i class="icon-append fa fa-envelope-o"></i>
                <input type="email" placeholder="Địa chỉ email" value="<?php echo $employer_data['username']?>" readonly>
                <b class="tooltip tooltip-bottom-right">Địa chỉ email của bạn</b>
            </label>
        </section>
        
        <div class="row">
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-home"></i>
                    <input type="text" placeholder="Tên công ty" value="<?php echo $employer_data['company']?>" readonly>
                    <b class="tooltip tooltip-bottom-right">Tên công ty của bạn</b>
                </label>
            </section>

            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-location-arrow"></i>
                    <input type="text" placeholder="Địa chỉ" value="<?php echo $employer_data['address']?>" readonly>
                    <b class="tooltip tooltip-bottom-right">Địa chỉ công ty</b>
                </label>
            </section>
        </div>
        
        <div class="row">
            <section class="col col-6">
                <label class="select">
                    <select disabled>
                        <option value="0" selected="" disabled="">Thành phố</option>
                        <?php foreach ($locations as $location) :?>
                        <option value="<?php echo $location['id']?>" <?php if(isset($employer_data['city']) && ($employer_data['city'] == $location['id'])){echo "selected";}?>><?php echo $location['City']?></option>
                        <?php endforeach;?>
                    </select>
                    <i></i>
                </label>
            </section>
            
            <section class="col col-6">
                <label class="select">
                    <select disabled>
                        <option value="0" selected="" disabled="">Quy mô công ty</option>
                        <?php foreach ($company_sizes as $company_size) :?>
                        <option value="<?php echo $company_size['company_size_id']?>" <?php if(isset($employer_data['company_size']) && ($employer_data['company_size'] == $company_size['company_size_id'])){echo "selected";}?>>
                            <?php echo $company_size['company_size_name']?>
                        </option>
                        <?php endforeach;?>
                    </select>
                    <i></i>
                </label>
            </section>            
        </div>    
        
        <div class="row">
            <section class="col col-3">
                <label class="input">
                    <i class="icon-append fa fa-external-link"></i>
                    <input type="text" placeholder="Website" value="<?php echo $employer_data['website'];?>" readonly>
                    <b class="tooltip tooltip-bottom-right">Website công ty</b>
                </label>
            </section>
            
            <section class="col col-3">
                <label class="input">
                    <input type="text" placeholder="Người đại diện" required value="<?php echo $employer_data['contact_person'];?>" readonly>
                </label>
            </section>
            
            <section class="col col-3">
                <label class="input">
                    <i class="icon-append fa fa-phone"></i>
                    <input type="text" placeholder="Số điện thoại" value="<?php echo $employer_data['phone'];?>" readonly>
                    <b class="tooltip tooltip-bottom-right">Số điện thoại của bạn</b>
                </label>
            </section>
            
            <section class="col col-3">
                <label class="select">
                    <select name="subscription_request_type">
                        <option value="0" disabled>Gói đăng ký</option>
                        <?php foreach ($subscriptions as $subscription) :?>
                        <option value="<?php echo $subscription['id']?>" <?php if($employer_data['subscription'] == $subscription['id']){echo "selected";}?>><?php echo $subscription['name']?></option>
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
                <label class="textarea">
                    <i class="icon-append fa fa-comment"></i>
                    <textarea type="text" name="employer_message" placeholder="Yêu cầu khác"></textarea>
                    <b class="tooltip tooltip-bottom-right">Những yêu cầu bạn chưa rõ về gói đăng ký v.v..</b>
                </label>
            </section>

            <section class="col col-12">
                <label class="checkbox"><input type="checkbox" name="terms" id="terms"><i></i>Tôi đồng ý với các điều khoản của vieclambanthoigian.com.vn</label>
            </section>
        </div>
        
        <section class="pull-right captcha">
            <p>Mã Captcha</p>
            <span>
                <input type="text" required name="code" value="" size="8">
                <img src="/vieclambanthoigian.com.vn/include/sec_image.php" width="150" height="30" >
            </span>
        </section>
        
    </fieldset>
    <footer>
        <button type="submit" class="button" name="submit">Gửi</button>
        <div class="row">
            <div class="col col-12">
                Lưu ý: <i>Sau khi đăng ký và chuyển khoản thành công, nhân viên của vieclambanthoigian.com.vn sẽ gọi điện để xác thực thông tin và kích hoạt yêu cầu của bạn.</i>
            </div>
        </div>
    </footer>
</form>

<style>
    .captcha label{
        display: block;
        margin-top: 15px;
    }
</style>

<script type="text/javascript">
    $(function()
    {
        // Validation		
        $("#credit-selection").validate({					
            // Rules for form validation
            rules:
            {                         
                email:{
                    required: true,
                    email: true
                },
                
                company_name: {
                    required: true
                },
                
                company_address: {
                    required: true
                },
                
                company_city : {
                    required: true
                },
                
                company_size : {
                    required: true
                },
                    
                mobile: {
                    required: true,
                    minlength: 6,
                    number: true
                },
                
                gender: {
                    required: true
                },
                terms: {
                    required: true
                },
                code: {
                    required: true        
                }
            },
                
            // Messages for form validation
            messages:{                            
                email:{
                    required: 'Vui lòng nhập chính xác địa chỉ email (ví dụ: abc@mail.com)',
                    email: 'Vui lòng nhập chính xác địa chỉ email (ví dụ: abc@mail.com)'
                },
                
                company_name: {
                    required: 'Bạn chưa điền tên công ty'
                },
                
                company_address: {
                    required: 'Bạn chưa điền tên công ty'
                },
                
                company_city : {
                    required: 'Vui lòng chọn thành phó'
                },
                
                company_size : {
                    required: 'Vui lòng chọn số nhân viên'
                },
                    
                mobile: {
                    required: 'Vui lòng nhập chính xác số điện thoại',
                    minlength: 'Số điện thoại phải có tối thiểu 6 ký tự',
                    number: 'Vui lòng nhập số điện thoại chính xác'
                },
                
                gender:{
                    required: 'Vui lòng lựa chọn giới tính'
                },
                
                code: {
                    required: 'Vui lòng nhập mã bảo mật captcha trước khi tiếp tục'        
                },
                
                terms:{
                    required: 'Bạn phải đồng ý với các điều khoản của vieclambanthoigian.com.vn mới có thể tiếp tục'
                }
            },					
                
            // Do not change code below
            errorPlacement: function(error, element)
            {
                error.insertAfter(element.parent());
            }
        });
    });			
</script>