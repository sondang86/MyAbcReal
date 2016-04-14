<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
?>
<div class="fright">
    <?php echo LinkTile ("jobs","my",$M_GO_BACK,"","red");?>    
</div>
<div class="clear"></div>

<h3 class="no-top-margin"><?php echo $M_ADD_NEW_QUESTION;?></h3>
<br/>
<div class="row">
    <div class="col-md-1">Câu hỏi: </div>
    <div class="col-md-6"><input type="text" style="width: 100%;"></div>
    <div class="col-md-3">
        <select>
            <option>Text area</option>
            <option>poll</option>
        </select>
    </div>
</div>

<div class="row answerTextArea" style="margin-top: 20px;">
    <div class="col-md-1">Câu trả lời: </div>
    <div class="col-md-6"><textarea style="width: 100%; min-height: 400px;"></textarea></div>
</div>

<div class="row answerPollArea" style="margin-top: 20px;">
    <div class="col-md-1">Câu trả lời: </div>
    <div class="col-md-6"><input type="text" name="answerPoll[]" style="width: 50%;"> <button>Add more +</button></div>    
</div>

