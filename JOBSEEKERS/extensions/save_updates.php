<?php
    require_once '../../include/CommonsQueries.class.php';
    require_once '../../include/MysqliDb.class.php';
    $db = new MysqliDb (Array (
            'host' => "localhost",
            'username' => "root", 
            'password' => "",
            'db'=> "viea83fe_vieclambanthoigian",
            'port' => 3306,
            'prefix' => "jobsportal_",
            'charset' => 'utf8'
        ));

    //Common queries 
    $commonQueries = new CommonsQueries($db); 

if (isset($_POST['request_type']) && $_POST['request_type'] == "language_update"){    
    //Convert single value array to multidimension array
    $languages_selected = $_POST['languages'];
    if (!is_array($languages_selected['js_language'])){
        $languages_selected = array(
            'js_language' => array($languages_selected['js_language']),
            'js_language_level' => array($languages_selected['js_language_level'])
        );
    }
    
    
    //Delete records first 
    $db->where('resume_id', $_POST['resume_id'])->withTotalCount()->get('jobseeker_languages');
    if ($db->totalCount !== "0"){
        $db->where('resume_id', $_POST['resume_id']);
        if(!$db->delete('jobseeker_languages')) {echo 'Problem'; die;};
    }
    
    //Insert selected languages on duplicate update
    foreach ($languages_selected['js_language'] as $key => $language) {
        $data = Array (
            "language_id" => $language,
            "level_id" => $languages_selected['js_language_level'][$key],
            "resume_id" => $_POST['resume_id']
        );
        $updateColumns = Array ("language_id");
        $lastInsertId = "language_id";
        $db->onDuplicate($updateColumns, $lastInsertId);
        $id = $db->insert ('jobseeker_languages', $data);
        if(!$id){
            echo 'problem';die;
        }
    }
    
    //Customize success message
    $message = array(
        "message"   => "Lưu thay đổi thành công",
        "status"    => "1", //Success
        "resume_id" => $_POST['resume_id']
    );
    
    echo json_encode($message);
}
?>

