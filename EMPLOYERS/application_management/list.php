<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $commonQueries_Employers;
$CVs_applied = $commonQueries_Employers->getCVApplieds_status('0', $AuthUserName);

?>

<!--NAVIGATION-->
<div class="row">
    <section class="col-md-6 col-sm-3 col-xs-12">
        <h4><?php $commonQueries->flash('message');?></h4>
        <p></p>
        <h5><?php echo $CONSULT_LIST_APPLIED;?></h5>   
    </section>
    
    <section class="col-md-2 col-sm-3 col-xs-4">
        <?php echo LinkTile( "application_management","approved",$M_APPROVED_APPLICATIONS,"","green");?>
    </section>
    <section class="col-md-2 col-sm-3 col-xs-4">
        <?php echo LinkTile( "application_management","list",$JOBSEEKERS_APPLIED,"","blue");?>
    </section>
    <section class="col-md-2 col-sm-3 col-xs-4">
        <?php echo LinkTile("application_management","rejected",$M_REJECTED_APPLICATIONS,"","red");?>
    </section>
</div>

<!--LIST--> 
<div class="row list-applied-CVs">
    <section class="table-responsive col-md-12">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <td>Ngày ứng tuyển</td>
                    <td>Ứng viên</td>
                    <td>Tiêu đề công việc</td>
                    <td>Chi tiết hồ sơ</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                    <?php foreach ($CVs_applied['CVs_applied'] as $CV_applied) :?>
                <tr>
                    <td><?php echo date('d/m/Y H:i', $CV_applied['apply_date'])?></td>
                    <td><?php echo $CV_applied['first_name']?> <?php echo $CV_applied['last_name']?></td>
                    <td>
                        <a href="http://<?php echo $DOMAIN_NAME;?>/chi-tiet-cong-viec/<?php echo $CV_applied['job_id'];?>/<?php echo $CV_applied['SEO_title'];?>" title="<?php echo $CV_applied['title'];?>" target="_blank">
                            <?php echo $CV_applied['title'];?>                      
                        </a>
                    </td>
                    <td><a href="index.php?category=application_management&amp;folder=my&amp;page=details&amp;posting_id=<?php echo $CV_applied['job_id']?>&amp;apply_id=<?php echo $CV_applied['apply_id']?>"><img src="/vieclambanthoigian.com.vn/images/job-details.png" border="0"></a></td>
                    <td style="text-align: center;">
                        <a href="index.php?category=application_management&amp;folder=list&amp;page=reply&amp;Proceed=approve&amp;id=<?php echo $CV_applied['apply_id']?>&amp;posting_id=<?php echo $CV_applied['job_id']?>" style="color:green;text-decoration:underline"><b>Phê duyệt</b></a>
                        <a href="index.php?category=application_management&amp;folder=list&amp;page=reply2&amp;Proceed=reject&amp;id=<?php echo $CV_applied['apply_id']?>&amp;posting_id=<?php echo $CV_applied['job_id']?>" style="color:red;text-decoration:underline"><b>Từ chối</b></a>
                    </td>
                </tr>
                    <?php endforeach;?>
            </tbody>
        </table>
    </section>
</div>