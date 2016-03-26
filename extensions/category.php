<?php
if(!defined('IN_SCRIPT')) die("");
global $db;
if (isset($_GET['id'])){
    //sanitize first
    $website->ms_i($_GET['id']); 
    
    //Find jobs based on specific category
    $jobsInfo_columns = Array (
        "jobsportal_jobs.id as job_id","jobsportal_jobs.date", "jobsportal_jobs.employer", //
        "jobsportal_jobs.job_category", "jobsportal_jobs.region", "jobsportal_jobs.title", "jobsportal_jobs.expires", //
        "jobsportal_jobs.message", "jobsportal_jobs.job_type", "jobsportal_jobs.salary","jobsportal_jobs.applications", // Main table
        "jobsportal_categories.category_name_vi","jobsportal_categories.category_id", //Categories table
        "jobsportal_salary.salary_id","jobsportal_salary.salary_range", //Salary table
        "jobsportal_locations.City","jobsportal_locations.id as location_id" //Locations table
        );
    $db->join('categories', "jobsportal_jobs.job_category = jobsportal_categories.category_id", "LEFT");
    $db->join('salary', "jobsportal_jobs.salary = jobsportal_salary.salary_id", "LEFT");
    $db->join('locations', "jobsportal_jobs.region = jobsportal_locations.id", "LEFT");
    $db->where('job_category', filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
    $jobs_list = $db->withTotalCount()->get('jobs', NULL, $jobsInfo_columns);
    if ($db->totalCount > 0){ //List found records
?>

<div class="container">
    <!--TITLE-->
    <section class="row">
        <label class="col-md-8">Ngành: <?php echo $jobs_list[0]['category_name_vi'];?></label>
    </section>
    
    <!--LIST JOBS-->
    <div class="row">
        <?php foreach ($jobs_list as $job) :?>
            <section class="col-md-8 category-details row">
                <figure class="col-md-3">
                    <img src="#">
                    <p>Việc làm khác từ TEK</p>
                    <p>Thông tin công ty</p>
                </figure>
                <article class="col-md-9">
                    <h5><strong>Tiêu đề công việc:</strong> <?php echo $job['title']?></h5>
                    <p><label>Mức lương (USD):</label> <?php echo $job['salary_range']?></p>
                    <p><label>Nơi làm việc:</label> <?php echo $job['City']?></p>
                    <p><label>Ngày đăng:</label> <?php echo date('Y-m-d',$job['date'])?></p>
                    <p><label>Ngày hết hạn:</label> <?php echo date('Y-m-d',$job['expires'])?></p>
                    <p><label>Ứng viên đã ứng tuyển:</label> <?php echo $job['applications']?></p>
                    <main><?php echo $website->limitCharacters(nl2br($job['message']), 200)?></main>
                </article>
                <nav>
                    <span class="col-md-3 pull-right"><a href="index.php?mod=apply_job&posting_id=<?php echo $job['job_id']?>&lang=vn">Nộp hồ sơ</a></span>
                    <span class="col-md-3 pull-right"><a href="index.php?mod=details&id=<?php echo $job['job_id']?>&lang=vn">Chi tiết công việc</a></span>
                </nav>
            </section>
        <?php endforeach;?>
    </div>
</div>
    <?php } else { // not found any records
     echo "Oopps, Not found any records!";
 }
}
?>