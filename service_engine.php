<?php

require_once('vendor/autoload.php');
require_once('index.php');
require_once('utils/constants.php');
require_once('utils/date_time.php');
require_once('db/db_utils.php');


$loop = React\EventLoop\Loop::get();


#create event loop
$timer = $loop->addPeriodicTimer(1, function () {
    foreach (LOTTERIES_INFO as $key => $value) {
      # code...
      foreach ($value as $val) {
             
            $start_end = $val['start_end'];
               echo "Got the start end". $start_end."\n";
            if(!str_contains($start_end,'~')){
               $date_time_from_timezone = get_time_in_right_zone($val['timezone']);
               $date_details = explode(' ',$date_time_from_timezone['shortend_date']);
               echo "Got the day of the week: ".$date_details[0]."\n";
               echo "Got the hrs and mins: ".$date_details[1]."\n";
               if(($date_details[0] == $start_end) || '21:30' == $start_end){
                // call the get functions to fetch scrape the data from the sites.
                $valid_fetch_days = explode(',',$val['num_mins_per_period']);
                echo 'Got the valid days: '.implode(',',$valid_fetch_days)."\n";

                if($val['lottery_name'] == 'hong_kong_mark6'){
               //if((empty($valid_fetch_days) || in_array($date_details[1],$valid_fetch_days))){
                   $function_name = "get_".$val['lottery_name'];
                   $full_date_time = explode(' ',$date_time_from_timezone['full_date']);
                   $results       = $function_name($val['link_url']);
                   $results['table_name']    = $val['table_name']; 
                   $results['draw_time']     = $full_date_time[1];
                   $results['date_created']  = $full_date_time[0];
                   $results['get_time']      = $start_end;
                   $res = store_draw_number($results);
                   print_r($res);
                 }
               
               }else{
                  echo "Wrong start_end"."\n";

               }
              
            }else{
               echo "handle date ranges differently";
               $start_end = explode(',',$start_end);
               $lower_time_bound = $start_end[0];
               $high_time_bound  = $start_end[1];
            }
      }
    }
   // echo getHongKongMark6();
   //echo 'Get Hong Kong Mark 6'."\n";
});