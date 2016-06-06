<?php
/**
* Common queries Class for admin area
* @category  List of common queries for admin area
*/

class CommonsQueries_Employers {
    private $_db;
    private $_dbPrefix;

    public function __construct(MysqliDb $db) {
        global $DBprefix;
        $this->_db = $db;
        $this->_dbPrefix = $DBprefix;
    }
    
    /**
    *   get subscriptions name based on username 
    *   @param var username employer's username    
    */
    
    public function getCurrent_Subscriptions($username){
        $cols = array(
            $this->_dbPrefix."employers.subscription as current_subscription",
            $this->_dbPrefix."subscriptions.name",$this->_dbPrefix."subscriptions.description",
        );
        $this->_db->join('subscriptions', $this->_dbPrefix."employers.subscription = ".$this->_dbPrefix."subscriptions.id", "LEFT");
        $current_subscription = $this->_db->where('username', "$username")->getOne('employers', $cols);
        return $current_subscription;        
    }
    
    
    /**
    *   get subscriptions name based on username 
    *   @param var username employer's username    
    */
    
    public function Employer_Subscriptions_request($username){
        $cols = array(
            $this->_dbPrefix."subscription_employer_request.employer_id as employer_id",$this->_dbPrefix."subscription_employer_request.employer_message",
            $this->_dbPrefix."subscription_employer_request.subscription_request_type as sub_request_id",$this->_dbPrefix."subscription_employer_request.date",
            $this->_dbPrefix."subscription_employer_request.employer_message",$this->_dbPrefix."subscription_employer_request.is_processed",
            $this->_dbPrefix."employers.username",$this->_dbPrefix."employers.subscription as current_subscription",
            $this->_dbPrefix."apply_status.name as status_name",$this->_dbPrefix."apply_status.name_en as status_name_en",
            $this->_dbPrefix."subscriptions.name as subscription_current",
            "employer_current_subscription.name as request_subscription",
        );

        $this->_db->join("employers", $this->_dbPrefix."subscription_employer_request.employer_id=".$this->_dbPrefix."employers.id", "LEFT");
        $this->_db->join("apply_status", $this->_dbPrefix."subscription_employer_request.is_processed=".$this->_dbPrefix."apply_status.status_id", "LEFT");
        $this->_db->join("subscriptions", $this->_dbPrefix."employers.subscription=".$this->_dbPrefix."subscriptions.id", "LEFT");
        $this->_db->join("subscriptions as employer_current_subscription", $this->_dbPrefix."subscription_employer_request.subscription_request_type=employer_current_subscription.id", "LEFT");
        
        
        $this->_db->where('username', "$username");        
//        $this->_db->where('is_processed', $status);
        
        $subscription_requests['data'] = $this->_db->withTotalCount()->getOne('subscription_employer_request', $cols);
        $subscription_requests['totalCount'] = $this->_db->totalCount;

        return $subscription_requests;       
    }
    
    /**
    *   get subscriptions name based on username 
    *   @param var username employer's username    
    */
    
    public function get_user_messages($username){
        $data['user_messages'] = $this->_db->where('user_to', "$username")->withTotalCount()->get('user_messages'); 
        $data['totalCount'] = $this->_db->totalCount;
        
        return $data;
    }
    
    
    /**
    *   get list of CV applies with status pending/accept/rejected (0/1/2)
    *   @param var status pending/accept/rejected (0/1/2)
    */
    
    public function getCVApplieds_status($status='0', $username){
        $selected_columns = array(
            $this->_dbPrefix."apply.posting_id as job_id",$this->_dbPrefix."apply.jobseeker as jobseeker_email",
            $this->_dbPrefix."apply.id as apply_id",$this->_dbPrefix."apply.date as apply_date",
            $this->_dbPrefix."apply.message as jobseeker_message",
            $this->_dbPrefix."jobs.title",$this->_dbPrefix."jobs.SEO_title",
            $this->_dbPrefix."jobseekers.first_name",$this->_dbPrefix."jobseekers.last_name",
        );
        $this->_db->join('jobs', $this->_dbPrefix."apply.posting_id = " . $this->_dbPrefix . "jobs.id", "LEFT");
        $this->_db->join('jobseekers', $this->_dbPrefix."apply.jobseeker = " . $this->_dbPrefix . "jobseekers.username", "LEFT");
        
        $this->_db->where($this->_dbPrefix.'jobs.employer', "$username")
                ->where($this->_dbPrefix.'apply.status', $status);
        
        $CVs_applied['CVs_applied'] = $this->_db->withTotalCount()->get('apply', NULL, $selected_columns);
        $CVs_applied['totalCount'] = $this->_db->totalCount;
        
        return $CVs_applied;
    }
    
    /**
    *   get list of CV applies with status pending/accept/rejected (0/1/2)
    *   @param var status pending/accept/rejected (0/1/2)
    */
    
    public function jobs_by_employer($username){
        $jobs_by_employer_columns = array(
            $this->_dbPrefix."jobs.id as jobId",$this->_dbPrefix."jobs.title",
            $this->_dbPrefix."jobs.applications",$this->_dbPrefix."jobs.date",
            $this->_dbPrefix."job_statistics.views_count",
        );
        $this->_db->join('job_statistics', $this->_dbPrefix."jobs.id = " . $this->_dbPrefix."job_statistics.job_id", "LEFT");
        $jobs_by_employer['jobs'] = $this->_db->where("employer", "$username")->withTotalCount()->orderBy('date', 'DESC')->get("jobs", NULL, $jobs_by_employer_columns);
        $jobs_by_employer['totalCount'] = $this->_db->totalCount;
        
        return $jobs_by_employer;
    }
}

