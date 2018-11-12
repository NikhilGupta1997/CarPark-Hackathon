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
$result= pg_query($db,"Select * from table_filled_slots_current" );
echo"
<table>
 <tr>
    <td>Vehicle ID</td>
    <td>CarPark ID</td>
    <td>InTime</td>
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