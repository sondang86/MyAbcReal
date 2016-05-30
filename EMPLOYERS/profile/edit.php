<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $employer_data;
//Get company info
$company_info = $db->where('username', "$AuthUserName")->getOne('employers');
//Edit form handle
require_once ('include/edit_handling.php');
    
?>

<div class="row">
    <div class="col-md-10">
        <h5><?php $commonQueries->flash('message')?></h5>
    </div>
    <div class="col-md-2">        
        <?php echo LinkTile("home","welcome",$M_DASHBOARD,"","blue");?>              
    </div>   
</div>
    
<form action="" method="POST" enctype="multipart/form-data" class="sky-form newJob" id="edit-form"> 
    <header>Chỉnh sửa thông tin công ty</header>
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
                <input id="uploadFile" placeholder="Tên tập tin" disabled="disabled">
                <p class="btn btn-info btn-file">
                    Thay đổi logo <input id="logo" type="file" name="file" class="upload" aria-required="true">
                </p>
            </span>
        </section>
        
        <!--COMPANY NAME-->
        <section class="col col-4">
            <label class="label">Tên công ty(*): </label>
            <label class="input"><input type="text" name="company" value="<?php echo $company_info['company']?>" required></label>
        </section>
        
        <!--COMPANY DESCRIPTION-->
        <section class="col col-12">
            <label class="label">
                Thông tin công ty(*):
            </label>
            <label class="textarea">
                <textarea type="text" name="company_description" required><?php echo $company_info['company_description']?></textarea>
            </label>
            <div class="note"><strong>Note:</strong> Hãy mô tả ngắn gọn về công ty bạn giúp ứng viên có thể hiểu rõ hơn về văn hóa công ty.</div>
        </section>
        
        <!--CONTACT PERSON-->
        <section class="col col-4">
            <label class="label">Tên người liên hệ(*): </label>
            <label class="input"><input type="text" name="contact_person" value="<?php echo $company_info['contact_person']?>" required></label>
        </section>
        
        <!--ADDRESS-->
        <section class="col col-4">
            <label class="label">Địa chỉ(*): </label>
            <label class="input"><input type="text" name="address" value="<?php echo $company_info['address']?>" required></label>
        </section>
        
        <!--PHONE NUMBER-->
        <section class="col col-4">
            <label class="label">Điện thoại liên hệ(*): </label>
            <label class="input"><input type="text" name="phone" value="<?php echo $company_info['phone']?>" required></label>
        </section>        
                
        <!--WEBSITE-->
        <section class="col col-4">
            <label class="label">Website: </label>
            <label class="input"><input type="text" name="website" value="<?php echo $company_info['website']?>"></label>
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
    
<style>
    .btn-file {
        margin-right: 0px;
        margin-top: 10px;
        display: block;
    }
</style>
<script>
    //Upload file
    $(function() {
        //Append selected upload file name to disabled input box
        //http://www.tutorialrepublic.com/faq/how-to-get-selected-file-name-from-input-type-file-using-jquery.php
        $("input:file[id=logo]").change (function(e) {
            
            $("#uploadFile").val(e.target.files[0].name); // remove fakepath, output file name only
            
            $("span.uploadBtn").text("upload");
            
        });
        
    });   
    
    /*Preview image before upload*/
        $("#logo").change(function() {
            previewImage(this);
        });
    /*Preview image before upload*/
    
    
    //Validate
    $(function(){
        // Validation
        $("#edit-form").validate(
        {					
            // Rules for form validation
            rules:
            {
                file:{
                    accept: "image/*"
                },
                
                company:{
                    required: true
                },
                
                company_description:{
                    required: true
                },
                
                contact_person:{
                    required: true
                },
                
                address:{
                    required: true
                },
                
                phone:{
                    required: true
                }
            },
            
            // Messages for form validation
            messages:
            {
                file:{
                    accept: 'Chỉ chấp nhận định dạng ảnh (jpeg, jpg, png ...)'
                },
                
                company:{
                    required: 'Mục này không thể để trống !'
                },
                
                company_description:{
                    required: 'Mục này không thể để trống !'
                },
                
                contact_person:{
                    required: 'Mục này không thể để trống !'
                },
                
                address:{
                    required: 'Mục này không thể để trống !'
                },
                
                phone:{
                    required: 'Mục này không thể để trống !'
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