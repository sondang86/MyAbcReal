<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $current_language, $gender, $jobseeker_profile;

print_r($jobseeker_profile['date_of_birth']);
?>
<div class="row">
    <div class="fright">
    <?php
        echo LinkTile ("profile","edit",$EDIT_YOUR_PROFILE,"","green");	 
        echo LinkTile("cv","resume_creator",$M_RESUME_CREATOR,"","green");
    ?>
    </div>
    <div class="clear"></div>
    <h3>
            <?php echo $VIEW_CURRENT_PROFILE;?>
    </h3>
    <br/>
</div>

<div class="row">
    <div class="col-md-12 js-editForm">
        <section>
            <span>Ảnh cá nhân: </span>
            
            <?php if($jobseeker_profile['profile_pic'] == NULL){?>
            <section class="profilePic">
                <div><img src="http://<?php echo $DOMAIN_NAME?>/images/jobseekers/profile_pic/avatar_nam.jpg" id="preview" alt="Ảnh cá nhân hiện tại" width="150px" height="200px"></div>
            </section>
            <?php } else {?>
            <div class="profilePic">
                <div><img src="../images/jobseekers/profile_pic/<?php echo $jobseeker_profile['profile_pic'];?>" id="preview" alt="Ảnh cá nhân hiện tại" width="150px" height="200px"></div>
            </div>                
            <?php }?>
        </section>
        
        <section>
            <span>Email:</span>
            <p><?php echo $jobseeker_profile['username']?></p>
        </section>
        
        <section>
            <span>Tên:</span>
            <p><?php echo $jobseeker_profile['first_name']?></p>
        </section>
        
        <section>
            <span>Họ:</span>
            <p><?php echo $jobseeker_profile['last_name']?></p>
        </section>
        
        <section>
            <span>Địa chỉ:</span>
            <p><?php $commonQueries->check_nA($jobseeker_profile['address'], $jobseeker_profile['address']);?></p>
        </section> 
        
        <section>
            <span>Điện thoại:</span>
            <p><?php $commonQueries->check_nA($jobseeker_profile['phone'], $jobseeker_profile['phone']);?></p>
        </section>
        
        <section>
            <span>Ngày sinh:</span>
            <p>
                <?php 
                    if(!empty($jobseeker_profile['date_of_birth'])){
                        $commonQueries->check_nA($jobseeker_profile['date_of_birth'], date('d-m-Y',$jobseeker_profile['date_of_birth']));
                    }
                ?>
            </p>
        </section>
        
        <section>
            <span>Giới tính:</span>
            <p><?php $commonQueries->check_nA($jobseeker_profile['gender_name'], $jobseeker_profile['gender_name']);?></p>
        </section> 
        
        <section>
            <span>Hiển thị hồ sơ:</span>
            <p><?php echo $commonQueries->YesOrNo($jobseeker_profile['profile_public']);?></p>
        </section> 
        
        <section>
            <span>Nhận tin tức: </span>
            <p><?php echo $commonQueries->YesOrNo($jobseeker_profile['newsletter']);?></p>
        </section>            
    </div>
</div>
