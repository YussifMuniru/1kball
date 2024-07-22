<?php
require_once('c:/xampp/htdocs/1kball/db/db_utils.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1kBall Draws</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        caption {
            caption-side: top;
            font-size: 1.5em;
            margin: 10px 0;
        }
        select {
            padding: 10px;
            margin-bottom: 20px;
        }
        .empty_row {
          width: 100%;
        }
    </style>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>Show Draws for <?php  if(isset($_GET['tbl'])){
    echo strtoupper(str_replace('_',' ',$_GET['tbl']));
};?></h2>


<table>
    <caption></caption>
    <thead>
        <tr>
            <th>Draw ID</th>
            <th>Lottery Name</th>
            <th>Draw Date</th>
            <th>Draw Time</th>
            <th>Draw Number</th>
            <th>Date Count</th>
            <th>Date Created</th>
            <th>Client</th>
            <th>Get Time</th>
        </tr>
    </thead>
    <tbody id="tableBody">
     <?php 
    if(isset($_GET['tbl'])){
     $results = fetch_all($_GET['tbl']."_1kb");

     if($results['status'] === 'success'){
       if(!empty($results['data'])){
       foreach($results['data'] as $key => $row){
         
        echo "<tr><td>{$row['drawid']}</td><td>{$row['lottery_name']}</td><td>{$row['draw_date']}</td><td>{$row['draw_time']}</td><td>{$row['draw_number']}</td><td>{$row['draw_count']}</td><td>{$row['date_created']}</td><td><a href='{$row['client']}' target='_blank'>Visit Client Site</a></td><td>{$row['get_time']}</td></tr>" ;
      }
     }
     }else{
          echo "<tr><td class='empty_row'>Sorry, but empty for now !!!</td></tr>";
     }
     }
    

?>

    </tbody>
</table>
</body>
</html>
