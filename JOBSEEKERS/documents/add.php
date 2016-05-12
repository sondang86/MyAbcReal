<?php
// Jobs Portal All Rights Reserved

if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries;
?>
<?php
    if(isset($_POST['submit'])){
//        print_r($_FILES);
        $storage = new \Upload\Storage\FileSystem('../user_files/jobseekers');
        $file = new \Upload\File('file', $storage);
        // Optionally you can rename the file on upload
        $new_filename = $file->setName('1');
        
        echo "<pre>";
        print_r($file->getName());
        echo "</pre>";
        
        // Validate file upload
        // MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
        $file->addValidations(array(
            // Ensure file is of type "image/png"
            new \Upload\Validation\Mimetype(array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')),

            //You can also add multi mimetype validation
            //new \Upload\Validation\Mimetype(array('image/png', 'image/gif'))

            // Ensure file is no larger than 5M (use "B", "K", M", or "G")
            new \Upload\Validation\Size('5M')
        ));

        // Access data about the file that has been uploaded
        $data = array(
            'name'       => $file->getNameWithExtension(),
            'extension'  => $file->getExtension(),
            'mime'       => $file->getMimetype(),
            'size'       => $file->getSize(),
            'md5'        => $file->getMd5(),
            'dimensions' => $file->getDimensions()
        );

        //delete old files if same userId, allowed store only one file 
        if ($file->getName() == "1"){
//            unlink("../user_files/jobseekers/1.*");
            $mask = '../user_files/jobseekers/1.*';
            array_map('unlink', glob($mask));
        }
        
        //Upload file info to db
        
        // Try to upload file
        try {
            // Success!
            $file->upload();
            
        } catch (\Exception $e) {
            // Fail!
            $errors = $file->getErrors();
        }
    }
?>

<div class="row">
    <section class="col-md-6"></section>
    <section class="col-md-6 navigation-tabs">
        <?php 
            echo LinkTile("home","welcome",$M_DASHBOARD,"","blue");
            echo LinkTile("documents","list",$M_MY_FILES,"","green");
        ?>
    </section>
</div>

<form action="" method="post" enctype="multipart/form-data" id="sky-form" class="sky-form">
    <header>Tùy chỉnh tập tin cá nhân của bạn</header>
    
    <fieldset>
        <div class="row">
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-user"></i>
                    <input type="text" name="name" placeholder="tiêu đề tập tin" required>
                </label>
            </section>
        </div>      
        
        <section>
            <label for="file" class="input input-file">
                <div class="button"><input type="file" name="file" onchange="this.parentNode.nextSibling.value = this.value">Browse</div><input type="text" placeholder="Include some file" readonly>
            </label>
        </section>
        
        <section>
            <label class="textarea">
                <i class="icon-append fa fa-comment"></i>
                <textarea rows="5" name="comment" placeholder="Mô tả tập tin"></textarea>
            </label>
        </section>
        
    </fieldset>
    <footer>
        <button type="submit" name="submit" class="button">Send request</button>
        <div class="progress"></div>
    </footer>				
    <div class="message">
        <i class="fa fa-check"></i>
        <p>Thanks for your order!<br>We'll contact you very soon.</p>
    </div>
</form>			
</div>

<script type="text/javascript">
    $(function()
    {
        // Validation
        $("#sky-form").validate(
                {					
                    // Rules for form validation
            rules:
                    {
                        name:{
                            required: true
                },
                file:{
                    required: 'Bạn chưa upload file',
//                    extension: "xls|csv",
                    accept: "application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword"
                }
            },

            // Messages for form validation
            messages:
                    {
                        name:
                        {
                            required: 'Please enter your name'
                },
                file:{
                    required: 'Bạn chưa upload file',
//                    extension: "sdadasd",
                    accept: 'Chỉ chấp nhận pdf và doc/docx file'
                }
            },

            // Do not change code below
            errorPlacement: function(error, element)
            {
                error.insertAfter(element.parent());
            }
        });
    });
</script>

