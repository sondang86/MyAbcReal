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
        $subscription_id = filter_input(INPUT_POST, 'subscription', FILTER_SANITIZE_NUMBER_INT);
        $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_EMAIL);        
        //Get subscriptions
        $subscription = $db->where('id', $subscription_id)->getOne('subscriptions', array('featured_listings', 'urgent_listings'));
        
        //Featured processing
        if ($_POST['request'] == 'set_featured'){
            //Get total featured jobs user has been listed
            $db->withTotalCount()->where('employer', $user)->where('featured', '1')->get('jobs', NULL, 'id');
            $total_featured_listed = $db->totalCount;


            //Get job data that has already featured
            $db->where('featured', '1')->where('id', $job_id)->where('employer', "$user")->withTotalCount()->getOne('jobs');

            if ($db->totalCount > 0){ // job already featured, set to un-featured

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
                if($total_featured_listed >= $subscription['featured_listings']){
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
        
        //Urgent processing
        if ($_POST['request'] == 'set_urgent'){
            //Get total urgent jobs user has been listed
            $db->withTotalCount()->where('employer', $user)->where('urgent', '1')->get('jobs');
            $total_urgent_listed = $db->totalCount;
            
            //Get job data that has already listed as urgent
            $db->where('urgent', '1')->where('id', $job_id)->where('employer', "$user")->withTotalCount()->getOne('jobs');            

            if ($db->totalCount > 0){ // set to un-featured

                if($commonQueries->updateSingleValue('jobs', 'id', $job_id, array('urgent' => '0')) == TRUE){//Success
                    $message = array(
                        "message"   => "Bạn đã hủy tuyển dụng gấp này",
                        "icon"      => "active_0.gif",
                        "jobid"     => $job_id
                    );

                    echo json_encode($message);
                };

            } else { //set to feature
                //Check if the featured listed jobs number is exceeds the allowed featured jobs in the setting
                if($total_urgent_listed >= $subscription['urgent_listings']){
                    $message = array(
                        "message"   => "Không thể đặt thêm tuyển dụng gấp. Danh sách đã đầy!",
                        "icon"      => "active_0.gif",
                        "jobid"     => $job_id
                    );
                    echo json_encode($message);

                } else {//Allow update to featured job                
                    if($commonQueries->updateSingleValue('jobs', 'id', $job_id, array('urgent' => '1')) == TRUE){//Success
                        $message = array(
                            "message"   => "Đặt thành công việc làm này với trạng thái tuyển dụng gấp",
                            "icon"      => "active_1.gif",
                            "jobid"     => $job_id
                        );

                        echo json_encode($message);
                    };            
                }
            }
        }
        
    }
    
