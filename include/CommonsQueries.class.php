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
        
        
        /**
        *  Find jobs by employer id
        * 
        *  @param var $id id or name of the selected column
        */
        public function jobs_by_employerId($id="1") {
            global $DBprefix;
            $selected_columns = array(
                $DBprefix."jobs.id as job_id",$DBprefix."jobs.employer",$DBprefix."jobs.job_category",
                $DBprefix."jobs.experience",$DBprefix."jobs.region",$DBprefix."jobs.title",$DBprefix."jobs.SEO_title",
                $DBprefix."jobs.message",$DBprefix."jobs.active",$DBprefix."jobs.featured",
                $DBprefix."jobs.job_type",$DBprefix."jobs.salary",$DBprefix."jobs.status",
                $DBprefix."locations.City",$DBprefix."locations.City_en",
                $DBprefix."categories.category_name_vi",$DBprefix."categories.category_name",
                $DBprefix."job_types.job_name",$DBprefix."job_types.job_name_en",
                $DBprefix."salary.salary_range",$DBprefix."salary.salary_range_en",
                $DBprefix."employers.id as employer_id",$DBprefix."employers.username as employer_username",$DBprefix."employers.logo"
            );

            $this->_db->join("categories", $DBprefix."jobs.job_category=".$DBprefix."categories.category_id", "LEFT");
            $this->_db->join("job_types", $DBprefix."jobs.job_type=".$DBprefix."job_types.id", "LEFT");
            $this->_db->join("salary", $DBprefix."jobs.salary=".$DBprefix."salary.salary_id", "LEFT");
            $this->_db->join("locations", $DBprefix."jobs.region=".$DBprefix."locations.id", "LEFT");
            $this->_db->join("employers", $DBprefix."jobs.employer=".$DBprefix."employers.username", "LEFT");
            $this->_db->where ($DBprefix."employers.id", "$id");
            
            $data = $this->_db->withTotalCount()->get("jobs", NULL, $selected_columns);
            
            if($this->_db->totalCount > 0){
                return $data;
            } else {
                return FALSE;
            }
        }
        
        /**
        *  check id parameter is exists or not
        * 
        *  @param var $type $_GET type to check
        *  @param var $SEO_setting setting of SEO URL (0 or 1)
        *  @param var $segment segment of the URL (default is 3)
        */
        
        public function check_present_id($type, $SEO_setting="0", $segment="3"){
            global $website;
            
            //Validate first
           
            if(!isset($_GET[$type])){
                die("The $type ID isn't set");
            }
            $website->ms_i($_GET[$type]); 
                
            //Get id number
            if ($SEO_setting == "0"){// SEO is disabled
                $value = $_GET[$type];    
            } else {    
                $value = $website->getURL_segment($website->currentURL(),$segment);    
            }
            
            return $value;
       }
    }
