<?php
// Jobs Portal
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
//Get user saved jobs in the last 1 days
$saved_jobs = $commonQueries->getSavedJobs(1);
if ($saved_jobs !== FALSE):
 ?>

<a href="<?php echo $website->check_SEO_link("saved_jobs", $SEO_setting);?>" class="sub-text underline-link r-margin-15"><?php echo ($saved_jobs['totalCount']). " ".$M_SAVED_JOBS;?></a>

<?php endif;?>