<?php
    /**
    * Common queries Class
    * @category  List of common queries
    */
    class CommonsQueries {
        private $_db;
        
        public function __construct(MysqliDb $db) {
            $this->_db = $db;
        }

        
        /**
        * Get specific data by id or all data
        * @param var $table table name
        * @param var $id 
        * @param var $column column in WHERE condition 
        */
        public function get_data($table="categories",$column="", $id="") {
            if(empty($id)){
                $this->_db->orderBy("id","asc");
                return $this->_db->get($table);
            } else { 
                $this->_db->where($column, $id);
                return $this->_db->get($table);
            }
        }
        
        /**
        * Output "selected" text if 2 params equal
        *  
        */
        public function Selected($param1, $param2) {
            if ($param1 == $param2){
                echo "selected";
            } else {
                return NULL;
            }
        }
        
        /**
        *  Output "Yes 1" or "No 0" texts based on language id
        *  @param var $value value 1 or 0
        *  @param int $lang_id language Id 1 or 2 default is 2 Vietnamese
        */
        public function YesOrNo($value, $lang_id=2) {
            if ($lang_id == 1){
                switch ($value) { //English
                    case "1": echo "Yes";
                    break;

                    case "0": echo "No";
                    break;
                }
            } elseif ($lang_id == 2) { //Vietnamese
                switch ($value) { //English
                    case "1": echo "Có";
                    break;

                    case "0": echo "Không";
                    break;
                }    
            }
        }
        
        /**
        *  Count records matched by id
        *  @param var $column column to be count in table
        *  @param var $id id of the selected column
         * @param var $table table name
        */
        public function countRecords($column="job_category", $id="1", $table="jobs") {
            $this->_db->where ($column, $id);
            $stats = ((object)$this->_db->getOne ($table, "count(*) as count"));
            return $stats;
        }
        
        /**
        *  Find jobs by id
        *  @param var $table table name
        *  @param var $column specific column in table
        *  @param var $id id or name of the selected column
        */
        public function job_by_id($table="jobs",$column="employer", $id="1") {
            if (!empty($column)){
                $this->_db->where ($column, $id);
            }
            $data = ((object)$this->_db->get($table));
            return $data;
        }

    }
