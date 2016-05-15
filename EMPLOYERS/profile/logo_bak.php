<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
?>

<div class="row main-nav">
    <section class="col-md-8 col-sm-6">
        <?php $commonQueries->flash('message')?>
    </section>
    <section class="col-md-2 col-sm-3 col-xs-6">
        <?php echo LinkTile("profile","edit",$M_EDIT,"","green");?>
    </section>
    <section class="col-md-2 col-sm-3 col-xs-6">
        <?php echo LinkTile("profile","video",$M_VIDEO_PRESENTATION,"","lila");?>
    </section>
</div>


<?php
if(isset($_REQUEST["ProceedDelete"]))
{

	if($arrUser["logo"] != "")
	{
		
		$database->SQLUpdate_SingleValue
		(
			"employers",
			"username",
			"'".$AuthUserName."'",
			"logo",
			""
		);
		
		
		if(file_exists("../uploaded_images/".$arrUser["logo"].".jpg"))		
		{
			unlink("../uploaded_images/".$arrUser["logo"].".jpg");
		}
		
		if(file_exists("../thumbnails/".$arrUser["logo"].".jpg"))		
		{
			unlink("../thumbnails/".$arrUser["logo"].".jpg");
		}

	}
}

?>


<h3>
	<?php echo $MODIFY_LOGO;?>
</h3>
	
<?php

AddEditForm
(
	array($M_LOGO.":"),
	array("logo"),
	array(),
	array("file"),
	"employers",
	"id",
	$arrUser["id"],
	$LES_VALEURS_MODIFIEES_SUCCES,
	"ValidateLogo"
);
?>
		
	
<br><br><br>

<i><?php echo $YOUR_CURRENT_LOGO;?></i>

<a class="pull-right" href="index.php?category=<?php echo $category;?>&action=<?php echo $action;?>&ProceedDelete=logo"><b>[<?php echo strtoupper($EFFACER);?>]</b></a>
  	
<br/><br/>
  
  <?php
  $arrUser = $database->DataArray("employers","username='$AuthUserName'");

  if(trim($arrUser["logo"]) == "" || $arrUser["logo"] == 0)
  {
  	echo "<br><br>".$NO_LOGO_AVAILABLE."";
  }
  else
  {
	echo "<img src=\"../thumbnails/".$arrUser["logo"].".jpg\">";
		
	echo "<br>";
  }
 ?>