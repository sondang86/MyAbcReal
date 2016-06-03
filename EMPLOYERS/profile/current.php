<?php
// Jobs Portal 
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $employerInfo, $FULL_DOMAIN_NAME;
//Get company info
$company_info = $db->where('username', "$AuthUserName")->getOne('employers');
?>
<div class="row">
    <div class="col-md-9"></div>
    <div class="col-md-3">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/chinh-sua-thong-tin-ca-nhan/", 'Chỉnh sửa', 'green');?>
    </div>
</div>
    
<form class="sky-form newJob" id="edit-form"> 
    <header>Thông tin công ty</header>
    <fieldset>
        <!--LOGIN NAME-->
        <section class="col col-4">
            <label class="label">Tên đăng nhập: </label>
            <span><?php echo $company_info['username']?></span>
        </section>
            
        <!--LOGO-->
        <section class="col col-8 logoEdit">
            <!--<label class="label">Logo công ty: </label>-->
            <span class="logo">
                <a href="index.php?category=profile&action=logo" title="Nhấn để thay đổi logo đại diện"><img src="http://localhost/vieclambanthoigian.com.vn/images/employers/logo/<?php echo $company_info['logo']?>" width="250" height="125" id="preview"></a>                
            </span>
        </section>
            
        <!--COMPANY NAME-->
        <section class="col col-4">
            <label class="label">Tên công ty(*): </label>
            <label class="input"><input type="text" value="<?php echo $company_info['company']?>" disabled class="disabled"></label>
        </section>
            
        <!--COMPANY DESCRIPTION-->
        <section class="col col-12">
            <label class="label">
                Thông tin công ty(*):
            </label>
            <label class="textarea">
                <textarea type="text" disabled class="disabled"><?php echo $company_info['company_description']?></textarea>
            </label>
            <div class="note"><strong>Note:</strong> Hãy mô tả ngắn gọn về công ty bạn giúp ứng viên có thể hiểu rõ hơn về văn hóa công ty.</div>
        </section>
            
        <!--CONTACT PERSON-->
        <section class="col col-4">
            <label class="label">Tên người liên hệ(*): </label>
            <label class="input"><input type="text" value="<?php echo $company_info['contact_person']?>" disabled class="disabled"></label>
        </section>
            
        <!--ADDRESS-->
        <section class="col col-4">
            <label class="label">Địa chỉ(*): </label>
            <label class="input"><input type="text" value="<?php echo $company_info['address']?>" disabled class="disabled"></label>
        </section>
            
        <!--PHONE NUMBER-->
        <section class="col col-4">
            <label class="label">Điện thoại liên hệ(*): </label>
            <label class="input"><input type="text" disabled class="disabled" value="<?php echo $company_info['phone']?>"></label>
        </section>        
            
        <!--WEBSITE-->
        <section class="col col-4">
            <label class="label">Website: </label>
            <label class="input"><input type="text" disabled class="disabled" value="<?php echo $company_info['website']?>"></label>
        </section>
            
        <!--FAX NUMBER-->
        <section class="col col-4">
            <label class="label">Fax: </label>
            <label class="input"><input type="text" value="<?php echo $company_info['fax']?>" disabled class="disabled"></label>
        </section>
    </fieldset>       
    
    <fieldset>
        <!--GOOGLE MAPS-->
        <section class="col col-12">
            <label class="label">Địa chỉ Google Maps: </label>
            <?php 
                //Check latitude/longitute values for Google Maps
                $latitude = $commonQueries->check_LatitudeLongitude($company_info['latitude'],$company_info['longitude'])['latitude'];
                $longitude = $commonQueries->check_LatitudeLongitude($company_info['latitude'],$company_info['longitude'])['longitude'];
                $note = "Chọn địa điểm làm việc để giúp ứng viên có thể tìm đường dễ dàng hơn.";
                require_once('../../vieclambanthoigian.com.vn/extensions/include/google_maps.php');
            ?>
        </section>            
    </fieldset>        
      
</form>