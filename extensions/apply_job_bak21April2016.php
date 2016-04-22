<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php 
if(!defined('IN_SCRIPT')) die("");
global $db, $SEO_setting;

if ($SEO_setting == "0" && isset($_REQUEST["posting_id"])){
    $posting_id=$_REQUEST["posting_id"];
} elseif($SEO_setting == "1" && ($website->getURL_segment($website->currentURL(),3) !== NULL)) {    
    $posting_id = $website->getURL_segment($website->currentURL(),3);
} else {
    die("The job ID wasn't set!");
}
$website->ms_i($posting_id);
$job_info = $db->where('id', $posting_id)->getOne('jobs');


?>
<div class="page-wrap">
<?php

$show_page_form = true;

//Update when form submitted
if(get_param("ProceedApply_Update") != "")
{
    if($website->GetParam("USE_CAPTCHA_IMAGES") && ( (md5($_POST['code']) != $_SESSION['code'])|| trim($_POST['code']) == "" ) )
    {
        echo "<br><span class=\"red-font\"><b>
        ".$M_WRONG_CODE."
        </b></span><br><br>";

        $show_page_form = true;
    }
    else
    {

        $username = $_SESSION['username'];
	$password = $_SESSION['password'];

        $iInsertID = 
        $database->SQLInsert
                (
                    "apply",
                    array("date","posting_id","jobseeker","message"),
                    array(time(),$posting_id,$username,get_param("message"))
                );

        $database->Query
        ("
                UPDATE ".$DBprefix."jobs
                SET applications=applications+1
                WHERE id=".$posting_id
        );	

        $questions = $database->DataTable("questionnaire","WHERE job_id=".$posting_id);

            if($database->num_rows($questions)>0)
            {
                    while($question = $database->fetch_array($questions))
                    {
                            if(trim(get_param("question".$question["id"]))!="")
                            {
                                $database->SQLInsert
                                (
                                    "questionnaire_answers",
                                    array("question_id","app_id","answer","user", "job_id"),
                                    array($question["id"],$iInsertID,strip_tags(get_param("question".$question["id"])),$username, $posting_id)
                                );
                            }
                    }
            }


            $show_page_form = false;

            //Send email
            if($website->GetParam("ENABLE_EMAIL_NOTIFICATIONS"))
            {	

                    $oPosting = $database->DataArray("jobs","id=".$posting_id);

                    $headers  = "From: \"".$website->GetParam("SYSTEM_EMAIL_FROM")."\"<".$website->GetParam("SYSTEM_EMAIL_ADDRESS").">\n";


                    $message="[$username] ".$M_APPLIED_JOB_POSITION.": ".strip_tags($oPosting["title"])."\n\n".$M_WE_INVITE_LOGIN." ".$DOMAIN_NAME." ".$M_AND_VIEW_INFO;

                    mail($oPosting["employer"], "".$M_NEW_JOBSEEKER_APPLIED.": \"".strip_tags($oPosting["title"])."\"", $message, $headers);

            }

            //Attachments handle
            if(isset($_POST['Ids']))	
            {
                foreach($_POST['Ids'] as $file_id)
                {
                    //Insert to db
                    $database->SQLInsert(
                        "apply_documents",
                        array("file_id","apply_id","job_id", "jobseeker"),
                        array($file_id,$iInsertID,$posting_id, $username)
                    );
                }
            }	
            echo "<h3>".$CONGRATULATIONS_APPLIED_SUCCESS."</h3>";
    }
}
//Showing form submittion
if($show_page_form == TRUE && $_SESSION['user_type'] == "jobseeker")
{
	$username = $_SESSION['username'];
	$password = $_SESSION['user_password'];	
	$arrJ = $db->where('username', "$username")->withTotalCount()->getOne("jobseekers");

        
	$userExists = true;
	
	if(!isset($arrJ["id"]))
	{
		$userExists = false;
	}

        //User already applied for this job
	if($userExists &&($database->SQLCount("apply","WHERE jobseeker='$username' AND posting_id='$posting_id'") >0 ))
	{	
		echo "<br><span class=\"red-font\"><strong>".$M_ALREADY_APPLIED."</strong></span><br><br><br><br><br><br>";		
	}
	elseif($userExists && ($arrJ["password"])==$password) //User authenticated, show submitting form
	{	
	?>
    
    <form method="post">
        <input type="hidden" name="ProceedApply_Update" value="1">
        <input type="hidden" name="mod" value="apply_job">
        <input type="hidden" name="posting_id" value="<?php echo $posting_id;?>">
        
        
        
        
        <div class="page-header">
				 <?php
					echo "<h3 class=\"no-margin\">".$APPLY_JOB_OFFER." \"<strong>".strip_tags(stripslashes($job_info["title"]))."</strong>\"</h3>";
				 ?>
        </div>
        
        
        
        <div class="col-md-6">
            <strong><?php echo $M_MESSAGE;?>:</strong> (<?php echo $M_OPTIONAL;?>)
            <br/>
            
            <textarea cols="50" rows="5" class="form-control" name="message"></textarea>
        </div>
        <div class="clearfix"></div>
        <br/>
        <br/>
        
				<?php
				
				$questions = $database->DataTable("questionnaire","WHERE job_id=".$posting_id);
				
				if($database->num_rows($questions)>0)
				{
				?>
        <h4><?php echo $M_PLEASE_ANSWER_QUESTIONS;?></h4>
        <hr/>
        <div class="col-md-6">
					<?php
					while($question = $database->fetch_array($questions))
					{
						echo "<strong>".stripslashes($question["question"])."</strong>";
						echo "<br/>";
						
						if(trim($question["answers"])=="")
						{
							echo "<input class=\"form-control\" type=\"text\" value=\"".get_param("question".$question["id"])."\" name=\"question".$question["id"]."\"/>";
						}
						else
						{
							echo "<select class=\"form-control\" name=\"question".$question["id"]."\"/>";
							
							$answers=explode("\n",trim($question["answers"]));
							
							foreach($answers as $answer)
							{
								echo "<option>".stripslashes($answer)."</option>";
							}
							
							echo "</select>";
						}
						
						echo "<br/><br/>";
					}
					
					?>
        </div>
				<?php
				}
				
				?>
        
        
        <div class="clearfix"></div>
        
        <h4>
				<?php echo $PLEASE_SELECT_THE_LIST_WITH_FILES;?>
        </h4>
        
        <hr/>
        <table >
				<?php
				$userFiles = $database->DataTable("files","WHERE user='$username'");
				$hasFiles = false;
				while($userFile = $database->fetch_array($userFiles))
				{
                                    $hasFiles = true;
                                    echo "<tr>";	

                                    echo "
                                            <td width=20>
                                                    <input type=checkbox ".(isset($Ids)&&in_array($userFile["file_id"], $Ids)?"checked":"")." name=Ids[] value=\"".$userFile["file_id"]."\">
                                            </td>
                                    ";

                                    echo "
                                            <td width=30>
                                    ";
							
                                    if(strstr($userFile['file_name'],".pdf"))
                                    {?>
            <a href="file.php?id=<?php echo $userFile['file_id']?>" target=_blank>
                <img src="http://<?php echo $DOMAIN_NAME?>/JOBSEEKERS/images/pdf.gif" width="22" height="22" alt="" border="0">
            </a>
                                <?php }
                                    else
                                    if(strstr($userFile['file_name'],".doc"))
                                    {?>
            <a href="file.php?id=<?php echo $userFile['file_id']?>"  target=_blank>
                <img src="http://<?php echo $DOMAIN_NAME?>/JOBSEEKERS/images/doc.gif" width="22" height="22" alt="" border="0">
            </a>
                                    <?php }	elseif(strstr($userFile['file_name'],".txt")){ ?>
            
            <a href="file.php?id=<?php echo $userFile['file_id']?>"  target=_blank>
                <img src="http://<?php echo $DOMAIN_NAME?>/JOBSEEKERS/images/text.gif" width="17" height="22" alt="" border="0">
            </a>					
                                    <?php }
                                    else{
                                        echo $M_UNKNOWN;				
                                    }								
                                        echo "	</td>
                                        ";

                                        echo "
                                                <td  width=100>
                                                        ".$userFile['file_name']."
                                                </td>
                                        ";

                                        echo "
                                                <td>
                                                        ".$userFile["description"]."
                                                </td>
                                        ";
                                        echo "</tr>";	
				}
				?>
            
        </table>
				<?php
				if(!$hasFiles)
				{
					echo $YOU_HAVE_ANY_FILES."<br>";
				}
				?>
        
        <br><br>
        
				<?php
				if($website->GetParam("USE_CAPTCHA_IMAGES"))
				{
				?>	
        <table summary="" border="0">
            <tr>
                <td>
                    <img src="http://<?php echo $DOMAIN_NAME?>/include/sec_image.php" width="150" height="30" >
                </td>
                <td>
                    
								<?php echo $M_CODE;?>:
                    
                    <input type="text" name="code" value="" size=8>
                    
                </td>
            </tr>
        </table>
        
        
        <br><br>
				<?php
				}
				?>
        
        
        
        <input type="submit"value=" <?php echo $APPLY_NOW;?> " class="btn btn-primary">
        <br><br>
        
				<?php
				if($MULTI_LANGUAGE_SITE)
				{
				?>
        <input type="hidden" name="lang" value="<?php echo $website->lang;?>"/>
				<?php
				}
				?>
        
        
        
    </form>
    
    
    
	<?php
    }	
} else {
    echo "you must be jobseeker to apply this job";
}


?>
    
</div>

<?php
$website->Title($APPLY_JOB_OFFER." ".strip_tags(stripslashes($job_info["title"])));
$website->MetaDescription("");
$website->MetaKeywords("");
?>