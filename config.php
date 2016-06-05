<?php
$DOMAIN_NAME = "localhost/vieclambanthoigian.com.vn";
$FULL_DOMAIN_NAME = "http://localhost/vieclambanthoigian.com.vn";
$EMPLOYER_DOMAIN_NAME = "http://$DOMAIN_NAME/EMPLOYERS/";
$JOBSEEKER_DOMAIN_NAME = "http://$DOMAIN_NAME/JOBSEEKERS/";

//Path define
define('DIR_BASE',          dirname( dirname( __FILE__ ) ) . '/vieclambanthoigian.com.vn/');
define('DIR_EMPLOYERS',     DIR_BASE . 'EMPLOYERS/');
define('DIR_JOBSEEKERS',    DIR_BASE . 'JOBSEEKERS/');
define('DIR_ADMIN',         DIR_BASE . 'ADMIN/');

//MYSQL DATABASE ACCESS SETTINGS
$DBHost="localhost";
$DBUser="root";
$DBPass="";
$DBName="viea83fe_vieclambanthoigian";
$DBprefix="jobsportal_";

$DEBUG_MODE=true;
$MULTI_LANGUAGE_SITE = true;
$AdminPanelLanguages=
array
(
	array("English","en")
);
?>