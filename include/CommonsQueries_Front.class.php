<?php
/**
* Common queries Class for front end area
* @category  List of common queries for front end area
*/

class CommonsQueries_Front {
    private $_db;
    private $_dbPrefix;

    public function __construct(MysqliDb $db) {
        global $DBprefix;
        $this->_db = $db;
        $this->_dbPrefix = $DBprefix;
    }
    
    /**
    *   get featured/urgent jobs list
    *   @param var job_type could be featured or urgent type
    *   @param var limit_records Number of records to be retrieved, default is all (NULL)
    *   @param var userId_cookie Unique UserId cookie to compare with job saved
    */
    public function getJobsList($job_type="featured", $limit_records=NULL, $userId_cookie=""){
        //Get featured jobs list
        $selected_jobsType_columns = array(
            $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.job_category",
            $this->_dbPrefix."jobs.title",$this->_dbPrefix."jobs.SEO_title",$this->_dbPrefix."jobs.date",
            $this->_dbPrefix."jobs.message",$this->_dbPrefix."employers.company",$this->_dbPrefix."employers.logo",
            $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_name",$this->_dbPrefix."categories.id as category_id",
            $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.City_en",$this->_dbPrefix."locations.id as location_id",
            $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
            $this->_dbPrefix."job_experience.name as experience_name",$this->_dbPrefix."job_experience.name_en as experience_name_en",
            $this->_dbPrefix."salary.salary_range",$this->_dbPrefix."salary.salary_range_en",
            $this->_dbPrefix."saved_jobs.user_type as saved_job_userType",$this->_dbPrefix."saved_jobs.date as saved_jobDate",
            $this->_dbPrefix."saved_jobs.browser", $this->_dbPrefix."saved_jobs.IPAddress",  
            $this->_dbPrefix."saved_jobs.user_uniqueId as user_uniqueId",$this->_dbPrefix."saved_jobs.job_id as saved_jobId"//saved jobs table
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
        
        $this->_db->where($this->_dbPrefix."jobs.active", "YES");
        $this->_db->where($this->_dbPrefix."jobs.status", "1");
        $this->_db->where($this->_dbPrefix."jobs.expires", time(), ">");
        $this->_db->where($this->_dbPrefix."jobs.$job_type", "1");

        $this->_db->orderBy('RAND()');


        $jobs_list['jobs_list'] = $this->_db->withTotalCount()->get("jobs", $limit_records,$selected_jobsType_columns);
        $jobs_list['totalCount'] = $this->_db->totalCount;
        
        return $jobs_list;
    }
    
    
    /**
    *   get featured/urgent jobs list with pagination
    *   @param var job_type could be featured or urgent type
    *   @param var current_page the current page of the pagination
    *   @param var page_limit limitation records per page
    */
    public function getJobsList_pagination($job_type="featured", $current_page="1", $page_limit='5'){
        $featured_jobs_columns = array(
            $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.job_category",
            $this->_dbPrefix."jobs.title",$this->_dbPrefix."jobs.SEO_title",
            $this->_dbPrefix."jobs.message",$this->_dbPrefix."employers.company",$this->_dbPrefix."employers.logo",
            $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_name",
            $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.City_en",
            $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
            $this->_dbPrefix."job_experience.name as experience_name",$this->_dbPrefix."job_experience.name_en as experience_name_en",
            $this->_dbPrefix."salary.salary_range",$this->_dbPrefix."salary.salary_range_en"
        );

        $this->_db->join("employers", $this->_dbPrefix."jobs.employer=".$this->_dbPrefix."employers.username", "LEFT");
        $this->_db->join("categories", $this->_dbPrefix."jobs.job_category=".$this->_dbPrefix."categories.category_id", "LEFT");
        $this->_db->join("locations", $this->_dbPrefix."jobs.region=".$this->_dbPrefix."locations.id", "LEFT");
        $this->_db->join("job_types", $this->_dbPrefix."jobs.job_type=".$this->_dbPrefix."job_types.id", "LEFT");
        $this->_db->join("job_experience", $this->_dbPrefix."jobs.experience=".$this->_dbPrefix."job_experience.experience_id", "LEFT");
        $this->_db->join("salary", $this->_dbPrefix."jobs.salary=".$this->_dbPrefix."salary.salary_id", "LEFT");
        $this->_db->where($this->_dbPrefix."jobs.active", "YES");
        $this->_db->where($this->_dbPrefix."jobs.status", "1");
        $this->_db->where($this->_dbPrefix."jobs.expires", time(), ">");
        
        $this->_db->where($this->_dbPrefix."jobs.$job_type", "1");
        
        $this->_db->orderBy('RAND()');
        
        // set page limit to 2 results per page. 20 by default
        $this->_db->pageLimit = $page_limit;
        $pagination_jobslist['pagination_jobslist'] = $this->_db->arraybuilder()->withTotalCount()->paginate("jobs", $current_page,$featured_jobs_columns);  
        $pagination_jobslist['totalCount']          = $this->_db->totalCount;
        $pagination_jobslist['totalPages']          = $this->_db->totalPages;
        
        return $pagination_jobslist;
    }
    
    
    /**
    *   get latest jobs list with pagination option
    *   @param var pagination true/false
    */
    public function getLatestJobsList($pagination=TRUE){
        $latest_jobs_columns = array(
            $this->_dbPrefix."jobs.id as job_id",$this->_dbPrefix."jobs.job_category",$this->_dbPrefix."jobs.title",
            $this->_dbPrefix."jobs.SEO_title",$this->_dbPrefix."jobs.date",$this->_dbPrefix."jobs.expires",
            $this->_dbPrefix."jobs.applications",$this->_dbPrefix."jobs.message",
            $this->_dbPrefix."jobs.featured",$this->_dbPrefix."jobs.urgent",
            $this->_dbPrefix."employers.id as employer_id",$this->_dbPrefix."employers.company",$this->_dbPrefix."employers.logo",
            $this->_dbPrefix."categories.category_name_vi",$this->_dbPrefix."categories.category_name",
            $this->_dbPrefix."locations.City",$this->_dbPrefix."locations.City_en",
            $this->_dbPrefix."job_types.job_name",$this->_dbPrefix."job_types.job_name_en",
            $this->_dbPrefix."job_experience.name as experience_name",$this->_dbPrefix."job_experience.name_en as experience_name_en",
            $this->_dbPrefix."salary.salary_range",$this->_dbPrefix."salary.salary_range_en"
        );

        $this->_db->join("employers", $this->_dbPrefix."jobs.employer=".$this->_dbPrefix."employers.username", "LEFT");
        $this->_db->join("categories", $this->_dbPrefix."jobs.job_category=".$this->_dbPrefix."categories.category_id", "LEFT");
        $this->_db->join("locations", $this->_dbPrefix."jobs.region=".$this->_dbPrefix."locations.id", "LEFT");
        $this->_db->join("job_types", $this->_dbPrefix."jobs.job_type=".$this->_dbPrefix."job_types.id", "LEFT");
        $this->_db->join("job_experience", $this->_dbPrefix."jobs.experience=".$this->_dbPrefix."job_experience.experience_id", "LEFT");
        $this->_db->join("salary", $this->_dbPrefix."jobs.salary=".$this->_dbPrefix."salary.salary_id", "LEFT");
        $this->_db->where($this->_dbPrefix."jobs.active", "YES");
        $this->_db->where($this->_dbPrefix."jobs.status", "1");
        $this->_db->where($this->_dbPrefix."jobs.expires", time(), ">");
        $this->_db->orderBy($this->_dbPrefix."jobs.date", "DESC");
        
        //Check pagination option
        if ($pagination == TRUE){
            //Pagination options
            $reload="http://$DOMAIN_NAME/viec-lam-moi-nhat/?";//Link href
            //Set current page to 1 if empty
            if (isset($_GET['trang'])){
                $current_page = filter_input(INPUT_GET, 'trang', FILTER_SANITIZE_NUMBER_INT);
            } else {
                $current_page = 1;
            }
            // set page limit to 2 results per page. 20 by default
            $this->_db->pageLimit = 10;
            $latest_jobs['latest_jobs'] = $this->_db->withTotalCount()->arraybuilder()->paginate("jobs", $current_page,$latest_jobs_columns);   
            $latest_jobs['totalCount'] = $this->_db->totalCount;
            
        } else { //No pagination
            $latest_jobs['jobs_list'] = $this->_db->withTotalCount()->get("jobs", NULL, $latest_jobs_columns);   
            $latest_jobs['totalCount'] = $this->_db->totalCount;            
        }
        
        return $latest_jobs;
    }
}

