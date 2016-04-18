<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db;
//Get company info
$company_info = $db->where('username', "$AuthUserName")->getOne('employers');

//echo "<pre>";
//print_r($company_info);
//echo "</pre>";
?>
<div class="row">
    <div class="col-md-3 col-md-push-9">        
<?php
    echo LinkTile("profile","edit",$M_EDIT,"","green");
    echo LinkTile("profile","logo",$M_LOGO,"","yellow");
    echo LinkTile("profile","video",$M_VIDEO_PRESENTATION,"","lila");        
?>              
    </div>        
    <div class="col-md-9 col-md-pull-3">
        <h4><?php echo $VIEW_PROFILE;?></h4>  
        <table>
            <tbody>
                <tr height="38">
                    <td width="120" valign="middle">Tên đăng nhập:</td>
                    <td valign="middle"><b><?php echo $company_info['username']?></b></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle">Công ty:</td>
                    <td valign="middle"><b><?php echo $company_info['company']?></b></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle">Thông tin công ty:</td>
                    <td valign="middle">
                        <p><?php echo $company_info['company_description']?></p>
                    </td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle">Người liên hệ:</td>
                    <td valign="middle"><b><?php echo $company_info['contact_person']?></b></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle">Địa chỉ:</td>
                    <td valign="middle"><p><?php echo $company_info['address']?></p></td>
                </tr><tr height="38">
                    <td width="120" valign="middle">Điện thoại:</td>
                    <td valign="middle"><b><?php echo $company_info['phone']?></b></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle">Fax:</td>
                    <td valign="middle"><p><?php echo $company_info['fax']?></p></td>
                </tr>
                <tr height="38">
                    <td width="120" valign="middle">Trang web:</td>
                    <td valign="middle"><p><?php echo $company_info['website']?></p></td>
                </tr>
            </tbody>
        </table>  
    </div>
</div>    