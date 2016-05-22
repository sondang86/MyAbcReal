<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net

if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $employer_data;

if (isset($_POST['submit'])){
    //Store image under jpg format
    $image_name = $employer_data['id'] . '.jpg';
    //Update logo info 
    $db->where('username', "$AuthUserName")->update('employers', array('logo' => "$image_name")); 
    
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

    // if no errors
    } else {
        //Redirect
        $commonQueries->flash('message',$commonQueries->messageStyle('info',"Thay đổi logo thành công"));
        $website->redirect("index.php?category=profile&action=logo");
    }
}
?>

<!--MAIN NAVIGATION-->    
<div class="row main-nav">
    <section class="col-md-9 col-sm-9">
        <?php $commonQueries->flash('message')?>
    </section>
    <section class="col-md-3 col-sm-3 col-xs-6">
        <?php echo LinkTile("profile","edit",$M_EDIT,"","green");?>
    </section>
<!--    <section class="col-md-2 col-sm-3 col-xs-6">
        <?php echo LinkTile("profile","video",$M_VIDEO_PRESENTATION,"","lila");?>
    </section>-->
</div>
     
<!--CHANGE LOGO--> 
<form action="" method="POST" enctype="multipart/form-data" id="sky-form" class="sky-form">
    <header>Tùy chỉnh Logo công ty</header>
        
    <fieldset>
        <div class="row">                        
            <section class="col col-6">
                <label class="logo">                    
                    <img src="/vieclambanthoigian.com.vn/images/employers/logo/<?php echo $employer_data['logo']?>" id="preview" height="125" width="250">
                </label>
            </section>
        </div>
            
        <div class="row">
            <section class="col col-6 uploadForm">
                <input id="uploadFile" placeholder="Tên tập tin" name="upload_name" disabled="disabled"/>
                <p class="btn btn-default btn-file">
                    Chọn <input id="logo" type="file" name="file" class="upload" required/>
                </p>
            </section>
        </div>

    </fieldset>
    <footer>
        <button type="submit" name="submit" class="button">Lưu</button>
        <div class="progress"></div>
    </footer>				
    <div class="message">
        <i class="fa fa-check"></i>
        <p>Thanks for your order!<br>We'll contact you very soon.</p>
    </div>
</form>	
    
<script type="text/javascript">
    
    $(function()
    {
        // Validation
        $("#sky-form").validate(
        {					
            // Rules for form validation
            rules:
            {
                file:{
                    required: true,
                    accept: "image/*"
                }
            },
            
            // Messages for form validation
            messages:
            {
                file:{
                    required: 'Bạn chưa chọn tập tin',
                    accept: 'Chỉ chấp nhận định dạng ảnh (jpeg, jpg, png ...)'
                }
            },
            
            // Do not change code below
            errorPlacement: function(error, element)
            {
                error.insertAfter(element.parent());
            }
        });
    });
    
    //Upload file
    $(function() {
        //Append selected upload file name to disabled input box
        //http://www.tutorialrepublic.com/faq/how-to-get-selected-file-name-from-input-type-file-using-jquery.php
        $("input:file[id=uploadBtn]").change (function(e) {
            
            $("#uploadFile").val(e.target.files[0].name); // remove fakepath, output file name only
            
            $("span.uploadBtn").text("upload");
            
        });
        
    });   
    
    /*file upload validation*/
    
    
    /*Preview image before upload*/
    $("#logo").change(function() {
        previewImage(this);
    });
    /*Preview image before upload*/
    
</script>
