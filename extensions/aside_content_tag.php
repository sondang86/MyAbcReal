<?php
if(!defined('IN_SCRIPT')) die("");
global $db;
if
(
	(isset($_REQUEST["page"])&&($_REQUEST["page"]=="en_Courses"||$_REQUEST["page"]=="es_Cursos"))
	||
	(isset($_REQUEST["mod"])&&$_REQUEST["mod"]=="courses")
)
{
//featured courses
$SearchTable = $db->withTotalCount()->rawQuery
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

	if($db->totalCount > 0)
	{
	?>
	<div class="gray-wrap">
            <div class="row">
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
            </div>
		<hr class="top-bottom-margin"/>
	<?php
            foreach($SearchTable as $value)
            {
                $headline = stripslashes($value["title"]);
                $strLink = $this->course_link($value["id"],$value["title"]);					
        
                    if($value["logo"]!="")
                    {				
                        if(file_exists("thumbnails/".$value["logo"].".jpg"))
                        {
                                echo "<a href=\"".$strLink."\"><img align=\"left\" src=\"thumbnails/".$value["logo"].".jpg\" width=\"50\" alt=\"".stripslashes(strip_tags($value["company"]))."\" class=\"img-shadow img-right-margin\"/></a>";
                        }
                    }
        ?>
			
			<h5 class="no-margin"><a href="<?php echo $strLink;?>" class="aside-link">
				<?php echo stripslashes(strip_tags($headline));?>
			</a></h5>
			<span class="sub-text">
			<?php echo $this->text_words(stripslashes(strip_tags($value["message"])),10);?>
			</span>
			
			<hr class="top-bottom-margin"/>
			
			
		<?php } ?>
		
		<br/>
		</div>
    <?php
	}

//end featured courses
}
else
{
	if(!isset($_REQUEST["mod"])&&(!isset($_REQUEST["page"])||(isset($_REQUEST["page"])&&$_REQUEST["page"]=="en_Home")||(isset($_REQUEST["page"])&&$_REQUEST["page"]=="vn_Trang chủ"))){
        } else
	{
		$is_featured=true;
	}

	$SearchTable = $db->withTotalCount()->rawQuery
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

        
	if($db->totalCount > 0)
	{
	?>
	<div class="gray-wrap">
            <div class="row">
		<h4 class="col-md-12 aside-header">
                    <?php
                        if(isset($is_featured)) { echo $FEATURED_JOBS;}
                        else { echo $M_LATEST_JOBS;}
                    ?>
		</h4>
            </div>
            <hr class="top-bottom-margin"/>
            <div class="row">
                <article class="aside-content">
                <?php
                    foreach($SearchTable as $value):

                        $headline = stripslashes($value["title"]);
                        $strLink = $this->job_link($value["id"],$value["title"]);
                        
                        //Image
                        if($value["logo"]!="")
                        {	
                            if(file_exists("thumbnails/".$value["logo"].".jpg"))
                            {
                                echo "<a href=\"".$strLink."\"><img align=\"left\" src=\"thumbnails/".$value["logo"].".jpg\" width=\"50\" alt=\"".stripslashes(strip_tags($value["company"]))."\" class=\"img-shadow img-right-margin\"/></a>";
                            }
                        }
                ?>
                    <!--Content-->
                    <h5 class="no-margin"><a href="<?php echo $strLink;?>" class="aside-link">
                            <?php echo stripslashes(strip_tags($headline));?>
                    </a></h5>
                    <span class="sub-text">
                    <?php echo $this->text_words(stripslashes(strip_tags($value["message"])),10);?>
                    </span>

                    <hr class="top-bottom-margin"/>			

                    <?php endforeach;?>
                </article>
            </div>
		<div class="text-center"><a class="underline-link" href="<?php echo $this->mod_link((isset($is_featured)?"featured":"latest")."-jobs");?>"><?php echo $M_SEE_ALL;?></a></div>
		<br/>
		</div>
		<?php
	}
}
?>
