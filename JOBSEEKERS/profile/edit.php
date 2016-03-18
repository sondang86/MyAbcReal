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

<?php
    echo LinkTile ("profile","current",$VIEW_PROFILE ,"","green");
    echo LinkTile ("cv","edit",$EDIT_YOUR_CV,"","blue");
?>	
	
</div>

<h3><?php echo $EDIT_YOUR_PROFILE;?></h3>


<?php
    if(isset($_POST['update_form'])){
//      If file upload not empty
        if ($_FILES["logo"]["error"] !== 4){
            $file_upload = new upload($_FILES['logo']);
            print_r($file_upload);
        }        
        
        //Sanitize & update data
        $data = Array( 
            "first_name" => $db->cleanData(filter_input(INPUT_POST, 'first_name')), 
            "last_name" => $db->cleanData(filter_input(INPUT_POST,'last_name')),
            "address" => $db->cleanData(filter_input(INPUT_POST,'address')),
            "phone" => filter_input(INPUT_POST,'phone', FILTER_SANITIZE_NUMBER_INT),
            "dob" => strtotime(filter_input(INPUT_POST,'dob')),
            "gender" => filter_input(INPUT_POST,'gender'),
            "profile_public" => filter_input(INPUT_POST,'profile_public'),
            "newsletter" => filter_input(INPUT_POST,'newsletter'),
         );
        
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
            <label>
                <span>Email:</span>
                <input type="text" id="first_name" disabled value="<?php echo $value['username']?>">
            </label>
            <label>
                <span>Tên:</span>
                <input type="text" name="first_name" id="first_name" value="<?php echo $value['first_name']?>">
            </label>
            <label>
                <span>Họ:</span>
                <input type="text" name="last_name" id="last_name" value="<?php echo $value['last_name']?>">
            </label>
            <label>
                <span>Địa chỉ:</span>
                <textarea name="address" id="address"><?php echo $value['address']?></textarea>
            </label>        
            <label>
                <span>Điện thoại:</span>
                <input type="text" name="phone" id="phone" value="<?php echo $value['phone']?>">
            </label>
            <label>
                <span>Ngày sinh:</span>
                <input type="text" name="dob" id="datePicker" value="<?php echo date('Y-m-d',$value['dob'])?>">
            </label>
            <label>
                <span>Giới tính:</span>
                <select name="gender">
                    <option value="2" <?Php $commonQueries->Selected($value['gender'], 2);?>>Nam</option>
                    <option value="1" <?Php $commonQueries->Selected($value['gender'], 1);?>>Nữ</option>
                    <option value="0" <?Php $commonQueries->Selected($value['gender'], 0);?>>Khác</option>
                </select>
            </label>  
            <label>
                <span>Hiển thị hồ sơ:</span>
                <select name="profile_public">
                    <option value="1" <?Php $commonQueries->Selected($value['profile_public'], 1);?>>Có</option>
                    <option value="0" <?Php $commonQueries->Selected($value['profile_public'], 0);?>>Không</option>
                </select>
            </label>      
            
            <label>
                <span>Ảnh cá nhân:</span>
                <input type="file" name="logo" id="logo"> 
            </label>
            
            
            <label>
                <span>Nhận tin tức: </span>
                <select name="newsletter">
                    <option value="1" <?Php $commonQueries->Selected($value['newsletter'], 1);?>>Có</option>
                    <option value="0" <?Php $commonQueries->Selected($value['newsletter'], 0);?>>Không</option>
                </select>
            </label>
            <label style="text-align: right;width: 95%;">
                <input class="btn btn-primary" type="submit" name="update_form" value="Save">
            </label>
        </div>
    </div>
</form>		
<?php endforeach;?>	

<i>(*) <?php echo $M_PUBLIC_PROFILE_EXPL;?></i>



