<div class="row">
    <div class="col-lg-12" id="main-content">        
    
        <div class="fright"></div>
        <div class="clear"></div>
        <br>
        <span class="medium-font">
            List of the current reviews
        </span>
        <br>
        <div style="float:right">
                <form action="index.php" method="post">
                    
                    
                <input type="hidden" name="category" value="users"><input type="hidden" name="action" value="reviews">
                Search in <select name="comboSearch" class="table-combo-search"><option value="date">Date</option><option value="title">Title</option><option value="html">Description</option><option value="author">User</option><option value="vote">Vote</option></select> 
                    
                <input class="table-search-field" value="" type="text" required="" name="textSearch"> 
                <input type="submit" class="btn btn-default btn-gradient" value=" Search ">
            </form>
        </div>
        <div class="clear"></div>
                    
                    
        <script>
            function SubmitForm()
            {
		
                document.getElementById('table-form').submit();
            }
        </script>
            
        <script>
            function CheckAll(source) 
            {
                checkboxes = document.getElementsByName('CheckList[]');
                for(var i=0, n=checkboxes.length;i<n;i++) {
                    checkboxes[i].checked = source.checked;
                }
            }
        </script>
                                <form action="index.php" id="table-form" method="post" enctype="multipart/form-data"><input type="hidden" name="FormSubmitted" value="1"><input type="hidden" name="category" value="users"><input type="hidden" name="action" value="reviews"><div class="table-responsive"><table cellpadding="3" cellspacing="0" width="100%" style="border-color:#eeeeee;border-width:1px 1px 1px 1px;border-style:solid"><tbody><tr class="table-tr" nowrap=""><td class="header-td" width="103" nowrap="">
            
            
                                Modify
                            
                            
                            
                            
                                </td><td class="header-td" width="103" nowrap="">
                            
                                <a class="header-td underline-link" href="index.php?category=users&amp;action=reviews&amp;order=date&amp;order_type=desc">
                                    Date
                                </a>
                            
                            
                            
                                </td><td class="header-td" width="103" nowrap="">
                            
                                <a class="header-td underline-link" href="index.php?category=users&amp;action=reviews&amp;order=title&amp;order_type=desc">
                                    Title
                                </a>
                            
                            
                            
                                </td><td class="header-td" width="103" nowrap="">
                            
                                <a class="header-td underline-link" href="index.php?category=users&amp;action=reviews&amp;order=html&amp;order_type=desc">
                                    Description
                                </a>
                            
                            
                            
                                </td><td class="header-td" width="103" nowrap="">
                            
                                <a class="header-td underline-link" href="index.php?category=users&amp;action=reviews&amp;order=author&amp;order_type=desc">
                                    User
                                </a>
                            
                            
                            
                                </td><td class="header-td" width="103" nowrap="">
                            
                                <a class="header-td underline-link" href="index.php?category=users&amp;action=reviews&amp;order=vote&amp;order_type=desc">
                                    Vote
                                </a>
                            
                            
                            
                                </td><td class="header-td" width="103" nowrap="">
                            
                            
                                Delete
                            
                            
                            
                            
                            </td></tr></tbody></table></div></form>
    </div>
</div>