<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $FULL_DOMAIN_NAME, $commonQueries;
?>

<div class="row">
    <section class="col-md-6 col-sm-3 col-xs-12"></section>
    
    <section class="col-md-2 col-sm-3 col-xs-4">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/danh-sach-don-xin-viec/", 'Danh sách các đơn xin việc', 'blue');?>
    </section>
    <section class="col-md-2 col-sm-3 col-xs-4">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/don-da-phe-duyet/", 'Đơn đã phê duyệt', 'green');?>
    </section>    
    <section class="col-md-2 col-sm-3 col-xs-4">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/don-da-tu-choi/", 'Đơn đã từ chối', 'red');?>
    </section>
</div>

<h3><?php echo $M_JOBSEEKERS_APPROVED;?></h3>


<?php        
    $QueryListCVs_Approved ="
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
            ".$DBprefix."apply.status = '1')
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
            ".$DBprefix."apply.status = '1')
	";

    $CVs_approved = $db->withTotalCount()->rawQuery($QueryListCVs_Approved); 
    
    //Delete selected value in tables
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
                    
                    //final delete apply Id 
                    $database->SQLDelete("apply","id",$_REQUEST["CheckList"]);
                    $website->redirect("index.php?category=application_management&action=approved");                    

            }
    }

    if($db->totalCount==0){
        echo "<div><i>".$M_NO_APPROVED_CANDIDATED."</i></div>";
    } else {
?>
        
<form action="index.php?category=application_management&action=approved" method="POST">
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
                        <a href="index.php?category=application_management&amp;folder=list&amp;page=reply2&amp;Proceed=reject&amp;id=<?php echo $CV_approved['id2']?>&amp;posting_id=<?php echo $CV_approved['posting_id']?>" style="color:red;text-decoration:underline"><b>Từ chối</b></a>
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
<?php }?>
