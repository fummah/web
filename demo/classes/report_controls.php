<?php
require "reportsClass.php";
$connect=new reportsClass();
class report_controls
{
    public function __construct()
    {
        global $connect;
        $this->db_reports=$connect;

    }

}