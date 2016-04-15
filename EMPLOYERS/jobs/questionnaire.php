<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
?>

<script>
    /*Add & Remove questionnaire*/
    $(function() {
        var scntDiv = $('#answerPoll');
        var i = $('#answerPollArea p').size() + 1;        
        $(document).on('click','#addMore', function(e) {
            $('<p><span><input type="text" size="20" name="answerPoll[]"/></span><span><a href="#" id="remScnt">Remove</a></span></p>').appendTo(scntDiv).fadeIn('slow');
            i++;
            if (i > 5) {
                $("#addMore").hide();
            } 
            return false;
        });
        
        /*Remove questionnaire*/
        $(document).on('click', '#remScnt', function() { 
            if( i > 1 ) {
                $(this).parents('p').remove();
                i--;
            };
            
            //Show 
            if (i < 6) {
                $("#addMore").show();
            }
            return false;
        });        
        
    });
    
    /*questionnaire selection*/
    $(document).ready(function(){
        $(document).on('change',"#pollSelection",function() {
            /*Show or hide option based on selection*/
            if($("#pollSelection").val() === '1') {
                $("#answerPollArea").show("fade", 500);
            } else {
                $("#answerPollArea").fadeOut();
            } 
          });
    });
    ;
</script>
<style>
   
    #answerPoll p span input {
        width: 80%;
        margin-right: 15px;
    }
</style>
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
        <select id="pollSelection">
            <option value="0">Text area</option>
            <option value="1">Poll</option>
        </select>
    </div>
</div>

<!--Text answer-->
<!--<div class="row answerTextArea" style="margin-top: 20px;">
    <div class="col-md-1">Câu trả lời: </div>
    <div class="col-md-11"><textarea style="width: 70%; min-height: 400px;"></textarea></div>
</div>-->

<!--Poll answers-->
<div class="row answerPollArea" id="answerPollArea" style="margin-top: 20px; display: none;">
    <h5 class="col-md-1">Câu trả lời: </h5>
    <section class="col-md-6 answerPoll" id="answerPoll">        
        <p>
            <span><input type="text" name="answerPoll[]"></span>
            <span><button id="addMore">Add more +</button></span>
        </p>        
    </section>  
</div>


