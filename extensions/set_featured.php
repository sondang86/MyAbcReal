<?php
//    if(!defined('IN_SCRIPT')) die("");
    if ($_POST['proceed'] == "1"){        
        //instantiate neccessary classes
        require_once '../include/CommonsQueries.class.php';
        require_once '../include/MysqliDb.class.php';
        $db = new MysqliDb (Array (
                'host' => "localhost",
                'username' => "root", 
                'password' => "",
                'db'=> "viea83fe_vieclambanthoigian",
                'port' => 3306,
                'prefix' => "jobsportal_",
                'charset' => 'utf8'
            ));
        $commonQueries = new CommonsQueries($db);
        
        //Safety sanitize input data first
        $job_featured = filter_input(INPUT_POST, 'job_featured', FILTER_SANITIZE_NUMBER_INT);
        $job_id = filter_input(INPUT_POST, 'job_id', FILTER_SANITIZE_NUMBER_INT);
        $subscription = filter_input(INPUT_POST, 'subscription', FILTER_SANITIZE_NUMBER_INT);
        $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_EMAIL);
        
        //Get subscription featured jobs allowed
        $featured_jobs_allowed = $db->where('id', $subscription)
                                ->getOne('subscriptions', 'featured_listings');
        //Get total featured jobs user has been listed
        $db->withTotalCount()->where('employer', $user)->where('featured', '1')->get('jobs', NULL, 'id');
        $total_featured_listed = $db->totalCount;
        
        
        //Get job data with feature = true (1)
        $data = $commonQueries->getSingleValue('jobs', 'featured', 'id', $job_id,
            'employer', $user, '1'
        );
        
        
        if ($data !== FALSE){ // job already featured, set to un-featured
            
            if($commonQueries->updateSingleValue('jobs', 'id', $job_id, array('featured' => '0')) == TRUE){
                $message = array(
                    "message"   => "Bạn đã hủy ưu tiên việc làm này",
                    "icon"      => "active_0.gif",
                    "jobid"     => $job_id
                );
                
                echo json_encode($message);
            };
            
        } else { //set to feature
            //Check if the featured listed jobs number is exceeds the allowed featured jobs in the setting
            if($total_featured_listed >= $featured_jobs_allowed['featured_listings']){
                $message = array(
                    "message"   => "Không thể đặt thêm việc ưu tiên. Danh sách đã đầy!",
                    "icon"      => "active_0.gif",
                    "jobid"     => $job_id
                );
                echo json_encode($message);
                
            } else {//Allow update to featured job                
                if($commonQueries->updateSingleValue('jobs', 'id', $job_id, array('featured' => '1')) == TRUE){//Success
                    $message = array(
                        "message"   => "Đặt thành công ưu tiên việc làm này",
                        "icon"      => "active_1.gif",
                        "jobid"     => $job_id
                    );
                    
                    echo json_encode($message);
                };            
            }
        }        
    }
