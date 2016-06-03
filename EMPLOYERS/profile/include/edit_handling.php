<?php
    if(!defined('IN_SCRIPT')) die("");
    
    
if (isset($_POST['submit'])){    
    //File upload handle
    if (isset($_FILES['file']) && ($_FILES['file']['error'] !== 4)){ //Make sure file upload exists
        //Store image under jpg format
        $image_name = $employer_data['id'] . '.jpg';
        //Update logo info 
//        $db->where('username', "$AuthUserName")->update('employers', array('logo' => "$image_name")); 

        // create a new instance of the class
        //http://stefangabos.ro/wp-content/docs/Zebra_Image/Zebra_Image/Zebra_Image.html
        $image = new Zebra_Image();

        // indicate a source image (a GIF, PNG or JPEG file)
        $image->source_path = $_FILES['file']['tmp_name'];
        // indicate a target image
        // note that there's no extra property to set in order to specify the target
        // image's type -simply by writing '.jpg' as extension will instruct the script
        // to create a 'jpg' file
        $image->target_path = "../images/employers/logo/" . "$image_name";


        // since in this example we're going to have a jpeg file, let's set the output 
        // image's quality
        $image->jpeg_quality = 100;
        $image->preserve_aspect_ratio = true;
        $image->enlarge_smaller_images = true;
        $image->preserve_time = true;

        // resize the image to exactly 100x100 pixels by using the "crop from center" method
        //  and if there is an error, check what the error is about
        if (!$image->resize(250, 0, ZEBRA_IMAGE_CROP_CENTER)) {

            // if there was an error, let's see what the error is about
            switch ($image->error) {

                case 1:
                    echo 'Source file could not be found!';
                    break;
                case 2:
                    echo 'Source file is not readable!';
                    break;
                case 3:
                    echo 'Could not write target file!';
                    break;
                case 4:
                    echo 'Unsupported source file format!';
                    break;
                case 5:
                    echo 'Unsupported target file format!';
                    break;
                case 6:
                    echo 'GD library version does not support target file format!';
                    break;
                case 7:
                    echo 'GD library is not installed!';
                    break;
            }
        }   

    } else { //Keep current image
        $image_name = $employer_data['id'] . '.jpg';
    }
    
    //Perform update         
    $data = Array (
        'company'               => filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING),  
        'company_description'   => filter_input(INPUT_POST, 'company_description', FILTER_SANITIZE_STRING),        
        'contact_person'        => filter_input(INPUT_POST, 'contact_person', FILTER_SANITIZE_STRING),
        'address'               => filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING),
        'phone'                 => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT),
        'fax'                   => filter_input(INPUT_POST, 'fax', FILTER_SANITIZE_NUMBER_INT),
        'website'               => filter_input(INPUT_POST, 'website', FILTER_SANITIZE_STRING),
        'logo'                  => "$image_name",
        'latitude'              => filter_input(INPUT_POST, 'job_map_latitude', FILTER_SANITIZE_STRING),
        'longitude'             => filter_input(INPUT_POST, 'job_map_longitude', FILTER_SANITIZE_STRING),
    );
    $db->where('username', "$AuthUserName");
    if ($db->update ('employers', $data)){
        $commonQueries->flash('message', $commonQueries->messageStyle('info', 'Lưu thay đổi thành công!'));
        $website->redirect('index.php?category=profile&action=edit');
    } else{
        echo 'update failed: ' . $db->getLastError();die;
    }

}    

