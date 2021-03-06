<?php    
    global $db, $commonQueries, $FULL_DOMAIN_NAME;
    $job_id = filter_input(INPUT_GET,'job_id', FILTER_SANITIZE_NUMBER_INT);
    $job_info = $commonQueries->jobDetails($job_id);
    
    //Redirect back to questionnaire menu if user already has limit 3 questions
    $questions_count = $commonQueries->countRecords('job_id', $job_id, 'questionnaire'); 
    if ($questions_count->count >= 3){
        $commonQueries->flash('questionnaire_message', $commonQueries->messageStyle('danger', 'Mỗi việc chỉ có tối đa là 3 câu hỏi'));
        $website->redirect($FULL_DOMAIN_NAME ."/EMPLOYERS/cau-hoi/$job_id");
    }
    
    //Fetch questionnaire data   
    $questionnaires_list = $commonQueries->getQuestionnaire($job_id); 

    //Form submitted
    if(isset($_POST['submit'])){
//        $questions_answers = array_reverse(filter_input(INPUT_POST,'answerPoll',FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY));
        $questions_answers = filter_input(INPUT_POST,'answerPoll',FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        $question_title = filter_input(INPUT_POST,'question-title', FILTER_SANITIZE_STRING);
            
        //Insert question
        $question_data = array(
            "job_id"            => $job_id,
            "question"          => $question_title,
            "question_type"     => filter_input(INPUT_POST,'pollSelection', FILTER_SANITIZE_NUMBER_INT),
            "employer"          => $AuthUserName,
            "date"              => strtotime(date("Y-m-d G:i:s")),
        );        
        $question_id = $db->insert('questionnaire',$question_data);
        if (!$question_id){
            echo 'problem while insert data';die;
        }
            
        //Insert question answers 
        foreach ($questions_answers as $questions_answer) {            
            $dataInsert = Array (                
                "question_ask"      => $questions_answer,
                "questionnaire_id"  => $question_id,
                "employer"          => $AuthUserName,
                "job_id"            => $job_id,
            );
                
            $questions_answerId = $db->insert('questionnaire_questions', $dataInsert);
            if(!$questions_answerId){
                echo 'problem while insert data';die;
            }            
        }
            
        //Succeed, back to question page
        $commonQueries->flash('message', $commonQueries->messageStyle('info', "Đã thêm câu hỏi mới"));
        $website->redirect($FULL_DOMAIN_NAME ."/EMPLOYERS/danh-sach-cau-hoi/$job_id");
    }
  
if ($AuthUserName !== $job_info['employer']){//Job question does not belong to current employer
   echo "Không tìm thấy dữ liệu :(";
} else {
?>
    
<script>
    /*Add & Remove questionnaire*/
    $(function() {
        var scntDiv = $('#answerPoll');
        var i = $('#answerPollArea p').size() + 1;        
        $(document).on('click','#addMore', function(e) {
            $('<p><span><input type="text" size="20" name="answerPoll[]" required/></span><span><a href="#" id="remScnt">Xóa</a></span></p>').appendTo(scntDiv).fadeIn('slow');
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
            
            //Show add more button
            if (i < 6) {
                $("#addMore").show();
            }
            return false;
        });        
        
    });
    
    /*questionnaire selection*/
    $(function() {
        $(document).on('change',"#pollSelection",function() {
            /*Show or hide option based on selection*/
            if($("#pollSelection").val() === '2') { //Quiz options, required input all fields
                $("#answerPollArea").show("fade", 500);
                $('#myform input').prop('required',true); 
            } else {
                $("#answerPollArea").fadeOut();
                $('#myform input').prop('required',false);
            } 
        });
        
        //disable values if hidden
        $('form').submit(function(e){
            $('#myform input:hidden').attr("disabled",true); 
        });
    });
</script>    

<!--QUESTIONNAIRES SECTION-->
<div class="row questionnaire-title">
    <section class="col-md-8"></section>    
    <aside class="col-md-2">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/danh-sach-cong-viec/", 'Danh sách công việc', 'green');?>
    </aside>
    <aside class="col-md-2">
        <?php echo $commonQueries->LinkTitle("$FULL_DOMAIN_NAME/EMPLOYERS/cau-hoi/$job_id/", 'Danh sách câu hỏi', 'blue');?>
    </aside>
</div>

<!--Questionnaire-->
<div class="col-md-9 questionnaire-employer">
    <form method="POST" id="myform">
        <!--SELECTION-->
        <div class="row question_selection">
            <span class="col-md-2">Loại câu hỏi: </span>
            <aside class="col-md-8" style="margin-top: -5px;">
                <select id="pollSelection" name="pollSelection">
                    <option value="1">Người dùng trả lời</option>
                    <option value="2">Trắc nghiệm</option>
                </select>
            </aside>
        </div>
        
        <!--QUESTION-->
        <div class="row question_area">
            <h5 class="col-md-2">Câu hỏi (*): </h5>
            <p class="col-md-10"><textarea name="question-title" style="width: 100%; min-height: 100px;" required></textarea></p>                   
        </div>
            
        <!--Poll answers-->
        <div class="row answerPollArea" id="answerPollArea" style="margin-top: 20px; display: none;">
            <h5 class="col-md-2">Câu trả lời: </h5>
            <section class="col-md-10 answerPoll" id="answerPoll">        
                <p>
                    <span><input type="text" name="answerPoll[]"></span>
                    <span><button id="addMore" class="btn btn-green">Thêm câu trả lời +</button></span>
                </p>        
            </section>  
        </div>
            
        <div class="row">
            <section class="col-md-2"></section>
            <label class="col-md-10 questionnaire-save" style="">
                <input type="submit" class="btn btn-primary" name="submit" value="Lưu">
            </label>
        </div>
    </form>
</div>
<?php }?>