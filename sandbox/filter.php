<?php

require_once("../db/db_utils.php");
require_once("../utils/constants.php");
require_once("../utils/util_functions.php");

if(!empty($_GET['date']) && !empty($_GET['tbl'])){
      
      // store the required fields in variables
      $table_name  = $_GET['tbl']."_1kb";
      $date        = $_GET['date'];
      $is_range    = str_contains($_GET['date'],'to');
      $date_ranges = $is_range ? explode('to',$_GET['date']) : $date;
      $start_date  = $is_range ? trim($date_ranges[0]) : $date;
      $end_date    = $is_range ? trim($date_ranges[1]) : $date;
      $sql = "SELECT * FROM {$table_name} WHERE `draw_date` BETWEEN '{$start_date}' AND '{$end_date}'";

      // fetch the results from the database
      echo json_encode(fetch_with_sql($sql));
      return;
    
}

 echo json_encode(['status' => 'error', 'message' =>"Invalid request."]);