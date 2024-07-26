<?php

function log_action($action, $message){
     try{
         file_put_contents('c:/xampp/htdocs/1kball/logs/failed_lotteries_logs.txt',"{$action}:: {$message} \n",FILE_APPEND);
         return ['status' => 'success', 'message' =>'Successfully logged in.'];
     }catch(Throwable $th){
     return ['status' => 'error', 'message' => $th->getMessage()];
     }
}

function convert_to_date_format(string $date_string, string $initial_format, string $resultant_format){

    try{
        $date = DateTime::createFromFormat($initial_format,$date_string);
        return $date ? $date->format($resultant_format) : "";
    }catch(Throwable $th){
        log_action('Date Formating Error',$th->getMessage()." in file ".__FILE__." on line ".__LINE__);
        return ['status' => 'error', 'message' => "Server error: Please try again later."];
    }
}