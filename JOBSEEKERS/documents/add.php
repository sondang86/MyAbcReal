<?php
// Jobs Portal All Rights Reserved
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
?>
<link href="http://hayageek.github.io/jQuery-Upload-File/4.0.10/uploadfile.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://hayageek.github.io/jQuery-Upload-File/4.0.10/jquery.uploadfile.min.js"></script>

<div id="fileuploader">Upload</div>

<div id="extrabutton" class="ajax-file-upload-green">Start Upload</div>

<script>
    $(document).ready(function()
    {
        var extraObj = $("#fileuploader").uploadFile({
            url:"index.php?category=documents&action=add",
            multiple:false,
            dragDrop:false,
            maxFileCount:1,
            autoSubmit:false,
            fileName:"myfile"
        });
        
        $("#extrabutton").click(function()
        {

                extraObj.startUpload();
        }); 
    });
</script>

<div class="row">
    <section class="col-md-6"></section>
    <section class="col-md-6 navigation-tabs">
        <?php 
            echo LinkTile("home","welcome",$M_DASHBOARD,"","blue");
            echo LinkTile("documents","list",$M_MY_FILES,"","green");
        ?>
    </section>
</div>

<form action="demo-order-process.php" method="post" enctype="multipart/form-data" id="sky-form" class="sky-form">
    <header>Order services</header>
        
    <fieldset>
        <div class="row">
            <section class="col col-6">
                <label class="input">
                    <i class="icon-append fa fa-user"></i>
                    <input type="text" name="name" placeholder="Name" required>
                </label>
            </section>
        </div>      
        
        <section>
                <label for="file" class="input input-file">
                        <div class="button"><input type="file" name="file" multiple onchange="this.parentNode.nextSibling.value = this.value">Browse</div><input type="text" placeholder="Include some file" readonly>
                </label>
        </section>
        
        <section>
            <label class="textarea">
                <i class="icon-append fa fa-comment"></i>
                <textarea rows="5" name="comment" placeholder="Tell us about your project"></textarea>
            </label>
        </section>
        					
    </fieldset>
    <footer>
        <button type="submit" class="button">Send request</button>
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
                extension: "xls|csv"
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
                extension: "sdadasd"
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

