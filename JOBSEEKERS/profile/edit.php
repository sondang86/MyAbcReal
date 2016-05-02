<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?>
<?php
    if(!defined('IN_SCRIPT')) die("");
    global $db, $commonQueries;    
    $jobseeker_profile = $db->get_data('jobseekers', '', "WHERE username='$AuthUserName'");
?>
<style>
    .navigation-tabs a{
        float: right;
    }    
</style>
<div class="row">
    <section class="col-md-6">
        <h3><?php $commonQueries->flash('message');?></h3>
    </section>
    <section class="col-md-6 navigation-tabs">
    <?php //Navigations
        echo LinkTile ("profile","current",$VIEW_PROFILE ,"","green");
        echo LinkTile("cv","resume_creator",$M_RESUME_CREATOR,"","green");
    ?>
    </section>
</div>        
        
<h3><?php echo $EDIT_YOUR_PROFILE;?></h3>
<div class="row">
<?php
    if(isset($_POST['update_form'])){
//        print_r(filter_input(INPUT_POST,'phone', FILTER_SANITIZE_NUMBER_INT));die;        
//        print_r(strtotime(filter_input(INPUT_POST,'dob', FILTER_SANITIZE_STRING)));die;
    
        if ($_FILES["logo"]["error"] !== 4){
            //image upload not empty, process the uploaded file
            $file_upload = new upload($_FILES['logo']);
                
            if ($file_upload->uploaded) {
                $file_upload->file_new_name_body   = $jobseeker_profile[0]['id'];
                $file_upload->image_resize         = true;
                $file_upload->image_x              = 150;
                $file_upload->image_y              = 200;
//              $file_upload->image_ratio_y        = true;
                $file_upload->file_overwrite       = true;
                $file_upload->process('../images/jobseekers/profile_pic/');
                    
                //Process insert image infos to database
                if ($file_upload->processed) {
                  echo 'image resized';
                  $profile_pic = $jobseeker_profile[0]['id']. "." .$file_upload->file_src_name_ext;
                  $file_upload->clean();
                } else {
                  echo 'error : ' . $file_upload->error;die;
                }
              }
        } else { //Use old image from db
            $profile_pic = $jobseeker_profile[0]['profile_pic'];
        }        
            
        //Sanitize input data        
        $data = Array( 
            "first_name"        => $db->cleanData(filter_input(INPUT_POST, 'first_name')), 
            "last_name"         => $db->cleanData(filter_input(INPUT_POST,'last_name')),
            "address"           => $db->cleanData(filter_input(INPUT_POST,'address')),
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

<form action="index.php?category=profile&action=edit" id="edit-form" method="POST" enctype="multipart/form-data">
<?php foreach ($jobseeker_profile as $value) :?>
        <div class="col-md-12 js-editForm sky-form">
            <section>
                <span>Ảnh cá nhân:</span>
                
                <?php if($value['profile_pic'] == NULL){ //User has not been updated their profile pic?>
                <section class="profilePic">
                    <div><img src="http://<?php echo $DOMAIN_NAME?>/images/jobseekers/profile_pic/avatar_nam.jpg" id="preview" alt="Ảnh cá nhân hiện tại" width="150px" height="200px"></div>
                    <div class="upload">
                        <input type="file" name="logo" id="logo">
                    </div>
                </section>
                <?php } else {?>
                <div class="profilePic">
                    <div><img src="../images/jobseekers/profile_pic/<?php echo $jobseeker_profile[0]['profile_pic'];?>" id="preview" alt="Ảnh cá nhân hiện tại" width="150px" height="200px"></div>
                    <div class="upload">
                        <input type="file" name="logo" id="logo">
                    </div>
                </div>
                    
                <?php }?>
            </section>
            
            <section>
                <span>Email:</span>
                <input type="text" id="first_name" disabled value="<?php echo $value['username']?>">
            </section>
            
            <section>
                <span>Tên:</span>
                <input type="text" name="first_name" value="<?php echo $value['first_name']?>">
            </section>
            
            <section>
                <span>Họ:</span>
                <input type="text" name="last_name" value="<?php echo $value['last_name']?>">
            </section>
            
            <section>
                <span>Địa chỉ:</span>
                <textarea name="address"><?php echo $value['address']?></textarea>
            </section> 
            
            <section>
                <span>Điện thoại:</span>
                <input type="text" name="phone" value="<?php echo $value['phone']?>">
            </section>
            <section>
                <span>Ngày sinh:</span>
                <input type="text" name="dob" id="datePicker" value="<?php if (!empty($value['dob'])){
                        $commonQueries->check_nA($value['dob'], date('d-m-Y',$value['dob']));
                    }
                ?>">
            </section>
            
            <section>
                <span>Giới tính:</span>
                <select name="gender">
                    <option value="1" <?Php $commonQueries->Selected($value['gender'], 1);?>>Nam</option>
                    <option value="2" <?Php $commonQueries->Selected($value['gender'], 2);?>>Nữ</option>
                    <option value="3" <?Php $commonQueries->Selected($value['gender'], 3);?>>Khác</option>
                </select>
            </section> 
            
            <section>
                <span>Hiển thị hồ sơ:</span>
                <select name="profile_public">
                    <option value="1" <?Php $commonQueries->Selected($value['profile_public'], 1);?>>Có</option>
                    <option value="0" <?Php $commonQueries->Selected($value['profile_public'], 0);?>>Không</option>
                </select>
            </section>  
            
            <section>
                <span>Nhận tin tức: </span>
                <select name="newsletter">
                    <option value="1" <?Php $commonQueries->Selected($value['newsletter'], 1);?>>Có</option>
                    <option value="0" <?Php $commonQueries->Selected($value['newsletter'], 0);?>>Không</option>
                </select>
            </section>           
                
            <section style="text-align: right;width: 95%;">
                <input class="btn btn-primary" type="submit" name="update_form" value="Save">
            </section>
        </div>
<?php endforeach;?>	
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
                }                                
            },
            // Messages for form validation
            messages:{
                phone:{
                    required: '<span style="color:red">Vui lòng nhập số điện thoại</span>',
                    minlength: '<span style="color:red">Ít nhất 5 ký tự</span>'
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
  