<?php
require 'include/facebook/facebook.php';

$facebook = new Facebook
(
	array
	(
        'appId' => $website->GetParam("FACEBOOK_KEY"),
        'secret' => $website->GetParam("FACEBOOK_SECRET"),
    ));

$user = $facebook->getUser();

if($user) 
{
  try 
  {
    $user_profile = $facebook->api('/me');
  } 
  catch (FacebookApiException $e) 
  {
	
    $user = null;
  }

    if (!empty($user_profile)) 
	{
	
		
        $uid = $user_profile['id'];
		
		
				
		if($database->SQLCount("jobseekers","WHERE facebook_id=".$uid)==0)
		{
			$email="";
			if(isset($user_profile['email']))
			{
				$email=$user_profile['email'];
			}
			if(isset($_POST["user_email"]))
			{
				$email=$_POST["user_email"];
			}
		
			if($email==""||$database->SQLCount("employers","WHERE username='".$email."' ") > 0 || $database->SQLCount("jobseekers","WHERE username='".$email."' ") > 0)
			{
				?>
				<h3><?php
				if($database->SQLCount("employers","WHERE username='".$email."' ") > 0 || $database->SQLCount("jobseekers","WHERE username='".$email."' ") > 0)
				{
					echo $USER_EXISTS;
				}
				else
				{
					echo $M_JUST_CONFIRM_USERNAME;
				}
				?></h3>


				<form id="main" action="index.php" method="post">
				<input type="hidden" name="mod" value="login-facebook">
				<fieldset>
					<ol>
					<li>
		
						<label>
						<?php echo $EMAIL;?>: (*) 
						</label>
			
						<input type="email" required name="user_email" id="user_email" value=""/> 
				
					</li>
					</ol>
				</fieldset>
				<br/>
				<button type="submit" class="btn btn-primary pull-right"><?php echo $M_SUBMIT;?></button>
				<div class="clearfix"></div>	
				</form>
				<?php
				$do_not_show_submit=true;
			}
			else
			{
				$name = $user_profile['name'];
				
			
				$first_name="";
				$last_name="";
				
				if(isset($user_profile["first_name"]))
				{
					$first_name=$user_profile["first_name"];	
				}
				
				if(isset($user_profile["last_name"]))
				{
					$last_name=$user_profile["last_name"];	
				}
				
				if(isset($user_profile["name"]))
				{
					$name_items=explode(" ",$user_profile["name"],2);
					
					$first_name=$name_items[0];	
					
					if(isset($name_items[1]))
					{
						$last_name=$name_items[1];
					}
				}
				$username=$email;
				
				$arrChars = array("A","F","B","C","O","Q","W","E","R","T","Z","X","C","V","N");
			
				$password = $arrChars[rand(0,(sizeof($arrChars)-1))]."".rand(1000,9999)
				.$arrChars[rand(0,(sizeof($arrChars)-1))].rand(1000,9999);
			
				
				$database->SQLInsert
				(
					"jobseekers",
					array("facebook_id","active","date","username","password","first_name","last_name"),
					array($uid,"1",time(),$email,$password,$first_name,$last_name)
				
				);
			
			}
		}
		else
		{
		
			$arrUser=$database->DataArray("jobseekers","facebook_id=".$uid);
			
			$username=$arrUser["username"];
			$password=$arrUser["password"];
			
		}
		
		if(!isset($do_not_show_submit))
		{
		
		?>
			<form id="login_form" style="display:none" class="no-margin" action="loginaction.php" method="post">
			<input type="hidden" name="Email" value="<?php echo $username;?>"/>
			<?php
			if($MULTI_LANGUAGE_SITE)
			{
			?>
			<input type="hidden" name="lng" value="<?php echo $website->lang;?>"/>
			<?php
			}
			?>
			<input type="hidden" name="Password" value="<?php echo $password;?>"/>
			</form>
			<script>
			document.getElementById("login_form").submit();
			</script>

		<?php
		}		
    } 
	else 
	{
       
    }
} 
else 
{
	$login_url = $facebook->getLoginUrl
	(
		array
		( 
			'scope' => 'email',
			'next' => "http://".$DOMAIN_NAME."/index.php?mod=login-facebook&fb_l=1"			
		)
		
	);

	if(isset($_REQUEST["fb_l"]))
	{
		die("<script>document.location.href='index.php?mod=jobseekers';</script>");
	}
	else
	{
		die("<script>document.location.href='".$login_url."';</script>");
	}
	//die("<script>document.location.href='".$login_url."';</script>");
}


$website->Title($M_LOGIN);
$website->MetaDescription("");
$website->MetaKeywords("");
?>