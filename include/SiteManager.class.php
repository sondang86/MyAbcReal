<?php
class SiteManager
{
	public $lang="en";
	public $arrPages = array();
	public $domain = "";
	public $multi_language = false;
	private $db;
	public $running_mode=1;

	public $admin_settings = array();
	public $global_categories=array();
	public $languages=array();
	public $categories_count;
	public $default_page_name="en_Home";
	
	
	function SiteManager()
	{
		
	}
	
	/// The website title and meta description and keywords,
	/// which can be used for SEO purposes
	public $Title = true;
	public $Description = true;
	public $Keywords = true;
	
	/// The current language version on the website
	public $Language = true;
	
	/// The html code of the website template
	public $TemplateHTML = "";
	
	/// The site paramets
	public $params = array();
	public $user_params = array();
	
	public $IsExtension = false;
	public $ExtensionFile = false;
	
	public $DefaultPage = "";
	public $DefaultPageId = 0;
	
	public $isAdminPanel = false;
		
	public $MenuHTML = "";
	
	function SetLanguage($lang)
	{
		$this->lang= substr(preg_replace("/[^a-z]/i", "", $lang), 0, 2); 
	}
		
	function SetDatabase(Database $db)
	{
		$this->db = $db;
	
	}
	
	function LoadSettings()
	{
		global $database,$DBprefix,$MULTI_LANGUAGE_SITE,$DOMAIN_NAME,$_REQUEST,$is_mobile;
		
			
		$this->domain = $DOMAIN_NAME;
		$this->multi_language = $MULTI_LANGUAGE_SITE;
		$this->admin_settings=$this->db->DataArray("admin_users","id=1");
		if(isset($is_mobile))
		{
		
		}
		else
		if(isset($_REQUEST["lang"])&&$_REQUEST["lang"]!="")
		{
			$lng=$_REQUEST["lang"];
			$this->ms_w($lng);
			$lng=substr(preg_replace("/[^a-z]/i", "", $_REQUEST["lang"]), 0, 2);
			
			if(file_exists("include/texts_".$lng.".php"))
			{
				$this->lang= $lng; 
			}
			else
			{
				echo "<i>Please create a language file for this new language!</i>";
			}
		}
		else
		if(isset($_REQUEST["page"]))
		{
			list($lang,$link)=explode("_",urldecode($_REQUEST["page"]),2);
		
			if(trim($lang)!="" && strlen($lang)==2)
			{
				$this->lang= substr(preg_replace("/[^a-z]/i", "", $lang), 0, 2); 
		
			}
		}
		else
		{
			if($MULTI_LANGUAGE_SITE)
			{
				$default_language = $database->DataArray("languages","default_language=1");
				
				if(isset($default_language["code"])&&strlen($default_language["code"])==2)
				{
					$this->lang = strtolower($default_language["code"]);
				}
			}
			
		}
		
		
		if
		(
			isset($_REQUEST["mod"]) &&
			$_REQUEST["mod"] != "" &&
			file_exists("extensions/".$_REQUEST["mod"].".php")
		)
		{
			$this->IsExtension = true;
			$this->ExtensionFile = $_REQUEST["mod"];
		}
		
		$sql_query = "select id,value from ".$DBprefix."settings";
		
		$results = $this->db->Query($sql_query);
	
		while($row = mysqli_fetch_array($results))
		{
			$this->params[$row["id"]]=$row["value"];
		}
		
		$this->DefaultPageId = $this->params[1];
	
		date_default_timezone_set($this->params[115]);
	
		
	}
	
	
	function SetPage()
	{
	
	}
	
	function LoadTemplate($template_id)
	{
		
		global $_REQUEST,$DBprefix;
		if($template_id==-1)
		{
			if(file_exists('../users_template.htm'))
			{
				$templateArray=array();
				$templateArray["html"] = file_get_contents('../users_template.htm');
			}
			else
			{
				$templateArray= $this->db->DataArray("templates","name LIKE '%admin%'");
			}
		}
		else
		if($template_id>0)
		{
			$templateArray= $this->db->DataArray("templates","id=".$template_id);
		}
		else
		if(file_exists("template.htm"))
		{
			$templateArray=array();
			
			if(file_exists("template_".$this->lang.".htm"))
			{
				$templateArray["html"] = file_get_contents('template_'.$this->lang.'.htm');
			}
			else
			{
				$templateArray["html"] = file_get_contents('template.htm');
			}
		}
		else
		if(file_exists("../template.htm"))
		{
			$templateArray=array();
			$templateArray["html"] = file_get_contents('../template.htm');
		}
		else
		{
			$templateArray= $this->db->DataArray("templates","id=".$this->params[10]);
		}
		
		
	
		///custom colors
		
		if($this->admin_settings["custom_color"]!=""&&file_exists("thumbnails"))
		{
		
					$custom_color=$this->admin_settings["custom_color"];
					$custom_color_light=
					dechex(hexdec($custom_color) + hexdec(202020));
					
					$templateArray["html"] = str_replace
					(
						"</head>",
		"	<style>
		.custom-back-color,.btn-primary
		{
			background:#".$custom_color." !important;
			background-color:#".$custom_color." !important;
			border-color:#".$custom_color." !important;
		}
		.custom-back-color-light
		{
			background:#".$custom_color_light." !important;
			background-color:#".$custom_color_light." !important;
		}
		.custom-color,h1,h2,h3,h4,.job-details-link,.results-job-title,.main_category_link
		{
			color:#".$custom_color." !important;
		}
		.job-details-info
		{
			border-color:#".$custom_color." !important;
		}
		.custom-color-light
		{
			color:#".$custom_color_light." !important;
		}
		
		.custom-gradient
		{
			background-color:#".$custom_color." !important;
			background-image:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#".$custom_color."), to(#".$custom_color_light.")) !important;
			background-image:-webkit-linear-gradient(top, #".$custom_color.", #".$custom_color_light.") !important;
			background-image:-moz-linear-gradient(top, #".$custom_color.", #".$custom_color_light.") !important;
			background-image:-ms-linear-gradient(top, #".$custom_color.", #".$custom_color_light.") !important;
			background-image:-o-linear-gradient(top, #".$custom_color.", #".$custom_color_light.") !important;
			filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr=#".$custom_color.", endColorstr=#".$custom_color_light.") !important;
			-ms-filter:\"progid:DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#".$custom_color.", endColorstr=#".$custom_color_light.")\" !important;
		}
		
		.custom-gradient-2
		{
			background-color:#ebebeb !important;
			background-image:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#fbfbfb), to(#ebebeb)) !important;
			background-image:-webkit-linear-gradient(top, #fbfbfb, #ebebeb) !important;
			background-image:-moz-linear-gradient(top, #fbfbfb, #ebebeb) !important;
			background-image:-ms-linear-gradient(top, #fbfbfb, #ebebeb) !important;
			background-image:-o-linear-gradient(top, #fbfbfb, #ebebeb) !important;
			filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr=#fbfbfb, endColorstr=#ebebeb) !important;
			-ms-filter:\"progid:DXImageTransform.Microsoft.gradient (GradientType=0, startColorstr=#fbfbfb, endColorstr=#ebebeb)\" !important;
			color:#000000 !important;
			background-color: #ffffff !important;
			border-color: #cccccc !important;
		}
		
		</style>
		</head>",
						$templateArray["html"]
					);
				}
				
				
		if($this->admin_settings["background"]!="")
		{
					$templateArray["html"] = str_replace
					(
						"</head>",
		"	<style>
		body
		{
			background-image:url('images/backgrounds/".$this->admin_settings["background"].".png') !important
		}
		</style>
		</head>",
						$templateArray["html"]
					);
		}
		
		///end custom colors
		
		$this->TemplateHTML = stripslashes($templateArray["html"]);
		$this->TemplateHTML = str_replace(" />","/>",$this->TemplateHTML);
		
		$pattern = "/{(\w+)}/i";
		preg_match_all($pattern, $this->TemplateHTML, $items_found);
		foreach($items_found[1] as $item_found)
		{
			global $$item_found;
			if(isset($$item_found))
			{
				$this->TemplateHTML=str_replace("{".$item_found."}",$$item_found,$this->TemplateHTML);
			}
		}
		
		///adsense
		$this->TemplateHTML = str_replace("<site top_banners/>","<site top_banners/>".stripslashes($this->params[190]),$this->TemplateHTML);
		$this->TemplateHTML = str_replace("<site side_column_banners/>","<site side_column_banners/>".stripslashes($this->params[191]),$this->TemplateHTML);
		$this->TemplateHTML = str_replace("<site bottom_banners/>","<site bottom_banners/>".stripslashes($this->params[192]),$this->TemplateHTML);
		$this->TemplateHTML = str_replace("</body>",stripslashes($this->params[193])."\n</body>",$this->TemplateHTML);
		///end adsense
		
		
		///banners
		if(!$this->isAdminPanel)
		{
			$tableBannerAreas = $this->db->DataTable("banner_areas","");

			while($arrBannerArea = mysqli_fetch_array($tableBannerAreas))
			{
				$areaHTML = "";
				
				
				$tableBanners = 
				$this->db->Query
				(
				"SELECT 
				".$DBprefix."banners.employer,
				".$DBprefix."banners.link,
				".$DBprefix."banners.link_type,
				".$DBprefix."banners.image_id,
				".$DBprefix."employers.id,
				".$DBprefix."employers.company
				FROM
				".$DBprefix."banners,".$DBprefix."employers
				 WHERE 
				".$DBprefix."banners.employer=".$DBprefix."employers.username
				 AND
				".$DBprefix."banners.banner_type=".$arrBannerArea["id"]."
				 AND
				 ".$DBprefix."banners.active=1
				  AND
				 ".$DBprefix."banners.expires>".time()
			);
	
			$iCounter = 0;
			
			if(mysqli_num_rows($tableBanners)>0)
			{
				
				$areaHTML .= "<table>";	
				
				
				while($arrBanner = mysqli_fetch_array($tableBanners))
				{
					if($iCounter>=($arrBannerArea["rows"]*$arrBannerArea["cols"]))
					{
						break;
					}
					
					if($iCounter%$arrBannerArea["cols"]==0)
					{
						$areaHTML .= "<tr>";
					}
					
					$areaHTML .= "<td>";
					
					if($arrBanner["link_type"] == "2")
					{
						$banner_link=str_replace("https://","",$arrBanner["link"]);
						$banner_link=str_replace("http://","",$banner_link);
						$areaHTML .= "<a href=\"http://".$banner_link."\" target=\"_blank\">";
					}
					else
					{
					
						$strLink=$this->company_jobs_link($arrBanner["id"],$arrBanner["company"]);
						
						$areaHTML .= "<a href=\"".$strLink."\">";
					}
					
					$areaHTML .= "<img alt=\"\" width=\"".$arrBannerArea["width"]."\" src=\"".($arrBannerArea["height"]<=75&&$arrBannerArea["width"]<=100?"thumbnails":"uploaded_images")."/".$arrBanner["image_id"].".jpg\"/>";		
					
					$areaHTML .= "</a>";
					
					$areaHTML .= "</td>";
					
					if(($iCounter%$arrBannerArea["cols"]+1)==0)
					{
						$areaHTML .= "</tr>";
					}
					
							
					$iCounter++;
				}
				
				if($iCounter%$arrBannerArea["cols"]!=0)
				{
					for($i=$iCounter;$i<($iCounter%$arrBannerArea["cols"]);$i++)
					{
						if($i%$arrBannerArea["cols"]==0)
						{
							$areaHTML .= "<tr>";
						}
						
						$areaHTML .= "<td>&nbsp;</td>";
						
						if(($i%$arrBannerArea["cols"]+1)==0)
						{
							$areaHTML .= "</tr>";
						}
					}
				
				}
				
				$areaHTML .= "</table>";	
				
				$area_position=str_replace(" ","_",strtolower($arrBannerArea["position"]));
				
				$this->TemplateHTML = str_replace("<site ".$area_position."_banners/>",$areaHTML,$this->TemplateHTML);
			}	
		}}
		
		///end banners
		
	}
	
	function Render()
	{
		if($this->isAdminPanel==false && $this->params[60] == "NO")
		{
			$this->TemplateHTML=str_replace
			(
				"</head>",
				"<style>
				td, div, span{font-family:".$this->params[61].";font-size:".$this->params[62]."px;color:".$this->params[63]."}
				a:link{color:".$this->params[64]."}
				a:visited{color:".$this->params[65]."}
				a:hover{color:".$this->params[66]."}
				h1,h2,h3,h4,h5,h6{color:".$this->params[67]."}
				</style></head>",
				$this->TemplateHTML
			);
		}
		echo $this->TemplateHTML;
	}
	
	function LanguagesMenu($page)
	{

		global $lang,$database,$website,$_REQUEST;
		$strResult="";

			
		$tableLanguages=$database->DataTable("languages","WHERE active=1");
		
		
			$bFirst=true;
		
					
			while($arrLanguages=mysqli_fetch_array($tableLanguages))
			{
				array_push($this->languages,strtolower($arrLanguages["code"]));
				if(strtolower($arrLanguages["code"])==$this->lang) continue;
					
				$str_link = "index.php?lang=".strtolower($arrLanguages["code"]);
				
			
				if(isset($page["id"]))
				{
					$str_link = $this->GenerateLink($this->params[1111],$this->params[1112],strtolower($arrLanguages["code"]),stripslashes($page["link_".strtolower($arrLanguages["code"])]));
				}
				
					
				
				$strResult.=
				"
					<a href=\"".$str_link."\"><img alt=\"".stripslashes($arrLanguages["name"])."\"  title=\"".stripslashes($arrLanguages["name"])."\" src=\"images/flags/".strtoupper($arrLanguages["code"]).".gif\" width=\"21\" height=\"14\"/></a>
				";	
			}
		
		return $strResult;
	}
	
	function GetSubArray($parent,$arr)
	{
		$result = array();

		for($i=0;$i<sizeof($arr);$i++)
		{
			if($arr[$i][1] == $parent)
			{
				array_push($result, $arr[$i]);
			}
		}

		return $result;

	}


	function GenerateMenu()
	{
		global $page,$database,$website;
		$strResult="";
		
		$site_pages=$database->DataTable("pages","WHERE id>0 AND active_".$this->lang."=1 order by id");
		
		while ($row = mysqli_fetch_array($site_pages))
		{
			
			array_push($this->arrPages, array($row['id'], $row['parent_id'], $row["link_".$this->lang], $row["custom_link_".$this->lang], $row["only_bottom"]));
		}
		
		$strLinkTemplate = '<li><a class="main-top-link" href="[LINK_HREF]">[LINK_TEXT]</a> </li>';
			
		foreach($this->arrPages as $arrPage)
		{
			
			if($arrPage[1]!=0) continue;
			if(trim($arrPage[3])=="1")  continue;
			
			$arrSubPages = $this->GetSubArray($arrPage[0],$this->arrPages);
		
			if($this->DefaultPage=="" && isset($_REQUEST["p"])&&trim($_REQUEST["p"])!="")
			{
				$this->DefaultPage = $this->lang."_".stripslashes($arrPage[2]);
			}
			else
			if($this->DefaultPageId == $arrPage[0]) 
			{
				$this->DefaultPage = $this->lang."_".stripslashes($arrPage[2]);
			}
			
			$strResult .= "\n";
			
			$strSubResult = "";
			
			if(sizeof($arrSubPages) > 0)
			{
			
				//$strSubResult .= "\n <ul>\n";
				$strSubResult .= "\n <ul  class=\"text-left dropdown-menu\">\n";
				
				
				foreach($arrSubPages as $arrSubPage)
				{
					$strSubResult .= "  <li><a href=\"".$this->GenerateLink($this->params[1111],$this->params[1112],$this->lang,stripslashes($arrSubPage[2]))."\">".stripslashes($arrSubPage[2])."</a></li>\n";
				}
				
				$strSubResult .= " </ul>\n";
				
				
				//$strResult .= str_replace("[LINK_TEXT]",stripslashes($arrPage[2]),str_replace("[LINK_HREF]",$this->GenerateLink($this->params[1111],$this->params[1112],$this->lang,stripslashes($arrPage[2])),
				//str_replace("</li>",$strSubResult."</li>",$strLinkTemplate)
				//));
				
				$strResult .= str_replace("[LINK_TEXT]",stripslashes($arrPage[2]),str_replace("[LINK_HREF]",$this->GenerateLink($this->params[1111],$this->params[1112],$this->lang,stripslashes($arrPage[2])),
				str_replace("</li>",$strSubResult."</li>",str_replace("<li>","<li class=\"dropdown\">",$strLinkTemplate))
				));
			}
			else
			{
				if($arrPage[4]==1) continue;
				$strResult .= str_replace("[LINK_TEXT]",stripslashes($arrPage[2]),str_replace("[LINK_HREF]",$this->GenerateLink($this->params[1111],$this->params[1112],$this->lang,stripslashes($arrPage[2])),$strLinkTemplate));
			}
			
			
		}
		$this->MenuHTML = $strResult;
	}

	function GenerateLink($urlFormat,$urlLanguage,$lang,$page)
	{
		global $DOMAIN_NAME;
		if($this->GetParam("SEO_URLS")==1)
		{
			if(false&&$lang."_".stripslashes($page) == "en_Home")
			{
				return "http://".$DOMAIN_NAME;
			}
			else
			{
				$path="";
				
				return $path.(urlencode($lang."_".stripslashes($page))).".html";	
			}
		}
		else
		{
			return "index.php?page=".urlencode($lang."_".stripslashes($page));	
		}
		
		
		
	}
	

	function ms_w($input)
	{
		if(!preg_match("/^[a-zA-Z0-9_]+$/i", $input)) die("");
	}
	
	function ms_ew($input)
	{
		if(!preg_match("/^[a-zA-Z0-9_\-. @\/\:]+$/i", $input)) die("");
	} 
	
	function ms_i($input)
	{
		if(!is_numeric($input)) die("");
	} 
	
	function ms_ia($input)
	{
		foreach($input as $inp) if(!is_numeric($inp)) die("");
	}
	
	function ForceLogin()
	{
		die("<script>document.location.href='login.php';</script>");
	}
	
	function aParameter($param_id)
	{
		return $this->params[$param_id];
	}
	
	function Statistics()
	{
		global $database,$_REQUEST,$_SERVER;
		
		
			$database->SQLInsert
			(
				"statistics",
				array("date","timestamp","host","referer","page"),
				array(date("F j, Y"),time(),$_SERVER["REMOTE_ADDR"],(isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:""),(isset($_REQUEST["page"])?$_REQUEST["page"]:"home"))
			);
		
	}

	
	function ProcessTags()
	{
		global $MULTI_LANGUAGE_SITE,$DBprefix,$DOMAIN_NAME;
		
		if(file_exists("include/texts_".$this->lang.".php"))
		{
			include("include/texts_".$this->lang.".php");
		}
		$this->TemplateHTML = str_replace("<site menu/>",$this->MenuHTML,$this->TemplateHTML);
				
		$arrTags = unserialize($this->params[100]);
		
		if(isset($_REQUEST["mod"])||isset($_REQUEST["page"]))
		{
			$this->TemplateHTML = str_replace('<a href="http://www.netartmedia.net/jobsportal','<a rel="nofollow" href="http://www.netartmedia.net/jobsportal',$this->TemplateHTML);
		}
		else
		{
			
		}
		
		if(!isset($_REQUEST["mod"])&&(!isset($_REQUEST["page"])||(isset($_REQUEST["page"])&&$_REQUEST["page"]=="en_Home")))
		{
			array_push($arrTags, array("carousel","carousel_tag.php"));
		}
		array_push($arrTags, array("logo","logo_tag.php"));
		array_push($arrTags, array("home_panel","home_panel_tag.php"));
		array_push($arrTags, array("saved_jobs","saved_jobs_tag.php"));
		
		if(is_array($arrTags))
		{
			foreach($arrTags as $arrTag)
			{
				$tag_pos = strpos($this->TemplateHTML,"<site ".$arrTag[0]."/>");
			
				if($tag_pos !== false)
				{
					if(trim($arrTag[1]) != "none" && trim($arrTag[0]) != "" && trim($arrTag[1]) != "")
					{
						$HTML="";
						ob_start();
						include("extensions/".$arrTag[1]);
						
						if($HTML=="")
						{
							$HTML = ob_get_contents();
						}
						ob_end_clean();
						$this->TemplateHTML = str_replace("<site ".$arrTag[0]."/>",$HTML,$this->TemplateHTML);
					}
				}
			}
		}

		
		$pattern = "/{(\w+)}/i";
		preg_match_all($pattern, $this->TemplateHTML, $items_found);
		
		
		foreach($items_found[1] as $item_found)
		{
			if(strstr($item_found,"DB")) continue;
			if(isset($$item_found))
			{
				$this->TemplateHTML=str_replace("{".$item_found."}",$$item_found,$this->TemplateHTML);
			}
		}
		
		
	}
	
	
	function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}
	
	function remove_vietnamese_accents($str)
	{
		$accents_arr=array(
		"à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
		"ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề",
		"ế","ệ","ể","ễ",
		"ì","í","ị","ỉ","ĩ",
		"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ",
		"ờ","ớ","ợ","ở","ỡ",
		"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
		"ỳ","ý","ỵ","ỷ","ỹ",
		"đ",
		"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă",
		"Ằ","Ắ","Ặ","Ẳ","Ẵ",
		"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
		"Ì","Í","Ị","Ỉ","Ĩ",
		"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ",
		"Ờ","Ớ","Ợ","Ở","Ỡ",
		"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
		"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
		"Đ"
		);
		$no_accents_arr=array(
		"a","a","a","a","a","a","a","a","a","a","a",
		"a","a","a","a","a","a",
		"e","e","e","e","e","e","e","e","e","e","e",
		"i","i","i","i","i",
		"o","o","o","o","o","o","o","o","o","o","o","o",
		"o","o","o","o","o",
		"u","u","u","u","u","u","u","u","u","u","u",
		"y","y","y","y","y",
		"d",
		"A","A","A","A","A","A","A","A","A","A","A","A",
		"A","A","A","A","A",
		"E","E","E","E","E","E","E","E","E","E","E",
		"I","I","I","I","I",
		"O","O","O","O","O","O","O","O","O","O","O","O",
		"O","O","O","O","O",
		"U","U","U","U","U","U","U","U","U","U","U",
		"Y","Y","Y","Y","Y",
		"D"
		);

		return str_replace($accents_arr,$no_accents_arr,$str);
	}

	
	function format_str($strTitle)
	{
		$strSEPage = ""; 
		$strTitle=strip_tags(stripslashes(strtolower(trim($strTitle))));
		$arrSigns = array("~", "!","\t", "@","1","2","3","4","5","6","7","8","9","0", "#", "$", "%", "^", "&", "*", "(", ")", "+", "-", ",",".","/", "?", ":","<",">","[","]","{","}","|"); 
		
		$strTitle = str_replace($arrSigns, "", $strTitle); 
		
		$strTitle=$this->remove_vietnamese_accents($strTitle);
		$pattern = '/[^\w ]+/';
		$replacement = '';
		$strTitle = preg_replace($pattern, $replacement, $strTitle);

		$arrWords = explode(" ",$strTitle);
		$iWCounter = 1; 
		
		foreach($arrWords as $strWord) 
		{ 
			if($strWord == "") { continue; }  
			
			if($iWCounter == 8) { break; }  
			if($iWCounter != 1) { $strSEPage .= "-"; }
			$strSEPage .= $strWord;  
			
			$iWCounter++; 
		} 
		
		return $strSEPage;
		
	}
	
	function page_link($page_name,$page_id,$lang)
	{
		$SEO_URLS=true;
		$MULTI_LANGUAGE=true;
		
		if($SEO_URLS)
		{
			return format_str($page_name)."-".$page_id.($MULTI_LANGUAGE?"-".strtolower($lang):"").".html";
		}
		else
		{
			return "index.php?page=".$page_id.($MULTI_LANGUAGE?"&lang=".strtolower($lang):"");
		
		}
	}
	
	function twords($string, $wordsreturned)
	{
		$string=trim($string);
		$string=str_replace("\n","",$string);
		$string=str_replace("\t"," ",$string);
		
		$string=str_replace("\r","",$string);
		$string=str_replace("  "," ",$string);
		 $retval = $string;    
		$array = explode(" ", $string);
	  
		if (count($array)<=$wordsreturned)
		{
			$retval = $string;
		}
		else
		{
			array_splice($array, $wordsreturned);
			$retval = implode(" ", $array)." ...";
		}
		return $retval;
	}
	
	function format_keywords($keywords_text)
	{
		$keywords_text = str_replace("-"," ",$keywords_text);
		$keywords_text = strtolower(str_replace(" ",",",str_replace(", ...","",$this->twords(stripslashes(strip_tags($keywords_text)),30))));
		$keywords_text = str_replace(",,",",",$keywords_text);
		return $keywords_text;
	}
	
	function Title($website_title)
	{
		$this->TemplateHTML = 
		str_replace
		(
			"<site title/>",
			strip_tags(stripslashes($website_title)),
			$this->TemplateHTML
		);
	}
	
	function NoIndex()
	{
		$this->TemplateHTML = 
		str_replace
		(
			"</title>",
			"</title>\n<meta name=\"robots\" content=\"noindex, follow\">",
			$this->TemplateHTML
		);
	}
	
	function MetaDescription($meta_description)
	{
		$this->TemplateHTML = 
		str_replace
		(
			"<site description/>",
			strip_tags(stripslashes($meta_description)),
			$this->TemplateHTML
		);
	}
	
	function MetaKeywords($meta_keywords)
	{
		$this->TemplateHTML = 
		str_replace
		(
			"<site keywords/>",
			strip_tags(stripslashes($meta_keywords)),
			$this->TemplateHTML
		);
	}
	
	function GetParam($param_name)
	{
		switch($param_name)
		{
			
			case "ENABLE_TWITTER_LOGIN":
				return $this->params[141];
				break;
				
			case "TWITTER_KEY":
				return $this->params[142];
				break;
			
			case "TWITTER_SECRET":
				return $this->params[143];
			break;
			
			
			case "ENABLE_LINKEDIN_LOGIN":
				return $this->params[144];
				break;
				
			case "LINKEDIN_KEY":
				return $this->params[145];
				break;
			
			case "LINKEDIN_SECRET":
				return $this->params[146];
			break;
			
			
			case "FACEBOOK_PAGE_URL":
				return $this->params[94];
				break;
				
			case "TWITTER_PAGE_URL":
				return $this->params[95];
				break;
				
			case "GOOGLE_PAGE_URL":
				return $this->params[96];
				break;
				
				
			case "AUTHORIZE_ID":
				return $this->params[437];
				break;
				
			case "AUTHORIZE_ID":
				return $this->params[437];
				break;
				
			case "AUTHORIZE_KEY":
				return $this->params[438];
				break;
				
			case "PRICE_JOB":
				return $this->params[710];
				break;
				
			case "PRICE_FEATURED_JOB":
				return $this->params[711];
				break;
				
			case "PRICE_RESUME":
				return $this->params[712];
				break;
				
			case "ENABLE_FACEBOOK_LOGIN":
				return $this->params[134];
				break;
				
			case "FACEBOOK_KEY":
				return $this->params[135];
				break;
			
			case "FACEBOOK_SECRET":
				return $this->params[136];
			break;
			
			case "EXPIRE_DAYS":
				return $this->params[97];
				break;
				
			case "ADS_EXPIRE":
				return $this->params[97];
				break;
				
			case "FEATURED_ADS_EXPIRE":
				return 30;
				//return $this->params[97];
				break;	
				
				
			case "MAXIMUM_NUMBER_IMAGES":
				return $this->params[98];
				break;
			
			case "SEO_URLS":
				return $this->params[99];
				break;
				
			case "AUTO_APPROVE":
				return $this->params[101];
				break;
				
			case "SYSTEM_EMAIL_ADDRESS":
				return $this->params[102];
				break;
				
			case "SYSTEM_EMAIL_FROM":
				return $this->params[103];
				break;
				
			case "SEND_WELCOME_EMAIL":
				return $this->params[104];
				break;
			
			case "WELCOME_EMAIL_SUBJECT":
				return $this->params[105];
				break;
				
			case "WELCOME_EMAIL_TEXT":
				return $this->params[106];
				break;
			
			case "RESULTS_PER_PAGE":
			
				return $this->params[107];
				break;
				
		
				
			case "NUMBER_OF_FEATURED_LISTINGS":
				return $this->params[109];
				break;
			
			case "DEC_POINT":
				return $this->params[110];
				break;
				
			case "THOUSANDS_SEP":
				return $this->params[111];
				break;
				
			case "WEBSITE_CURRENCY":
				return $this->params[112];
				break;
				
			case "CURRENCY":
				return $this->params[112];
				break;
				
			case "CURRENCY_CODE":
				return $this->params[113];
				break;
				
			case "USE_CAPTCHA_IMAGES":
		
				return $this->params[114];
				break;
				
			case "TIMEZONE":
				return $this->params[115];
				break;
				
			case "PAYPAL_ID":
				return $this->params[116];
				break;

			case "2CHECKOUT_ID":
				return $this->params[117];
				break;	
				
			case "CHEQUES_ADDRESS":
				return $this->params[118];
				break;
				
			case "BANK_ACCOUNT":
				return $this->params[119];
				break;
				
			case "AMAZON_ID":
				return $this->params[120];
				break;
				
			case "AMAZON_ACCESS_KEY":
				return $this->params[121];
				break;
				
			case "AMAZON_SECRET_KEY":
				return $this->params[122];
				break;
				
			case "PAYFAST_ID":
				return $this->params[123];
				break;
				
			case "INTERKASSA_ID":
				return $this->params[124];
				break;
				
			case "GOOGLE_CHECKOUT_ID":
				return $this->params[125];
				break;
				
			case "GOOGLE_CHECKOUT_KEY":
				return $this->params[126];
				break;
				
			case "MONEYBOOKERS_ID":
				return $this->params[127];
				break;
				
			case "PAYMATE_ID":
				return $this->params[128];
				break;
				
			case "SEO_APPEND_TITLE":
				return $this->params[129];
				break;
				
			case "SEO_APPEND_DESCRIPTION":
				return $this->params[130];
				break;
				
			case "SEO_APPEND_KEYWORDS":
				return $this->params[131];
				break;
				
			case "DATE_FORMAT":
				
				return $this->params[132];
				break;
				
			case "DATE_HOUR_FORMAT":
				
				return $this->params[108];
				break;
				
			case "SHOW_LISTINGS_NUMBER":
			
				return $this->params[133];
				break;
				
			case "VERIFY_EMAIL":
				return $this->params[134];
				break;
				
			case "VERIFY_EMAIL_SUBJECT":
				return $this->params[135];
				break;
				
			case "VERIFY_EMAIL_MESSAGE":
				return $this->params[136];
				break;
			
			case "ENABLE_EMAIL_ALERTS":
				return $this->params[137];
				break;
				
			case "ENABLE_FB_LOGIN":
				return $this->params[138];
				break;
				
			case "FB_APP_ID":
				return $this->params[139];
				break;
				
			case "FB_APP_SECRET":
				return $this->params[140];
				break;
				
		
				
			case "PAGE_SIZE":
				return 20;
				break;
				
			case "NUMBER_OF_CATEGORIES_PER_ROW":
				return 3;
				break;
				
			
			case "USE_GD":
				return true;
				break;
				
			case "DEFAULT_THUMBNAIL_WIDTH":
				return "100";
				break;
				
			case "LIMIT_THUMBNAIL_HEIGHT":
				return "100";
				break;
		
				
			case "ENABLE_EMAIL_NOTIFICATIONS":
				return true;
				break;
				
		
			case "CHEQUE_INFO":
				return $this->params[118];
				break;
				
			case "BANK_INFO":
				return $this->params[119];
				break;
				
		
				
			case "JOBSEEKER_FIELDS":
				return $this->params[260];
				break;
				
			case "EMPLOYER_FIELDS":
				return $this->params[270];
				break;
				
			case "JOBSEEKER_ACTIVATION_TEXT":
				return $this->params[812];
				break;
				
			case "JOBSEEKER_ACTIVATION_SUBJECT":
				return $this->params[813];
				break;
				
			case "CHARGE_THE_JOBSEEKERS":
				return false;
				break;
				
		
			case "PRICE_LISTING_CREDITS":
				return 1;
				break;
				
			case "ENABLE_ZIP_SEARCH":
				return $this->params[411];
				break;
				
			case "ASK_FOR_ZIP":
				return $this->params[410];
				break;
			
			case "ENABLE_INDEED_BACKFILL":
				return false;
				break;	

			case "ALLOW_GUEST_APPLY":
				return true;
				break;	
				
			case "CHARGE_TYPE":
				return $this->params[899];
				break;				
			
			case "NEW_USERS_EMAIL_VALIDATION_ON_SIGNUP":
				return false;
				break;
				
			case "ENABLE_AUTHORIZE_NET_AIM_PAYMENTS":
				return false;
				break;
				
			case "FREE_WEBSITE_ADS_EXPIRE_DAYS":
				return 120;
				break;
				
			case "MAX_FILE_SIZE":
				return 20000000;
				break;
				
			case "INDEED_PUBLISHER_ID":
				return $this->params[607];
				break;
				
			case "FEED_DEFAULT_COUNTRY":
				return $this->params[608];
				break;
				
			case "INDEED_DEFAULT_COUNTRIES":
				return $this->params[609];
				break;
				
			case "SIMPLYHIRED_PUBLISHER_ID":
				return $this->params[610];
				break;
				
				
				
			case "SIMPLYHIRED_API_DOMAIN":
				return $this->params[611];
				break;	

			case "CAREERJET_AFF_ID":
				return $this->params[612];
				break;	
				
			case "TEXT_EMPTY_QUERIES":
				return $this->params[613];
				break;	

			case "NUMBER_JOBS_HOME_PAGE":
				return 10;
				break;					
				
			case "FEEDS_USAGE":
				return $this->params[600];
				break;
				
			case "MAIN_FEED":
				return $this->params[601];
				break;
				
			case "ADD_FEED_1":
				return $this->params[602];
				break;
				
			case "ADD_FEED_2":
				return $this->params[603];
				break;
				
			case "MAIN_FEED_WEIGHT":
				return $this->params[604];
				break;
				
			case "ADD_FEED_1_WEIGHT":
				return $this->params[605];
				break;
				
			case "ADD_FEED_2_WEIGHT":
				return $this->params[606];
				break;
			
			case "ANIMATION_SPEED":
				return $this->params[169];
				break;
				
			case "SLIDER_CONTENT":
				return $this->params[170];
				break;
				
			case "SLIDER_TYPE":
				return $this->params[171];
				break;
				
			case "ACCEPTED_FILE_TYPES":
				$file_types = Array 
				(
						array("application/msword","doc"),
						array("application/pdf","pdf"),
						array("text/plain","txt"),
						array("text/rtf","rtf"),
						array("application/rtf","rtf"),
						array("application/octet-stream","docx"),
						array("application/vnd.openxmlformats-officedocument.wordprocessingml.document","docx"),
						array("application/vnd.oasis.opendocument.text","odt")
				);
				return $file_types;
				break;
			
			
			case "arrStudyModes":
			
				return $this->returnArrayValues($this->params[901]);
				
				break;
				
				
			case "arrJobTypes":
				
				return $this->returnArrayValues($this->params[900]);
				
				break;
				
			case "arrExperienceLevels":
					return $this->returnArrayValues($this->params[905]);
				break;
				
				
				case "arrAvailabilityTypes":
					return $this->returnArrayValues($this->params[906]);
				break;	
				
				
				case "arrResumeLanguages":
					return $this->returnArrayValues($this->params[902]);
				break;
				
				case "arrProficiencies":
					return $this->returnArrayValues($this->params[903]);
				break;
				
				
				case "arrEducationLevels":
					return $this->returnArrayValues($this->params[904]);
				break;
	
	
				

		}
	}
	
	
	
	
	function show_full_location($location) 
	{ 
   
	  $str_result=""; 
	  
	  if(file_exists("locations/locations_array.php"))
	  {
		include("locations/locations_array.php"); 
	  }
	  else
		{
		include("../locations/locations_array.php"); 
	  }
	 
	  $location=str_replace("~",".",$location); 
	  $arr_digits= explode(".",$location); 
	
	  while(sizeof($arr_digits) >= 1) 
	  { 
	   
	   if(sizeof($arr_digits)==1) 
	   {
//		   die("-".$loc[$arr_digits[0]]."-");


			if(isset($loc[$arr_digits[0]])) $str_result = $loc[$arr_digits[0]].$str_result; 
	   } 
	   elseif(sizeof($arr_digits)==2) 
	   { 
			if(isset($loc1[$arr_digits[0]][$arr_digits[1]] )) $str_result = ", ".$loc1[$arr_digits[0]][$arr_digits[1]].$str_result; 
	   } 
	   elseif(sizeof($arr_digits)==3) 
	   { 
			if(isset($loc2[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]]))  $str_result = ", ". $loc2[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]].$str_result; 
	   } 
	   elseif(sizeof($arr_digits)==4) 
	   { 
			if(isset($loc3[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]][$arr_digits[3]]))  $str_result = ", ". $loc3[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]][$arr_digits[3]].$str_result; 
	   } 
		
	   array_pop($arr_digits); 
	   
	  } 
	   
	  $str_result_items = explode(",",$str_result); 
	   
	  $str_result_items = array_reverse($str_result_items); 
	  return implode(", ",$str_result_items); 
	   
	} 



	function show_location($location)
	{
		global $loc,$loc1,$loc2,$loc3,$loc4;
		
		if(!isset($loc))
		{
			if(file_exists("locations/locations_array.php")) include("locations/locations_array.php");
			elseif(file_exists("../locations/locations_array.php")) include("../locations/locations_array.php");
			else return "";
		}

		$arr_digits= explode(".",$location);
		
		if(sizeof($arr_digits)==1)
		{
			if(isset($loc[$arr_digits[0]])) return $loc[$arr_digits[0]];else return "";
		}
		elseif(sizeof($arr_digits)==2)
		{
			if(isset($loc1[$arr_digits[0]][$arr_digits[1]] )) return $loc1[$arr_digits[0]][$arr_digits[1]];else return "";
		}
		elseif(sizeof($arr_digits)==3)
		{
			if(isset($loc2[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]])) return $loc2[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]];else return "";
		}
		elseif(sizeof($arr_digits)==4)
		{
			if(isset($loc3[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]][$arr_digits[3]])) return $loc3[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]][$arr_digits[3]];else return "";
		}
		else
		{
			return "";
		}
	}
	
	
	function show_pic($pic_id,$display_mode="small",$alt_text="",$img_class="", $path = "")
	{
		$pic_id = preg_replace('/^0,/', '', $pic_id);
		$result="";
		if($display_mode=="small"||$display_mode=="small-x")
		{
			$images=explode(",",$pic_id);
			if($pic_id==""||!file_exists($path.'thumbnails/'.$images[0].'.jpg'))
			{
				$result = '<img src="'.($path!=""?$path:$this->get_file_prefix()).'images/no_pic.gif" width="'.($display_mode=="small-x"?"150":"100").'" '.($img_class!=""?'class="'.$img_class.'"':'').'/>';
		
			}
			else
			{
				$result = '<img src="'.($path!=""?$path:$this->get_file_prefix()).'thumbnails/'.$images[0].'.jpg" class="thumb-image" width="'.($display_mode=="small-x"?"150":"150").'" alt="'.$alt_text.'" '.($img_class!=""?'class="'.$img_class.'"':'').'/>';
			}
		}
		else
		{
			if($pic_id=="")
			{
				$result = '<img src="'.($path!=""?$path:$this->get_file_prefix()).'images/no_pic.gif" width="300" height="250" '.($img_class!=""?'class="'.$img_class.'"':'').' alt="no picture available"/>';
		
			}
			else
			{
				$images=explode(",",$pic_id);
				if(file_exists($path.'uploaded_images/'.$images[0].'.jpg'))
				{
					$result = '<img src="'.($path!=""?$path:$this->get_file_prefix()).'uploaded_images/'.$images[0].'.jpg" width="300" '.($img_class!=""?'class="'.$img_class.' img-responsive"':'class="img-responsive"').' alt="'.$alt_text.'"/>';
				}
		
			}
		}
		
		return $result;
	}
                
 	function show_category($category)
	{
		global $l,$l1,$l2,$l3,$l4;
		
		if(!isset($l))
		{
			if(file_exists("categories/categories_array_".$this->lang.".php")) include("categories/categories_array_".$this->lang.".php");
			elseif(file_exists("../categories/categories_array_".$this->lang.".php")) include("../categories/categories_array_".$this->lang.".php");
			else return "";
		}

		$arr_digits= explode(".",$category);
		
		if(sizeof($arr_digits)==1)
		{
			if(isset($l[$arr_digits[0]])) return $l[$arr_digits[0]];else return "";
		}
		elseif(sizeof($arr_digits)==2)
		{
			if(isset($l1[$arr_digits[0]][$arr_digits[1]] )) return $l1[$arr_digits[0]][$arr_digits[1]];else return "";
		}
		elseif(sizeof($arr_digits)==3)
		{
			if(isset($l2[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]])) return $l2[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]];else return "";
		}
		elseif(sizeof($arr_digits)==4)
		{
			if(isset($l3[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]][$arr_digits[3]])) return $l3[$arr_digits[0]][$arr_digits[1]][$arr_digits[2]][$arr_digits[3]];else return "";
		}
		else
		{
			return "";
		}
	}
	
	function text_words($string, $wordsreturned)
	{
		  $retval = $string;    
		$array = explode(" ", trim($string));
	  
		   if (count($array)<=$wordsreturned)
		{
			$retval = $string;
		}
		else
		{
			array_splice($array, $wordsreturned);
			$retval = implode(" ", $array)." ...";
		}
			return $retval;
	 }
 
	function current_url() 
	{
		$pageURL = 'http://';
		
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		
		return $pageURL;
	}
	
	
	function returnArrayValues($str_value)
	{
		
		$items=explode(";",stripslashes($str_value));
		$result=array();
		foreach($items as $item)
		{
			if(trim($item)=="") continue;
			
			$vals=explode("-",$item,2);
			
			if(sizeof($vals)!=2) continue;
			
			if(isset($GLOBALS[$vals[1]]))
			{
				$result[$vals[0]]=$GLOBALS[$vals[1]];
			}
			else
			{
				$result[$vals[0]]=$vals[1];
			}
		}
		
		return $result;
	}
	
	function create_cat_link($key,$arr_categories,$add_html=true)
	{
		
		$key_items=explode(".",$key);
		
		$result="";
		
	
		for($i=sizeof($key_items)-1;$i>=0;$i--)
		{
			$c_items =  array_slice($key_items, 0, sizeof($key_items)-$i);
				
			$c_key = implode(".",$c_items);
			
			if($result!="") $result.="/";
			
			$str_cat_name=strtolower(trim($arr_categories[$c_key]));
			$arrSigns = array("~", "!", "@","#", "$", "%", "^", "&", "*", "(", ")", "+", "-", ",",".","/", "?", ":","<",">","[","]","{","}","|"); 
		
			$str_cat_name = str_replace($arrSigns, "", $str_cat_name); 
			$str_cat_name = str_replace("\t"," ",$str_cat_name);
			$str_cat_name = str_replace("  "," ",$str_cat_name);
			$str_cat_name = str_replace(" ","-",$str_cat_name);
			
			$result.=$str_cat_name;
			
		}
		
		if($add_html)
		{
			$result.="/";
		}
		
		return $result;
	}
	
	
	
	function find_cat_id($category,$arr_categories)
	{
		foreach($arr_categories as $key=>$value)
		{
			$str_c_link = $this->create_cat_link($key,$arr_categories,false);
			
			if(trim($category)==trim($str_c_link))
			{
				return $key;
			}
			
		}
		return 0;
	}
	
	
	function show_stars($vote,$class="")
	{
	
		//$result = "<div ".($class!=""?"class=\"".$class."\"":"").">";
	
	$result="";
		for($x=0;$x<floor($vote);$x++)
		{
			$result .= "<img src=\"".$this->get_file_prefix()."images/full-star.gif\" width=\"13\" height=\"12\" alt=\"\"/>";
		}
		
		for($c=0;$c<ceil(fmod($vote, 1) );$c++)
		{
			$result .= "<img src=\"".$this->get_file_prefix()."images/half-star.gif\" width=\"13\" height=\"12\" alt=\"\"/>";
		}
		
		for($v=($c+$x);$v<5;$v++)
		{
			$result .= "<img src=\"".$this->get_file_prefix()."images/empty-star.gif\" width=\"13\" height=\"12\" alt=\"\"/>";
		}

		//$result .= "</div>";
		
		return $result;
	}
	
	
	function sanitize($input)
	{
		$strip_chars = array("~", "`", "!","#", "$", "%", "^", "&", "*", "(", ")", "=", "+", "[", "{", "]",
                 "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                 ",", "<", ">", "/", "?");
		$output = trim(str_replace($strip_chars, " ", strip_tags($input)));
		$output = preg_replace('/\s+/', ' ',$output);
		$output = preg_replace('/\-+/', '-',$output);
		return $output;
	}
	
	
	
	
	function UpdateListingRating($id)
	{
		global $database,$DBprefix;
		$this->ms_i($id);
		$database->Query("
		UPDATE ".$DBprefix."products
		SET rating=(
		 SELECT AVG(vote)
		 FROM ".$DBprefix."comments
		 WHERE product_id=".$id."
		)
		WHERE id=".$id);
	}
	
	function UpdateVendorRating($id)
	{
		global $database,$DBprefix;
		$this->ms_i($id);
		$database->Query("
		UPDATE ".$DBprefix."vendors
		SET rating=(
		 SELECT AVG(vote)
		 FROM ".$DBprefix."vendor_reviews
		 WHERE vendor_id=".$id."
		),number_reviews=(
		 SELECT COUNT(id)
		 FROM ".$DBprefix."vendor_reviews
		 WHERE vendor_id=".$id."
		)
		WHERE id=".$id);
	}
	
	
	function format_url($path)
	{
		global $DOMAIN_NAME,$MULTI_LANGUAGE_SITE;
		
		
		if($this->GetParam("SEO_URLS")==0)
		{
			if($MULTI_LANGUAGE_SITE)
			{
				$path .= "&lang=".$this->lang;
			}
			
			return $path;
		}
		else
		{
			$path=str_replace("index.php?","",$path);
			$path=str_replace("=","-",$path);
			
			if($MULTI_LANGUAGE_SITE)
			{
				$path = $this->lang."-".$path;
			}
			
			
			
			return $path.".html";
		
		}
	
	}
	
	
	function job_feed_link($job_id,$job_title,$type,$url)
	{
		global $M_SEO_FEED_JOB,$MULTI_LANGUAGE_SITE;
		
		$result = "";
		
		if($type=="indeed")
		{
			if($this->GetParam("SEO_URLS")==1)
			{
				$result =$M_SEO_FEED_JOB."-".$job_id."-".$this->format_str($job_title).".html";
			}
			else
			{
				$result = "index.php?mod=feed_details&id=".$job_id.($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
			}
		}
		else
		{
			$result = $url;
		}
	
		return $result;
	}
	
	function job_link($job_id,$job_title,$seo_lang="",$seo_text="")
	{
		
		global $M_SEO_JOB,$MULTI_LANGUAGE_SITE;
		
			
		$result = "";
		
		if($this->GetParam("SEO_URLS")==1)
		{
			$result =($seo_text!=""?$seo_text:$M_SEO_JOB)."-".$this->format_str($job_title)."-".$job_id.".html";
		}
		else
		{
			$result = "index.php?mod=details&id=".$job_id.($MULTI_LANGUAGE_SITE?"&lang=".($seo_lang!=""?$seo_lang:$this->lang):"");
		}
	
		return $result;
	}
	
	function course_link($course_id,$course_title)
	{
		global $M_SEO_COURSE,$MULTI_LANGUAGE_SITE;
		
		$result = "";
		
		if($this->GetParam("SEO_URLS")==1)
		{
			$result =$M_SEO_COURSE."-".$this->format_str($course_title)."-".$course_id.".html";
		}
		else
		{
			$result = "index.php?mod=course_details&id=".$course_id.($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
		}
		
		return $result;
	}
	
	
	function news_link($news_id,$news_title)
	{
		global $M_SEO_NEWS,$MULTI_LANGUAGE_SITE;
		
		$result = "";
		
		if($this->GetParam("SEO_URLS")==1)
		{
			$result =$M_SEO_NEWS."-".$news_id."-".$this->format_str($news_title).".html";
		}
		else
		{
			$result = "index.php?mod=news&id=".$news_id.($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
		}
	
		return $result;
	}
	
	function course_category_link($category_id,$category_name,$seo_lang="",$seo_name="")
	{
		global $MULTI_LANGUAGE_SITE,$M_SEO_COURSE_CATEGORY;
		
		$result = "";
		$category_id=str_replace(".","-",$category_id);
		if($this->GetParam("SEO_URLS")==1)
		{
			$result =($seo_name!=""?$seo_name:$M_SEO_COURSE_CATEGORY)."-".$this->format_str($category_name)."-".$category_id.".html";
		}
		else
		{
			$result = "index.php?mod=courses&category=".$category_id.($MULTI_LANGUAGE_SITE?"&lang=".($seo_lang!=""?$seo_lang:$this->lang):"");
		}
	
		return $result;
	}
	
	function category_link($category_id,$category_name,$seo_lang="",$seo_name="")
	{
		global $MULTI_LANGUAGE_SITE,$M_SEO_CATEGORY;
		
		$result = "";
		$category_id=str_replace(".","-",$category_id);
		if($this->GetParam("SEO_URLS")==1)
		{
			$result =($seo_name!=""?$seo_name:$M_SEO_CATEGORY)."-".$this->format_str($category_name)."-".$category_id.".html";
		}
		else
		{
			$result = "index.php?mod=search&category=".$category_id.($MULTI_LANGUAGE_SITE?"&lang=".($seo_lang!=""?$seo_lang:$this->lang):"");
		}
	
		return $result;
	}
	
	function location_link($location_id,$location_name)
	{
		global $MULTI_LANGUAGE_SITE,$M_SEO_LOCATION;
		
		$result = "";
		$location_id=str_replace(".","-",$location_id);
		if($this->GetParam("SEO_URLS")==1)
		{
			$result =$M_SEO_LOCATION."-".$this->format_str($location_name)."-".$location_id.".html";
		}
		else
		{
			$result = "index.php?mod=search&location=".$location_id.($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
		}
	
		return $result;
	}
	
	
	
	function company_link($company_id,$company_name)
	{
		global $M_SEO_COMPANY,$MULTI_LANGUAGE_SITE;
		
		$result = "";
		if($this->GetParam("SEO_URLS")==1)
		{
			$result =$M_SEO_COMPANY."-".$this->format_str($company_name)."-".$company_id.".html";
		}
		else
		{
			$result = "index.php?mod=company&id=".$company_id.($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
		}
	
		return $result;
	}
	
	function company_reviews_link($company_id,$company_name)
	{
		global $M_SEO_REVIEWS,$MULTI_LANGUAGE_SITE;
		
		$result = "";
		if($this->GetParam("SEO_URLS")==1)
		{
			$result =$M_SEO_REVIEWS."-".$this->format_str($company_name)."-".$company_id.".html";
		}
		else
		{
			$result = "index.php?mod=reviews&id=".$company_id.($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
		}
	
		return $result;
	}
	
	function company_jobs_link($company_id,$company_name)
	{
		global $M_SEO_JOBS,$MULTI_LANGUAGE_SITE;
		
		$result = "";
		if($this->GetParam("SEO_URLS")==1)
		{
			$result =$M_SEO_JOBS."-".$this->format_str($company_name)."-".$company_id.".html";
		}
		else
		{
			$result = "index.php?mod=search&company=".$company_id.($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
		}
	
		return $result;
	}
	
	function get_file_prefix()
	{
		
		$result = "";
		
		if
		(
			$this->GetParam("SEO_URLS")==1
		
		)
		{
			$result ="http://".$this->domain."/";
		}
		
		return $result;
		
	}
	
	
	function job_type($type_id)
	{
		$job_types=$this->GetParam("arrJobTypes");
		
		if(isset($job_types[$type_id]))
		{
			return $job_types[$type_id];
		}
		
		return "";
	}
	
	function mod_link($mod)
	{
		global $DOMAIN_NAME,$MULTI_LANGUAGE_SITE;
		
		if($this->GetParam("SEO_URLS")==0)
		{
			if($mod=="featured-jobs")
			{
				return "index.php?mod=search&featured=1".($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
			}
			else
			if($mod=="latest-jobs")
			{
				return "index.php?mod=search&latest=1".($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
			}
			else
			{
				return "index.php?mod=".$mod.($MULTI_LANGUAGE_SITE?"&lang=".$this->lang:"");
			}
		}
		else
		{
			if($MULTI_LANGUAGE_SITE)
			{
				return "mod-".$this->lang."-".$mod.".html";
			}
			else
			{
				return "mod-".$mod.".html";
			}
		}
	
	}
	
	
		
	function show_value($type,$value)
	{
		$arrValues=$this->GetParam($type);
		if(isset($arrValues[$value]))
		{
			return $arrValues[$value];
		}
		else
		{
			return "";
		}
	}
	
	function hex2rgb($hex) 
	{
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
		return implode(",", $rgb); 
	}
	
	function get_image_file($type,$id)
	{
		if(file_exists($type."/".$id.".jpg"))
		{
			return $type."/".$id.".jpg";
		}
		else
		if(file_exists($type."/".$id.".png"))
		{
			return $type."/".$id.".png";
		}
		else
		if(file_exists($type."/".$id.".gif"))
		{
			return $type."/".$id.".gif";
		}
		
		return $type."/1.jpg";
	}
        
        /**
        * Strip Vietnamese texts to Latin texts
        *
        */
        
        function stripVN($str) {
            $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
            $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
            $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
            $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
            $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
            $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
            $str = preg_replace("/(đ)/", 'd', $str);

            $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
            $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
            $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
            $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
            $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
            $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
            $str = preg_replace("/(Đ)/", 'D', $str);
            return $str;
        }
        
        
        /**
        * Convert strings to SEO friendly
        *
        */
        function seoUrl($string) {
            //Lower case everything
            $string = strtolower($string);
            //Make alphanumeric (removes all other characters)
            $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
            //Clean up multiple dashes or whitespaces
            $string = preg_replace("/[\s-]+/", " ", $string);
            //Convert whitespaces and underscore to dash
            $string = preg_replace("/[\s_]/", "-", $string);
            return $string;
        }
	
}	
?>