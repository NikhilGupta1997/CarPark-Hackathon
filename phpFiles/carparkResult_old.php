<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>SearchForCarpark</title>

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="../../dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="theme.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <style type="text/css">
  input[type=button], input[type=submit], input[type=reset] 
  {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 16px 32px;
    text-decoration: none;
    margin: 4px 2px;
    cursor: pointer;
  }
  </style>

<body>

<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$cLat=$_GET["cLat"];
$cLon=$_GET["cLon"];
$dLat=$_GET["dLat"];
$dLon=$_GET["dLon"];
$vehicle_type=$_GET["vehicle_type"];

$db = pg_connect("host=nlpserver.postgres.database.azure.com port=5432 dbname=carpark_db user=nikhil@nlpserver password=MOMDAD<3nick");
$result= pg_query($db,"DROP MATERIALIZED VIEW parkings3" );
$result= pg_query($db,"DROP MATERIALIZED VIEW parkings2" );
$result= pg_query($db,"DROP MATERIALIZED VIEW occupancy" );
$result= pg_query($db,"DROP MATERIALIZED VIEW parkings1" );

$result= pg_query($db,"CREATE MATERIALIZED VIEW parkings1
AS
select * from table_carpark where vehicle_type='$vehicle_type'
" );

$result= pg_query($db,"CREATE MATERIALIZED VIEW occupancy
AS
SELECT carpark_id, count(carpark_id) FROM table_filled_slots_current INNER JOIN table_vehicle ON (table_vehicle.vehicle_id = table_filled_slots_current.vehicle_id and table_vehicle.vehicle_type='$vehicle_type') group by carpark_id
" );

$result= pg_query($db,"CREATE MATERIALIZED VIEW parkings2
AS
select parkings1.*,occupancy.count from occupancy RIGHT OUTER JOIN parkings1 ON (occupancy.carpark_id=parkings1.carpark_id)" );

$result= pg_query($db,"CREATE MATERIALIZED VIEW parkings3
AS
select parkings2.*,table_carpark_location.latitude,table_carpark_location.longitude,table_carpark_location.gate_id,table_carpark_location.zone from table_carpark_location,parkings2 where (table_carpark_location.carpark_id=parkings2.carpark_id and table_carpark_location.active_status=true);" );
$result= pg_query($db,"Select * from parkings3");
$data = pg_fetch_all($result);

function GetDrivingDistance($lat1, $lat2, $long1, $long2)
{
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL&key=AIzaSyD_kaLbzcZMUgIPoLIB4Fd0Y9FuorUUfk4";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    // echo "$lat1";
    // echo "$lat2";
    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
    // echo "aman"."$response_a[\'rows\']"."aman";
    // print_r($response_a);
    // echo "ayush"."$dist"."ayush";
    // echo "$time";
    return array('distance' => $dist, 'time' => $time);
}

foreach( $data as &$row) 
{
    $dist_current = GetDrivingDistance($cLat, $row[latitude], $cLon, $row[longitude]);
    $dist_dest = GetDrivingDistance($dLat, $row[latitude], $dLon, $row[longitude]);
    $row[cDist] = $dist_current['distance'];
    $row[cTime] = $dist_current['time'];
    $row[dDist] = $dist_dest['distance'];
    $row[dTime] = $dist_dest['time'];


    // $row->cDist = $dist_current['distance'];
    // $row->cTime = $dist_current['time'];
    // $row->dDist = $dist_dest['distance'];
    // $row->dTime = $dist_dest['time'];
}


$sortArray = array();
foreach($data as $person){
    foreach($person as $key=>$value){
        if(!isset($sortArray[$key])){
            $sortArray[$key] = array();
        }
        $sortArray[$key][] = $value;
    }
}
$orderby = "dDist";
array_multisort($sortArray[$orderby],SORT_ASC,$data);

?>


<center>
<div class="jumbotron">


<?php

echo "<table class=\"table table-striped\">";

echo "<tr>
<th>Carpark_Name</th>
<th>Capacity</th>
<th>Occupied</th>
<th>Zone</th>
<th>distanceFromDestination</th>
<th>timeToCarpark</th>
</tr>";

foreach( $data as &$row) 
{
  echo "<tr>
  <td><a href=\"carParkWiki.php?videoid=$row[carpark_id]\" target=\"_blank\">$row[carpark_id]</a></td>
  <td>$row[capacity]</td>
  <td>$row[count]</td>
  <td>$row[zone]</td>
  <td>$row[dDist]</td>
  <td>$row[dTime]</td>
  </tr>";
    
}

echo "</table>";

?>




</div>
</center>


<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>