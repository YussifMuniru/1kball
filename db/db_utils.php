<?php


ini_set("display_errors",1);
require_once("c:/xampp/htdocs/1kball/utils/util_functions.php");

class Database {

public static $pdo;
public static $mysqli_db;
public static function openConnection() : pdo | string {
    try {
        self::$pdo = new PDO (
            "mysql:host=localhost;dbname=track_db", 
            "enzerhub", 
            "enzerhub"
        );
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$pdo;
    } catch (PDOException $th) {
        echo $th->getMessage();
         return $th->getMessage();
    }
}
public static function open_db_connection() : mysqli | string {
    try {
        self::$mysqli_db = new mysqli ("localhost","enzerhub", "enzerhub","track_db");
        return self::$mysqli_db;
    } catch (mysqli_sql_exception $e) {
        echo $e->getMessage();
        return $e->getMessage();
    }
}
public static function closeConnection($close_pdo = true) : null {
     if($close_pdo){
         return self::$pdo = null;
     }else{
         return self::$mysqli_db = null;
     }
   
}
}

function record_failed_lotteries($columns = [],$values){

      $db = Database::open_db_connection();
      $values = array_map(function($value) use ($db){
        return $db->real_escape_string($value);
      },$values);
    try{
         $sql = 'INSERT INTO failed_lotteries ('.implode(',',$columns).') VALUES ('.implode(',',array_map(function($value){return "'$value'";},$values)).')';
         if($db->query($sql) === TRUE) {
         return   ['status' => 'success', 'msg' => "Successfully recorded a failed login. "];
        }
    }catch(mysqli_sql_exception $e){
        return ['status' => 'error', 'msg' => "Recording failed for failed logins ". $e->getMessage()];
    }
}

function  store_draw_number(array $args = []){
  
    $table_name   = $args['table_name'];
    $draw_date    = $args['draw_date'];
    $draw_count   = $args['draw_count'];
    $draw_number  = $args['draw_number'];
    $date_created = $args['date_created'];
    $draw_time    = $args['draw_time'];
    $get_time     = $args['get_time'];
  
    try {   
    $db = Database::openConnection();
     $sql = "INSERT INTO {$table_name} (draw_date,lottery_name,draw_time,draw_number,draw_count,date_created,client,get_time) VALUES (:draw_date,:lottery_name,:draw_time,:draw_number,:draw_count,:date_created,:client,:get_time)";
    // Step 1: Fetch the table name from `gamestable_map` where `dtb_id` = 1
$stmt = $db->prepare($sql);
$client = '';

$stmt->bindParam(":draw_date",    $draw_date);
$stmt->bindParam(":draw_time",    $draw_time);
$stmt->bindParam(":draw_number",  $draw_number);
$stmt->bindParam(":lottery_name",  $args['lottery_name']);
$stmt->bindParam(":draw_count",   $draw_count);
$stmt->bindParam(":date_created", $date_created);
$stmt->bindParam(":client",       $client);
$stmt->bindParam(":get_time",     $get_time);

$stmt->execute();

return ['status' => 'success', 'msg' => "Successfully inserted in {$table_name}."];
  } catch (\PDOException $e) {
      log_action('DATABASE_ERROR', $e->getMessage()." Draw number to be stored is {$draw_number}, draw count is: {$draw_count} on line ".__LINE__." in file ".__FILE__);
      return ['status' => 'error', 'msg' => "Insertion into {$table_name} error. ".$e->getMessage()];
    }
}
function fetch_all($table_name){
    try {   
    $db = Database::openConnection();
    $stmt = $db->prepare("SELECT * FROM {$table_name}");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ['status' => 'success','data' => $res, 'msg' => "Successfully fetched all in {$table_name}."];
  } catch (\PDOException $e) {
    return ['status' => 'error', 'msg' => "Insertion into {$table_name} error. ".$e->getMessage()];
    }

}
function fetch_one($table_name,string $ordery_by = 'DESC'){
    try {   
    $db = Database::openConnection();
    $stmt = $db->prepare("SELECT draw_number FROM {$table_name} LIMIT 1 ORDER BY {$ordery_by}");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return ['status' => 'success','data' => $res];
  } catch (\PDOException $e) {
    return ['status' => 'error', 'msg' => "Insertion into {$table_name} error. ".$e->getMessage()];
    }

}
function insert_a_row($table_name, $columns, $values){

    try { 
     $db = Database::openConnection();
     $sql = "INSERT INTO {$table_name} (draw_date,draw_time,draw_number,draw_count,date_created,client,get_time) VALUES (:draw_date,:draw_time,:draw_number,:draw_count,:date_created,:client,:get_time)";
    // Step 1: Fetch the table name from `gamestable_map` where `dtb_id` = 1
$stmt = $db->prepare($sql);
$client = '';

$stmt->bindParam(":draw_date",    $draw_date);
$stmt->bindParam(":draw_time",    $draw_time);
$stmt->bindParam(":draw_number",  $draw_number);
$stmt->bindParam(":draw_count",   $draw_count);
$stmt->bindParam(":date_created", $date_created);
$stmt->bindParam(":client",       $client);
$stmt->bindParam(":get_time",     $get_time);

$stmt->execute();

return ['status' => 'success', 'msg' => "Successfully inserted in {$table_name}."];
  } catch (\PDOException $e) {
        //throw $th;
        return ['status' => 'error', 'msg' => "Insertion into {$table_name} error. ".$e->getMessage()];
    }
}

function delete_all($table_name){
    try{

     $db = Database::openConnection();
     $sql = "DELETE FROM {$table_name}";
     $stmt = $db->prepare($sql);
     $stmt->execute();
    }catch(PDOException $e) {
          return ['status' => 'error', 'msg' => "Deletion from {$table_name} error. ".$e->getMessage()];
    }
}

function delete_one($table_name,$id){
    try{
     $db = Database::openConnection();
     $sql = "DELETE FROM {$table_name} WHERE id =:id";
     $stmt = $db->prepare($sql);
     $stmt->bindParam(":id",    $id);
      $stmt->execute();
    }catch(PDOException $e) {
          return ['status' => 'error', 'msg' => "Deletion from {$table_name} error. ".$e->getMessage()];
    }
}
function fetchDrawNumbers($lottery_id){
    try{
         $lottery_id = intval($lottery_id);

     
    $db = Database::openConnection();
   

    
    // Step 1: Fetch the table name from `gamestable_map` where `dtb_id` = 1
$stmt = $db->prepare("SELECT draw_table FROM gamestable_map WHERE game_type = :id LIMIT 1");
$stmt->bindParam(":id", $lottery_id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$row) return ["draw_periods"=>[],"draw_numberss"=>[]]; 
 $tableName = $row['draw_table'];





// Step 2: Dynamically construct and execute a query to fetch data from the determined table
$query = "SELECT * FROM {$tableName} ORDER BY draw_id DESC LIMIT :limit" ; 
$stmt = $db->prepare($query);
$limit = 30;
$stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res = [];
   
    // Use $results as needed
    foreach ($results as $key=> $row) {
        // Process each row
        $res["draw_numberss"][$key] = true  ? json_decode($row["draw_numbers"]) : explode(",",$row["draw_numbers"]);
        $res["draw_periods"][$key] = implode("",array_slice(str_split($row["draw_id"]),-4,));
    }
    Database::closeConnection();
    return $res;

    }catch(Throwable $th){
        echo"Error: ". $th->getMessage();
        return $th->getMessage();
    }


  
}


