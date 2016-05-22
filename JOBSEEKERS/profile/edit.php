<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?>
<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries;    
    $jobseeker_profile = $db->where('username', "$AuthUserName")->getOne('jobseekers');
?>
<style>
    .navigation-tabs a{
        float: right;
    }    
</style>
<div class="row">
    <section class="col-md-6">
        <h4><?php $commonQueries->flash('message');?></h4>
        <h4><?php echo $EDIT_YOUR_PROFILE;?></h4>
    </section>
    <section class="col-md-6 navigation-tabs">
    <?php //Navigations
        echo LinkTile ("profile","current",$VIEW_PROFILE ,"","green");
        echo LinkTile("cv","resume_creator",$M_RESUME_CREATOR,"","green");
    ?>
    </section>
</div>        
    
<div class="row">
<?php
    if(isset($_POST['update_form'])){
        
        if ($_FILES["logo"]["error"] !== 4){
            //image upload not empty, process the uploaded file
            $file_upload = new upload($_FILES['logo']);
                
            if ($file_upload->uploaded) {
                $file_upload->file_new_name_body   = $jobseeker_profile['id'];
                $file_upload->image_resize         = true;
                $file_upload->image_x              = 150;
                $file_upload->image_y              = 200;
//              $file_upload->image_ratio_y        = true;
                $file_upload->file_overwrite       = true;
                $file_upload->process('../images/jobseekers/profile_pic/');
                    
                //Process insert image infos to database
                if ($file_upload->processed) {
                  echo 'image resized';
                  $profile_pic = $jobseeker_profile['id']. "." .$file_upload->file_src_name_ext;
                  $file_upload->clean();
                } else {
                  echo 'error : ' . $file_upload->error;die;
                }
              }
        } else { //Use old image from db
            $profile_pic = $jobseeker_profile['profile_pic'];
        }        
            
        //Sanitize input data        
        $data = Array( 
            "first_name"        => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING), 
            "last_name"         => filter_input(INPUT_POST,'last_name', FILTER_SANITIZE_STRING),
            "address"           => filter_input(INPUT_POST,'address', FILTER_SANITIZE_STRING),
            "phone"             => filter_input(INPUT_POST,'phone', FILTER_SANITIZE_NUMBER_INT),
            "dob"               => strtotime(filter_input(INPUT_POST,'dob', FILTER_SANITIZE_STRING)),
            "gender"            => filter_input(INPUT_POST,'gender'),
            "profile_public"    => filter_input(INPUT_POST,'profile_public'),
            "newsletter"        => filter_input(INPUT_POST,'newsletter'),
            "profile_pic"       => $profile_pic
         );
             
        //update
        $db->where('username', "$AuthUserName");
        if ($db->update ('jobseekers', $data)){
            echo $db->count . ' records were updated';
        } else {
            echo 'update failed: ' . $db->getLastError();die;
        }
            
        $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Lưu thay đổi thành công'));
        $website->redirect("index.php?category=profile&action=edit");
    }
?>
    <style>
        #edit-form {
            margin-top: 15px;
        }
    </style>
    <form action="index.php?category=profile&action=edit" id="edit-form" method="POST" enctype="multipart/form-data">
        <fieldset class="col-md-12 js-editForm sky-form">
            <section class="row">
                <span class="col col-1"></span>
                    
                <div class="col col-6">
                    <?php if($jobseeker_profile['profile_pic'] == NULL){ //User has not been updated their profile pic?>
                    <section class="profilePic">
                        <div><img src="http://<?php echo $DOMAIN_NAME?>/images/jobseekers/profile_pic/avatar_nam.jpg" id="preview" alt="Ảnh cá nhân hiện tại" width="150px" height="200px"></div>
                        <div class="upload">
                            <input type="file" name="logo" id="logo">
                        </div>
                    </section>
                    <?php } else {?>
                    <section class="profilePic">
                        <div><img src="../images/jobseekers/profile_pic/<?php echo $jobseeker_profile['profile_pic'];?>" id="preview" alt="Ảnh cá nhân hiện tại" width="150px" height="200px"></div>
                        <div class="upload">
                            <input type="file" name="logo" id="logo">
                        </div>
                    </section>
                        
                    <?php }?>
                </div>
            </section>
                
            <section class="row input">
                <span class="col col-1">Email:</span>
                <div class="col col-6">
                    <input type="text" id="first_name" disabled value="<?php echo $jobseeker_profile['username']?>">
                </div>
            </section class="row input">
                
            <section class="row input">
                <span class="col col-1">Tên:</span>
                <div class="col col-6">
                    <input type="text" name="first_name" value="<?php echo $jobseeker_profile['first_name']?>">
                </div>
            </section>
                
            <section class="row input">
                <span class="col col-1">Họ:</span>
                <div class="col col-6">
                    <input type="text" name="last_name" value="<?php echo $jobseeker_profile['last_name']?>">
                </div>
            </section>
                
            <section class="row textarea">
                <span class="col col-1">Địa chỉ:</span>
                <div class="col col-6">
                    <textarea name="address"><?php echo $jobseeker_profile['address']?></textarea>
                </div>
            </section> 
                
            <section class="row input">
                <span class="col col-1">Điện thoại:</span>
                <div class="col col-2">
                    <input type="text" name="phone" value="<?php echo $jobseeker_profile['phone']?>">
                </div>
            </section>
                
            <section class="row input">
                <span class="col col-1">Ngày sinh:</span>
                <div class="col col-2">
                    <input type="text" name="dob" id="datePicker" required value="<?php if (!empty($jobseeker_profile['dob'])){
                            echo date('d-m-Y',$jobseeker_profile['dob']);
                        }
                    ?>">
                </div>
            </section>
                
            <section class="row">
                <span class="col col-1">Giới tính:</span>
                <div class="col col-2">
                    <label class="select">
                        <select name="gender">
                            <option value="1" <?Php $commonQueries->Selected($jobseeker_profile['gender'], 1);?>>Nam</option>
                            <option value="2" <?Php $commonQueries->Selected($jobseeker_profile['gender'], 2);?>>Nữ</option>
                            <option value="3" <?Php $commonQueries->Selected($jobseeker_profile['gender'], 3);?>>Khác</option>
                        </select>
                        <i></i>
                    </label>
                </div>
            </section> 
                
            <section class="row">
                <span class="col col-1">Hiển thị hồ sơ:</span>
                <div class="col col-2">
                    <label class="select">
                        <select name="profile_public">
                            <option value="1" <?Php $commonQueries->Selected($jobseeker_profile['profile_public'], 1);?>>Có</option>
                            <option value="0" <?Php $commonQueries->Selected($jobseeker_profile['profile_public'], 0);?>>Không</option>
                        </select>
                        <i></i>
                    </label>
                </div>
            </section>  
                
            <section class="row">
                <span class="col col-1">Nhận tin tức: </span>
                <div class="col col-2">
                    <label class="select">
                        <select name="newsletter">
                            <option value="1" <?Php $commonQueries->Selected($jobseeker_profile['newsletter'], 1);?>>Có</option>
                            <option value="0" <?Php $commonQueries->Selected($jobseeker_profile['newsletter'], 0);?>>Không</option>
                        </select>
                        <i></i>
                    </label>
                </div>
            </section>           
            
            <section class="submit">
                <input class="btn btn-primary" type="submit" name="update_form" value="Save">
            </section>
        </fieldset>
    </form>		
</div>
    
<script>
    $(function(){
        // Validation
        $("#edit-form").validate({
            rules: {
                phone:{
                    required: true,
                    minlength: 2
                },
                dob:{
                    required: true
                }
            },
            // Messages for form validation
            messages:{
                phone:{
                    required: '<span style="color:red">Vui lòng nhập số điện thoại</span>',
                    minlength: '<span style="color:red">Số điện thoại ít nhất là 5 ký tự</span>'
                },
                dob:{
                    required: '<span style="color:red">Ngày sinh không được để trống</span>'
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

<i>(*) <?php echo $M_PUBLIC_PROFILE_EXPL;?></i>
    