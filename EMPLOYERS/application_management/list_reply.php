<?php
// Jobs Portal

if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries,$FULL_DOMAIN_NAME;
//Sanitize prevents harmful input
$id=$_REQUEST["id"];
$website->ms_i($id);

if(isset($_REQUEST["posting_id"]))
{
$posting_id=$_REQUEST["posting_id"];
$website->ms_i($posting_id);

$db->where ('id', $id);
$db->where('posting_id', $posting_id); 
$userData = $db->getOne('apply');

//    Update data when form submitted
if(isset($_POST['submit'])){
    $safe_message = $db->cleanData($_POST['employer_reply']);
    
    $dataInsert = array(
        'status' => 1, // 0 not responding, 1 approved, 2 rejected
        'employer_reply' => "$safe_message"
    );    

    $db->where ('id', $id);
    $db->where('posting_id', $posting_id);    
    if ($db->update ('apply', $dataInsert)){ //success redirect to list applied jobs page
        $website->redirect($FULL_DOMAIN_NAME . '/EMPLOYERS/don-da-phe-duyet/'); 
    } else {
        echo 'update failed: ' . $db->getLastError();die;
    }
}
?>

<!--Approved Message form--> 
<div class="row">
    <div class="col-md-3 col-md-push-9 col-sm-4 col-sm-push-8 col-xs-12">
	<?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/danh-sach-don-xin-viec/", 'Quay lại', 'red');?>
    </div>
    
    <div class="col-md-9 col-md-pull-3 col-sm-8 col-sm-pull-4 col-xs-12 reply-form">
<?php
}
?>
        <h4>
	<?php 
		echo "<label>".$M_APPROVE_JOBSEEKER_APPLICATION . "</label>";
	?>
        </h4>
        
<?php
    
 ?>
            
            
        <form action="" id="EditForm" method="POST">
            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
            <input type="hidden" name="posting_id" value="<?php echo $_GET['posting_id']?>">
            <span><label>Lý do:</label></span>
            <span>
                <!--<input type="hidden" id="post_employer_reply" name="post_employer_reply">-->
                <textarea name="employer_reply" id="employer_reply" rows="15" required><?php echo $userData['employer_reply']?></textarea>
            </span>
            <span>
                <input class="btn btn-primary" type="submit" name="submit" value="Save">
            </span>
        </form>
    </div>
</div>     

<!--Approved message form--> 