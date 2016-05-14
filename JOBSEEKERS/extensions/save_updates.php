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

//Languages update
if (isset($_POST['request_type']) && $_POST['request_type'] == "language_update"){ 
    $resume_id = filter_input(INPUT_POST,'resume_id', FILTER_SANITIZE_NUMBER_INT);
    $jobseeker_id = filter_input(INPUT_POST,'jobseeker_id', FILTER_SANITIZE_NUMBER_INT);
    
    //Convert single value array to multidimensional array
    $languages_selected = filter_input(INPUT_POST, 'languages', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
    if (!is_array($languages_selected['js_language'])){
        $languages_selected = array(
            'js_language' => array($languages_selected['js_language']),
            'js_language_level' => array($languages_selected['js_language_level'])
        );
    }
    
    //Delete records first 
    $db->where('resume_id', $resume_id)->withTotalCount()->get('jobseeker_languages');
    if ($db->totalCount !== "0"){
        $db->where('resume_id', $resume_id);
        if(!$db->delete('jobseeker_languages')) {echo 'Problem'; die;};
    }
        
    //Insert selected languages on duplicate update
    foreach ($languages_selected['js_language'] as $key => $language) {
        $data = Array (
            "language_id"   => $language,
            "jobseeker_id"  => $jobseeker_id,
            "level_id"      => $languages_selected['js_language_level'][$key],
            "resume_id"     => $resume_id
        );
        $updateColumns = Array ("language_iddsa","level_id","resume_id");        
        $db->onDuplicate($updateColumns);
        $id = $db->insert('jobseeker_languages', $data);
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

//Expected area 1
if (isset($_POST['request_type']) && $_POST['request_type'] == "save_expected_area1"){ 
    $data = array (
        "title"                 => filter_var($_POST['data']['js-title'], FILTER_SANITIZE_STRING),
        "current_position"      => filter_var($_POST['data']['js-current-position'], FILTER_SANITIZE_NUMBER_INT), 
        "salary"                => filter_var($_POST['data']['js-salary'], FILTER_SANITIZE_NUMBER_INT), 
        "expected_position"     => filter_var($_POST['data']['js-expected-position'], FILTER_SANITIZE_NUMBER_INT), 
        "expected_salary"       => filter_var($_POST['data']['js-expected-salary'], FILTER_SANITIZE_NUMBER_INT), 
        "job_category"          => filter_var($_POST['data']['js-category'], FILTER_SANITIZE_NUMBER_INT), 
        "location"              => filter_var($_POST['data']['js-location'], FILTER_SANITIZE_NUMBER_INT), 
        "education_level"       => filter_var($_POST['data']['education_level'], FILTER_SANITIZE_NUMBER_INT),
        "job_type"              => filter_var($_POST['data']['js-jobType'], FILTER_SANITIZE_NUMBER_INT)        
    );
        
    $db->where ('username', filter_input(INPUT_POST,'username', FILTER_SANITIZE_STRING));
    if ($db->update ('jobseeker_resumes', $data)){
        //Customize success message
        $message = array(
            "message"   => "Lưu thay đổi thành công",
            "status"    => "1", //Success
            "resume_id" => $_POST['resume_id']
        );
        echo json_encode($message);
        
    } else {
        echo 'update failed: ' . $db->getLastError();die;
    }
}

//Expected area 2
if (isset($_POST['request_type']) && $_POST['request_type'] == "save_expected_area2"){ 
    $data = array (
        "career_objective"  => filter_var($_POST['data']['js-careerObjective'], FILTER_SANITIZE_STRING),
        "experiences"       => filter_var($_POST['data']['js-experience'], FILTER_SANITIZE_STRING), 
        "skills"            => filter_var($_POST['data']['skills'], FILTER_SANITIZE_STRING),
        "referrers"         => filter_var($_POST['data']['referrers'], FILTER_SANITIZE_STRING),
        "IT_skills"         => filter_var($_POST['data']['js-IT_skill'], FILTER_SANITIZE_NUMBER_INT), 
        "group_skills"      => filter_var($_POST['data']['js-group_skill'], FILTER_SANITIZE_NUMBER_INT), 
        "pressure_skill"    => filter_var($_POST['data']['js-pressure_skill'], FILTER_SANITIZE_NUMBER_INT), 
        "facebook_URL"      => filter_var($_POST['data']['js-facebookURL'], FILTER_SANITIZE_STRING)
    );
        
    $db->where ('username', filter_input(INPUT_POST,'username', FILTER_SANITIZE_STRING));
    if ($db->update ('jobseeker_resumes', $data)){
        //Customize success message
        $message = array(
            "message"   => "Lưu thay đổi thành công",
            "status"    => "1", //Success
            "resume_id" => $_POST['resume_id']
        );
        echo json_encode($message);
        
    } else {
        echo 'update failed: ' . $db->getLastError();die;
    }
}
?>

