<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php 
if(!defined('IN_SCRIPT')) die("");
if(isset($_REQUEST["posting_id"]))
{
	$website->ms_i($_REQUEST["posting_id"]);
	$posting_id = $_REQUEST["posting_id"];
}
else
{
	die("The job ID wasn't set!");
}
$website->ms_i($posting_id);
$arrPosting = $database->DataArray("jobs","id=".$posting_id);

?>
<div class="page-wrap">
<?php

$show_page_form = true;

//Update when form submitted
if(get_param("ProceedApply_Update") != "")
{
	if(get_param("guest")== "") //User logged in apply
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
                $arrUserLgn = explode("~", $_COOKIE["AuthJ"]);

                $username = $arrUserLgn[0];
                $password = $arrUserLgn[1];

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


                    if(isset($Ids))	
                    {
                            foreach($Ids as $file_id)
                            {
                                    $database->SQLInsert(
                                    "apply_documents",
                                    array("file_id","apply_id"),
                                    array($file_id,$iInsertID)
                                    );
                            }
                    }	
                    echo "<h3>".$CONGRATULATIONS_APPLIED_SUCCESS."</h3>";

            }
	
	} else {
            die;
        }
	
	
}

//Showing form submittion
if($show_page_form && isset($_COOKIE["AuthJ"]))
{

	$arrUserLgn = explode("~", $_COOKIE["AuthJ"]);	
	$username = $arrUserLgn[0];
	$password = $arrUserLgn[1];	
	$arrJ = $database->DataArray("jobseekers","username='$username'");

        
	$userExists = true;
	
	if(!isset($arrJ["id"]))
	{
		$userExists = false;
	}

        //User already applied for this job
	if($userExists&&($database->SQLCount("apply","WHERE jobseeker='$username' AND posting_id='$posting_id'") >0 ))
	{	
		echo "<br><span class=\"red-font\"><strong>".$M_ALREADY_APPLIED."</strong></span><br><br><br><br><br><br>";		
	}
	elseif($userExists&&md5($arrJ["password"])==$password) //User authenticated, show submitting form
	{	
	?>
    
    <form action="index.php" method="post">
        <input type="hidden" name="ProceedApply_Update" value="1">
        <input type="hidden" name="mod" value="apply">
        <input type="hidden" name="posting_id" value="<?php echo $posting_id;?>">
        
        
        
        
        <div class="page-header">
				 <?php
					echo "<h3 class=\"no-margin\">".$APPLY_JOB_OFFER." \"<strong>".strip_tags(stripslashes($arrPosting["title"]))."</strong>\"</h3>";
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
				{
					echo '
					<a href="file.php?id='.$userFile['file_id'].'" target=_blank>
						<img src="JOBSEEKERS/images/pdf.gif" width="22" height="22" alt="" border="0">
					</a>
					';
				}
				else
				if(strstr($userFile['file_name'],".doc"))
				{
					echo '
					<a href="file.php?id='.$userFile['file_id'].'"  target=_blank>
						<img src="JOBSEEKERS/images/doc.gif" width="22" height="22" alt="" border="0">
					</a>
					';
				}
				else
				if(strstr($userFile['file_name'],".txt"))
				{
					echo '
					<a href="file.php?id='.$userFile['file_id'].'"  target=_blank>
						<img src="JOBSEEKERS/images/text.gif" width="17" height="22" alt="" border="0">
					</a>
					';
				}
				else
				{
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
                    <img src="include/sec_image.php" width="150" height="30" >
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
}

elseif($show_page_form) 
{

if(isset($_REQUEST["error"])&&$_REQUEST["error"]=="login")
{
	echo "<h3 class=\"red-font\">".$WRONG_USERNAME_OR_PASSWORD."</h3>";
}
else
{
	echo "<h3>".$APPLY_JOB_OFFER." \"<strong>".strip_tags(stripslashes($arrPosting["title"]))."</strong>\"</h3>";
}
?>
    
    
    
    
    
    
    
	<?php
	if(isset($_REQUEST["guest"]))
	{
	?>
       
    
</form>
<form action="index.php" method=post ENCTYPE="multipart/form-data">
    <input type="hidden" name="ProceedApply_Update" value="1">
    <input type="hidden" name="mod" value="apply">
    <input type="hidden" name="guest" value="1">
    <input type="hidden" name="posting_id" value="<?php echo $posting_id;?>">
	<?php
	if($MULTI_LANGUAGE_SITE)
	{
	?>
    <input type="hidden" name="lang" value="<?php echo $website->lang;?>"/>
	<?php
	}
	?>
    
    
    <table>	
        <tr height="24">
            
        <label>
            <b><?php echo $EMAIL;?>: </b>
            </td>
            <td valign="middle">
                
                <input type="email" size="44" class="200px-field" name="user_email" id=user_email value="<?php echo get_param("user_email");?>" required> 
                
                
            </td>
            </tr>
            <tr height=24>
                
            <label>
                <b><?php echo $FIRST_NAME;?>: </b>
                </td>
                <td valign="middle">
                    
                    <input type=text class="200px-field" name="first_name" id="first_name" size=44 value="<?php echo get_param("first_name");?>">
                    
                </td>
                </tr>
                <tr height=24>
                    
                <label>
                    <b><?php echo $LAST_NAME;?>: </b>
                    </td>
                    <td valign="middle">
                        
                        <input type=text class="200px-field" name="last_name" id="last_name" size=44 value="<?php echo get_param("last_name");?>">
                        
                    </td>
                    </tr>
                    <tr height=24>
                    <label>
                        <b><?php echo $M_PHONE;?>:</b>
                        </td>
                        <td valign="middle">
                            <input type=text name="phone" id="phone" class="200px-field" size=44 value="<?php echo get_param("phone");?>"></td></tr>
                        <tr height=24>
                        <label>
                            <b><?php echo $M_MOBILE;?>:</b>
                            </td>
                            <td valign="middle">
                                
                                <input type=text name="mobile" id="mobile" class="200px-field" size=44 value="<?php echo get_param("mobile");?>">
                                
                            </td>
                            </tr>
                            </table>
                            <br>
                            <span>
                                
                                <b><?php echo $M_MESSAGE;?>:</b> (<?php echo $M_OPTIONAL;?>)
                                <br>
                                <textarea cols="50" rows="5" style="width:80%" name="message"></textarea>
                                
                                <br><br><br>
                                
                                <b>
				<?php
						$M_ATTACH_UP_TO = str_replace("[FILES_NUMBER]",aParameter(816),$M_ATTACH_UP_TO);
				
						echo $M_ATTACH_UP_TO."";
				?>
                                </b>
                                <br><br>
                                <table>
				<?php 
				
		
				
				for($i=0;$i<aParameter(816);$i++)
				{
					echo "<tr height=29>";
					echo "<td>".$FILE." #".($i+1)."</td>";
					
					echo "<td>";
					echo "<input type=file name=\"userfile".$i."\">";
					echo "</td>";
					echo "</tr>";
				}
				
				?>
                                </table>
                                
                                
                                <br>
                                
                                <br><br>
                                
				<?php
				if($website->GetParam("USE_CAPTCHA_IMAGES"))
				{
				?>	
                                <table summary="" border="0">
                                    <tr>
                                        <td>
                                            <img src="include/sec_image.php" width="150" height="30" >
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
                                
                                
                                
                                
                                </form>
                                
                                
                                
                                
                                
	<?php
	}
	else
	{
	?>					
                                
                                <br/>
                                
                                <form id="main" action="loginaction.php" method="post">
                                    
                                    <input type="hidden" name="errorURL" value="index.php?ProceedApply=1&mod=<?php echo $_REQUEST["mod"];?>&posting_id=<?php echo $posting_id;?>&error_login=1<?php if($MULTI_LANGUAGE_SITE) echo "&lang=".$website->lang;?>">
                                    <input type="hidden" name="returnURL" value="index.php?ProceedApply=1&mod=<?php echo $_REQUEST["mod"];?>&posting_id=<?php echo $posting_id;?><?php if($MULTI_LANGUAGE_SITE) echo "&lang=".$website->lang;?>">
                                    <fieldset>
                                        <legend>
					<?php echo $APPLY_EXPLANATION;?>
                                            
                                        </legend>
                                        <ol>
                                            <li>
                                                <label><?php echo $M_EMAIL;?>:</label>
                                                <input type="email" size="20" name="Email" required/>
                                            </li>
                                            <li>
                                                <label><?php echo $M_PASSWORD;?>:</label>
                                                <input type="password" size="20" name="Password" required/>
                                            </li>
                                        </ol>
                                    </fieldset>
			<?php
			if($MULTI_LANGUAGE_SITE)
			{
			?>
                                    <input type="hidden" name="lang" value="<?php echo $website->lang;?>"/>
			<?php
			}
			?>
                                    <button type="submit" class="btn btn-primary pull-right"><?php echo $M_CONNEXION;?></button>
                                    <div class="clearfix"></div>			
                                </form>
                                
                                <br>
                                
                                <br/>
                                <a class="underline-link" href="<?php echo $website->mod_link("jobseekers");?>"><?php echo $M_REGISTER_CLICK;?></a>
                                
                                <i>
			<?php
			if(false&&$website->GetParam("ALLOW_GUEST_APPLY"))
			{
				$strLink = "index.php?mod=apply&posting_id=".$posting_id."&guest=1";
				
				$M_GUEST_APPLY_EXPLANATION = str_replace("[APPLY_LINK]",$strLink,$M_GUEST_APPLY_EXPLANATION);
				
				echo $M_GUEST_APPLY_EXPLANATION;
			}
			?>
                                </i>
                                <br><br>
<?php
}
?>
                                <br><br>
                                
                                
<?php
}
?>
                                
                                </div>
                                
<?php
$website->Title($APPLY_JOB_OFFER." ".strip_tags(stripslashes($arrPosting["title"])));
$website->MetaDescription("");
$website->MetaKeywords("");
?>
