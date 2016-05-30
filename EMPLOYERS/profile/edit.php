<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
//Get company info
$company_info = $db->where('username', "$AuthUserName")->getOne('employers');
    
if (isset($_POST['submit'])){
    //Perform update    
    $data = Array (
        'company'               => filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING),  
        'company_description'   => filter_input(INPUT_POST, 'company_description', FILTER_SANITIZE_STRING),        
        'contact_person'        => filter_input(INPUT_POST, 'contact_person', FILTER_SANITIZE_STRING),
        'address'               => filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING),
        'phone'                 => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT),
        'fax'                   => filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_NUMBER_INT),
        'website'               => filter_input(INPUT_POST, 'website', FILTER_SANITIZE_STRING),
        'latitude'              => filter_input(INPUT_POST, 'job_map_latitude', FILTER_SANITIZE_STRING),
        'longitude'             => filter_input(INPUT_POST, 'job_map_longitude', FILTER_SANITIZE_STRING),
    );
    $db->where('username', "$AuthUserName");
    if ($db->update ('employers', $data)){
        $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Lưu thay đổi thành công!'));
        $website->redirect('index.php?category=profile&action=edit');
    } else{
        echo 'update failed: ' . $db->getLastError();die;
    }
}
    
?>
    
<div class="row">
    <div class="col-md-9">
        <h5><?php $commonQueries->flash('message')?></h5>
    </div>
    <div class="col-md-3">        
        <?php echo LinkTile("profile","logo",$M_LOGO,"","yellow");?>              
    </div>   
</div>

<form action="" method="POST" class="sky-form newJob"> 
    <header>Chỉnh sửa thông tin công ty</header>
    <fieldset>
        <!--LOGIN NAME-->
        <section class="col col-4">
            <label class="label">Tên đăng nhập: </label>
            <span><?php echo $company_info['username']?></span>
        </section>
        
        <!--LOGO-->
        <section class="col col-8 logoEdit">
            <label class="label">Logo công ty: </label>
            <span class="logo">
                <img src="http://localhost/vieclambanthoigian.com.vn/images/employers/logo/12.jpg" width="250" height="125">
            </span>
        </section>
            
        <!--COMPANY NAME-->
        <section class="col col-4">
            <label class="label">Tên công ty: </label>
            <label class="input"><input type="text" name="company" value="<?php echo $company_info['company']?>"></label>
        </section>
            
        <!--COMPANY DESCRIPTION-->
        <section class="col col-12">
            <label class="label">
                Thông tin công ty:
            </label>
            <label class="textarea">
                <textarea type="text" name="company_description"><?php echo $company_info['company_description']?></textarea>
            </label>
            <div class="note"><strong>Note:</strong> Hãy mô tả chi tiết nhưng đầu mục công việc để ứng viên có thể hiểu rõ hơn về yêu cầu của công ty bạn với vị trí này.</div>
        </section>
            
        <!--CONTACT PERSON-->
        <section class="col col-4">
            <label class="label">Tên người liên hệ: </label>
            <label class="input"><input type="text" name="contact_person" value="<?php echo $company_info['contact_person']?>"></label>
        </section>
            
        <!--ADDRESS-->
        <section class="col col-4">
            <label class="label">Địa chỉ: </label>
            <label class="input"><input type="text" name="address" value="<?php echo $company_info['address']?>"></label>
        </section>
            
        <!--CONTACT PERSON-->
        <section class="col col-4">
            <label class="label">Website: </label>
            <label class="input"><input type="text" name="website" value="<?php echo $company_info['website']?>"></label>
        </section>
            
        <!--PHONE NUMBER-->
        <section class="col col-4">
            <label class="label">Điện thoại liên hệ: </label>
            <label class="input"><input type="text" name="phone" value="<?php echo $company_info['phone']?>"></label>
        </section>        
            
        <!--FAX NUMBER-->
        <section class="col col-4">
            <label class="label">Fax: </label>
            <label class="input"><input type="text" name="fax" value="<?php echo $company_info['fax']?>"></label>
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
    
    <footer>
            <button type="submit" name="submit" class="button">Lưu</button>
            <button type="button" class="button button-secondary" onclick="window.history.back();">Quay lại trang trước</button>
    </footer>
    
    
</form>