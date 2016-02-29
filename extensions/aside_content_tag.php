<?php
if(!defined('IN_SCRIPT')) die("");

if
(
	(isset($_REQUEST["page"])&&($_REQUEST["page"]=="en_Courses"||$_REQUEST["page"]=="es_Cursos"))
	||
	(isset($_REQUEST["mod"])&&$_REQUEST["mod"]=="courses")
)
{
//featured courses
$SearchTable = $this->db->Query
	("
		SELECT 
		".$DBprefix."courses.id,
		".$DBprefix."courses.title,
		
		".$DBprefix."courses.message,
		".$DBprefix."employers.company,
		".$DBprefix."employers.logo
		FROM ".$DBprefix."courses,".$DBprefix."employers  
		WHERE 
		".$DBprefix."courses.employer =  ".$DBprefix."employers.username
		AND ".$DBprefix."courses.active='YES'
		AND status=1
		AND expires>".time()." 
		
		".((isset($_REQUEST["mod"])&&$_REQUEST["mod"]=="courses")?" AND featured=1 ":"")."
		 
		ORDER BY ".$DBprefix."courses.id DESC
		LIMIT 0,".$this->GetParam("NUMBER_OF_FEATURED_LISTINGS")."
	");

	if($this->db->num_rows($SearchTable)>0)
	{
	?>
	<div class="gray-wrap">

		<h4 class="aside-header">
		
			<?php 
			if(isset($_REQUEST["mod"])&&$_REQUEST["mod"]=="courses")
			{
				echo $M_LATEST_COURSES;
			}
			else
			{
				echo $M_FEATURED_COURSES;
			}
			?>
			
			
		</h4>
		<hr class="top-bottom-margin"/>
	<?php
		while($listing = $this->db->fetch_array($SearchTable))
		{	

			$headline = stripslashes($listing["title"]);

			$strLink = $this->course_link($listing["id"],$listing["title"]);
					
		?>

			<?php
			if($listing["logo"]!="")
			{
				
				if(file_exists("thumbnails/".$listing["logo"].".jpg"))
				{
					echo "<a href=\"".$strLink."\"><img align=\"left\" src=\"thumbnails/".$listing["logo"].".jpg\" width=\"50\" alt=\"".stripslashes(strip_tags($listing["company"]))."\" class=\"img-shadow img-right-margin\"/></a>";
				}
			}
			?>
			
			<h5 class="no-margin"><a href="<?php echo $strLink;?>" class="aside-link">
				<?php echo stripslashes(strip_tags($headline));?>
			</a></h5>
			<span class="sub-text">
			<?php echo $this->text_words(stripslashes(strip_tags($listing["message"])),10);?>
			</span>
			
			<hr class="top-bottom-margin"/>
			
			
		<?php
		}
		?>
		
		<br/>
		</div>
		<?php
	}

//end featured courses
}
else
{
	if(!isset($_REQUEST["mod"])&&(!isset($_REQUEST["page"])||(isset($_REQUEST["page"])&&$_REQUEST["page"]=="en_Home")||(isset($_REQUEST["page"])&&$_REQUEST["page"]=="vn_Trang chủ")))
	{

	}
	else
	{
		$is_featured=true;
	}

	$SearchTable = $this->db->Query
	("
		SELECT 
		".$DBprefix."jobs.id,
		".$DBprefix."jobs.title,
		".$DBprefix."jobs.message,
		".$DBprefix."employers.company,
		".$DBprefix."employers.logo
		FROM ".$DBprefix."jobs,".$DBprefix."employers  
		WHERE 
		".$DBprefix."jobs.employer =  ".$DBprefix."employers.username
		AND ".$DBprefix."jobs.active='YES'
		AND status=1
		AND expires>".time()." 
		".(isset($is_featured)?"AND featured=1 ":"")."
		ORDER BY 
		".(isset($is_featured)?"RAND()":$DBprefix."jobs.id DESC")."
		 
		LIMIT 0,".$this->GetParam("NUMBER_OF_FEATURED_LISTINGS")."
	");

	if($this->db->num_rows($SearchTable)>0)
	{
	?>
	<div class="gray-wrap">

		<h4 class="aside-header">
		<?php
		if(isset($is_featured))
		{
		?>
			<?php echo $FEATURED_JOBS;?>
		<?php
		}
		else
		{
		
		?>
			<?php echo $M_LATEST_JOBS;?>
			
		<?php
		}
		?>
		</h4>
			<hr class="top-bottom-margin"/>
	<?php
		while($listing = $this->db->fetch_array($SearchTable))
		{	

			$headline = stripslashes($listing["title"]);

			$strLink = $this->job_link($listing["id"],$listing["title"]);
					
		?>

			<?php
			if($listing["logo"]!="")
			{
				
				if(file_exists("thumbnails/".$listing["logo"].".jpg"))
				{
					echo "<a href=\"".$strLink."\"><img align=\"left\" src=\"thumbnails/".$listing["logo"].".jpg\" width=\"50\" alt=\"".stripslashes(strip_tags($listing["company"]))."\" class=\"img-shadow img-right-margin\"/></a>";
				}
			}
			?>
			
			<h5 class="no-margin"><a href="<?php echo $strLink;?>" class="aside-link">
				<?php echo stripslashes(strip_tags($headline));?>
			</a></h5>
			<span class="sub-text">
			<?php echo $this->text_words(stripslashes(strip_tags($listing["message"])),10);?>
			</span>
			
			<hr class="top-bottom-margin"/>
			
			
		<?php
		}
		?>
		
		<div class="text-center"><a class="underline-link" href="<?php echo $this->mod_link((isset($is_featured)?"featured":"latest")."-jobs");?>"><?php echo $M_SEE_ALL;?></a></div>
		<br/>
		</div>
		<?php
	}
}
?>
