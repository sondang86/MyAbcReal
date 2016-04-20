<?php
// Jobs Portal
// http://www.netartmedia.net/jobsportal
// Copyright (c) All Rights Reserved NetArt Media
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
session_start();

//Remove cookie (this one is bullshit, will remove it ASAP)
setcookie("AuthE","",time()-1);
setcookie("AuthJ","",time()-1);

// Finally, destroy the session.
session_destroy();

echo "<script>document.location.href='index.php".(isset($_REQUEST["lang"])?"?lang=".$_REQUEST["lang"]:"")."';</script>";

?>