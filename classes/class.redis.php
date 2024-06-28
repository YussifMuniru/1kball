<?php
require_once('vendor/autoload.php');

class RedisClient {



 public static function store(array $data): array
{

    try {
        $redis =self::get_redis_cli();
        $redis->set($data['key'], $data['value']);
        return ['status' => true, 'msg' => "success"];
    } catch (Throwable $th) {
        echo $th->getMessage();
        return ['status' => false, 'msg' => "Redis error: line ( " . __LINE__ . " )"];
        //echo $th->getMessage();
    }
}
 public static function check_and_get_failed_lotteries(string $key): array
{
    try {
          $redis = self::get_redis_cli();
          return ['status' => 'success', 'msg' => "Successfully stored.", 'data' => json_decode($redis->get($key),true) ];
    } catch (Throwable $th) {
        echo $th->getMessage();
        return ['status' => 'success', 'msg' => "Redis error: line ( " . __LINE__ . " )"];
        //echo $th->getMessage();
    }
}
 public static function delete_entry(string $key): array
{
    try {
          $redis = self::get_redis_cli();
          $res = json_decode($redis->get($key),true);
          if(empty($res))  $redis->del($key);
          return ['status' => 'success', 'msg' => "Successfully deleted."];
    } catch (Throwable $th) {
        echo $th->getMessage();
        return ['status' => 'success', 'msg' => "Redis error: line ( " . __LINE__ . " )"];
        //echo $th->getMessage();
    }
}
 public static function remove_failed_lottery(string $key): array
{
    try {
          $redis = self::get_redis_cli();
          $res = json_decode($redis->get("failed_lotteries"),true);
          unset($res[$key]);
          return self::store(['key' =>'failed_lotteries','value'=> json_encode($res)]);
    } catch (Throwable $th) {
         log_action('REDIS_ERROR', $th->getMessage()." on line".__LINE__." in file ".__FILE__);
        return ['status' => 'success', 'msg' => "Redis error: line ( " . __LINE__ . " )"];
        //echo $th->getMessage();
    }
}


 public static function get_redis_cli(){
    return  new \Predis\Client();
 }
}




