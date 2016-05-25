<?php
// Jobs Portal All Rights Reserved

if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $user_info;
$user_files = $db->where('user_id', $user_info['id'])->get('files');

//Upload form
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
            throw new Exception($file->getErrors('could not upload file'));
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
            } else {
                //Redirect
                $commonQueries->flash('message',$commonQueries->messageStyle('info',"Tải file thành công"));
                $website->redirect("index.php?category=documents&action=add");
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

//Delete file
if(isset($_POST['delete_submitted']) && ($_POST['delete_submitted'] == '1')){
    
    try {
        $db->where('file_id', $_POST['file_id']);
        if(!$db->delete('files')){
            throw new Exception('problem when deleting file');
        
        } else { //Delete physical file
            $mask_file = '../user_files/jobseekers/'. $user_info['id'] . '.*';
            array_map('unlink', glob($mask_file));
            
            //Redirect
            $commonQueries->flash('message',$commonQueries->messageStyle('info',"Đã xóa file"));
            $website->redirect("index.php?category=documents&action=add");
        }
        
    } catch (Exception $e) {
        // throw Fail exceptions!
        echo "message " . $e->getMessage() . "<br>";
        echo "line " . $e->getLine() . "<br>";
    }
    
}
?>
<div class="row">
    <section class="col-md-9">
        <h4><?php $commonQueries->flash('message');?></h4>
        <p>Bạn có thể tải CV của bạn với định dạng PDF/doc hoặc docx để đính kèm khi nộp hồ sơ cho các công việc trên vieclambanthoigian.com.vn</p>
        <h5>Lưu ý: 1 hồ sơ chỉ có thể up tối đa 1 file, file có sẵn sẽ bị ghi đè lên bằng file mới</h5>            
    </section>
    <section class="col-md-3 navigation-tabs">
        <?php 
            echo LinkTile("home","welcome",$M_DASHBOARD,"","blue");
        ?>
    </section>
</div>


<form action="" method="POST" enctype="multipart/form-data" id="sky-form" class="sky-form">
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
        
        <div class="row">
            <section class="col col-6 uploadForm">
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
        <button type="submit" name="submit" class="button">Lưu</button>
        <div class="progress"></div>
    </footer>				
    <div class="message">
        <i class="fa fa-check"></i>
        <p>Thanks for your order!<br>We'll contact you very soon.</p>
    </div>
</form>	

<style>
    .list_files {
        margin-top: 50px;
    }
</style>

<form action="" method="POST" id="list_files" class="list_files">
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
                    <td class="col-md-1">
                        <a href="/vieclambanthoigian.com.vn/user_files/jobseekers/<?php echo $user_file['file_name'];?>" class="btn btn-primary btn-block" target="_blank" title="Lưu file">
                            <i class="fa fa-download" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td class="col-md-1">
                        <input type="hidden" name="file_id" value="<?php echo $user_file['file_id']?>">
                        <!--For jquery-confirm catchs data--> 
                        <input type="hidden" name="delete_submitted" value="1"> 
                        <input class="btn btn-danger btn-block confirm" type="submit" name="delete" value="Xóa">                
                    </td>
                </tr>        
                <?php endforeach;?>
            </tbody>
        </table>
    </section>
</form>

<script type="text/javascript">

    $('.confirm').confirm({
        content: 'Xóa mục đã chọn?',
        title: 'Vui lòng xác nhận',
        confirm: function(){
            $('#list_files').submit();
        }
    });
    
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
    
    //Upload file
    $(function() {
        //Append selected upload file name to disabled input box
        //http://www.tutorialrepublic.com/faq/how-to-get-selected-file-name-from-input-type-file-using-jquery.php
        $("input:file[id=uploadBtn]").change (function(e) {
            
            $("#uploadFile").val(e.target.files[0].name); // remove fakepath, output file name only
            
            $("span.uploadBtn").text("upload");

        });
        
    });   
        
</script>

