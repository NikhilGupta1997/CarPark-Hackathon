<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Add_Vehicle</title>

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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// define variables and set to empty values
$vehicle_id_err = $vehicle_id= "";
$vehicle_type_err = $vehicle_type= "";
$vehicle_number_err = $vehicle_number= "";
$userId = "tempUserId";
$update_status="";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{  
  $userId = "tempUserId";
  $getout=0;
  if (empty($_POST["vehicle_id"])) 
  {
    $vehicle_id_err = "VehicleID is required";
    $getout=1;
  } 
  else 
  {
    $vehicle_id = test_input($_POST["vehicle_id"]);
  }
  if (empty($_POST["vehicle_type"])) 
  {
    $vehicle_type_err = "VehicleType is required";
    $getout=1;
  } 
  else
  {
  	$vehicle_type = $_POST["vehicle_type"];
  }

  if (empty($_POST["vehicle_number"])) 
  {
    $vehicle_number_err = "Vehicle Number is required";
    $getout=1;
  } 
  else 
  {
    $vehicle_number = test_input($_POST["vehicle_number"]);
  }


  
  if($getout==0)
  {
    $db = pg_connect("host=nlpserver.postgres.database.azure.com port=5432 dbname=carpark_db user=nikhil@nlpserver password=MOMDAD<3nick");
    $result= pg_query($db,"Select * from table_vehicle WHERE vehicle_id='$vehicle_id'" );
    $row = pg_fetch_assoc($result);
    $sizeof_row=sizeof($row,1);
    if($sizeof_row==4)
    {
      $update_status="This Vehicle is already registered with us! Please dont try to add same vehicle multiple times!";
    }
    else
    {
      $result= pg_query($db,"INSERT INTO table_vehicle VALUES('$vehicle_id','$vehicle_type','$vehicle_number','$userId')" );
      $update_status="Thanks for registering : $userId";
      $email="";
      $password_value="";
      $permission="";
    }
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<center>
<div class="jumbotron">
<h2>Add Vehicle</h2>
<p><span class="error">* required field.</span></p>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  VehicleId: <input type="text" name="vehicle_id" value="<?php echo $vehicle_id;?>">
  <span class="error">* <?php echo $vehicle_id_err;?></span>
  <br><br>
  VehicleType: <input type="text" name="vehicle_type" value="<?php echo $vehicle_type;?>">
  <span class="error">* <?php echo $vehicle_type_err;?></span>
  <br><br>
  VehicleNumber: <input type="text" name="vehicle_number" value="<?php echo $vehicle_number;?>">
  <span class="error">* <?php echo $vehicle_number_err;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>

<?php
echo
"
$update_status
";
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