<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
</head>
<body>  


<?php
$carparkid=$_GET["carpark_id"];
$db = pg_connect("host=nlpserver.postgres.database.azure.com port=5432 dbname=carpark_db user=nikhil@nlpserver password=MOMDAD<3nick");
$result= pg_query($db,"Select * from table_carpark where carpark_id='$carparkid'" );
echo"
<table>
 <tr>
    <td>Vehicle Type</td>
    <td>Capacity</td>
 </tr>
";

$open = '8';
$close = '18';
$space_type = 'Ground';
$fare = '0.1';
while ($row = pg_fetch_assoc($result)) 
{
    $open = $row[open_time];
    $close = $row[close_time];
    $fare = $row[fare];
    $space_type = $row[space_type];

    echo
    "
    <tr>
    <td>$row[vehicle_type]</td>
    <td>$row[capacity]</td>
    </tr>
    ";
}

echo

"
</table>
<div>Opening Time: $open</div>
<div>Closing Time: $close</div>
<div>Space Type: $space_type</div>
<div>Fare: SGD $fare / min </div>";

$result_gates= pg_query($db,"Select * from table_carpark_location where carpark_id='$carparkid'" );
echo"
<table>
 <tr>
    <td>Gate Id</td>
    <td>Latitude</td>
    <td>Longitude</td>
    <td>Active Status</td>
    <td>Zone</td>
 </tr>
";
while ($row = pg_fetch_assoc($result_gates)) 
{
   echo
    "
    <tr>
    <td>$row[gate_id]</td>
    <td>$row[latitude]</td>
    <td>$row[longitude]</td>
    <td>$row[active_status]</td>
    <td>$row[zone]</td>
    </tr>
    ";
}

echo"
</table>
</body>
</html>
";

?>