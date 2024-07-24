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
        .date_selector{
            cursor:pointer;
            margin-right: 30px;
        }
        .header{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .lottery_title{
            display: inline;
        }
        .button {
        position: relative;
        overflow: hidden;
        height: 3rem;
        padding: 0 2rem;
        border-radius: 1.5rem;
        background: #3d3a4e;
        background-size: 400%;
        color: #fff;
        border: none;
        cursor: pointer;
        }

        .button:hover::before {
        transform: scaleX(1);
        }

        .button-content {
        position: relative;
        z-index: 1;
        }

        .button::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        transform: scaleX(0);
        transform-origin: 0 50%;
        width: 100%;
        height: inherit;
        border-radius: inherit;
        background: linear-gradient(
            82.3deg,
            rgba(150, 93, 233, 1) 10.8%,
            rgba(99, 88, 238, 1) 94.3%
        );
        transition: all 0.475s;
        }
        .opt_container{
            display: flex;
            width: 410px;
            justify-content: space-evenly;
            align-items: center;
        }

    </style>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>  
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
</head>
<body>

<div class="header">
<h2 class="lottery_title">Show Draws for <?php  if(isset($_GET['tbl'])){
    echo strtoupper(str_replace('_',' ',$_GET['tbl']));
};?></h2>

<div class="opt_container">
<button class="button" id="date-button">
  <span class="button-content">Date </span>
</button>
<button class="button">
  <span class="button-content">Save </span>
</button>
<button class="button">
  <span class="button-content">Export </span>
</button>
</div>
</div>

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
          echo "<tr class='empty_row'><td>Sorry, but empty for now !!! </td></tr>";
     }
     }
    

?>
   <script>
   
   $(document).ready(function () {
    // Initialize table with the first dataset
    //   updateTable();
     const fp = flatpickr("#date-button",{
         mode: "range",
        dateFormat: "Y-m-d H:i:s",

        onChange: function(selectedDates, dateStr, instance){
            console.log("Date changed: ", dateStr);
        },
        onClose: function(selectedDates,dateStr,instance){
            console.log("Picker closed with date: ", dateStr);
        }
        
      });
      console.log(fp.selectedDates);
     });

   </script>
    </tbody>
</table>
</body>
</html>
