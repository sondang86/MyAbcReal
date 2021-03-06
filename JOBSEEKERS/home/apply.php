<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db;


$selected_columns = array(
    $DBprefix."apply.id as apply_id",$DBprefix."apply.date as apply_date",
    $DBprefix."apply.posting_id as job_id",$DBprefix."apply.jobseeker",
    $DBprefix."apply.employer_reply",
    $DBprefix."apply.message as apply_message",$DBprefix."apply.status as apply_status",
    $DBprefix."apply_status.name as status_name",$DBprefix."apply_status.name_en as status_name_en",
    $DBprefix."jobs.title as job_title",$DBprefix."jobs.SEO_title as SEO_title",
);
$db->join('apply_status', $DBprefix."apply.status =".$DBprefix."apply_status.status_id", "LEFT");
$db->join('jobs',$DBprefix."apply.posting_id =".$DBprefix."jobs.id", "LEFT");
$apply_details = $db->where('jobseeker', "$AuthUserName")->withTotalCount()->get('apply', NULL, $selected_columns);

//echo "<pre>";
//print_r($apply_details);
//echo "</pre>";

?>
<div class="row main-nav">
    <section class="col-md-9"></section>
    <section class="col-md-3">
        <?php echo LinkTile("home","welcome",	$M_DASHBOARD,"","blue");?>
    </section>
</div>
    
<h3><?php echo $M_YOUR_JOB_APPLICATIONS_HISTORY;?></h3>

<?php if($db->totalCount == 0) { // Did not apply any jobs?>			

<i><?php echo $M_STILL_DIDNT_APPLY;?></i>
    
<?php } else { ?>

<!--List applied jobs-->
<div class="table-responsive" >          
    <table class="table">
        <thead>
            <tr>
                <th width="100px">Ngày</th>
                <th>Trạng thái</th>
                <th>Người tuyển dụng phản hồi</th>
                <th>Tiêu đề</th>
                <th style="text-align: center">Chi tiết công việc</th>
            </tr>
        </thead>
        <tbody>                                
            <?php foreach ($apply_details as $apply_detail) :?>                                
            <tr id="apply_id_<?php echo $apply_detail['job_id']?>">                            
                <td class="col-md-1"><?php echo date('Y-m-d', $apply_detail['apply_date'])?></td>
                <td class="col-md-2"><?php echo $apply_detail['status_name'];?></td>
                <td class="col-md-3"><?php echo $apply_detail['employer_reply'];?></td>
                <td class="col-md-5"><?php echo $apply_detail['job_title'];?></td>
                <td class="col-md-1" style="text-align: center">
                    <a href="/vieclambanthoigian.com.vn/chi-tiet-cong-viec/<?php echo $apply_detail['job_id']?>/<?php echo $apply_detail['SEO_title']?>" title="<?php echo $apply_detail['job_title']?>" target="_blank">
                        <img src="/vieclambanthoigian.com.vn/images/job-details.png">
                    </a>
                </td>            
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div> 
<?php } ?>