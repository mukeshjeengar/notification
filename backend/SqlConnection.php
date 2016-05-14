<?php

/**
* Mysql connection class
*/
class SqlConnection{

    public $mysqli;

    public $db_hostname = "localhost", $db_username = "root", $db_password = "root", $db_name = "test";
    
    function __construct() {        
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new mysqli($this->db_hostname, $this->db_username, $this->db_password, $this->db_name);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    }
}