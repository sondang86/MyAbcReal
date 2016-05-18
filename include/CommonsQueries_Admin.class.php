<?php
/**
* Common queries Class for admin area
* @category  List of common queries for admin area
*/

class CommonsQueries_Admin {
    private $_db;
    private $_dbPrefix;

    public function __construct(MysqliDb $db) {
        global $DBprefix;
        $this->_db = $db;
        $this->_dbPrefix = $DBprefix;
    }
    
    /**
    *   get subscriptions list based on status (pending/approved/rejected)
    *   @param var logo logo's name
    *   @param var user_type could be employers or jobseekers, default is employers
    */
    public function getSubscriptions_List($status="0"){
        $cols = array(
            $this->_dbPrefix."subscription_employer_request.employer_id as employer_id",$this->_dbPrefix."subscription_employer_request.employer_message",
            $this->_dbPrefix."subscription_employer_request.subscription_request_type as sub_request_id",$this->_dbPrefix."subscription_employer_request.date",
            $this->_dbPrefix."employers.username",$this->_dbPrefix."employers.subscription as current_sucscription",
            $this->_dbPrefix."apply_status.name as status_name",$this->_dbPrefix."apply_status.name_en as status_name_en",
            $this->_dbPrefix."subscriptions.name as subscription_current",
            "employer_current_subscription.name as request_subscription",
        );

        $this->_db->join("employers", $this->_dbPrefix."subscription_employer_request.employer_id=".$this->_dbPrefix."employers.id", "LEFT");
        $this->_db->join("apply_status", $this->_dbPrefix."subscription_employer_request.is_processed=".$this->_dbPrefix."apply_status.status_id", "LEFT");
        $this->_db->join("subscriptions", $this->_dbPrefix."employers.subscription=".$this->_dbPrefix."subscriptions.id", "LEFT");
        $this->_db->join("subscriptions as employer_current_subscription", $this->_dbPrefix."subscription_employer_request.subscription_request_type=employer_current_subscription.id", "LEFT");
        $subscription_requests = $this->_db->where('is_processed', $status)->withTotalCount()->get('subscription_employer_request', NULL, $cols);

        return $subscription_requests;
    }
}

