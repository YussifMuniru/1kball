<?php

require_once('c:/xampp/htdocs/1kball/db/db_utils.php');

if(isset($_GET['tbl'])){
     echo json_encode(fetch_all($_GET['tbl']."_1kb"));
}