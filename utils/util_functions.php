<?php

function log_action($action, $message){
     try{
         file_put_contents('c:/xampp/htdocs/1kball/logs/failed_lotteries_logs.txt',"{$action}:: {$message} \n",FILE_APPEND);
         return ['status' => 'success', 'message' =>'Successfully logged in.'];
     }catch(Throwable $th){
     return ['status' => 'error', 'message' => $th->getMessage()];
     }
}