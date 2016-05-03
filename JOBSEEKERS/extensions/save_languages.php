<?php
    $data = json_decode($_POST['languages_selected'], true);;
    print_r(json_encode($data));
//echo json_encode("success");
?>

