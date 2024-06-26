<?php

require_once('vendor/autoload.php');
require_once('index.php');
require_once('utils/constants.php');
require_once('utils/date_time.php');
require_once('utils/util_functions.php');
require_once('db/db_utils.php');
require_once('classes/class.redis.php');


$loop = React\EventLoop\Loop::get();

$res = fetch_all('failed_logins');
#create event loop
$timer = $loop->addPeriodicTimer(1, function () {
   foreach (LOTTERIES_INFO as $key => $value) {
    foreach ($value as $val) {
       perform_data_query($key,$val);
      }
    }
   // fetch and retry all the failed lotteries from the perform data query invocation
   $res = RedisClient::check_and_get_failed_lotteries('failed_lotteries');
   if($res['status'] === "success" && !empty($res['data'])){
     $data = $res['data'];
       foreach ($data as $current_time_utc => $value) {
         perform_data_query($current_time_utc,$val);
       }
    }elseif(empty($res['data'])){
      echo "No failed lotteries yet. \n";
   }else{
      echo $res['msg']."\n";
   }
   // echo getHongKongMark6();
   //echo 'Get Hong Kong Mark 6'."\n";
});
echo "Service Engine started"."\n";


function perform_data_query($current_time_utc,$val){
      $start_end = $val['start_end'];
      echo $val['lottery_name']."\n";
      $date_time_from_timezone = get_time_in_right_zone($val['timezone']);
      $date_details = explode(' ',$date_time_from_timezone['shortend_date']);
      $valid_fetch_time = '';
      if(str_contains($start_end,'~')){
         $valid_fetch_time = handle_time_ranges($current_time_utc,$start_end,$val['timezone']);
      }else{
         $valid_fetch_time = ($date_details[1] == $start_end);
      }
     
         if($valid_fetch_time || true){
                // get the valid fetch days for the current iteration of the lotteries.
                $valid_fetch_days = explode(',',trim($val['num_mins_per_period']));
            if(empty(trim($val['num_mins_per_period'])) || in_array($date_details[0],$valid_fetch_days)){

                   // get and call the appropriate service function
                   $function_name  = "get_".$val['lottery_name'];
                   $full_date_time = explode(' ',$date_time_from_timezone['full_date']);
                   $results        = $function_name($val['link_url']);
                   
                  // if fetching the data is successful, organize and store it.
               if($results['status'] === 'success'){
                   $results = $results['data'];
                   $results['draw_date']     = implode('-',array_slice(explode('-',$full_date_time[0]),0,3));
                   $results['table_name']    = $val['table_name']; 
                   $results['draw_count']    = str_replace('-','',$results['draw_date']).$results['draw_count']; 
                   $results['draw_time']     = $full_date_time[1];
                   $results['date_created']  = $full_date_time[0];
                   $results['get_time']      = $start_end;

                   // store the draw number and related data in the db
                   $res = store_draw_number($results);

                   // on success check and remove any failed lottery from the redis cache
                   // that matches the just stored draw number
                   if($res['status'] === 'success')  $res = RedisClient::remove_failed_lottery($current_time_utc);
                   
                   // check and retry any failed requests
                   }else{
                     if($results['code'] !== 2){
                        // retry this lottery
                        $res = RedisClient::check_and_get_failed_lotteries('failed_lotteries');
                        if($res['status'] === "success"){
                           $res['data'][$current_time_utc] = $val;
                           }else{
                           echo $res['msg']."\n";
                        }
                       $res = RedisClient::store(['key' => 'failed_lotteries', 'value' => json_encode($res['data'])]);
                         if($res['status'] === "error"){
                        // if you have not successfully stored recorded the failed lottery,
                        if (file_put_contents('logs/failed_lotteries_logs.txt', "TIMEOUTEXCEPTION::  {$val['lottery_name']} timed out with a url of ({$val['link_url']}) \n") === false) {
                           echo 'Failed to write data to file.';
                        } 

                         }else{
                           echo "Stored the failed lottery fetch in redis.";
                         }
                       
                     }
                     if($results['code'] === 2){
                       file_put_contents('logs/failed_lotteries_logs.txt', "NOSUCHELEMENTEXCEPTION:: {$val['lottery_name']} elements updated from source. With a url of ({$val['link_url']}) \n");
                     }
                     if($results['code'] === 3){
                        file_put_contents('logs/failed_lotteries_logs.txt', "CURLTIMEOUTEXCEPTION:: {$val['lottery_name']} Curl time out with a url of ({$val['link_url']}) \n");
                     }
                   }
                   
                 }else{
                  // $day = explode('-',explode(' ',$date_time_from_timezone['full_date'])[0])[3];
                  echo "Wrong day: Today is {$date_details[0]}, the valid days are {$val['num_mins_per_period']}\n\n\n";
                  // if($res['status'] === 'success'){
                  // if you have successfully stored recorded the failed lottery, then update the session data
               //    }else{
               // // if you have not successfully stored recorded the failed lottery,
               // if (file_put_contents('failed_lotteries_logs.txt', 'Failed to store the failed login for '.$val['lottery_name'].' on line '.__LINE__.' on file '.__FILE__) === false) {
               //    echo 'Failed to write data to file.';
               // } 
               
               // echo "\n\n\n\n\n\n********** EMERGENCY !!!! {$val['lottery_name']} TIMEOUT AND COULDN'T BE STORED IN THE DATABASE. REQUIRES MANUAL INSERTION IMMEDIATELY. ************\n\n\n\n\n\n\n";
               // }
                 }
               

               }else{

                  echo "Not time for a fetch : {$val['lottery_name']}"."\n";

               }
}

function handle_time_ranges(string $current_time, string $start_end, string $timezone){
      $start_end  = explode('~', $start_end);
      $start_time = convert_time_to_other_timezone($start_end[0], $timezone);
      $end_time   = convert_time_to_other_timezone($start_end[1], $timezone);
      $start_time = explode(' ',$start_time['shortend_date'])[1];
      $end_time   = explode(' ',$end_time['shortend_date'])[1];
      return ($start_time >= $current_time && $end_time <= $current_time);
}