<?php
// Jobs Portal All Rights Reserved

if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $user_info;
$user_files = $db->where('user_id', $user_info['id'])->get('files');

    if(isset($_POST['submit'])){
        //Instanciate new upload
        $storage    = new \Upload\Storage\FileSystem('../user_files/jobseekers');
        $file       = new \Upload\File('file', $storage);
        // Rename the file on upload
        $new_filename = $file->setName($user_info['id']);
        
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

       
        //delete old files if same userId, allowed store only one file 
        if ($file->getName() == $user_info['id']){
            $mask = '../user_files/jobseekers/'. $user_info['id'] . '.*';
            array_map('unlink', glob($mask));
        }
                
        // Try to upload file
        try {
            // !Success
            if(!$file->upload()){
                throw new Exception($file->getErrors());
            } else {
            
                //Insert file info to db
                $data = Array (
                    "title"             => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
                    'description'       => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
                    "short_file_name"   => $file->getName(),
                    "file_name"         => $file->getNameWithExtension(),
                    "user"              => "$AuthUserName",
                    "user_id"           => $user_info['id'],
                    "user_type"         => 2, //jobseekers
                    "mime_type"         => $file->getMimetype(),
                    "file_extension"    => $file->getExtension(),
                    "file_date"         => time(),
                    "file_size"         => $file->getSize(),
                );

                $updateColumns = array(
                    'file_extension','file_date', 'title',
                    'short_file_name', 'file_size','mime_type',
                    'description',"user_type"
                );                
                $lastInsertId = "user_id";
                
                $db->onDuplicate($updateColumns, $lastInsertId);
                
                $id = $db->insert ('files', $data);
                if (!$id){
                     throw new Exception('there was a problem');
                }
            }
        } catch (\Exception $e) {
            // Fail!
            $errors = $file->getErrors();
            echo "message" . $e->getMessage() . "<br>";
            echo "file" . $e->getFile() . "<br>";
            echo "line" . $e->getLine() . "<br>";
            echo "Trace" . $e->getTraceAsString() . "<br>";
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
                    <input type="text" name="title" placeholder="tiêu đề tập tin" required>
                </label>
            </section>
        </div>      
        
        <div class="row uploadForm">
            <section class="col col-6">
                <input id="uploadFile" placeholder="Tên tập tin" name="upload_name" disabled="disabled"/>
                <div class="fileUpload btn btn-primary">
                    <span>Tải lên</span>
                    <input id="uploadBtn" type="file" name="file" class="upload" required/>
                </div>
            </section>
        </div>
        
        <section>
            <label class="textarea">
                <i class="icon-append fa fa-comment"></i>
                <textarea rows="5" name="description" placeholder="Mô tả tập tin"></textarea>
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

<!--LIST FILE-->
<h4>Danh sách tập tin của bạn</h4>
<section class="table-responsive">
    <table class="table table-hover .table-striped">
        <thead>
            <tr>
                <th>File id</th>
                <th>Ngày up</th>
                <th>Tiêu đề tập tin</th>
                <th>Tên tập tin</th>
                <th>Thông tin tập tin</th>
                <th>Kích cỡ (KB)</th>
                <th>Định dạng</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user_files as $user_file) :?>
            <tr>
                <td><?php echo $user_file['file_id'];?></td>
                <td><?php echo date('d-m-Y',$user_file['file_date']);?></td>
                <td><?php echo $user_file['title'];?></td>
                <td><?php echo $user_file['file_name'];?></td>
                <td><?php echo $user_file['description'];?></td>
                <td><?php echo $commonQueries->formatSizeUnits($user_file['file_size']);?></td>
                <td><?php echo $user_file['mime_type'];?></td>
                <td>
                    <button class="btn btn-primary btn-block">
                        <i class="fa fa-download" aria-hidden="true"></i> Download
                    </button>
                    <input class="btn" type="button" value="Input">
                </td>
                <td>
                    <button class="btn btn-danger btn-block"><i class="fa fa-times" aria-hidden="true"></i> Delete</button>
                    <input class="btn" type="submit" value="Submit">                
                </td>
            </tr>        
            <?php endforeach;?>
        </tbody>
    </table>
</section>

<script type="text/javascript">
    $(function()
    {
        // Validation
        $("#sky-form").validate(
                {					
                    // Rules for form validation
            rules:
                    {
                        title:{
                            required: true
                },
                file:{
                    required: true,
                    accept: "application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword"
                }
            },

            // Messages for form validation
            messages:
                    {
                        title:
                        {
                            required: 'Bạn chưa điền tiêu đề tập tin'
                },
                file:{
                    required: 'Bạn chưa chọn tập tin',
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
    
    
    $(function() {
        //Append selected upload file name to disabled input box
        //http://www.tutorialrepublic.com/faq/how-to-get-selected-file-name-from-input-type-file-using-jquery.php
        $("input:file[id=uploadBtn]").change (function(e) {
            
            $("#uploadFile").val(e.target.files[0].name); // remove fakepath, output file name only
            
            $("span.uploadBtn").text("upload");

        });
        
    });
</script>

