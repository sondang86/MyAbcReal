<?php
// Jobs Portal 
// Copyright (c) All Rights Reserved, NetArt Media 2003-2015
// Check http://www.netartmedia.net/jobsportal for demos and information
    
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $commonQueries_Employers, $FULL_DOMAIN_NAME, $employerInfo;

$jobs_by_employer = $commonQueries_Employers->jobs_by_employer($AuthUserName);

$new_messages = $db->where('user_to', "$AuthUserName")->withTotalCount()->get('user_messages');
$new_messages_count = $db->totalCount;
?>
    
<div class="col-md-3 welcome-left-block">
    <h5><?php $commonQueries->flash('message');?></h5>
    
    <h4><strong><?php echo $M_WELCOME;?> <?php echo $LoginInfo["contact_person"];?>,</strong></h4> 
    
    <h5><strong>Thống kê hiện tại: </strong></h5>
    <p>Bạn có: <a href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/tin-nhan/"><?php echo $new_messages_count;?> tin nhắn mới </a></p>

    <p>Gói đăng ký hiện tại: <a href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/dang-ky/"><?php echo $employerInfo['subscription_name']?></a> </p></p>
    <p>Số công việc đã đăng: <a href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/danh-sach-cong-viec/"><?php echo $jobs_by_employer['totalCount'];?>/<?php echo $employerInfo['subscription_listing']?></a> </p>
</div>


<div id="home-links-area" class="col-md-9">    
    <div class="row" style="padding:10px">
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-1">
            <div class="tile-p" id="b-1">
                <a class="home-tile box-1-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/dang-viec-moi/"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/add.png">
                    <h3 class="h3-tile">Đăng việc mới</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-2">
            <div class="tile-p" id="b-2">
                <a class="home-tile box-2-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/danh-sach-cong-viec/"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/my.png">
                    <h3 class="h3-tile">Danh sách công việc</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-3">
            <div class="tile-p" id="b-3">
                <a class="home-tile box-3-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/danh-sach-don-xin-viec/"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/list.png">
                    <h3 class="h3-tile">Đơn xin việc</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-4">
            <div class="tile-p" id="b-4">
                <a class="home-tile box-4-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/tin-nhan/"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/received.png">
                    <h3 class="h3-tile">Tin nhắn</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-5">
            <div class="tile-p" id="b-5">
                <a class="home-tile box-5-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/chinh-sua-thong-tin-ca-nhan/"><img class="pull-right" src="http://localhost/vieclambanthoigian.com.vn/EMPLOYERS/images/icons/edit.png">
                    <h3 class="h3-tile">Chỉnh sửa thông tin cá nhân</h3>
                </a>            
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 t-padding" id="box-6">
            <div class="tile-p" id="b-6">
                <a class="home-tile box-6-back" href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/tim-kiem-ung-vien/"><img class="pull-right" src="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/images/icons/logo.png">
                    <h3 class="h3-tile">Tìm kiếm ứng viên</h3>
                </a>            
            </div>
        </div>
    </div>
        
</div>

<style>
    .title-panel {
        padding-bottom: 30px;
    }
</style>
            
<div class="row">
    <p class="col-md-12">
        <strong><?php echo $M_YOUR_LATEST_JOBS;?></strong>
    </p>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row panel panel-default">
            <section class="panel-heading title-panel">                
                <div class="col-md-2">
                    <p>Ngày</p>            
                </div>
                
                <div class="col-md-2">
                    <p>Tiêu đề</p>            
                </div>

            </section>
                    
            <div class="panel-body">
                <div class="list-group">
                    
                    <?php foreach ($jobs_by_employer['jobs'] as $job) :?>                            
                    <a  href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/chi-tiet-cong-viec/<?php echo $job["jobId"];?>/<?php echo $website->seoURL($job["title"]);?>" class="list-group-item no-decoration" >
                        <div class="row">
                            <div class="col-md-2">
                            <?php echo date('d/m/Y',$job["date"]);?>
                            </div>
                            <div class="col-md-6">
                                <strong><?php echo stripslashes($job["title"]);?></strong>
                            </div>
                            <div class="col-md-2 italic">
                                <?php echo $job['applications']?> đơn xin việc                                                           
                            </div>
                            <div class="col-md-2 italic">
                                <?php if($job['views_count'] == NULL){echo "0";} else { echo $job['views_count'];}?> lượt xem  			
                            </div>
                                                    
                        </div>
                    </a>
                    <?php endforeach;?>
                    
                </div>
                            
                <a href="<?php echo $FULL_DOMAIN_NAME;?>/EMPLOYERS/danh-sach-cong-viec/" class="btn btn-default btn-block alt-back"><?php echo $M_SEE_ALL;?></a>
            </div>
        </div>
    </div>
</div>