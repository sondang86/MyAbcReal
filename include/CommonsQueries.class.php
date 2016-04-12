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
        *  Find jobs by employer id, default find by employer Id 1
        * 
        *  @param var $id id or name of the selected column
        *  @param var $limit limit the number of records to be appear
        */
        public function jobs_by_employerId($id="1", $limit=NULL) {
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
                $DBprefix."employers.id as employer_id",$DBprefix."employers.username as employer_username",
                $DBprefix."employers.logo",$DBprefix."employers.company"
            );

            $this->_db->join("categories", $DBprefix."jobs.job_category=".$DBprefix."categories.category_id", "LEFT");
            $this->_db->join("job_types", $DBprefix."jobs.job_type=".$DBprefix."job_types.id", "LEFT");
            $this->_db->join("salary", $DBprefix."jobs.salary=".$DBprefix."salary.salary_id", "LEFT");
            $this->_db->join("locations", $DBprefix."jobs.region=".$DBprefix."locations.id", "LEFT");
            $this->_db->join("employers", $DBprefix."jobs.employer=".$DBprefix."employers.username", "LEFT");
            
            //Find jobs by id if id not empty
            if(!empty($id)){
                $this->_db->where ($DBprefix."employers.id", "$id");
            }
            
            $data = $this->_db->withTotalCount()->get("jobs", $limit, $selected_columns);
            
            if($this->_db->totalCount > 0){
                return $data;
            } else {
                return FALSE;
            }
        }

        /**
        *  Find jobs by category or location or another types
        * 
        *  @param var $type id or name of the selected column
        *  @param var $limit limit the number of records to be appear
        */
        public function jobs_by_type($type="job_category", $limit=NULL) {
            global $DBprefix;
            //Find jobs based on specific category
            $jobsInfo_columns = Array (
                $DBprefix."jobs.id as job_id",$DBprefix."jobs.date", $DBprefix."jobs.employer", 
                $DBprefix."jobs.job_category", $DBprefix."jobs.region", $DBprefix."jobs.title", $DBprefix."jobs.expires", //
                $DBprefix."jobs.message", $DBprefix."jobs.job_type", $DBprefix."jobs.salary","jobsportal_jobs.applications", // Main table
                $DBprefix."categories.category_name_vi",$DBprefix."categories.category_id", //Categories table
                $DBprefix."salary.salary_id",$DBprefix."salary.salary_range", //Salary table
                $DBprefix."locations.City",$DBprefix."locations.id as location_id", //Locations table
                $DBprefix."employers.id as employer_id",$DBprefix."employers.company as company",$DBprefix."employers.logo as company_logo" //Employer table
                );
            $this->_db->join('categories', "jobsportal_jobs.job_category = jobsportal_categories.category_id", "LEFT");
            $this->_db->join('salary', "jobsportal_jobs.salary = jobsportal_salary.salary_id", "LEFT");
            $this->_db->join('locations', "jobsportal_jobs.region = jobsportal_locations.id", "LEFT");
            $this->_db->join('employers', "jobsportal_jobs.employer = jobsportal_employers.username", "LEFT");
            $this->_db->where($type, filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
            $jobs_list = $this->_db->withTotalCount()->get('jobs', NULL, $jobsInfo_columns);
            if ($this->_db->totalCount > 0){
                return $jobs_list;
            } else {
                return FALSE;
            }
        }
        
        /**
        *  Search jobs function
        * 
        *  @param var $type id or name of the selected column
        *  @param var $limit limit the number of records to be appear
        */
        public function jobs_by_keywords($queryString="",$category="",$location="", $limit=NULL){
            global $DBprefix;
            $jobsInfo_columns = Array (
                $DBprefix."jobs.id as job_id",$DBprefix."jobs.date", $DBprefix."jobs.employer",
                $DBprefix."jobs.SEO_title",$DBprefix."jobs.job_category", 
                $DBprefix."jobs.region",$DBprefix."jobs.featured",
                $DBprefix."jobs.title", $DBprefix."jobs.expires", //
                $DBprefix."jobs.message", $DBprefix."jobs.job_type", 
                $DBprefix."jobs.salary",$DBprefix."jobs.applications", // Main table
                $DBprefix."job_types.job_name",$DBprefix."job_types.job_name_en",
                $DBprefix."categories.category_name_vi",$DBprefix."categories.category_id", //Categories table
                $DBprefix."salary.salary_id",$DBprefix."salary.salary_range", //Salary table
                $DBprefix."locations.City",$DBprefix."locations.id as location_id", //Locations table
                $DBprefix."employers.id as employer_id",$DBprefix."employers.company as company",$DBprefix."employers.logo as company_logo" //Employer table
            );
            $this->_db->join('categories', $DBprefix."jobs.job_category =".$DBprefix."categories.category_id", "LEFT");
            $this->_db->join('salary', $DBprefix."jobs.salary = ".$DBprefix."salary.salary_id", "LEFT");
            $this->_db->join('job_types', $DBprefix."jobs.job_type = ".$DBprefix."job_types.id", "LEFT");
            $this->_db->join('locations', $DBprefix."jobs.region = ".$DBprefix."locations.id", "LEFT");
            $this->_db->join('employers', $DBprefix."jobs.employer = ".$DBprefix."employers.username", "LEFT");
            
            //Check conditions
            if(!empty($queryString)){
                $this->_db->where("title LIKE '%" . $queryString ."%'");
            }
            if(!empty($category)){
                $this->_db->where("job_category", $category);
            }                        
            if(!empty($location)){
                $this->_db->where("region", $location);
            }
            
            //Order by featured first
            $this->_db->orderBy("featured", "DESC");
            $this->_db->orderBy("date", "DESC");
            $jobs_list['data'] = $this->_db->withTotalCount()->get("jobs", NULL, $jobsInfo_columns);
            $jobs_list['totalCount'] = $this->_db->totalCount;
            
            
            if ($this->_db->totalCount > 0){
                return $jobs_list;
            } else {
                return FALSE;
            }
        }
        
        
        /**
        *  check id is exists or not
        * 
        *  @param var $type $_GET type to check
        *  @param var $SEO_setting setting of SEO URL (0 or 1)
        *  @param var $segment segment of the URL (default is 3)
        */
        
        public function check_present_id($type, $SEO_setting="0", $segment="3"){
            global $website;
            if ($SEO_setting == "0"){// SEO is disabled
                //Make sure ID not empty
                if (!isset($_GET[$type])){
                    die("the ID wasn't set yet");
                } else {
                    //Sanitize data
                    $filted_type = filter_input(INPUT_GET, $type, FILTER_SANITIZE_NUMBER_INT);
                    $website->ms_i($filted_type); 

                    $value = $filted_type;    
                }
            } else { //SEO enabled   
                $value = $website->getURL_segment($website->currentURL(),$segment);    
            }
            
            return $value;
       }
       
       /**
        *  Convert True or False to integer
        * 
        *  @param var $data data input is either string TRUE or FALSE
        *  
        */
        
        public function convert_TrueFalse($data){
            if($data == true){
                $data = 1;
            } else {
                $data = 0;
            }
            return $data;
        }
        
        /**
        *  calculate and output time ago result
        * 
        *  @param var $time input time in Unix timestamp, the calculation is current time - $time
        *  @param var $lang time language array s/m/h/d/w/m/y/decade
        */
        public function time_ago($time, $lang=array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thập kỷ")){
           $periods = $lang;
           $lengths = array("60","60","24","7","4.35","12","10");

           $now = time();

               $difference     = $now - $time;
               $tense         = "ago";

           for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
               $difference /= $lengths[$j];
           }

           $difference = round($difference);

           if($difference != 1) {
               $periods[$j].= "";
           }

           return "$difference $periods[$j] trước ";
        }
        
        
        
        /**
        *  Calculate Real Differences Between Two Dates or Timestamps
        *  Time format is UNIX timestamp or PHP strtotime compatible strings
        *  @param var $time1 input current time in Unix timestamp or strtotime 
        *  @param var $time2 time language array s/m/h/d/w/m/y/decade
        */
        function timeCalculation($time1, $time2, $precision = 6) {
          // If not numeric then convert texts to unix timestamps
          if (!is_int($time1)) {
            $time1 = strtotime($time1);
          }
          if (!is_int($time2)) {
            $time2 = strtotime($time2);
          }

          // If time1 is bigger than time2
          // Then swap time1 and time2
          if ($time1 > $time2) {
            $ttime = $time1;
            $time1 = $time2;
            $time2 = $ttime;
          }

          // Set up intervals and diffs arrays
          $intervals = array('year','month','day','hour','minute','second');
          $diffs = array();

          // Loop thru all intervals
          foreach ($intervals as $interval) {
            // Create temp time from time1 and interval
            $ttime = strtotime('+1 ' . $interval, $time1);
            // Set initial values
            $add = 1;
            $looped = 0;
            // Loop until temp time is smaller than time2
            while ($time2 >= $ttime) {
              // Create new temp time from time1 and interval
              $add++;
              $ttime = strtotime("+" . $add . " " . $interval, $time1);
              $looped++;
            }

            $time1 = strtotime("+" . $looped . " " . $interval, $time1);
            $diffs[$interval] = $looped;
          }

          $count = 0;
          $times = array();
          // Loop thru all diffs
          foreach ($diffs as $interval => $value) {
            // Break if we have needed precission
            if ($count >= $precision) {
              break;
            }
            // Add value and interval 
            // if value is bigger than 0
            if ($value > 0) {
              // Add s if value is not 1
              if ($value != 1) {
                $interval .= "s";
              }
              // Add value and interval to times array
              $times[] = $value . " " . $interval;
              $count++;
            }
          }

          // Return string with times
          return implode(", ", $times);
        }
        
        
        /**
        *  Get saved jobs in the last xxx days
        *  @param var $day the last days range where you want to retrieve
        */
        public function getSavedJobs($day="1") {
            global $DBprefix;
            $jobsInfo_columns = Array (
                $DBprefix."jobs.id as job_id",$DBprefix."jobs.date", $DBprefix."jobs.employer",
                $DBprefix."jobs.SEO_title",$DBprefix."jobs.job_category", 
                $DBprefix."jobs.region",$DBprefix."jobs.featured",
                $DBprefix."jobs.title", $DBprefix."jobs.expires", //
                $DBprefix."jobs.message", $DBprefix."jobs.job_type", 
                $DBprefix."jobs.salary",$DBprefix."jobs.applications", // Main table
                $DBprefix."job_types.job_name",$DBprefix."job_types.job_name_en",
                $DBprefix."categories.category_name_vi",$DBprefix."categories.category_id", //Categories table
                $DBprefix."salary.salary_id",$DBprefix."salary.salary_range", //Salary table
                $DBprefix."locations.City",$DBprefix."locations.id as location_id", //Locations table
                $DBprefix."employers.id as employer_id",$DBprefix."employers.company as company",$DBprefix."employers.logo as company_logo", //Employer table
                $DBprefix."saved_jobs.job_id as saved_jobId",$DBprefix."saved_jobs.user_type as saved_job_userType",$DBprefix."saved_jobs.date as saved_jobDate" //saved jobs table
            );
            
            $this->_db->join('categories', $DBprefix."jobs.job_category =".$DBprefix."categories.category_id", "LEFT");
            $this->_db->join('salary', $DBprefix."jobs.salary = ".$DBprefix."salary.salary_id", "LEFT");
            $this->_db->join('job_types', $DBprefix."jobs.job_type = ".$DBprefix."job_types.id", "LEFT");
            $this->_db->join('locations', $DBprefix."jobs.region = ".$DBprefix."locations.id", "LEFT");
            $this->_db->join('employers', $DBprefix."jobs.employer = ".$DBprefix."employers.username", "LEFT");
            $this->_db->join('saved_jobs', $DBprefix."jobs.id = ".$DBprefix."saved_jobs.job_id", "LEFT");
            
            //Get user saved jobs in the last 1 days
            //http://dba.stackexchange.com/questions/97211/get-rows-where-lastlogintimestamp-is-in-last-7-days
            $data['saved_jobs'] = $this->_db->where($DBprefix."saved_jobs.date >= CAST(UNIX_TIMESTAMP(NOW() - INTERVAL $day DAY) AS CHAR(10))")//http://dba.stackexchange.com/questions/97211/get-rows-where-lastlogintimestamp-is-in-last-7-days
            ->where("user_uniqueId",filter_input(INPUT_COOKIE,'userId', FILTER_SANITIZE_STRING))
            ->where("IPAddress", filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP))
            ->withTotalCount()->get("jobs", NULL, $jobsInfo_columns);
            
            //Total records found
            $data['totalCount'] = $this->_db->totalCount;
            
            if ($this->_db->totalCount > 0) {
                return $data;
            } else {
                return FALSE; //No records found
            }
            
        }
    }
