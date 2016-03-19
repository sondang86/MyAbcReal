<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $current_language, $gender, $jobseeker_profile;
?>
<div class="row">
    <div class="fright">
    <?php
        echo LinkTile ("profile","edit",$EDIT_YOUR_PROFILE,"","green");	 
        echo LinkTile ("cv","description",$EDIT_YOUR_CV,"","yellow");
    ?>
    </div>
    <div class="clear"></div>
    <h3>
            <?php echo $VIEW_CURRENT_PROFILE;?>
    </h3>
    <br/>
</div>

<?php foreach ($jobseeker_profile as $value) :?>
    <div class="row">
        <div class="col-md-12 js-editForm">
            <section>
                <span>Email:</span>
                <p><?php echo $value['username']?></p>
            </section>
            <section>
                <span>Tên:</span>
                <p><?php echo $value['first_name']?></p>
            </section>
            <section>
                <span>Họ:</span>
                <p><?php echo $value['last_name']?></p>
            </section>
            <section>
                <span>Địa chỉ:</span>
                <p><?php echo $value['address']?></p>
            </section>        
            <section>
                <span>Điện thoại:</span>
                <p><?php echo $value['phone']?></p>
            </section>
            <section>
                <span>Ngày sinh:</span>
                <p><?php echo date('Y-m-d',$value['dob'])?></p>
            </section>
            <section>
                <span>Giới tính:</span>
                <p><?php echo $gender['Text']?></p>
            </section>  
            <section>
                <span>Hiển thị hồ sơ:</span>
                <p><?php echo $commonQueries->YesOrNo($value['profile_public']);?></p>
            </section>  
            <section>
                <span>Nhận tin tức: </span>
                <p><?php echo $commonQueries->YesOrNo($value['newsletter']);?></p>
            </section>
            <section>
                <span>Ảnh cá nhân:</span>
                
                <?php if($value['profile_pic'] == NULL){?>
                <input type="file" name="logo" id="logo"> 
                <?php } else {?>
                <div class="profilePic">
                    <div><img src="../images/jobseekers/profile_pic/<?php echo $jobseeker_profile[0]['profile_pic'];?>" id="preview" alt="Ảnh cá nhân hiện tại" width="150px" height="200px"></div>
                </div>                
                <?php }?>
            </section>
        </div>
    </div>

<?php endforeach;?>	