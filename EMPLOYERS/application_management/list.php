<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
?>
<div class="row">
    <section class="col-md-6 col-sm-3 col-xs-12">
        <h4><?php $commonQueries->flash('message');?></h4>
        <p></p>
        <h5><?php echo $CONSULT_LIST_APPLIED;?></h5>   
    </section>
    
    <section class="col-md-2 col-sm-3 col-xs-4">
        <?php 
            echo LinkTile( "application_management","approved",$M_APPROVED_APPLICATIONS,"","green");
        ?>
    </section>
    <section class="col-md-2 col-sm-3 col-xs-4">
        <?php 
            echo LinkTile( "application_management","list",$JOBSEEKERS_APPLIED,"","blue");
        ?>
    </section>
    <section class="col-md-2 col-sm-3 col-xs-4">
        <?php 
            echo LinkTile("application_management","rejected",$M_REJECTED_APPLICATIONS,"","red");
        ?>
    </section>
</div>


<?php

if($database->SQLCount("jobs","WHERE employer='".$AuthUserName."' ") == 0)
{

?>
<i> <?php echo strtoupper($ANY_JOB_ADS);?></i>
<?php
}
else
{
    $_REQUEST["hide_refine_search"]=true;
?>

<form action="index.php" method="post">
    
    <div id="div1">
        <i><?php echo $PLEASE_SELECT_AD;?></i>
    </div>
    
    
    <input type="hidden" name="Proceed">
    <input type="hidden" name="category" value="<?php echo $category;?>">
    <input type="hidden" name="action" value="<?php echo $action;?>">
    
    
		<?php
		
		        
                $applicants_applied = $db->withTotalCount()->rawQuery(
                    "
			SELECT 
			".$DBprefix."jobs.id,
			title,
			count(title) cc  
			FROM 
			".$DBprefix."jobs,".$DBprefix."apply
			WHERE
			".$DBprefix."jobs.id=".$DBprefix."apply.posting_id 
			AND
			employer='".$AuthUserName."'
			AND 
			".$DBprefix."apply.status=0
			GROUP BY ".$DBprefix."jobs.id
                    "
                );
                
                
		if($db->totalCount == 0)
		{		
                    echo "<br><i>".$M_NO_CANDIDATES_APPLIED."</i>
                    <script>

                    document.getElementById(\"div1\").style.display=\"none\";				
                    </script>

                    ";		
		}
		else
		{		
                    foreach ($applicants_applied as $value) { ?>
    <div class="block-display">
        <b><a title="<?php echo $value['title']?>" href="index.php?category=<?php echo $category;?>&action=<?php echo $action;?>&Proceed=1&id=<?php echo $value['id']?>"><img src="images/link_arrow.gif"> <?php echo mb_strimwidth($value['title'],0, 40, "...")?></a></b>
        <i><strong class="red-font"><?php echo $value["cc"]?></strong> <?php echo $M_APPLICATIONS;?></i>
    </div>                                                    
<?php               }
		}		
?>
    
    
</form>


<?php
}
?>

<?php
if(isset($_REQUEST["Proceed"]))
{
?>


<br>

<?php

if(isset($_REQUEST["id"]) && $_REQUEST["id"] != "")
{
	$id=$_REQUEST["id"];
	$website->ms_i($id);
	$arrJobAd = $database->DataArray("jobs","id=".$id);
	
	if($arrJobAd["employer"] != $AuthUserName)
	{
		die("");
	}
        
    $QueryListCVs_Applied ="
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
			posting_id=".$id."
			AND
			".$DBprefix."apply.status = '0')
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
			posting_id=".$id."
			AND 
			guest=1
			AND
			".$DBprefix."apply.status = '0')
	";

    $CVs_applied = $db->withTotalCount()->rawQuery($QueryListCVs_Applied);    
?>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <td>#</td>
                <td>Ngày đăng</td>
                <td>Tên</td>
                <td>Họ</td>
                <td></td>
                <td>Chi tiết</td>
            </tr>
        </thead>
        <tbody>
                <?php foreach ($CVs_applied as $CV_applied) :?>
            <tr>
                <td>#</td>
                <td><?php echo date('d/m/Y H:i', $CV_applied['date'])?></td>
                <td><?php echo $CV_applied['first_name']?></td>
                <td><?php echo $CV_applied['last_name']?></td>
                <td style="text-align: center;">
                    <a href="index.php?category=application_management&amp;folder=list&amp;page=reply&amp;Proceed=approve&amp;id=<?php echo $CV_applied['id2']?>&amp;posting_id=<?php echo $CV_applied['posting_id']?>" style="color:green;text-decoration:underline"><b>Phê duyệt</b></a>
                    <a href="index.php?category=application_management&amp;folder=list&amp;page=reply2&amp;Proceed=reject&amp;id=<?php echo $CV_applied['id2']?>&amp;posting_id=<?php echo $CV_applied['posting_id']?>" style="color:red;text-decoration:underline"><b>Từ chối</b></a>
                </td>
                <td><a href="index.php?category=application_management&amp;folder=my&amp;page=details&amp;posting_id=<?php echo $CV_applied['posting_id']?>&amp;apply_id=<?php echo $CV_applied['id2']?>"><img src="../images/job-details.png" border="0"></a></td>
            </tr>
                <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php
    }
}
?>

