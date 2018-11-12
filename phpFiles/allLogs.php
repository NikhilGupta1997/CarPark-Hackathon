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
$carparkid='cid1';
$db = pg_connect("host=nlpserver.postgres.database.azure.com port=5432 dbname=carpark_db user=nikhil@nlpserver password=MOMDAD<3nick");
$result= pg_query($db,"Select * from table_filled_slots_past" );
echo"
<table>
 <tr>
    <td>Vehicle ID</td>
    <td>Carpark ID</td>
    <td>In Time</td>
    <td>Out Time</td>
    <td>Charge </td>
    <td>Payment Type</td>
 </tr>
";
while ($row = pg_fetch_assoc($result)) 
{
echo
"
 <tr>
    <td>$row[vehicle_id]</td>
    <td>$row[carpark_id]</td>
    <td>$row[in_time]</td>
    <td>$row[out_time]</td>
    <td>$row[charge]</td>
    <td>$row[payment_type]</td>
 </tr>
";
}
echo
"
</table>
</body>
</html>
";

?>