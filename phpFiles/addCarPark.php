<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Add_CarPark</title>

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
$carpark_id_err = $carpark_id= "";
$H_capacity_err = $H_capacity= "";
$C_capacity_err = $C_capacity= "";
$M_capacity_err = $M_capacity= "";
$B_capacity_err = $B_capacity= "";
$space_type = "";
$open_time = "";
$close_time = "";
$fare = "";
$userId = "tempUserId";
$update_status="";
$latitude_err = $latitude = "";
$longitude_err = $longitude = "";
$gate_id_err = $gate_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{  
  $userId = "tempUserId";
  $getout=0;
  if (empty($_POST["carpark_id"])) 
  {
    $carpark_id_err = "CarParkID is required";
    $getout=1;
  } 
  else 
  {
    $carpark_id = test_input($_POST["carpark_id"]);
  }
  if (empty($_POST["H_capacity"])) 
  {
    $H_capacity_err = "vehicle Capacity is required";
    $getout=1;
  } 
  else
  {
  	$H_capacity = $_POST["H_capacity"];
  }
  if (empty($_POST["C_capacity"])) 
  {
    $C_capacity_err = "vehicle Capacity is required";
    $getout=1;
  } 
  else
  {
    $C_capacity = $_POST["C_capacity"];
  }
  if (empty($_POST["M_capacity"])) 
  {
    $M_capacity_err = "vehicle Capacity is required";
    $getout=1;
  } 
  else
  {
    $M_capacity = $_POST["M_capacity"];
  }
  if (empty($_POST["B_capacity"])) 
  {
    $B_capacity_err = "vehicle Capacity is required";
    $getout=1;
  } 
  else
  {
    $B_capacity = $_POST["B_capacity"];
  }

  if (empty($_POST["latitude"])) 
  {
    $latitude_err = "Latitude is required";
    $getout=1;
  } 
  else
  {
    $latitude = $_POST["latitude"];
  }

  if (empty($_POST["longitude"])) 
  {
    $longitude_err = "Longitude is required";
    $getout=1;
  } 
  else
  {
    $longitude = $_POST["longitude"];
  }

if (empty($_POST["gate_id"])) 
  {
    $gate_id_err = "Gate ID is required";
    $getout=1;
  } 
  else
  {
    $gate_id = $_POST["gate_id"];
  }





  if (!(empty($_POST["space_type"]))) 
  {
    $space_type = test_input($_POST["space_type"]);
  }


  if (!(empty($_POST["space_type"]))) 
  {
    $space_type = test_input($_POST["space_type"]);
  }


  if (!(empty($_POST["open_time"]))) 
  {
    $open_time = test_input($_POST["open_time"]);
  }

  if (!(empty($_POST["close_time"]))) 
  {
    $close_time = test_input($_POST["close_time"]);
  }

  if (!(empty($_POST["fare"]))) 
  {
    $fare = test_input($_POST["fare"]);
  }



  
  if($getout==0)
  {
    $db = pg_connect("host=nlpserver.postgres.database.azure.com port=5432 dbname=carpark_db user=nikhil@nlpserver password=MOMDAD<3nick");
    $result= pg_query($db,"Select * from table_carpark WHERE carpark_id='$carpark_id'" );
    $row = pg_fetch_assoc($result);
    $sizeof_row=sizeof($row,1);
    if($sizeof_row==7)
    {
      $update_status="This Carpark already exists";
    }
    else
    {
      $resultH= pg_query($db,"INSERT INTO table_carpark VALUES('$carpark_id','H','$H_capacity','$space_type','$open_time','$close_time','$fare')" );
      $resultC= pg_query($db,"INSERT INTO table_carpark VALUES('$carpark_id','C','$C_capacity','$space_type','$open_time','$close_time','$fare')" );
      $resultM= pg_query($db,"INSERT INTO table_carpark VALUES('$carpark_id','M','$M_capacity','$space_type','$open_time','$close_time','$fare')" );
      $resultB= pg_query($db,"INSERT INTO table_carpark VALUES('$carpark_id','B','$B_capacity','$space_type','$open_time','$close_time','$fare')" );
      $resultL= pg_query($db,"INSERT INTO table_carpark_location VALUES('$carpark_id','$latitude','$longitude','$gate_id','True','Other')");
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
<h2>Add CarPark</h2>
<p><span class="error">* required field.</span></p>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  CarParkId: <input type="text" name="carpark_id" value="<?php echo $carpark_id;?>">
  <span class="error">* <?php echo $carpark_id_err;?></span>
  <br><br>
  H_capacity: <input type="int" name="H_capacity" value="<?php echo $H_capacity;?>">
  <span class="error">* <?php echo $H_capacity_err;?></span>
  <br><br>
  C_capacity: <input type="int" name="C_capacity" value="<?php echo $C_capacity;?>">
  <span class="error">* <?php echo $C_capacity_err;?></span>
  <br><br>
  M_capacity: <input type="int" name="M_capacity" value="<?php echo $M_capacity;?>">
  <span class="error">* <?php echo $M_capacity_err;?></span>
  <br><br>
  B_capacity: <input type="int" name="B_capacity" value="<?php echo $B_capacity;?>">
  <span class="error">* <?php echo $B_capacity_err;?></span>
  <br><br>

  Latitude: <input type="int" name="latitude" value="<?php echo $latitude;?>">
  <span class="error">* <?php echo $latitude_err;?></span>
  <br><br>
  Longitude: <input type="text" name="longitude" value="<?php echo $longitude;?>">
  <span class="error">* <?php echo $latitude_err;?></span>
  <br><br>
  Gate_ID <input type="text" name="gate_id" value="<?php echo $gate_id;?>">
  <span class="error">* <?php echo $gate_id_err;?></span>
  <br><br>



  Open_Time: <input type="int" name="open_time" value="<?php echo $open_time;?>">
  <br><br>
  Close_Time: <input type="int" name="close_time" value="<?php echo $close_time;?>">
  <br><br>
  Fare: <input type="number" step = "any" name="fare" value="<?php echo $fare;?>">
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
