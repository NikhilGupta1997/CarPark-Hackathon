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

  .redButton
  {
   background-color: #FF0000;
    border: none;
    color: white;
    padding: 16px 32px;
    text-decoration: none;
    margin: 4px 2px;
    cursor: pointer;
  }

  .grayButton
  {
   background-color: #bbb;
    border: none;
    color: white;
    padding: 16px 32px;
    text-decoration: none;
    margin: 4px 2px;
    cursor: pointer;
    width: 120px;
    height: 50px;
  }


  .greenButton, input[type=submit], input[type=reset] 
  {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 16px 32px;
    text-decoration: none;
    margin: 4px 2px;
    cursor: pointer;
  }
.accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: ridge;
    font-size: 24px;
    transition: 0.4s;
}

.hidden_latitude
{
  visibility: hidden;
}

.hidden_longitude
{
  visibility: hidden;
}

#current_latitude_hidden
{
  visibility: hidden;
}
#current_longitude_hidden
{
  visibility: hidden;
}
#dest_latitude_hidden
{
  visibility: hidden;
}
#dest_longitude_hidden
{
  visibility: hidden;
}

.active, .accordion:hover {
    background-color: #ccc; 
}

ul {
  overflow: none;
  list-style-type: none; 
  margin:0; 
  padding: 0; 
  text-align: center;
}
 
li {
  height: 25px;
  margin-right: 0px;
  border-right: 1px solid #aaa;
  padding: 0 20px;
  margin: 0 10px; 
  display: inline;
}

.listed {
  height: 50px;
  margin-right: 0px;
  border-right: 1px solid #aaa;
}
 
li:last-child {
  border-right: none;
}
 
li a {
  text-decoration: none;
  color: #666;
  font: 25px/1 Helvetica, Verdana, sans-serif;
  text-transform: uppercase;
 
  -webkit-transition: all 0.5s ease;
  -moz-transition: all 0.5s ease;
  -o-transition: all 0.5s ease;
  -ms-transition: all 0.5s ease;
  transition: all 0.5s ease;
}
 
li a:hover {
  color: #111;
}
 
li.active a {
  font-weight: bold;
  color: #000;
}


.panel {
    height: 300px;
    width: 100%;
    padding: 0 18px;
    display: none;
    background-color: white;
    overflow: hidden;
}
</style>
<script type="text/javascript">
  onload = function() {
    onfocus = function() {location.reload(true)}
  }
</script>

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
<div class="table-responsive">

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/><link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet"/><div class="container"> 
<h1>Suggested Carparks</h1>

<!-- echo "<script type=\"text/javascript\">
  
  $(document).ready(function() {
  $('#example').DataTable();
});

</script>"; -->


<?php

echo "<p style=\"display:none\" id=\"current_latitude_hidden\">$cLat</p>";
echo "<p style=\"display:none\" id=\"current_longitude_hidden\">$cLon</p>";
echo "<p style=\"display:none\" id=\"dest_latitude_hidden\">$dLat</p>";
echo "<p id=\"dest_longitude_hidden\">$dLon</p>";

echo " 
      <center>
      <ul>
      <li class=\"listed\"><a href=#><input type=button class=\"grayButton\" value='Back' onclick=\"history.go(-1);\"></a></li>
      <li class=\"listed\"><a href=\"feed.html\"><input type=button class=\"grayButton\" value='Exit'></a></li>
      </ul>
      </center><br><br>
      ";


for ($tp=0;$tp<count($data) && $tp<5 ;$tp++) 
{
  $row=$data[$tp];
  $tp_t=$tp+1;
  if(empty($row[count])) {
    $row[count] = 0;
  }
  echo "<button class=\"accordion\">
  <b>Suggested Parking $tp_t:</b>
  <a href=\"carParkWiki.php?videoid=$row[carpark_id]\" target=\"_blank\">$row[carpark_id]</a> <br><br>
  <ul class=\"population\">
  <li><a href=\"#\"><b>currentlyOccupied:</b>$row[count]</a></li>
  <li><a href=\"#\"><b>capacity:</b>$row[capacity]</a></li>
  <li><a href=\"#\"><b>charges:</b>$row[fare]</a></li>
  <li><a href=\"#\"><b>timeToReachCarstop:</b>$row[cTime]</a></li>
  <li><a href=\"#\"><b>distanceToYourDestinationFromCarpark:</b>$row[dDist]</a></li>
  </ul>  
    <p class = \"hidden_latitude\">$row[latitude]</p><p class = \"hidden_longitude\">$row[longitude]</p>
  ";

  if($row[count]>=$row[capacity])
  {
     echo " 
      <center><input type=button class=\"redButton\" value='CarparkIsFull!'></center>
      ";
  }
  else
  {
    
  echo " 
     <center><a href=\"https://www.google.com/maps/dir/?api=1&destination=$row[latitude], $row[longitude]&travelmode=driving&dir_action=navigate\" target=\"_blank\"><input type=button class=\"greenButton\" value='NavigateToCarpark!'></a></center>
      ";
  }

  echo "

    </button>
    <div class=\"panel\" id=\"$row[carpark_id]\">

    </div>
    ";

}


// foreach( $data as &$row) 
// {
  
// }

?>


<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}
</script>
  <script>
      // var map
      function initMap() 
      {
        var mapDropDowns = document.getElementsByClassName("panel");
        var destination_longitude_hidden = document.getElementsByClassName("hidden_longitude");
        var destination_latitude_hidden = document.getElementsByClassName("hidden_latitude");
        var tz;
        for (tz=0;tz<mapDropDowns.length;tz++)
        {
            var pointA = new google.maps.LatLng(document.getElementById("current_latitude_hidden").innerHTML, document.getElementById("current_longitude_hidden").innerHTML);
            var pointB = new google.maps.LatLng(destination_latitude_hidden[tz].innerHTML, destination_longitude_hidden[tz].innerHTML);
            var pointC = new google.maps.LatLng(document.getElementById("dest_latitude_hidden").innerHTML, document.getElementById("dest_longitude_hidden").innerHTML);
            var options = 
            {
            zoom:16,
            center:pointA
            }
            var map = new google.maps.Map(mapDropDowns[tz],options);
            
            // window.alert(destination_latitude_hidden[tz].innerHTML);
            // window.alert(destination_latitude_hidden[tz].innerHTML);
            directionsService = new google.maps.DirectionsService;
            directionsDisplay = new google.maps.DirectionsRenderer({
              map: map,
              preserveViewport: true
            });
            markerA = new google.maps.Marker({
              position: pointA,
              title: "point A",
              label: "A",
              map: map
            });
            markerB = new google.maps.Marker({
              position: pointB,
              title: "point B",
              label: "B",
              map: map
            });
            markerC = new google.maps.Marker({
              position: pointC,
              title: "point C",
              label: "C",
              map: map
            });

          // get route from A to B
          calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB);
            
          //   var marker = new google.maps.Marker({
          //   position:location,
          //   draggable:true,
          //   map:map,
          //   icon:'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'
          // });
        }

        // var map = new google.maps.Map(document.getElementById('mapcid2'), options);

        

        // document.myform.dLat.value = location['lat'];
        // document.myform.dLon.value = location['lng'];

        // google.maps.event.addListener(marker, 'dragend', function (evt) {
        //   document.myform.dLat.value = evt.latLng.lat().toFixed(4);
        //   document.myform.dLon.value = evt.latLng.lng().toFixed(4);
        // });
      }

      function switchfun(output) 
      {
        // var output = document.getElementById("map");

        if (!navigator.geolocation){
        output.innerHTML = "<p>Geolocation is not supported by your browser</p>";
        return;
        }

        function success(position) {
        var latitude  = position.coords.latitude;
        var longitude = position.coords.longitude;
        }

        function error() {
        output.innerHTML = "Unable to retrieve your location";
        }

        output.innerHTML = "<p>Locating…</p>";

        navigator.geolocation.getCurrentPosition(success, error);
        window.open("https://www.google.com/maps/dir/?api=1&destination=1.347823, 103.680633&travelmode=driving&dir_action=navigate")
        // window.open("https://maps.google.com/maps?daddr=<latitude>,<longitude>&amp;ll=");
          }

      function calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB) {
        directionsService.route({
          origin: pointA,
          destination: pointB,
          travelMode: google.maps.TravelMode.DRIVING
        }, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_kaLbzcZMUgIPoLIB4Fd0Y9FuorUUfk4&callback=initMap"
    async defer></script>


</div><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js">
</script><script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js"></script>
</div>

</div>
</center>


<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>