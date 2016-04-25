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
<div class="fright">

<?php //Navigations
    echo LinkTile ("profile","current",$VIEW_PROFILE ,"","green");
//    echo LinkTile ("cv","edit",$EDIT_YOUR_CV,"","blue");
    echo LinkTile("cv","resume_creator",$M_RESUME_CREATOR,"","green");
?>	
	
</div>

<h3><?php echo $EDIT_YOUR_PROFILE;?></h3>


<?php
    if(isset($_POST['update_form'])){

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

        //Sanitize
        
        $data = Array( 
            "first_name"        => $db->cleanData(filter_input(INPUT_POST, 'first_name')), 
            "last_name"         => $db->cleanData(filter_input(INPUT_POST,'last_name')),
            "address"           => $db->cleanData(filter_input(INPUT_POST,'address')),
            "phone"             => filter_input(INPUT_POST,'phone', FILTER_SANITIZE_NUMBER_INT),
            "dob"               => strtotime(filter_input(INPUT_POST,'dob')),
            "gender"            => filter_input(INPUT_POST,'gender'),
            "profile_public"    => filter_input(INPUT_POST,'profile_public'),
            "newsletter"        => filter_input(INPUT_POST,'newsletter'),
            "profile_pic"       => $profile_pic
         );
        
        //And update data
        $db->where('username', $AuthUserName);
        if ($db->update ('jobseekers', $data)){
            echo $db->count . ' records were updated';
            $website->redirect("index.php?category=profile&action=edit");
        } else {
            echo 'update failed: ' . $db->getLastError();die;
        }
    }
?>
<?php foreach ($jobseeker_profile as $value) :?>
<form action="index.php?category=profile&action=edit" id="editForm" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12 js-editForm">
            <section>
                <span>Email:</span>
                <input type="text" id="first_name" disabled value="<?php echo $value['username']?>">
            </section>
            <section>
                <span>Tên:</span>
                <input type="text" name="first_name" id="first_name" value="<?php echo $value['first_name']?>">
            </section>
            <section>
                <span>Họ:</span>
                <input type="text" name="last_name" id="last_name" value="<?php echo $value['last_name']?>">
            </section>
            <section>
                <span>Địa chỉ:</span>
                <textarea name="address" id="address"><?php echo $value['address']?></textarea>
            </section>        
            <section>
                <span>Điện thoại:</span>
                <input type="text" name="phone" id="phone" value="<?php echo $value['phone']?>">
            </section>
            <section>
                <span>Ngày sinh:</span>
                <input type="text" name="dob" id="datePicker" value="<?php echo date('Y-m-d',$value['dob'])?>">
            </section>
            <section>
                <span>Giới tính:</span>
                <select name="gender">
                    <option value="2" <?Php $commonQueries->Selected($value['gender'], 2);?>>Nam</option>
                    <option value="1" <?Php $commonQueries->Selected($value['gender'], 1);?>>Nữ</option>
                    <option value="0" <?Php $commonQueries->Selected($value['gender'], 0);?>>Khác</option>
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
            <section>
                <span>Ảnh cá nhân:</span>
                
                <?php if($value['profile_pic'] == NULL){?>
                <input type="file" name="logo" id="logo"> 
                <?php } else {?>
                <div class="profilePic">
                    <div><img src="../images/jobseekers/profile_pic/<?php echo $jobseeker_profile[0]['profile_pic'];?>" id="preview" alt="Ảnh cá nhân hiện tại" width="150px" height="200px"></div>
                    <div class="upload">
                        <input type="file" name="logo" id="logo">
                    </div>
                </div>
                
                <?php }?>
            </section>
            
            <section style="text-align: right;width: 95%;">
                <input class="btn btn-primary" type="submit" name="update_form" value="Save">
            </section>
        </div>
    </div>
</form>		
<?php endforeach;?>	

<i>(*) <?php echo $M_PUBLIC_PROFILE_EXPL;?></i>



