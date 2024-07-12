<?php
require_once("db/db_utils.php");
require_once("utils/constants.php");

$lottery_table_names = "";
foreach(LOTTERIES_INFO as $key => $group_lotteries){
    foreach($group_lotteries as $val){
        $lottery_table_names .= "<option value='{$val['lottery_name']}'>{$val['lottery_name']}</option>";
     }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styled Table with Dropdown</title>
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
    </style>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>Styled Table with Dropdown Example</h2>

<label for="dataSelect">Choose a Lottery:</label>
<select id="dataSelect" onchange="updateTable()"><?php echo $lottery_table_names; ?></select>

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
    <tbody id="tableBody"></tbody>
</table>

<script>
     $(document).ready(function() {
    // Initialize table with the first dataset
    updateTable();
    });

function updateTable() {
    let selectElement = document.getElementById('dataSelect');
    let selectedElement = selectElement.value;
        $.ajax({
                url: `utils/entry.php?tbl=` + selectedElement,
                type: 'GET',
                success: function(response) {
                    response = JSON.parse(response);
                     const tableBody = document.getElementById('tableBody');
                    tableBody.innerHTML = '';
                    response.data.forEach(row => {
                        console.log(row);
                        const tr = document.createElement('tr');
                        $(tr).append(`<td>${row.drawid}</td><td>${row.lottery_name}</td><td>${row.draw_date}</td><td>${row.draw_time}</td><td>${row.draw_number}</td><td>${row.draw_count}</td><td>${row.date_created}</td><td><a href='${row.client}' target='_blank'>Visit Client Site</a></td><td>${row.get_time}</td>`);
                        tableBody.appendChild(tr);
                    });
                },
                error: function(error) {
                    console.log('Error: ', error);
                }
                });
   
   
    }
</script>

</body>
</html>