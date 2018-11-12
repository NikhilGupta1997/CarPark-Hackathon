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
  <script>

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            window.alert ("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) 
    {
        document.myform.cLat.value = position.coords.latitude;
        document.myform.cLon.value = position.coords.longitude;
    }

</script>

<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$cLat=$cLon="";
$dLat=$dLon="";

$destination_string=$use_current_location="";
$vehicle_id="ayush_car";
$use_current_location="";
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  $use_current_location=$_POST["use_current_location"];
  $cLat= $_POST["cLat"];
  $cLon= $_POST["cLon"];
  $destination_string= $_POST["destination_string"];
  if(strcmp($destination_string,"executive_centre")==0)
  {
    $dLat=0;
    $dLon=1;
  }
  else if(strcmp($destination_string,"macdonalds")==0)
  {
    $dLat=2;
    $dLon=3;
  }
  else if(strcmp($destination_string,"pioneer_hall")==0)
  {
    $dLat=4;
    $dLon=5;
  }
  echo "<script type='text/javascript'>window.top.location='carparkResult.php?cLat=$cLat&cLon=$cLon&dLat=$dLat&dLon=$dLon&useCurrLoc=$use_current_location';</script>"; exit;
  // header("Location: carparkResult.php?cLat=$cLat&cLon=$cLon&dLat=$dLat&dLon=$dLon&useCurrLoc=$use_current_location");
  // die();  

  // $totalLink="carparkResult.php?cLat=".$cLat."&cLon=".$cLon."&dLat=".$dLat."&dLon=".$dLon."&useCurrLoc=".$use_current_location;
  

}
else
{
  echo "<script> getLocation(); </script>";
}

?>


<center>
<div class="jumbotron">




<form name="myform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
  Select Destination: 
  <select name="destination_string" value="<?php echo $destination_string;?>">
  <option value="executive_centre">Executive Centre</option>
  <option value="macdonalds">Macdonalds</option>
  <option value="pioneer_hall">Pioneer Hall</option>
  </select>
  <br><br>
  Use Current Location? : 
  <select name="use_current_location" value="<?php echo $use_current_location;?>">
  <option value="yes">YES</option>
  <option value="no">NO</option>
  </select>
  <br><br>
  <input type="hidden" name="cLat" value="<?php echo $cLat;?>" >
  <input type="hidden" name="cLon" value="<?php echo $cLon;?>" >
  <input type="submit" name="submit" value="Submit">  
</form>
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