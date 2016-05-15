<?php
    /**
    * Common queries Class
    * @category  List of common queries
    */
    class CommonsQueries {
        private $_db;
        private $_dbPrefix;
            
        public function __construct(MysqliDb $db) {
            global $DBprefix;
            $this->_db = $db;
            $this->_dbPrefix = $DBprefix;
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
        *  Check if value empty will output n/a message instead
        * 
        *  @param var value value to be check
        *  @param var method method to be use when value not null
        * 
        */
        public function check_nA($value, $method){
            if ($value !== ""){
                echo $method;
            } else {
                echo "N/A";
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
        *  @param var $where specific where condition
        *  @param var $id id or name of the selected column
        *  @param var $limit limit records to be selected, default is NULL
        *  @param var $column specific columns to be selected
        */
        public function job_by_id($table="jobs",$where="employer", $id="1",$limit=NULL, $columns="") {
            if (!empty($where)){
                $this->_db->where ($where, $id);
            }
            $this->_db->orderBy("date", "DESC");
            $data = ((object)$this->_db->get($table,$limit,$columns));
            return $data;
        }
            
            
        /**
        *  Find jobs by employer id, default find by employer Id 1
        * 
        *  @param var $id id or name of the selected column
        *  @param var $limit limit the number of records to be appear
        */
        public function jobs_by_employerId($id="1", $limit=NULL) {
            $selected_columns = array(
                $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.employer",$this->_dbPrefix."jobs.job_category",
                $this->_dbPrefix."jobs.experience",$this->_dbPrefix."jobs.region",$this->_dbPrefix."jobs.title",$this->_dbPrefix."jobs.SEO_title",
                $this->_dbPrefix."jobs.message",$this->_dbPrefix."jobs.active",$this->_dbPrefix."jobs.featured",
                $this->_dbPrefix."jobs.job_type",$this->_dbPrefix."jobs.salary",$this->_dbPrefix."jobs.status",
                $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.City_en",
                $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_name",
                $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
                $this->_dbPrefix."salary.salary_range",$this->_dbPrefix."salary.salary_range_en",
                $this->_dbPrefix."employers.id as employer_id",$this->_dbPrefix."employers.username as employer_username",
                $this->_dbPrefix."employers.logo",$this->_dbPrefix."employers.company"
            );
                
            $this->_db->join("categories", $this->_dbPrefix."jobs.job_category=".$this->_dbPrefix."categories.category_id", "LEFT");
            $this->_db->join("job_types", $this->_dbPrefix."jobs.job_type=".$this->_dbPrefix."job_types.id", "LEFT");
            $this->_db->join("salary", $this->_dbPrefix."jobs.salary=".$this->_dbPrefix."salary.salary_id", "LEFT");
            $this->_db->join("locations", $this->_dbPrefix."jobs.region=".$this->_dbPrefix."locations.id", "LEFT");
            $this->_db->join("employers", $this->_dbPrefix."jobs.employer=".$this->_dbPrefix."employers.username", "LEFT");
                
            //Find jobs by id if id not empty
            if(!empty($id)){
                $this->_db->where ($this->_dbPrefix."employers.id", "$id");
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
            //Find jobs based on specific category
            $jobsInfo_columns = Array (
                $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.date", $this->_dbPrefix."jobs.employer", 
                $this->_dbPrefix."jobs.job_category", $this->_dbPrefix."jobs.region", $this->_dbPrefix."jobs.title", $this->_dbPrefix."jobs.expires", //
                $this->_dbPrefix."jobs.message", $this->_dbPrefix."jobs.job_type", $this->_dbPrefix."jobs.salary","jobsportal_jobs.applications", // Main table
                $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_id", //Categories table
                $this->_dbPrefix."salary.salary_id",$this->_dbPrefix."salary.salary_range", //Salary table
                $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.id as location_id", //Locations table
                $this->_dbPrefix."employers.id as employer_id",$this->_dbPrefix."employers.company as company",$this->_dbPrefix."employers.logo as company_logo" //Employer table
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
        public function jobs_by_keywords($queryString="",$category="",$location="", $limit=NULL, $userId_cookie=""){
            $jobsInfo_columns = Array (
                $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.date", $this->_dbPrefix."jobs.employer",
                $this->_dbPrefix."jobs.SEO_title",$this->_dbPrefix."jobs.job_category", 
                $this->_dbPrefix."jobs.region",$this->_dbPrefix."jobs.featured",
                $this->_dbPrefix."jobs.title", $this->_dbPrefix."jobs.expires", //
                $this->_dbPrefix."jobs.message", $this->_dbPrefix."jobs.job_type", 
                $this->_dbPrefix."jobs.salary",$this->_dbPrefix."jobs.applications", // Main table
                $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
                $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_id", //Categories table
                $this->_dbPrefix."salary.salary_id",$this->_dbPrefix."salary.salary_range", //Salary table
                $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.id as location_id", //Locations table
                $this->_dbPrefix."employers.id as employer_id",$this->_dbPrefix."employers.company as company",$this->_dbPrefix."employers.logo as company_logo", //Employer table
                $this->_dbPrefix."saved_jobs.user_type as saved_job_userType",$this->_dbPrefix."saved_jobs.date as saved_jobDate",
                $this->_dbPrefix."saved_jobs.browser", $this->_dbPrefix."saved_jobs.IPAddress",  
                $this->_dbPrefix."saved_jobs.user_uniqueId as user_uniqueId",$this->_dbPrefix."saved_jobs.job_id as saved_jobId"//saved jobs table
            );
            $this->_db->join('categories', $this->_dbPrefix."jobs.job_category =".$this->_dbPrefix."categories.category_id", "LEFT");
            $this->_db->join('salary', $this->_dbPrefix."jobs.salary = ".$this->_dbPrefix."salary.salary_id", "LEFT");
            $this->_db->join('job_types', $this->_dbPrefix."jobs.job_type = ".$this->_dbPrefix."job_types.id", "LEFT");
            $this->_db->join('locations', $this->_dbPrefix."jobs.region = ".$this->_dbPrefix."locations.id", "LEFT");
            $this->_db->join('employers', $this->_dbPrefix."jobs.employer = ".$this->_dbPrefix."employers.username", "LEFT");
            $this->_db->join('saved_jobs', $this->_dbPrefix."jobs.id = ".$this->_dbPrefix."saved_jobs.job_id AND "
                    .$this->_dbPrefix."saved_jobs.user_uniqueId = '$userId_cookie' AND "
                    .$this->_dbPrefix."saved_jobs.IPAddress = '".filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP)."' ", "LEFT");
                        
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
                
            //Pagination options
            //Set current page to 1 if empty
            if (isset($_GET['trang'])){
                $current_page = filter_input(INPUT_GET, 'trang', FILTER_SANITIZE_NUMBER_INT);
            } else {
                $current_page = 1;
            }
            // set page limit to 2 results per page. 20 by default
            $this->_db->pageLimit = 10;
            $jobs_list['data'] = $this->_db->arraybuilder()->withTotalCount()->paginate("jobs", $current_page,$jobsInfo_columns);            
            $jobs_list['totalCount'] = $this->_db->totalCount; 
            $jobs_list['current_page'] = $current_page;
            $jobs_list['total_pages'] = $this->_db->totalPages;
                
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
        *  calculate and output time ago result, the calculation method is current time - $time
        * 
        *  @param var $time input time in Unix timestamp
        *  @param var $lang time language array s/m/h/d/w/m/y/decade
        */
        public function time_ago($time, $lang=array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm")){
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
            $jobsInfo_columns = Array (
                $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.date", $this->_dbPrefix."jobs.employer",
                $this->_dbPrefix."jobs.SEO_title",$this->_dbPrefix."jobs.job_category", 
                $this->_dbPrefix."jobs.region",$this->_dbPrefix."jobs.featured",
                $this->_dbPrefix."jobs.title", $this->_dbPrefix."jobs.expires", //
                $this->_dbPrefix."jobs.message", $this->_dbPrefix."jobs.job_type", 
                $this->_dbPrefix."jobs.salary",$this->_dbPrefix."jobs.applications", // Main table
                $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
                $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_id", //Categories table
                $this->_dbPrefix."salary.salary_id",$this->_dbPrefix."salary.salary_range", //Salary table
                $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.id as location_id", //Locations table
                $this->_dbPrefix."employers.id as employer_id",$this->_dbPrefix."employers.company as company",$this->_dbPrefix."employers.logo as company_logo", //Employer table
                $this->_dbPrefix."saved_jobs.job_id as saved_jobId",$this->_dbPrefix."saved_jobs.user_type as saved_job_userType",
                $this->_dbPrefix."saved_jobs.date as saved_jobDate",$this->_dbPrefix."saved_jobs.user_uniqueId as user_uniqueId" //saved jobs table
            );
                
            $this->_db->join('categories', $this->_dbPrefix."jobs.job_category =".$this->_dbPrefix."categories.category_id", "LEFT");
            $this->_db->join('salary', $this->_dbPrefix."jobs.salary = ".$this->_dbPrefix."salary.salary_id", "LEFT");
            $this->_db->join('job_types', $this->_dbPrefix."jobs.job_type = ".$this->_dbPrefix."job_types.id", "LEFT");
            $this->_db->join('locations', $this->_dbPrefix."jobs.region = ".$this->_dbPrefix."locations.id", "LEFT");
            $this->_db->join('employers', $this->_dbPrefix."jobs.employer = ".$this->_dbPrefix."employers.username", "LEFT");
            $this->_db->join('saved_jobs', $this->_dbPrefix."jobs.id = ".$this->_dbPrefix."saved_jobs.job_id", "LEFT");
                
            //Get user saved jobs in the last 1 days
            //http://dba.stackexchange.com/questions/97211/get-rows-where-lastlogintimestamp-is-in-last-7-days
            $data['saved_jobs'] = $this->_db->where($this->_dbPrefix."saved_jobs.date >= CAST(UNIX_TIMESTAMP(NOW() - INTERVAL $day DAY) AS CHAR(10))")//http://dba.stackexchange.com/questions/97211/get-rows-where-lastlogintimestamp-is-in-last-7-days
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
            
        /**
        *  Count total records present in table
        *  @param var $table table where retrieve data
        *  @param var $columns columns to be selected
        */
        public function countAllRecords($table="jobs", $columns=array("id")){
            $this->_db->withTotalCount()->get($table, NULL, $columns);
            return $this->_db->totalCount;
        }
            
        /**
        *  Find job by id and all of details information
        * 
        *  @param var $id job's id
        *  
        */
            
       public function jobDetails($id, $userId_cookie=""){
           $columns = array(
                $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.job_category",
                $this->_dbPrefix."jobs.applications",$this->_dbPrefix."jobs.employer",
                $this->_dbPrefix."jobs.requires_description",$this->_dbPrefix."jobs.benefits_description",
                $this->_dbPrefix."jobs.profileCV_description",
                $this->_dbPrefix."jobs.title",$this->_dbPrefix."jobs.SEO_title",$this->_dbPrefix."jobs.date as date",
                $this->_dbPrefix."jobs.message",$this->_dbPrefix."employers.company",$this->_dbPrefix."employers.logo",$this->_dbPrefix."employers.id as employer_id",
                $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_name",$this->_dbPrefix."categories.id as category_id",
                $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.City_en",$this->_dbPrefix."locations.id as location_id",
                $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
                $this->_dbPrefix."job_experience.name as experience_name",$this->_dbPrefix."job_experience.name_en as experience_name_en",
                $this->_dbPrefix."salary.salary_range",$this->_dbPrefix."salary.salary_range_en",
                $this->_dbPrefix."saved_jobs.user_type as saved_job_userType",$this->_dbPrefix."saved_jobs.date as saved_jobDate",
                $this->_dbPrefix."saved_jobs.browser", $this->_dbPrefix."saved_jobs.IPAddress",  
                $this->_dbPrefix."saved_jobs.user_uniqueId as user_uniqueId",$this->_dbPrefix."saved_jobs.job_id as saved_jobId",//saved jobs table
                $this->_dbPrefix."job_statistics.views_count"
            );
                
            $this->_db->join("employers", $this->_dbPrefix."jobs.employer=".$this->_dbPrefix."employers.username", "LEFT");
            $this->_db->join("categories", $this->_dbPrefix."jobs.job_category=".$this->_dbPrefix."categories.category_id", "LEFT");
            $this->_db->join("locations", $this->_dbPrefix."jobs.region=".$this->_dbPrefix."locations.id", "LEFT");
            $this->_db->join("job_types", $this->_dbPrefix."jobs.job_type=".$this->_dbPrefix."job_types.id", "LEFT");
            $this->_db->join("job_experience", $this->_dbPrefix."jobs.experience=".$this->_dbPrefix."job_experience.experience_id", "LEFT");
            $this->_db->join("salary", $this->_dbPrefix."jobs.salary=".$this->_dbPrefix."salary.salary_id", "LEFT");
            $this->_db->join('saved_jobs', $this->_dbPrefix."jobs.id = ".$this->_dbPrefix."saved_jobs.job_id AND "
                    .$this->_dbPrefix."saved_jobs.user_uniqueId = '$userId_cookie' AND "
                    .$this->_dbPrefix."saved_jobs.IPAddress = '".filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP)."' ", "LEFT");
            $this->_db->join("job_statistics", $this->_dbPrefix."jobs.id=".$this->_dbPrefix."job_statistics.job_id", "LEFT");
                
            $this->_db->where($this->_dbPrefix."jobs.id", $id);
            $this->_db->where($this->_dbPrefix."jobs.status", "1");
                
                
            $job_details = $this->_db->withTotalCount()->get("jobs", NULL,$columns);
                
                
            if ($this->_db->totalCount > 0) {
                return $job_details[0];
            } else {
                return FALSE;
            }
        }
            
        /**
         *  get questionnaire by job id
         * 
         *  @param var $id job's id
         *  @param var $username username to be searched
         *  @param var $questionnaire_id questionnaire's id
         *  @param var $getall get all the records or get one record only
         */
             
        public function getQuestionnaire($job_id, $username="", $questionnaire_id="", $getall=TRUE){
            //Fetch questionnaire data
            $columns = array(
                $this->_dbPrefix."questionnaire.id as questionnaire_id",$this->_dbPrefix."questionnaire.employer",
                $this->_dbPrefix."questionnaire.job_id",$this->_dbPrefix."questionnaire.question",
                $this->_dbPrefix."questionnaire.question_type",$this->_dbPrefix."questionnaire.date",
                $this->_dbPrefix."questionnaire_type.name as questionnaire_typeName",
                $this->_dbPrefix."questionnaire_type.name_en as questionnaire_typeName_en",
            );
            $this->_db->join("questionnaire_type", $this->_dbPrefix."questionnaire.question_type =".$this->_dbPrefix."questionnaire_type.questionnaire_type", "LEFT");
                
            $this->_db->where('job_id', $job_id);
                
            if ($questionnaire_id !== ""){ 
                $this->_db->where($this->_dbPrefix.'questionnaire.id', $questionnaire_id);
            }
                
            if ($username !== ""){ 
                $this->_db->where('employer', "$username");
            }
                
            if ($getall == TRUE){
               $data = $this->_db->withTotalCount()->get('questionnaire', NULL, $columns);
            } else {
                $data = $this->_db->withTotalCount()->getOne('questionnaire', NULL, $columns);
            }
                
            if ($this->_db->totalCount > 0) {
                return $data;
            } else {
                return FALSE;
            }
        }
            
        /**
         *  get questionnaire questions by questionnaire id and job id
         * 
         *  @param var $questionnaire_id questionnaire id
         *  @param var $job_id job id
         */
             
        public function getQuestionnaireQuestions($questionnaire_id, $job_id){
            //Fetch questionnaire data
             $columns = array(
                 $this->_dbPrefix."questionnaire_questions.id as questionsId",
                 $this->_dbPrefix."questionnaire_questions.question_ask",
                 $this->_dbPrefix."questionnaire_questions.questionnaire_id",
                 $this->_dbPrefix."questionnaire_questions.employer",
                 $this->_dbPrefix."questionnaire_questions.job_id as questions_jobId",
                 $this->_dbPrefix."questionnaire.id as questionnaireId",
                 $this->_dbPrefix."questionnaire.question as questionnaireQuestion",
                 $this->_dbPrefix."questionnaire.question_type",$this->_dbPrefix."questionnaire.date",
             );
             $this->_db->join("questionnaire",
                     $this->_dbPrefix."questionnaire_questions.questionnaire_id =".$this->_dbPrefix."questionnaire.id AND "
                     .$this->_dbPrefix."questionnaire_questions.job_id = ".$this->_dbPrefix."questionnaire.job_id", "LEFT");
             $data = $this->_db->where($this->_dbPrefix."questionnaire_questions.job_id", $job_id)
                     ->where($this->_dbPrefix."questionnaire_questions.questionnaire_id", $questionnaire_id)
                     ->withTotalCount()->get('questionnaire_questions', NULL, $columns);
                         
             if ($this->_db->totalCount > 0) {
                 return $data;
             } else {
                 return FALSE;
             }    
        }
            
            
        /**
         *  get single value in specific table
         * 
         *  @param var $table table where you want to get data, default is jobs table
         *  @param var $show_column column to be show in out, default is show all columns
         *  @param var $where_column1 column to be selected in condition 1
         *  @param var $where_clause1 clause in where condition 1
         *  @param var $where_column2 column to be selected in condition 2 (this could be optional)
         *  @param var $where_clause2 clause in where condition 2 (this could be optional)
         *  @param var $featured where featured could be active (1) or not (0)
         */        
        public function getSingleValue($table="jobs",$show_column=NULL, $where_column1, $where_clause1, $where_column2=NULL, $where_clause2=NULL, $featured=NULL) {
            //Retrieve data
            $this->_db->where($where_column1, $where_clause1); 
            if($where_column2 !== NULL && $where_clause2 !== NULL){
                $this->_db->where($where_column2, $where_clause2);
            }
            if ($featured !== NULL){
                $this->_db->where('featured', $featured); 
            }
            $data = $this->_db->withTotalCount()->getOne($table, $show_column);
                
            if ($this->_db->totalCount > 0) {//featured record
                return $data;
            } else {
                return FALSE;
            }
        }
            
            
        /**
         *  update single value in specific table
         * 
         *  @param var $table table where you want to get data, default is jobs table
         *  @param var $show_column column to be show in out, default is show all columns
         *  @param var $where_column1 column to be selected in condition 1
         *  @param var $where_clause1 clause in where condition 1
         *  @param var $where_column2 column to be selected in condition 2 (this could be optional)
         *  @param var $where_clause2 clause in where condition 2 (this could be optional)
         *  @param var $featured where featured could be active (1) or not (0)
         */        
        public function updateSingleValue($table="jobs",$where_column, $where_clause,
                $data = Array (
                    'featured' => '1',
                )
            ) {           
                
            $this->_db->where($where_column, $where_clause);
            if ($this->_db->update ($table, $data)){
                return TRUE;
            }
            else {
                echo 'update failed: ' . $db->getLastError();
            }
        }
            
            
        /**
        * Function to create and display error and success messages
        * http://www.phpdevtips.com/2013/05/simple-session-based-flash-messages/
        * @access public
        * @param string session name
        * @param string message
        * @param string display class
        * @return string message
        * 
        */
       function flash( $name = '', $message = '', $class = 'success fadeout-message' )
       {
           //We can only do something if the name isn't empty
           if( !empty( $name ) )
           {
               //No message, create it
               if( !empty( $message ) && empty( $_SESSION[$name] ) )
               {
                   if( !empty( $_SESSION[$name] ) )
                   {
                       unset( $_SESSION[$name] );
                   }
                   if( !empty( $_SESSION[$name.'_class'] ) )
                   {
                       unset( $_SESSION[$name.'_class'] );
                   }
                       
                   $_SESSION[$name] = $message;
                   $_SESSION[$name.'_class'] = $class;
               }
               //Message exists, display it
               elseif( !empty( $_SESSION[$name] ) && empty( $message ) )
               {
                   $class = !empty( $_SESSION[$name.'_class'] ) ? $_SESSION[$name.'_class'] : 'success';
                   echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
                   unset($_SESSION[$name]);
                   unset($_SESSION[$name.'_class']);
               }
           }
       }
           
       /**
        * customize the flash messages or messages
        * http://www.tutorialrepublic.com/twitter-bootstrap-tutorial/bootstrap-alerts.php
        * @access public
        * @param type class of message (info/warning/danger/alert), default is info
        * @param message message output
        * 
        */
        public function messageStyle($type="info", $message){
           $data = "<div class='alert alert-$type fade in'>"
                   ."<a href='#' class='close' data-dismiss='alert'>&times;</a>"
                   ."<strong>$message</strong></div>";
                       
           return $data;
        }       
            
        /**
        * Find jobs by username/employer
        * 
        * @access public
        * @param username username to be searched
        * @param limit limitation records to be selected
        * 
        */
        public function jobs_by_username($username, $limit=NULL){
            $selected_columns = array(
                $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.employer",$this->_dbPrefix."jobs.job_category",
                $this->_dbPrefix."jobs.experience",$this->_dbPrefix."jobs.region",$this->_dbPrefix."jobs.title",$this->_dbPrefix."jobs.SEO_title",
                $this->_dbPrefix."jobs.message",$this->_dbPrefix."jobs.active",$this->_dbPrefix."jobs.featured",
                $this->_dbPrefix."jobs.job_type",$this->_dbPrefix."jobs.salary",$this->_dbPrefix."jobs.status",
                $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.City_en",
                $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_name",
                $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
                $this->_dbPrefix."salary.salary_range",$this->_dbPrefix."salary.salary_range_en",
                $this->_dbPrefix."employers.id as employer_id",$this->_dbPrefix."employers.username as employer_username",
                $this->_dbPrefix."employers.logo",$this->_dbPrefix."employers.company",
                $this->_dbPrefix."questionnaire.id as questionnaire_id",
            );
                
            $this->_db->join("categories", $this->_dbPrefix."jobs.job_category=".$this->_dbPrefix."categories.category_id", "LEFT");
            $this->_db->join("job_types", $this->_dbPrefix."jobs.job_type=".$this->_dbPrefix."job_types.id", "LEFT");
            $this->_db->join("salary", $this->_dbPrefix."jobs.salary=".$this->_dbPrefix."salary.salary_id", "LEFT");
            $this->_db->join("locations", $this->_dbPrefix."jobs.region=".$this->_dbPrefix."locations.id", "LEFT");
            $this->_db->join("employers", $this->_dbPrefix."jobs.employer=".$this->_dbPrefix."employers.username", "LEFT");
            $this->_db->join("questionnaire", $this->_dbPrefix."jobs.id=".$this->_dbPrefix."questionnaire.job_id AND "
                            .$this->_dbPrefix."jobs.employer=".$this->_dbPrefix."questionnaire.employer", "LEFT"); 
                                
            $this->_db->where ($this->_dbPrefix."jobs.employer", "$username");
                
            $data = $this->_db->withTotalCount()->get("jobs", $limit, $selected_columns);
                
            if($this->_db->totalCount > 0){
                return $data;
            } else {
                return FALSE;
            }
        }
            
        /**
        * Check whether user is employer or jobseeker
        * 
        * @access public
        * @param username username to be searched
        * 
        */
        public function isEmployer($username){
            $this->_db->withTotalCount()->where('username',"$username")->getOne('employers');
            if ($this->_db->totalCount > 0){// Is employer
                return TRUE;
            } else { //Is jobseeker
                return FALSE;
            }
        }
            
        /**
        * Check if user inactivity for specific time
        * 
        * @access public
        * @param last_activity last activity of user in website, by default is 3600s = 60mins
        * 
        */
        public function CheckSession($last_activity="7200") {
            if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $last_activity)) {
                // last request was more than 60 minutes ago
                session_unset();     // unset $_SESSION variable for the run-time 
                session_destroy();   // destroy session data in storage
            }
            $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
        }
            
            
        /**
        * A simple pagination using Bootstrap
        * 
        * @access public
        * @param reload url where you want to navigate to
        * @param page current page of pagination
        * @param totalPages total pages in pagination
        * @param SEO_setting default 1 is enabled 
        */
        public function pagination($reload, $page, $totalPages, $SEO_setting="1") {
            echo '<div><ul class="pagination">';
            if ($totalPages > 1) {
                echo $this->paginate($reload, $page, $totalPages, $SEO_setting);
            }
            echo "</ul></div>";
        }
            
        protected function paginate($reload, $page, $totalPages, $SEO_setting, $adjacents=5) {
            //Remove page= and allow integer numbef only if SEO setting enabled (1)
            if ($SEO_setting == "1"){
                $additional_page_word = "";
            } else {
                $additional_page_word = "trang=";
            }
            $prevlabel = "&lsaquo; Prev";
            $nextlabel = "Next &rsaquo;";
            $out = "";
            // previous
            if ($page == 1) {
                $out.= "";
            } elseif ($page == 2) {
                $out.="<li><a href=\"".$reload."$additional_page_word"."1\">".$prevlabel."</a>\n</li>";
            } else {
                $out.="<li><a href=\"".$reload."$additional_page_word"."1\">First</a>\n</li>";                
//                $out.="<li><a href='&page=1'>1</a>\n</li>";
            }
            $pmin=($page>$adjacents)?($page - $adjacents):2; //Hide page 1
            $pmax=($page<($totalPages - $adjacents))?($page + $adjacents):$totalPages;
            for ($i = $pmin; $i <= $pmax; $i++) {
                if ($i == $page) {
                    $out.= "<li class=\"active\"><a href=''>".$i."</a></li>\n";
                } elseif ($i == 1) {
                    $out.= "<li><a href=\"".$reload."\">".$i."</a>\n</li>";
                } else {
                    $out.= "<li><a href=\"".$reload. "$additional_page_word".$i."\">".$i. "</a>\n</li>";
                }
            }
                
            if ($page<($totalPages - $adjacents)) {
                $out.= "<li><a >..</a>\n</li>";
                $out.= "<li><a href=\"" . $reload."$additional_page_word".$totalPages."\">" .$totalPages."</a>\n</li>";
            }
            // next
            if ($page < $totalPages) {
                $out.= "<li><a href=\"".$reload."$additional_page_word".($page + 1)."\">".$nextlabel."</a>\n</li>";
            } else {
                $out.= "";
            }
            $out.= "";
            return $out;
        }
            
            
        /**
        * A simple function that track execution time of Mysql's query
        * 
        * @access public
        * @param sql_query Mysql query to be tracks
        * 
        *         
        */        
        public function QueryExecutionTime($sql_query){
             $msc_old = microtime(true);
             $this->_db->rawQuery($sql_query);
             $msc_new = microtime(true) - $msc_old;
             echo "Query execution time in ". $msc_new . ' seconds'; // in seconds
         //    echo ($msc * 1000) . ' milliseconds'; // in millseconds
        }
            
            
        /**
        * A simple function that get jobseeker desired categories
        * 
        * @access public
        * @param jobseeker_id jobseeker id
        * 
        *         
        */        
        public function getJobseeker_categories($jobseeker_id){
            $this->_db->join("categories", $this->_dbPrefix."jobseeker_categories.category_id=".$this->_dbPrefix."categories.id", "LEFT");
            $jobseeker_categories = $this->_db->withTotalCount()->where('jobseeker_id', $jobseeker_id)->get('jobseeker_categories');
            return $jobseeker_categories;
        }
        
        /**
        * Get jobseeker languages skills
        * 
        * @access public
        * @param jobseeker_id jobseeker id
        * 
        *         
        */        
        public function getJobseeker_languages($jobseeker_id){
            $selected_cols = array(
                $this->_dbPrefix."jobseeker_languages.updated_at",
                $this->_dbPrefix."skill_languages.language_name",$this->_dbPrefix."skill_languages.language_name_en",
                $this->_dbPrefix."language_levels.level_name",$this->_dbPrefix."language_levels.level_name_en"
            );
            $this->_db->join("language_levels", $this->_dbPrefix."jobseeker_languages.level_id=".$this->_dbPrefix."language_levels.level", "LEFT");
            $this->_db->join("skill_languages", $this->_dbPrefix."jobseeker_languages.language_id=".$this->_dbPrefix."skill_languages.language_id", "LEFT");
            $jobseeker_languagues = $this->_db->withTotalCount()->where('jobseeker_id', $jobseeker_id)->get('jobseeker_languages', NULL, $selected_cols);
            return $jobseeker_languagues;
        }
            
            
        /**
        * A simple function that get jobseeker desired locations
        * 
        * @access public
        * @param jobseeker_id jobseeker id
        * 
        *         
        */        
        public function getJobseeker_locations($jobseeker_id){
            $this->_db->join("locations", $this->_dbPrefix."jobseeker_locations.location_id=".$this->_dbPrefix."locations.id", "LEFT");
            $jobseeker_locations = $this->_db->withTotalCount()->where('jobseeker_id', $jobseeker_id)->get('jobseeker_locations');
            return $jobseeker_locations;
        }
            
        /**
        * A function that get jobseeker profile details
        * 
        * @access public
        * @param username username/email to be search
        *         
        */        
        public function getJobseeker_profile($username){
            $selected_columns = array(
                $this->_dbPrefix."jobseekers.id as jobseeker_id",$this->_dbPrefix."jobseekers.username",
                $this->_dbPrefix."jobseekers.first_name",$this->_dbPrefix."jobseekers.last_name",
                $this->_dbPrefix."jobseekers.address",$this->_dbPrefix."jobseekers.phone",
                $this->_dbPrefix."jobseekers.description",$this->_dbPrefix."jobseekers.profile_pic",
                $this->_dbPrefix."jobseekers.profile_description",$this->_dbPrefix."jobseekers.profile_description",
                $this->_dbPrefix."marital_status.name as marital_status_name",$this->_dbPrefix."marital_status.name_en as marital_status_name_en",
                $this->_dbPrefix."gender.name as gender_name",$this->_dbPrefix."gender.name_en as gender_name_en"
            );
                
            $this->_db->join("marital_status", $this->_dbPrefix."jobseekers.marital_status=".$this->_dbPrefix."marital_status.marital_id", "LEFT");
            $this->_db->join("gender", $this->_dbPrefix."jobseekers.gender=".$this->_dbPrefix."gender.gender_id", "LEFT");
            $jobseeker_profile = $this->_db->where('username', "$username")->getOne('jobseekers', $selected_columns);
                
            return $jobseeker_profile;
        }
            
            
        /**
        * A function that get jobseeker profile details
        * 
        * @access public
        * @param table table to be selected
        *         
        */        
        public function getCommonTables($table){
            $data['positions'] = $this->_db->get('positions');
            $data['salaries'] = $this->_db->get('salary');
            $data['categories'] = $this->_db->get('categories');
            $data['education'] = $this->_db->get('education');
            $data['job_types'] = $this->_db->get('job_types');
            $data['locations'] = $this->_db->get('locations');
            $data['languages'] = $this->_db->get('languages');
            $data['language_levels'] = $this->_db->get('language_levels');
            $data['skills'] = $this->_db->get('skills');
                
            return $data[$table];
        }
            
            
        /**
        *  Search jobseeker resumes function
        * 
        *  @param var $type id or name of the selected column
        *  @param var $limit limit the number of records to be appear
        */
        public function get_jobseeker_resumes($queryString="",$category="",$location="", $limit=NULL, $userId_cookie=""){
            $jobsInfo_columns = Array (
                $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.date", $this->_dbPrefix."jobs.employer",
                $this->_dbPrefix."jobs.SEO_title",$this->_dbPrefix."jobs.job_category", 
                $this->_dbPrefix."jobs.region",$this->_dbPrefix."jobs.featured",
                $this->_dbPrefix."jobs.title", $this->_dbPrefix."jobs.expires", //
                $this->_dbPrefix."jobs.message", $this->_dbPrefix."jobs.job_type", 
                $this->_dbPrefix."jobs.salary",$this->_dbPrefix."jobs.applications", // Main table
                $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
                $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_id", //Categories table
                $this->_dbPrefix."salary.salary_id",$this->_dbPrefix."salary.salary_range", //Salary table
                $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.id as location_id", //Locations table
                $this->_dbPrefix."employers.id as employer_id",$this->_dbPrefix."employers.company as company",$this->_dbPrefix."employers.logo as company_logo", //Employer table
                $this->_dbPrefix."saved_jobs.user_type as saved_job_userType",$this->_dbPrefix."saved_jobs.date as saved_jobDate",
                $this->_dbPrefix."saved_jobs.browser", $this->_dbPrefix."saved_jobs.IPAddress",  
                $this->_dbPrefix."saved_jobs.user_uniqueId as user_uniqueId",$this->_dbPrefix."saved_jobs.job_id as saved_jobId"//saved jobs table
            );
            $this->_db->join('categories', $this->_dbPrefix."jobs.job_category =".$this->_dbPrefix."categories.category_id", "LEFT");
            $this->_db->join('salary', $this->_dbPrefix."jobs.salary = ".$this->_dbPrefix."salary.salary_id", "LEFT");
            $this->_db->join('job_types', $this->_dbPrefix."jobs.job_type = ".$this->_dbPrefix."job_types.id", "LEFT");
            $this->_db->join('locations', $this->_dbPrefix."jobs.region = ".$this->_dbPrefix."locations.id", "LEFT");
            $this->_db->join('employers', $this->_dbPrefix."jobs.employer = ".$this->_dbPrefix."employers.username", "LEFT");
            $this->_db->join('saved_jobs', $this->_dbPrefix."jobs.id = ".$this->_dbPrefix."saved_jobs.job_id AND "
                    .$this->_dbPrefix."saved_jobs.user_uniqueId = '$userId_cookie' AND "
                    .$this->_dbPrefix."saved_jobs.IPAddress = '".filter_input(INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP)."' ", "LEFT");
                        
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
                
            //Pagination options
            //Set current page to 1 if empty
            if (isset($_GET['trang'])){
                $current_page = filter_input(INPUT_GET, 'trang', FILTER_SANITIZE_NUMBER_INT);
            } else {
                $current_page = 1;
            }
            // set page limit to 2 results per page. 20 by default
            $this->_db->pageLimit = 10;
            $jobs_list['data'] = $this->_db->arraybuilder()->withTotalCount()->paginate("jobs", $current_page,$jobsInfo_columns);            
            $jobs_list['totalCount'] = $this->_db->totalCount; 
            $jobs_list['current_page'] = $current_page;
            $jobs_list['total_pages'] = $this->_db->totalPages;
                
            if ($this->_db->totalCount > 0){
                return $jobs_list;
            } else {
                return FALSE;
            }
        }
            
            
        /**
        *  Add + before every words
        * 
        *  @param var $string input string 
        */
        public function addPlustoString($string){
            $search_words = explode(' ', $string);
            $sql_search = '+' . implode(" +", $search_words);
            return $sql_search;
        }
            
        /**
        *  Add + before every words and before "" double quotes
        * 
        *  @param var $string input string 
        */
        public function addPlustoString2($string){
            //"[^"]*"|\S+ is the regex that matches a double quoted text OR any non-space word 
            //and replacement is +$0 that prefixes each match with +.
            $regex = '/"[^"]*"|\S+/m'; 
            $result = preg_replace($regex, '+$0', $string);
            return $result;
        }
            
        /**
        *  Check if variable1 presents and equal with second variable, then output desired text
        * 
        *  @param var string input string 
        *  @param var var2 input string 
        *  @param var desired_text input string 
        */
        public function checkEqually($var1,$var2,$desired_text="selected"){
            if(($var1 !== NULL) && $var1 == $var2){ 
                echo $desired_text;                
            }
        }
            
            
        /**
        *  get jobseeker resume details or all resumes
        * 
        *  @param var username username/email of customer
        *
        * 
        */
        public function getJobseekerResume($resume_id){
            $jobseeker_resume_columns = array(
                $this->_dbPrefix."jobseeker_resumes.id as resume_id",$this->_dbPrefix."jobseeker_resumes.username",
                $this->_dbPrefix."jobseeker_resumes.skills",$this->_dbPrefix."jobseeker_resumes.username",
                $this->_dbPrefix."jobseeker_resumes.career_objective",$this->_dbPrefix."jobseeker_resumes.facebook_URL",
                $this->_dbPrefix."jobseeker_resumes.experiences",$this->_dbPrefix."jobseeker_resumes.name_current_position",    
                $this->_dbPrefix."job_experience.name as job_experience_name",$this->_dbPrefix."job_experience.name_en as job_experience_name_en",
                $this->_dbPrefix."education.education_name",$this->_dbPrefix."education.education_name_en",
                $this->_dbPrefix."languages.name as language_name",
                $this->_dbPrefix."language_levels.level_name as language_level_name",
                $this->_dbPrefix."salary.salary_range as current_salary",$this->_dbPrefix."salary.salary_range_en current_salary_en",
                $this->_dbPrefix."categories.category_name as desired_category",$this->_dbPrefix."categories.category_name_vi as desired_category_vi",
                $this->_dbPrefix."locations.City as desired_city",$this->_dbPrefix."locations.City_en as desired_city_en",
                $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
                $this->_dbPrefix."positions.position_name as current_position_name",$this->_dbPrefix."positions.position_name_en as current_position_name_en",
                "expected_position.position_name as expected_position_name",
                "expected_position.position_name_en as expected_position_name_en",
                "expected_salary.salary_range as expected_salary_range",
                "expected_salary.salary_range_en as expected_salary_range_en",
                "IT_skill.name as IT_skill_name","IT_skill.name_en as IT_skill_name_en",
                "group_skill.name as group_skill_name","group_skill.name_en as group_skill_name_en",
                "pressure_skill.name as pressure_skill_name","pressure_skill.name_en as pressure_skill_name_en",
            );
                
            $this->_db->join("job_experience", $this->_dbPrefix."jobseeker_resumes.experience_level=".$this->_dbPrefix."job_experience.experience_id", "LEFT");
            $this->_db->join("education", $this->_dbPrefix."jobseeker_resumes.education_level=".$this->_dbPrefix."education.education_id", "LEFT");
            $this->_db->join("languages", $this->_dbPrefix."jobseeker_resumes.language=".$this->_dbPrefix."languages.id", "LEFT");
            $this->_db->join("language_levels", $this->_dbPrefix."jobseeker_resumes.language_level=".$this->_dbPrefix."language_levels.level", "LEFT");
            $this->_db->join("salary", $this->_dbPrefix."jobseeker_resumes.salary=".$this->_dbPrefix."salary.salary_id", "LEFT");
            $this->_db->join("categories", $this->_dbPrefix."jobseeker_resumes.job_category=".$this->_dbPrefix."categories.category_id", "LEFT");
            $this->_db->join("locations", $this->_dbPrefix."jobseeker_resumes.location=".$this->_dbPrefix."locations.id", "LEFT");
            $this->_db->join("job_types", $this->_dbPrefix."jobseeker_resumes.job_type=".$this->_dbPrefix."job_types.id", "LEFT");
            $this->_db->join("positions", $this->_dbPrefix."jobseeker_resumes.current_position=".$this->_dbPrefix."positions.position_id", "LEFT");
            $this->_db->join("positions as expected_position", $this->_dbPrefix."jobseeker_resumes.expected_position=expected_position.position_id", "LEFT");
            $this->_db->join("salary as expected_salary", $this->_dbPrefix."jobseeker_resumes.expected_salary=expected_salary.salary_id", "LEFT");
            $this->_db->join("skills as IT_skill", $this->_dbPrefix."jobseeker_resumes.IT_skills=IT_skill.skill_id", "LEFT");
            $this->_db->join("skills as group_skill", $this->_dbPrefix."jobseeker_resumes.group_skills=group_skill.skill_id", "LEFT");
            $this->_db->join("skills as pressure_skill", $this->_dbPrefix."jobseeker_resumes.pressure_skill=pressure_skill.skill_id", "LEFT");
                
            $this->_db->where ($this->_dbPrefix."jobseeker_resumes.id", $resume_id);                
            $jobseeker_resumes['jobseeker_resumes'] = $this->_db->withTotalCount()->getOne("jobseeker_resumes", $jobseeker_resume_columns);
            $jobseeker_resumes['totalCount'] = $this->_db->totalCount;
                
            if ($this->_db->totalCount > 0){ //Found record
                return $jobseeker_resumes;
            } else {
                return FALSE;
            }
        }
            
        /**
        *  search jobseeker resumes based by conditions or all resumes
        * 
        *  @param var condition true is enable search with conditions, false is search all
        *  @param var queryString query input in title's search
        *  @param var by_category search by category included
        *  @param var by_location search by location included
        *  @param var by_education search by education included
        *  @param var by_expected_position search by expected_position included
        *  @param var by_experience_level search by experience_level included
        */
        public function Search_Resumes($condition=TRUE,$queryString="",$by_category="",$by_location="",$by_education="",$by_expected_position="",$by_experience_level="") {
            $jobsInfo_columns = Array (
                $this->_dbPrefix."jobseeker_resumes.id as resume_id",$this->_dbPrefix."jobseeker_resumes.username",
                $this->_dbPrefix."jobseeker_resumes.title as resume_title",$this->_dbPrefix."jobseeker_resumes.skills as resume_skills",
                $this->_dbPrefix."jobseeker_resumes.date_updated as resume_date_updated",
                $this->_dbPrefix."job_experience.name as job_experience_name",$this->_dbPrefix."job_experience.name_en as job_experience_name_en",
                $this->_dbPrefix."job_experience.experience_id",
                $this->_dbPrefix."positions.position_name",$this->_dbPrefix."positions.position_name_en",
                $this->_dbPrefix."education.education_name as education_name",$this->_dbPrefix."education.education_name_en as education_name_en",
                $this->_dbPrefix."education.education_id",
                $this->_dbPrefix."salary.salary_range",$this->_dbPrefix."salary.salary_range_en",$this->_dbPrefix."salary.salary_id",
                $this->_dbPrefix."categories.category_name",$this->_dbPrefix."categories.category_name_vi",
                $this->_dbPrefix."categories.id as category_id",
                $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",$this->_dbPrefix."job_types.id as job_type_id",
                $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.City_en",$this->_dbPrefix."locations.id as location_id",
                $this->_dbPrefix."jobseekers.first_name",$this->_dbPrefix."jobseekers.last_name",
            );
                
            $this->_db->join('positions', $this->_dbPrefix."jobseeker_resumes.current_position =".$this->_dbPrefix."positions.position_id", "LEFT");
            $this->_db->join('education', $this->_dbPrefix."jobseeker_resumes.education_level =".$this->_dbPrefix."education.education_id", "LEFT");
            $this->_db->join('salary', $this->_dbPrefix."jobseeker_resumes.expected_salary =".$this->_dbPrefix."salary.salary_id", "LEFT");
            $this->_db->join('categories', $this->_dbPrefix."jobseeker_resumes.job_category =".$this->_dbPrefix."categories.category_id", "LEFT");
            $this->_db->join('job_types', $this->_dbPrefix."jobseeker_resumes.job_type =".$this->_dbPrefix."job_types.id", "LEFT");
            $this->_db->join('locations', $this->_dbPrefix."jobseeker_resumes.location =".$this->_dbPrefix."locations.id", "LEFT");
            $this->_db->join('job_experience', $this->_dbPrefix."jobseeker_resumes.experience_level =".$this->_dbPrefix."job_experience.experience_id", "LEFT");
            $this->_db->join('jobseekers', $this->_dbPrefix."jobseeker_resumes.username =".$this->_dbPrefix."jobseekers.username", "LEFT");
                
            //perform search by conditions   
            if ($condition == TRUE){
                if ($queryString !== ""){ //title search with keywords included
                    $this->_db->where("MATCH(".$this->_dbPrefix."jobseeker_resumes.title) AGAINST ('$queryString*' IN BOOLEAN MODE)");
                }
                    
                if ($by_category !== ""){  
                    $this->_db->where('job_category', $by_category);
                }
                    
                if ($by_location !== ""){ 
                    $this->_db->where('location', $by_location);
                }
                    
                if ($by_education !== ""){ 
                    $this->_db->where('education_level', $by_education);
                }
                    
                if ($by_experience_level !== ""){
                    $this->_db->where('experience_level', $by_experience_level);
                }
                    
                if ($by_expected_position !== ""){ //expected position included
                    $this->_db->where('expected_position', $by_expected_position);
                }  
                    
            }
                
            //Get the data
            $resumes['resumes'] = $this->_db->withTotalCount()->get('jobseeker_resumes', NULL, $jobsInfo_columns);
            $resumes['totalCount'] = $this->_db->totalCount; 
            return $resumes;
        }
            
        /**
        *  insert CV views to the database
        * 
        *  @param var resume_id resume's id to be insert
        *  @param var employer employer who viewed jobseeker's CV
        *  @param var jobseeker jobseeker username
        *  @param var employer_id employer id
        */
        public function Insert_View($resume_id, $employer, $jobseeker, $employer_id){
            //Count view to the database
            $view_data = array(
                "date_seen"     => time(),
                "resume_id"     => $resume_id,
                "employer"      => "$employer",
                "jobseeker"     => "$jobseeker",
                "employer_id"   => $employer_id,
                "views_count"   => $this->_db->inc(1)
            );
                
            $updateColumns = Array ("date_seen","views_count");
            $lastInsertId = "employer_id";
            $this->_db->onDuplicate($updateColumns, $lastInsertId);
            $insert_id = $this->_db->insert('jobseekers_stat', $view_data);
            if(!$insert_id){echo 'there was a problem when insert data';}
        }
            
        /**
        *  Update total job views count
        * 
        *  @param var job_id job id to be insert/update
        *
        */
        public function Update_job_views($job_id){
            //Count view to the database
            $view_data = array(
                "job_id"   => $job_id,
                "views_count"   => $this->_db->inc(1)
            );
                
            $updateColumns = Array ("views_count");
            $lastInsertId = "job_id";
            $this->_db->onDuplicate($updateColumns, $lastInsertId);
            $insert_id = $this->_db->insert('job_statistics', $view_data);
            if(!$insert_id){echo 'there was a problem when insert data';}
        }
            
        /**
        * A random code generator
        */
        public function generateConfirmationCode() {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = '';
            for ($i = 0; $i < 60; $i++) {
              $code .= $chars[ rand( 0, strlen( $chars ) - 1 ) ];
            }
            return $code;
        }
           
       // Example output
       // jAWrOD1mFaHXjkt9dm28BmVctHP5E6aOwX6brXnWYVJ75369fZ8HbTKvrqqV
           
        /**
        *  PHP filesize MB/KB conversion
        * 
        *  @param var bytes input bytes number to be conversion
        *
        */
        
         function formatSizeUnits($bytes){
            if ($bytes >= 1073741824)
            {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            }
            elseif ($bytes >= 1048576)
            {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            }
            elseif ($bytes >= 1024)
            {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            }
            elseif ($bytes > 1)
            {
                $bytes = $bytes . ' bytes';
            }
            elseif ($bytes == 1)
            {
                $bytes = $bytes . ' byte';
            }
            else
            {
                $bytes = '0 bytes';
            }
                
            return $bytes;
        }
            
    }
?>