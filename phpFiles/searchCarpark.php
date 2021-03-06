<html lang = "en">
   
  <head>
    <title>SearchForCarpark</title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
      $( function() {
        var availableTags = [
          "Current Location",
          "Administration Building",
          "Chinese Heritage Centre",
          "Community Herb Garden",
          "Nanyang Arch",
          "Nanyang Auditorium",
          "Nanyang Executive Centre",
          "Nanyang Lake",
          "Nanyang Tablet",
          "North Spine",
          "The Hive",
          "The Quad",
          "Research Techno Plaza",
          "Student Services Centre",
          "South Spine",
          "University Health Service Building",
          "Yunnan Garden"
        ];
        $( "#tags" ).autocomplete({
          source: availableTags
        });
      } );
    </script>
  </head>

  <body >
  <style type="text/css">
    #map {
        height: 300px;
        width: 80%;
      }
    input[type=button], input[type=submit], input[type=reset] {
      background-color: #4CAF50;
      border: none;
      color: white;
      font-size: 20px;
      padding: 16px 32px;
      text-decoration: none;
      margin: 4px 2px;
      cursor: pointer;
    }
  </style>
  <style>

        body {
          background-image: url("../images/whatsapp.png");
          background-color: #cccccc;
          background-repeat: repeat, repeat, no-repeat;
          background-size: auto, auto, cover;
          z-index: 1;
        }
        h1 {
            font-size: 20px;
            color: #111;
        }

        .content {
            width: 80%;
            margin: 0 auto;
            margin-top: 50px;
        }

        .tt-hint,
        .city {
            border: 2px solid #CCCCCC;
            border-radius: 8px 8px 8px 8px;
            font-size: 24px;
            height: 45px;
            line-height: 30px;
            outline: medium none;
            padding: 8px 12px;
            width: 400px;
        }

        .tt-dropdown-menu {
            width: 400px;
            margin-top: 5px;
            padding: 8px 12px;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 8px 8px 8px 8px;
            font-size: 18px;
            color: #111;
            background-color: #F1F1F1;
        }

        .styled-select{
          border: 1px solid #ccc;
          font-size: 16px;
          height: 34px;
          width: 268px;
        }
        .black   { background-color: #000; }
        .rounded {
          -webkit-border-radius: 20px;
         -moz-border-radius: 20px;
          border-radius: 20px;}

        #drop-select{
          border: 1px solid #ccc;
          font-size: 16px;
          height: 34px;
          width: 268px;
          -webkit-border-radius: 20px;
          -moz-border-radius: 20px;
          border-radius: 20px;

        }


}
  </style>
  <script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            window.alert ("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        document.myform.cLat.value = position.coords.latitude;
        document.myform.cLon.value = position.coords.longitude;
    }
  </script>

  <?php
    $db = pg_connect("host=nlpserver.postgres.database.azure.com port=5432 dbname=carpark_db user=nikhil@nlpserver password=MOMDAD<3nick");

    $cLat=$cLon="";
    $dLat=$dLon="";

    $destination_string="";
    $vehicle_id="ayush_car";
    $vehicle_type="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $vehicle_type=$_POST["vehicle_type"];
      $cLat= $_POST["cLat"];
      $cLon= $_POST["cLon"];
      echo "$_POST[js_sub]";
      if(!$_POST[submit]) {
        if(is_null($_POST["dLat"]) || is_null($_POST["dLon"])) {
          $dLat= $_POST["cLat"];
          $dLon= $_POST["cLon"];
        } else {
          $dLat= $_POST["dLat"];
          $dLon= $_POST["dLon"];
        }
      }
      else {
        if(is_null($_POST[destination_string])) {
          $dLat= $_POST["cLat"];
          $dLon= $_POST["cLon"];
        }
        else {
          $result= pg_query($db,"SELECT * FROM table_landmarks WHERE name = '$_POST[destination_string]'" );
          $myrow = pg_fetch_assoc($result);
          if(is_null($myrow[latitude]) || is_null($myrow[longitude])) {
            $dLat=$_POST["cLat"];
            $dLon=$_POST["cLon"];
          } else {
            $dLat=$myrow[latitude];
            $dLon=$myrow[longitude];
          }
        }
      }

      if(is_null($dLat) || is_null($dLon)) {
        $dLat=$_POST["cLat"];
        $dLon=$_POST["cLon"];
      }
      
       echo "<script type='text/javascript'>window.top.location='carparkResult.php?cLat=$cLat&cLon=$cLon&dLat=$dLat&dLon=$dLon&vehicle_type=$vehicle_type';</script>"; exit;
    }
    else
    {
      echo "<script> getLocation(); </script>";
    }
  ?>  

  <center>
  <div class="content">

  <form name="myform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
    <h2>Vehicle Type </h2>
    <div>
      <select id = "drop-select" name="vehicle_type" value="<?php echo $vehicle_type;?>">
      <option value="C">Car</option>
      <option value="H">Heavy Vehicle</option>
      <option value="M">Motorcycle</option>
      <option value="B">Bicycle</option>
      </select>
    </div>
    <br><br>
    <h2>Select Destination</h2>
    <input type="hidden" name="cLat" value="<?php echo $cLat;?>" >
    <input type="hidden" name="cLon" value="<?php echo $cLon;?>" >
    <input type="hidden" name="dLat" value="<?php echo $dLat;?>" >
    <input type="hidden" name="dLon" value="<?php echo $dLon;?>" >
    <input type="hidden" name="js_sub" value="<?php echo $js_sub;?>" >
    <input id="tags" class="city" type="text" name="destination_string" placeholder="Current Location" value="<?php echo $destination_string;?>">
    <input type="submit" name="submit" value="Submit"> 
    <br>
    <h2>OR </h2>
    <div id="map"></div>
    <!-- <button onclick="switchfun()">Click me</button> -->
    <br><br>
    <input type="submit" name="map_button" value="Set Map Destination">   
  </form>
  </div>
  </center>
    <script>
      // var map
      function initMap() {

        var location ={lat:1.349043, lng:103.678520};

        var options = {
        zoom:16,
        center:location
      }

        var map = new google.maps.Map(document.getElementById('map'), options);

        var marker = new google.maps.Marker({
          position:location,
          draggable:true,
          map:map,
          icon:'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'
        });

        document.myform.dLat.value = location['lat'];
        document.myform.dLon.value = location['lng'];

        google.maps.event.addListener(marker, 'dragend', function (evt) {
          document.myform.dLat.value = evt.latLng.lat().toFixed(4);
          document.myform.dLon.value = evt.latLng.lng().toFixed(4);
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_kaLbzcZMUgIPoLIB4Fd0Y9FuorUUfk4&callback=initMap"
    async defer></script>
    <script src="https://code.getmdl.io/1.2.1/material.min.js"></script>
  </body>
</html>
