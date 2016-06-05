<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries,$FULL_DOMAIN_NAME, $locations, $gender, $employerInfo, $commonQueries_Employers,$subscriptions;
    $company_sizes = $db->get('employers_company_size');
        
    $current_subscription_request = $commonQueries_Employers->Employer_Subscriptions_request($AuthUserName);
    $current_subscription = $commonQueries_Employers->getCurrent_Subscriptions($AuthUserName);

    
if (isset($_POST['credit_selection_submit'])){
    require_once ('include/credit_selection_handling.php'); // handling credit form submission
}
//If credit id not exists, redirect back to credits area with message
    
//Show register form
    
?>
<div class="row">
    <section class="col-md-10">
        <h4><?php $commonQueries->flash('message');?></h4>
        
        <?php if($current_subscription['current_subscription'] > 1):?>
        <section class="note"><i>Bạn đang dùng gói 
                <strong><?php echo $current_subscription['name'];?></strong>
                từ ngày <?php echo date('d-m-Y', $employerInfo['subscription_date']);?>, hết hạn vào <?php echo date('d-m-Y',$employerInfo['subscription_date_end']);?>. Sau khi hết hạn, tài khoản của bạn sẽ tự động trở về gói miễn phí</i>
        </section>
        <?php else:?>
        <section class="note">
            <i>Bạn đang dùng gói không giới hạn về thời gian sử dụng, để đăng ký gói tuyển dụng khác, vui lòng xem chi tiết tại <a href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/dang-ky/">đây</a></i>
        </section>
        <?php endif;?>
    </section>
        
    <section class="col-md-2 navigation-tabs">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/", 'Về trang chính', 'blue');?>
    </section>
</div>

<?php if($current_subscription_request['totalCount'] !== "0") :?>

<!--CURRENT SUBSCRIPTION REQUEST TABLE-->
<div class="table-responsive">
    <h4>Chi tiết yêu cầu của bạn</h4>
    <table class="table" style="border-color:#eeeeee;border-width:1px 1px 1px 1px;border-style:solid">
        <tbody>
            <tr class="table-tr header-title">
                <td class="col-md-2">                                
                    <a class="header-td underline-link" href="#">
                        Ngày yêu cầu
                    </a>
                </td>
                <td class="col-md-2">                                
                    <a class="header-td underline-link" href="#">
                        Gói đăng ký hiện tại
                    </a>
                </td>
                                
                <td class="col-md-2">                                
                    <a class="header-td underline-link" href="#">
                        Gói đăng ký yêu cầu
                    </a>
                </td>
                                
                <td class="col-md-5">
                    <a class="header-td underline-link" href="#">
                        Lời nhắn
                    </a>
                </td>
                <td class="col-md-1">
                    <a class="header-td underline-link" href="#">
                        Trạng thái
                    </a>
                </td>                
            </tr>
                            
            <tr bgcolor="#ffffff" class="header-title" height="30">
                <td width="20"><?php echo date('d-m-Y',$current_subscription_request['data']['date']);?></td>                
                <td><?php echo $current_subscription_request['data']['subscription_current']?></td>
                <td><?php echo $current_subscription_request['data']['request_subscription']?></td>                            
                <td><?php echo $current_subscription_request['data']['employer_message']?></td>
                <td><?php echo $current_subscription_request['data']['status_name']?></td>
            </tr>
        </tbody>
    </table>
</div>
<!--END OF CURRENT SUBSCRIPTION REQUEST-->
<?php endif;?>
    
<!--SUBSCRIPTION FORM-->
<div class="request_form">
    <?php require_once ('templates/credit_selection_request_form.php');?>
</div>
<!--SUBSCRIPTION FORM-->


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