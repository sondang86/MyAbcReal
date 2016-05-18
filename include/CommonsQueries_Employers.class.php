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
}

