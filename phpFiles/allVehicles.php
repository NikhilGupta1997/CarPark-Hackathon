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
$result= pg_query($db,"Select * from table_vehicle" );
echo"
<table>
 <tr>
    <td>Vehicle ID</td>
    <td>Vehicle Type</td>
    <td>Vehicle Number</td>
    <td>User ID</td>
 </tr>
";
while ($row = pg_fetch_assoc($result)) 
{
echo
"
 <tr>
    <td>$row[vehicle_id]</td>
    <td>$row[vehicle_type]</td>
    <td>$row[vehicle_number]</td>
    <td>$row[user_id]</td>
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