<?php
// Jobs Portal, http://www.netartmedia.net/jobsportal
// A software product of NetArt Media, All Rights Reserved
// Find out more about our products and services on:
// http://www.netartmedia.net
?><?php
if(!defined('IN_SCRIPT')) die("");
global $db;
$cols = array(
    $DBprefix."jobseekers_stat.employer",$DBprefix."jobseekers_stat.resume_id", 
    $DBprefix."jobseekers_stat.date_seen", $DBprefix."jobseekers_stat.views_count", $DBprefix."jobseekers_stat.employer_id",
    $DBprefix."employers.company",$DBprefix."employers.contact_person",
);
$db->join('employers', $DBprefix."jobseekers_stat.employer_id = " .$DBprefix ."employers.id");
$statistics = $db->get('jobseekers_stat', NULL, $cols);

//echo "<pre>";
//print_r($statistics);
//echo "</pre>";
?>
<style>
    .navigation-items a {
        float: right;
    }
    
    .navigation-items {
        margin-bottom: 25px;
    }
    
    .search-form {
        margin-top: 35px;
        margin-bottom: 10px;
    }
    
    .search-form ul li{
        display: inline-block;
        list-style-type: none;
        margin-right: 5px;
    }
</style>
<div class="nav">
    <section class="col-md-12 navigation-items">
    <?php 
        echo LinkTile("home","welcome",$M_DASHBOARD,"","blue");       
        echo LinkTile("profile","edit",$EDIT_YOUR_PROFILE,"","green");           
    ?>
    </section>
</div>

<form action="" role="form" method="GET" id="form_statistics">
    
<!--    <section class="pull-right search-form" id="sort-by">
        <ul>
            <li><input type="text" name="query" placeholder="Tiêu đề công việc"></li>
            <li><input type="submit" value="Tìm kiếm"></li>
        </ul>        
    </section>-->
    
    <div class="table-responsive">          
        <table class="table">
            
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ngày xem</th>
                    <th>Nhà tuyển dụng</th>
                    <th>Số lần xem CV</th>
                </tr>
            </thead>
            
            <tbody>                               
                <?php foreach ($statistics as $statistic) :?>
                <tr id="job_id_101">
                    <td class="col-md-1"></td>                                 
                    <td class="col-md-3"><?php echo date("d-m-Y G:m:s",$statistic['date_seen'])?></td>
                    <td class="col-md-5">
                        <a href="http://<?php echo $DOMAIN_NAME;?>/thong-tin-cong-ty/<?php echo $statistic['employer_id']?>/<?php echo $website->seoUrl($statistic['company'])?>" target="blank">
                            <?php echo $statistic['company']?>
                        </a>
                    </td>
                    <td class="col-md-3"><?php echo $statistic['views_count']?></td>                    
                </tr>
                <?php endforeach;?>    
            </tbody>
        </table>
    </div>
</form>