<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
?>
<div class="row">
    <section class="col-md-9"></section>
    <section class="col-md-3 pull-right">
        <?php echo LinkTile("jobseekers","list",$M_BROWSE,"","yellow");?>
    </section>
</div>
    
<form action="" name="search" method="GET">
    <div class="row">
        <div class="filter-panel collapse in" style="height: auto;">
            <div class="panel panel-default">
                <div class="panel-body">
                    <section class="form-inline search-form" role="form">                        
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control input-sm" id="pref-search" placeholder="Nhập từ khóa muốn tìm">
                        </div><!-- form group [search] -->
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control">
                                <option>Ngành nghề</option>
                                <option>Descendent</option>
                                <option>Descendent</option>
                                <option>Descendent</option>
                            </select>                                
                        </div> <!-- form group [order by] -->      
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control">
                                <option>Địa điểm</option>
                                <option>Descendent</option>
                                <option>Descendent</option>
                                <option>Descendent</option>
                            </select>                                
                        </div> <!-- form group [order by] -->  
                        <div class="form-group col-md-1">
                             <button class="input-group-addon search-button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div> <!-- form group [order by] -->  
                    </section>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary">
            <span class="glyphicon glyphicon-cog"></span> Tìm kiếm
        </button>
    </div>
</form>    
<style>
    .search-button {
        width: 100%;
        /* border: 1px solid; */
        border-radius: 5px;
        padding: 8px;
        background: #fc205b;
    }
    
    .search-button i {
        color: #ffffff;
    }
    
    .search-form input[type="text"]{
        width: 100%;
        color: grey;
    }
    
    ::-webkit-input-placeholder {
        color: red;
    }
</style>