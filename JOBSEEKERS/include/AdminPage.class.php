<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
?><?php
class AdminPage
{
	
	var $templateHTML="";
	var $pageHTML="";
	public $page;
	
	var $arrElements=array("title","menu","content","keywords","description");
	var $arrElementsHTML=array();
	var $templateID=0;
	var $arrPage;
	
	function AdminPage($page_name="")
	{
		$this->page = $page_name;
		
	}
	
	
	function Process($is_mobile=false)
	{
		global $is_mobile;
		global $DOMAIN_NAME,$lang,$AdminUser,$arrUser,$LoginInfo,$currentUser;

		
		include("../include/texts_".$lang.".php");
		include("../ADMIN/texts_".$lang.".php");
		include("pages_structure.php");
		include("wysiwyg/detect_browser.php");
		
		global $AuthUserName,$lang,$website,$database, $_REQUEST, $DBprefix;
		$iKEY = "AZ8007";
		$DN=2;
		$this->pageHTML=$website->TemplateHTML;
		include("include/check_php_version.php");
		
		if(isset($_REQUEST["category"]))
		{
			$category = $_REQUEST["category"];
		}
		else
		{
			$category = "home";
		}
		$website->ms_w($category);
		
		$HTML="";
		ob_start();
		if(isset($_REQUEST["folder"])&&isset($_REQUEST["page"]))
		{
			$folder = $_REQUEST["folder"];
			$page = $_REQUEST["page"];
			$website->ms_w($folder);
			$website->ms_w($page);
			
			if(file_exists($category."/".$folder."_".$page.".php"))
			{
				include($category."/".$folder."_".$page.".php");
			}
		}
		else
		{
			if(isset($_REQUEST["action"]))
			{
				$action = $_REQUEST["action"];
			}
			else
			{
				$action = "welcome";
			}
		
			$website->ms_w($action);

			if(file_exists($category."/".$action.".php"))
			{
				include($category."/".$action.".php");
			}
			else
			{
				
			}
		}
		
		if($HTML=="")
		{
			$HTML = ob_get_contents();
		}
		ob_end_clean();

		if($is_mobile)
		{
			$mobile_start = strpos($HTML, '<!--mobile-start-->');
			$mobile_end   = strpos($HTML, '<!--mobile-end-->', $mobile_start);
			
			if($mobile_start !== false && $mobile_end !== false) 
			{
				$HTML   = substr($HTML, $mobile_start, ($mobile_end - $mobile_start));
			}
		}

		$logo_html ="";
		
			$main_admin = $database->DataArray("admin_users","id=1");
		if
		(
			$main_admin["logo"]!=""
			&&
			file_exists("../thumbnails/".$main_admin["logo"].".jpg")
		)
		{
			$logo_html .= '<a href="index.php">';
			$logo_html .= '<img src="../thumbnails/'.$main_admin["logo"].'.jpg" class="img-responsive"/>';
			$logo_html .= '</a>';
		}
		
		else
		if
		(
			$main_admin["logo_text"]!=""
		)
		{
			$logo_html .= '<a class="navbar-brand text-logo admin-user-text-logo custom-color" href="http://'.$DOMAIN_NAME.'/JOBSEEKERS/index.php">'.stripslashes($main_admin["logo_text"]).'</a>';
		}
		else
		{   //Default is vieclambanthoigian.com.vn logo if blank logo
                    $logo_html .= '<a href="http://'.$DOMAIN_NAME.'/JOBSEEKERS"><img src="../images/commons/jobs_portal_logo_demo.jpg" class="img-responsive site-logo"/></a>';
		}
		
		$this->pageHTML = str_replace("<site logo/>",$logo_html,$this->pageHTML);
		
		
		$this->pageHTML = str_replace("<site content/>",$HTML,$this->pageHTML);
		$this->pageHTML = str_replace("<site title/>","",$this->pageHTML);
		
		$login_links='
			<a class="btn btn-default btn-blue btn-sm" href="../logout.php">LOGOUT</a>
			<a class="btn btn-default btn-green btn-sm" href="../index.php">MAIN WEBSITE</a>
		';
		$this->pageHTML = str_replace("<site login_links/>",$login_links,$this->pageHTML);
			
			$this->pageHTML=
			str_replace
			(
				"<site languages_menu/>",
				$this->LanguagesMenu(),
				$this->pageHTML
			);
			
			$this->pageHTML=
			str_replace
			(
				"<site menu/>",
				$this->StartMenu(),
				$this->pageHTML
			);
			
			
		
		$website->TemplateHTML=$this->pageHTML;
		
		$str_page_link = "";
		if(isset($_REQUEST["folder"])&&isset($_REQUEST["page"]))
		{
			$str_page_link = "index.php?category=".$_REQUEST["category"]."&page=".$_REQUEST["page"]."&folder=".$_REQUEST["folder"]."&";
		}
		else
		if(isset($_REQUEST["category"])&&isset($_REQUEST["action"]))
		{
			$str_page_link = "index.php?category=".$_REQUEST["category"]."&action=".$_REQUEST["action"]."&";	
		}
		else
		{
			$str_page_link = "index.php?";
		}
		
		if($is_mobile)
		{
			$website->TemplateHTML = 
			str_replace("[MOBILE-LINK]",$str_page_link."switch_mobile=0",$website->TemplateHTML);
			include("include/help_tips.php");
		}
		else
		{
			$website->TemplateHTML = 
			str_replace("[MOBILE-LINK]",$str_page_link."switch_mobile=1",$website->TemplateHTML);
			include("include/help_tips.php");
		}
		
	}
	
	
	function CheckPermissions($category, $action)
	{
		global $currentUser;
		
		
		if
		(
			true||
			$currentUser->AuthGroup == "Administrators"
			|| $category == "exit"
		)
		{
			return true;
		}
		else
		if(array_search("@".$currentUser->AuthGroup."@".$category."@".$action, $currentUser->arrPermissions,false))
		{
			return true;
		}
		else
		if($category != "" && $action=="")
		{
			$vr2 = ($category."_oLinkActions");	
			global $$vr2;
			$evLinkActions = $$vr2;

			if(isset($evLinkActions))
			{
				foreach($evLinkActions as $evAction)
				{
					if(array_search("@".$currentUser->AuthGroup."@".$category."@".$evAction, $currentUser->arrPermissions,false))
					{
						return true;
					}
				}
			}
			
			return false;
		}
		else
		{
			return false;
		}
		
	}
	
	function GetHTML()
	{
		global $is_mobile;
		global $lang,$AdminUser,$arrUser,$LoginInfo,$currentUser;
		
		include("../include/texts_".$lang.".php");

		include("pages_structure.php");
		include("wysiwyg/detect_browser.php");
		
		global $AuthUserName, $lang,$website,$database, $_REQUEST, $DBprefix;
		$iKEY = "AZ8007";
		$DN=2;
		
		if(isset($_REQUEST["category"]))
		{
			$category = $_REQUEST["category"];
		}
		else
		{
			$category = "home";
		}
		$website->ms_w($category);
		
		$HTML="";
		ob_start();
		if(isset($_REQUEST["folder"])&&isset($_REQUEST["page"]))
		{
			$folder = $_REQUEST["folder"];
			$page = $_REQUEST["page"];
			$website->ms_w($folder);
			$website->ms_w($page);
			
			if(file_exists($category."/".$folder."_".$page.".php"))
			{
				include($category."/".$folder."_".$page.".php");
			}
		}
		else
		{
			if(isset($_REQUEST["action"]))
			{
				$action = $_REQUEST["action"];
			}
			else
			{
				$action = "welcome";
			}
		
			$website->ms_w($action);
			
			if($this->CheckPermissions($category,$action)
			&&file_exists($category."/".$action.".php"))
			{
				include($category."/".$action.".php");
			}
		}
		
		if($HTML=="")
		{
			$HTML = ob_get_contents();
		}
		ob_end_clean();

		return $HTML;
		
	}
	
	function StartMenu()
	{
		global $M_START,$M_ADD_ON,$lang,$DOMAIN_NAME;
		
		include("../ADMIN/texts_".$lang.".php");
		include("../include/texts_".$lang.".php");
		include("pages_structure.php");
		$menu_html = "";
		$bottom_menu = "";
		
		for($i=0;$i<count($oLinkTexts);$i++)
		{
			$vr1 = ($oLinkActions[$i]."_oLinkTexts");		
			$vr2 = ($oLinkActions[$i]."_oLinkActions");	
		
			$evSLinkTexts=$$vr1;
			$evSLinkActions=$$vr2;
			
			$bottom_menu.="\n<div class=\"col-md-3 no-right-padding\">";
			
			if($this->CheckPermissions($oLinkActions[$i], $evSLinkActions[0]))
			{
				if($oLinkActions[$i]=="home"&&$evSLinkActions[0]=="welcome")
				{
					$menu_html.="\n<li class=\"dropdown\"><a class=\"main-top-link\" href=\"http://$DOMAIN_NAME/JOBSEEKERS/index.php\">".$oLinkTexts[$i]."</a>";
					
				}
				else
				{
					$menu_html.="\n<li class=\"dropdown\"><a class=\"main-top-link\" href=\"http://$DOMAIN_NAME/JOBSEEKERS/index.php?category=".$oLinkActions[$i]."&action=".$evSLinkActions[0]."\">".$oLinkTexts[$i]."</a>";
					
				}
				$bottom_menu.="\n<h5 class=\"bottom-header\">".$oLinkTexts[$i]."</h5>";
			}
			
			if(sizeof($evSLinkTexts)>1)
			{
				$menu_html.="\n<ul class=\"text-left dropdown-menu\">";
		
				for($j=0;$j<count($evSLinkTexts);$j++)
				{
					
					if(!strstr($evSLinkTexts[$j],$M_ADD_ON))
					{
						if($this->CheckPermissions($oLinkActions[$i], $evSLinkActions[$j]))
						{
							$no_ajax = false;
							if(strpos($evSLinkActions[$j],"_")!==false||strpos($evSLinkActions[$j],"-")!==false)
							{
								$no_ajax = true;
							}
							
							if($evSLinkActions[$j] == "add"||$evSLinkActions[$j] == "new_user"||$evSLinkActions[$j] == "newsletter2")
							{
								$no_ajax = true;
							}
							
							if($oLinkActions[$i]=="home"&&$evSLinkActions[$j]=="welcome")
							{
							  $str_link = "index.php";
							
							}
							else
							{
								if($no_ajax||true)
								{
									$str_link = "index.php?category=".$oLinkActions[$i]."&action=".$evSLinkActions[$j];
								}
								else
								{
									$str_link = "#".$oLinkActions[$i]."-".$evSLinkActions[$j];
								}
							}
							$menu_html.="\n<li ondragstart=\"javascript:dragStart()\" id=\"".$oLinkActions[$i]."-".$evSLinkActions[$j]."\" class=\"menu-sub-link\"><a href=\"".$str_link."\">".$evSLinkTexts[$j]."</a></li>";
							
							
							$bottom_menu.="\n<a class=\"admin-bottom-link\" href=\"".$str_link."\">".$evSLinkTexts[$j]."</a>";
							
						}
					}
					else
					{
						if($this->CheckPermissions($evSLinkActions[$j], ""))
						{
							$menu_html.="\n<li class=\"menu-sub-link\"><a href=\"index.php?category=".$evSLinkActions[$j]."&action=home\">".$evSLinkTexts[$j]."</a></li>";
						}
					}
					
					
				}
				
				$menu_html.="\n</ul>";
			}
			
			$bottom_menu.="\n</div>";
			$menu_html.="\n</li>";
			
			if($i==0) $bottom_menu="";
		}
		
		$this->pageHTML=
		str_replace
		(
			"<site bottom_menu/>",
			$bottom_menu.'<div class="clear"></div>',
			$this->pageHTML
		);
			
		return $menu_html;
	
	}
	
	function LanguagesMenu()
	{
		global $currentUser,$lang,$AdminPanelLanguages,$_REQUEST,$language,$website;
		
		$language_menu_html = "";
		
		if(isset($_REQUEST["folder"])&&isset($_REQUEST["page"]))
		{
			$strPageLink="category=".$_REQUEST["category"]."&folder=".$_REQUEST["folder"]."&page=".$_REQUEST["page"]."&";
		}
		else
		if(isset($_REQUEST["category"])&&isset($_REQUEST["action"]))
		{
			$strPageLink="category=".$_REQUEST["category"]."&action=".$_REQUEST["action"]."&";
		}
		else
		{
			$strPageLink="";
		}
										
		foreach($AdminPanelLanguages as $arrLang)
		{
			list($languageName,$languageCode)=$arrLang;
			
			if($languageCode == $lang) continue;
			
			$language_menu_html .= "<a class=\"language-link\" href=\"index.php?".$strPageLink."lng=".$languageCode."\">".$languageName."</a> ";
			
		}
		
		global $M_MAIN_WEBSITE,$M_MY_STORE;
		
		$language_menu_html .= "<a class=\"underline-link language-link left-margin-20px\" href=\"../index.php\">".$M_MAIN_WEBSITE."</a> ";
			
		return $language_menu_html;
	}
	
	function MobileLink()
	{
	
	}
	
	function CreateFooterMenu()
	{
	
	}
	
}
?>