<?php
// Jobs Portal 
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
//Get company info
$company_info = $db->where('username', "$AuthUserName")->getOne('employers');
?>
<div class="row">
    <div class="col-md-3 col-md-push-9">        
<?php
    echo LinkTile("profile","edit",$M_EDIT,"","green");
    echo LinkTile("profile","logo",$M_LOGO,"","yellow");
//    echo LinkTile("profile","video",$M_VIDEO_PRESENTATION,"","lila");        
?>              
    </div>        
    <div class="col-md-9 col-md-pull-3">
        <h4><?php echo $VIEW_PROFILE;?></h4>
        <table>
            <tbody>
                <tr height="38">
                    <td width="120" valign="middle"><label>Tên đăng nhập:</label></td>
                    <td valign="middle"><p><?php echo $company_info['username']?></p></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle"><label>Công ty:</label></td>
                    <td valign="middle"><p><?php echo $company_info['company']?></p></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle"><label>Thông tin công ty:</label></td>
                    <td valign="middle">
                        <p><?php echo $company_info['company_description']?></p>
                    </td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle"><label>Người liên hệ:</label></td>
                    <td valign="middle"><p><?php echo $company_info['contact_person']?></p></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle"><label>Địa chỉ:</label></td>
                    <td valign="middle"><p><?php echo $company_info['address']?></p></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle"><label>Điện thoại:</label></td>
                    <td valign="middle"><p><?php echo $company_info['phone']?></p></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle"><label>Fax:</label></td>
                    <td valign="middle"><p><?php echo $company_info['fax']?></p></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle"><label>Trang web:</label></td>
                    <td valign="middle"><p><?php echo $company_info['website']?></p></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle"><label>Video giới thiệu:</label></td>
                    <td valign="middle"><p><?php echo $company_info['video_id']?></p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>    