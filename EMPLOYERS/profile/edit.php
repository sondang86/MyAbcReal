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
        'company' => $_POST['company'],
        'company_description' => $_POST['company_description'],        
        'contact_person' => $_POST['contact_person'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
        'fax' => $_POST['fax'],
        'website' => $_POST['website'],
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
    <div class="col-md-3 col-md-push-9">        
<?php
    echo LinkTile("profile","logo",$M_LOGO,"","yellow");
    echo LinkTile("profile","video",$M_VIDEO_PRESENTATION,"","lila");        
?>              
    </div>        
    <div class="col-md-9 col-md-pull-3">
        <h4><?php $commonQueries->flash('message')?></h4>
        <h4><?php echo $VIEW_PROFILE;?></h4>
        <form action="" method="POST">
            <table class="editForm">
                <tbody>
                    <tr height="38">
                        <td width="120"><label>Tên đăng nhập:</label></td>
                        <td><p><?php echo $company_info['username']?></p></td>
                    </tr>
                    <tr height="38">
                        <td width="120"><label>Công ty:</label></td>
                        <td><input type="text" name="company" value="<?php echo $company_info['company']?>"></td>
                    </tr>
                    <tr height="38">
                        <td width="120" valign="middle"><label>Thông tin công ty:</label></td>
                        <td  style="width: 80%">
                            <textarea type="text" name="company_description"><?php echo $company_info['company_description']?></textarea>
                        </td>
                    </tr>
                    <tr height="38">
                        <td width="120"><label>Người liên hệ:</label></td>
                        <td><input type="text" name="contact_person" value="<?php echo $company_info['contact_person']?>"></td>
                    </tr>
                    <tr height="38">
                        <td width="120"><label>Địa chỉ:</label></td>
                        <td><input type="text" name="address" value="<?php echo $company_info['address']?>"></td>
                    </tr>
                    <tr height="38">
                        <td width="120" valign="middle"><label>Điện thoại:</label></td>
                        <td><input type="text" name="phone" value="<?php echo $company_info['phone']?>"></td>
                    </tr>
                    <tr height="38">
                        <td width="120"><label>Fax:</label></td>
                        <td><input type="text" name="fax" value="<?php echo $company_info['fax']?>"></td>
                    </tr>
                    <tr height="38">
                        <td width="120"><label>Trang web:</label></td>
                        <td><input type="text" name="website" value="<?php echo $company_info['website']?>"></td>
                    </tr>
                    <tr height="38">
                        <td width="120"></td>
                        <td><input type="submit" name="submit" value="Lưu"></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>    