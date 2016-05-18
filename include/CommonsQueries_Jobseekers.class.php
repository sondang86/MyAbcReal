<?php
/**
* Common queries Class for admin area
* @category  List of common queries for admin area
*/

class CommonsQueries_Jobseekers {
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
        
    }
}

