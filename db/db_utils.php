<?php


ini_set("display_errors",1);

class Database {

public static $pdo;
public static function openConnection() : pdo | string {
    try {
        self::$pdo = new PDO (
            "mysql:host=localhost;dbname=lottery", 
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
public static function closeConnection() : null {
    return self::$pdo = null;
}
}


function store_draw_number(array $args = []){
    $table_name   = $args['table_name'];
    $drawid       = $args['drawid'];
    $draw_date    = $args['draw_date'];
    $draw_count   = $args['draw_count'];
    $draw_number  = $args['draw_number'];
    $date_created = $args['date_created'];
    $draw_time    = $args['draw_time'];
    $get_time     = $args['get_time'];
  
    try {   
    $db = Database::openConnection();
     $sql = "INSERT INTO {$table_name} (drawid,draw_date,draw_time,draw_number,draw_count,date_created,client,get_time) VALUES (:drawid,:draw_date,:draw_time,:draw_number,:draw_count,:date_created,:client,:get_time)";
    // Step 1: Fetch the table name from `gamestable_map` where `dtb_id` = 1
$stmt = $db->prepare($sql);
$client = '';

$stmt->bindParam(":drawid",       $drawid);
$stmt->bindParam(":draw_date",    $draw_date);
$stmt->bindParam(":draw_time",    $draw_time);
$stmt->bindParam(":draw_number",  $draw_number);
$stmt->bindParam(":draw_count",   $draw_count);
$stmt->bindParam(":date_created", $date_created);
$stmt->bindParam(":client",       $client);
$stmt->bindParam(":get_time",     $get_time);

$stmt->execute();

return ['status' => 'success', 'msg' => 'Successfully inserted.'];
  } catch (\PDOException $e) {
        //throw $th;
        return $e->getMessage();
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



// echo json_encode(recenLotteryIsue(1));

