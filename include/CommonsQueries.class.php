<?php
    /**
    * Common queries Class
    * @category  List of common queries
    */
    class CommonsQueries extends MysqliDb{
        public function get_data($table, $query="") {
            $this->get($table);
        }
    }
