<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $categories, $salaries,$experience_list, $positions, $locations, $education;
//print_r(date("Y-m-d H:i:s"));
//echo "<br>";
//print_r(strtotime(date("Y-m-d H:i:s")));

if (isset($_GET['keywords'])){
    print_r($_GET['dada']);
}
?>
<div class="row">
    <section class="col-md-9"></section>
    <section class="col-md-3 pull-right">
        <?php echo LinkTile("jobseekers","list",$M_BROWSE,"","yellow");?>
    </section>
</div>
    
<form action="tim-kiem-ung-vien" name="search" method="GET">
    <div class="row">
        <div class="filter-panel collapse in" style="height: auto;">
            <div class="panel panel-default">
                <div class="panel-body">
                    <section class="form-inline search-form" role="form">                        
                        <div class="form-group col-md-6">
                            <input type="text" name="keywords" class="form-control input-sm" id="pref-search" placeholder="Nhập từ khóa muốn tìm">
                        </div><!-- form group [search] -->
                        
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="category">
                                <option>Ngành nghề</option>
                                <?php foreach ($categories as $category) :?>
                                <option><?php echo $category['category_name_vi']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->      
                        
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="position">
                                <option>Địa điểm</option>
                                <?php foreach ($locations as $location) :?>
                                <option><?php echo $location['City']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->  
                          
                        <div class="form-group col-md-3">
                            <select id="pref-orderby" class="form-control" name="education">
                                <option value="">Trình độ học vấn</option>
                                <?php foreach ($education as $value) :?>
                                <option><?php echo $value['education_name']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->
                        
                        <div class="form-group col-md-3">
                            <select id="pref-orderby" class="form-control" name="expected_position">
                                <option value="">Vị trí mong muốn</option>
                                <?php foreach ($positions as $position) :?>
                                <option><?php echo $position['position_name']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->
                        
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="experience_level">
                                <option value="">Mức kinh nghiệm</option>
                                <?php foreach ($experience_list as $experience) :?>
                                <option><?php echo $experience['name']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->
                        
                        <div class="form-group col-md-2">
                            <select id="pref-orderby" class="form-control" name="date_updated">
                                <option value="">Hồ sơ cập nhật trong vòng</option>
                                <?php foreach ($categories as $category) :?>
                                <option><?php echo $category['category_name']?></option>
                                <?php endforeach;?>
                            </select>                                
                        </div> <!-- form group [order by] -->                        
                        
                        <div class="form-group col-md-1">
                             <button class="input-group-addon search-button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div> <!-- Search button --> 
                        
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
    
    .search-form div select {
        width: 100% !important;
    }
</style>