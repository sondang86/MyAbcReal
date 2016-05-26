<?php
    if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
    global $db, $website, $SEO_setting, $commonQueries;
?>

<!--Job by attribute-->
<div class="gray-wrap">
    <header class="row top-bottom-margin">
        <h4 class="aside-header"><i class="fa fa-tags"></i> Việc làm theo tính chất</h4>
    </header>
    <section class="row">
        <article class="col-md-12">
            <ul>
                <li><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-luong-cao/">Việc làm lương cao</a></li>
                <li><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-toan-thoi-gian/">Việc làm toàn thời gian</a></li>
                <li><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-part-time/">Việc làm thêm, sinh viên, part-time</a></li>
                <li><a href="http://<?php echo $DOMAIN_NAME;?>/viec-lam-freelancer/">Việc làm freelance</a></li>
            </ul>
        </article>
    </section>
</div>


<?php if(
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
?>

<?php 	if($db->totalCount > 0)	{ ?>

<div class="gray-wrap">
    <header class="row">
        <h4 class="aside-header"><i class="fa fa-newspaper-o"></i> 
            
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
    </header>
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
                                echo "<a href=\"".$strLink."\"><img align=\"left\" src=\"/vieclambanthoigian.com.vn/thumbnails/".$value["logo"].".jpg\" width=\"50\" alt=\"".stripslashes(strip_tags($value["company"]))."\" class=\"img-shadow img-right-margin\"/></a>";
                        }
                    }
        ?>
    
    <h5 class="no-margin">
        <a href="<?php echo $strLink;?>" class="aside-link"><?php echo stripslashes(strip_tags($headline));?></a>
    </h5>
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
		".$DBprefix."jobs.id,".$DBprefix."jobs.date,
		".$DBprefix."jobs.title,
		".$DBprefix."jobs.message,
                ".$DBprefix."jobs.SEO_title,
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

//      print_r($SearchTable);
	if($db->totalCount > 0)
	{
	?>
<div class="gray-wrap">
    <header class="row">
        <h4 class="col-md-12 aside-header"><i class="fa fa-newspaper-o"></i> 
                    <?php
                        if(isset($is_featured)) { echo $FEATURED_JOBS;}
                        else { echo $M_LATEST_JOBS;}
                    ?>
        </h4>
    </header>
    <hr class="top-bottom-margin"/>
    <div class="row">
        <article class="col-md-12">
                        <?php
                            foreach($SearchTable as $value):
                                $headline = stripslashes($value["title"]);
                                $strLink = $this->job_link($value["id"],$value["title"]);
                        ?>
            <div class="row">
                <div class="col-md-12 aside-content">
                                <?php                                
                                    //Image
                                    if($value["logo"]!="")
                                    {	
                                        if(file_exists("thumbnails/".$value["logo"].".jpg"))
                                        {
                                            echo "<a href=\"http://$DOMAIN_NAME/chi-tiet-cong-viec/".$value['id']."/".$value['SEO_title']."\"><img src=\"/vieclambanthoigian.com.vn/thumbnails/".$value["logo"].".jpg\" width=\"50\" alt=\"".stripslashes(strip_tags($value["company"]))."\" title='". $value["title"] . "' class=\"img-shadow img-right-margin\"/></a>";
                                        }
                                    }
                                ?>
                    <!--Content-->
                    <h5 class="no-margin">                        
                        <?php if($SEO_setting == 0){?>
                        <a href="http://<?php echo $DOMAIN_NAME;?>/index.php?mod=details&id=<?php echo $value['id']?>&lang=vn">
                            <?php echo $website->limitCharacters(stripslashes(strip_tags($headline)), 40);?>
                        </a>
                        <?php } else {?>  
                        <a href="http://<?php echo $DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $value['id']?>/<?php echo $value['SEO_title']?>" class="aside-link" title="<?php echo $value['title']?>">
                            <?php echo $website->limitCharacters(stripslashes(strip_tags($headline)), 50);?>
                        </a>
                        <?php }?>
                        <p class="sub-text">
                            <?php echo $this->text_words(stripslashes(strip_tags($value["message"])),30);?>
                        </p>
                        <p class="sub-text"><strong><?php echo $commonQueries->time_ago($value['date']);?></strong></p>
                    </h5>               
                    <hr class="top-bottom-margin"/>
                </div>    
            </div>
                        <?php endforeach;?>
            
        </article>
    </div>
    <?php if(isset($is_featured)){?>
        <div class="text-center"><a class="underline-link" href="<?php $website->check_SEO_link("featured", $SEO_setting)?>"><?php echo $M_SEE_ALL;?></a></div>    
    <?php }else {?>
        <div class="text-center"><a class="underline-link" href="<?php $website->check_SEO_link("latest", $SEO_setting)?>"><?php echo $M_SEE_ALL;?></a></div>
    <?php }?>
</div>
<?php
	}
}
?>
