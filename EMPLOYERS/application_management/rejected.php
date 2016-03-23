<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db;
?>

<div class="fright">
    
	<?php
	
	echo LinkTile
		 (
			"application_management",
			"approved",
			$M_APPROVED_APPLICATIONS,
			"",
			"green"
		 );
		 
		 
			echo LinkTile
		 (
			"application_management",
			"list",
			$JOBSEEKERS_APPLIED,
			"",
			"blue"
		 );
	
	
	?>
    
</div>
<div class="clear"></div>
<h3>
	<?php echo $M_REJECTED_JOBSEEKERS;?>
</h3>
<br/>
<?php
	
	$QueryListCVs_Rejected ="
                    (SELECT ".
			 $DBprefix."jobseekers.first_name,"
			.$DBprefix."jobseekers.last_name,"
			.$DBprefix."jobseekers.id,"
			.$DBprefix."apply.id id2,"
			.$DBprefix."apply.posting_id,"
			.$DBprefix."apply.jobseeker,"
			.$DBprefix."apply.date
			FROM
			".$DBprefix."apply,".$DBprefix."jobseekers,".$DBprefix."jobs
			WHERE 
			".$DBprefix."jobseekers.username=".$DBprefix."apply.jobseeker 
			AND
			".$DBprefix."jobs.id=".$DBprefix."apply.posting_id 
			AND
			".$DBprefix."jobs.employer='".$AuthUserName."'
			AND
			".$DBprefix."apply.status = '2')
			UNION 
			(SELECT ".
			 $DBprefix."jobseekers_guests.first_name,"
			.$DBprefix."jobseekers_guests.last_name,"
			.$DBprefix."jobseekers_guests.id,"
			.$DBprefix."apply.id id2,"
			.$DBprefix."apply.posting_id,"
			.$DBprefix."apply.jobseeker,"
			.$DBprefix."apply.date
			FROM
			".$DBprefix."apply,".$DBprefix."jobseekers_guests,".$DBprefix."jobs
			WHERE 
			".$DBprefix."jobseekers_guests.id=".$DBprefix."apply.guest_id 
			AND
			".$DBprefix."jobs.id=".$DBprefix."apply.posting_id 
			AND
			".$DBprefix."jobs.employer='".$AuthUserName."'
			AND 
			guest=1
			AND
			".$DBprefix."apply.status = '2')
	";
    $CVs_approved = $db->withTotalCount()->rawQuery($QueryListCVs_Rejected);
//    print_r($CVs_approved);
    
    //Delete selected value
    if(isset($_REQUEST["Delete"])&&isset($_REQUEST["CheckList"]))
    { 
            if(sizeof($_REQUEST["CheckList"])>0)
            {      
                    $website->ms_ia($_REQUEST["CheckList"]);
                
                    //Check if records exist in apply_documents table first
                    $db->withTotalCount()
                       ->where('job_id', $CVs_approved[0]['posting_id'])->where('jobseeker', $CVs_approved[0]['jobseeker'])
                       ->get('apply_documents');
                    

                    
                    if ($db->totalCount > 0){

                    //Delete records in apply_documents if exist
                    $db->where('job_id', $CVs_approved[0]['posting_id'])->where('jobseeker', $CVs_approved[0]['jobseeker']);
                    if(!$db->delete('apply_documents')) {
                            echo "failed to delete apply documents";die;
                        }
                    }
                    
                    //Check if records exist in questionnaire_answers table first
                    $db->withTotalCount()
                       ->where('job_id', $CVs_approved[0]['posting_id'])->where('user', $CVs_approved[0]['jobseeker'])
                       ->get('questionnaire_answers');
                    
                    //Delete questionnaire answers if exist
                    if ($db->totalCount > 0){
                    $db->where('job_id', $CVs_approved[0]['posting_id'])->where('user', $CVs_approved[0]['jobseeker']);
                        if(!$db->delete('questionnaire_answers')) { 
                                echo "failed to delete questionnaries";die;
                            }
                    }
                    
                    //Final delete apply Id 
                    $database->SQLDelete("apply","id",$_REQUEST["CheckList"]);
                    $website->redirect("index.php?category=application_management&action=rejected");                    
                    
            }
    }

 
    if($db->totalCount==0){
        echo "<div><i>".$M_NO_APPROVED_CANDIDATED."</i></div>";
    } else { ?>


<form action="index.php?category=application_management&action=rejected" method="POST">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <td><input type="checkbox" class="th-check" title="Xóa toàn bộ" onclick="CheckAll(this)"></td>
                    <td>Ngày đăng</td>
                    <td>Tên</td>
                    <td>Họ</td>
                    <td></td>
                    <td>Chi tiết</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($CVs_approved as $CV_approved) :?>
                <tr>
                    <td><input type="checkbox" name="CheckList[]" value="<?php echo $CV_approved['id2']?>"></td>
                    <td><?php echo date('d/m/Y H:i', $CV_approved['date'])?></td>
                    <td><?php echo $CV_approved['first_name']?></td>
                    <td><?php echo $CV_approved['last_name']?></td>
                    <td style="text-align: center;">
                        <a href="index.php?category=application_management&amp;folder=list&amp;page=reply&amp;Proceed=approve&amp;id=<?php echo $CV_approved['id2']?>&amp;posting_id=<?php echo $CV_approved['posting_id']?>" style="color:green;text-decoration:underline"><b>Phê duyệt</b></a>
                    </td>
                    <td><a href="index.php?category=application_management&amp;folder=my&amp;page=details&amp;posting_id=<?php echo $CV_approved['posting_id']?>&amp;apply_id=<?php echo $CV_approved['id2']?>"><img src="../images/job-details.png" border="0"></a></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        
        <div class="fleft">
            <input type="hidden" name="Delete" value="1">
            <input type="submit" value=" Xóa bỏ " class="btn btn-default btn-gradient">
        </div>
    </div>
</form>

<?php }	?>
