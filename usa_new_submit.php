<!DOCTYPE html>

<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
	require_once __DIR__ . '/db_config.php';
		
		// open a connection to the database server
		$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
		password=$password");
		if (!$connection)
		{
		die("Could not open connection to database server");
		}
	if(isset($_GET['firstname']) && isset($_GET['lastname']) && isset($_GET['birthdate']) && isset($_GET['not']) && isset($_GET['gender'])){
		$firstname = $_GET['firstname'];
		$lastname = $_GET['lastname'];
		$not = $_GET['not'];
		$gender= $_GET['gender'];
		$birthd = $_GET['birthdate'];
	}
	if($not == 1){
		$t1s =  $_GET['t1s'];
		$t1e = 	$_GET['t1e'];
		$t1p = $_GET['t1p'];
		// generate and execute a query
		$query = "INSERT INTO usexecutives (firstname,lastname,birtddate,gender,numofterms,term1start,term1end,term1party) 
		VALUES('$firstname','$lastname','$birthd','$gender','$not','$t1s','$t1e','$t1p')"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));
	}
	if($not == 2){
		$t1s =  $_GET['t1s'];
		$t1e = 	$_GET['t1e'];
		$t1p = $_GET['t1p'];
		$t2s =  $_GET['t2s'];
		$t2e = $_GET['t2e'];
		$t2p =$_GET['t2p'];
		// generate and execute a query
		$query = "INSERT INTO usexecutives (firstname,lastname,birtddate,gender,numofterms,term1start,term1end,term1party,term2start,term2end,term2party) 
		VALUES('$firstname','$lastname','$birthd','$gender','$not','$t1s','$t1e','$t1p','$t2s','$t2e','$t2p')"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));
	}
	if($not == 3){
		$t1s =  $_GET['t1s'];
		$t1e = 	$_GET['t1e'];
		$t1p = $_GET['t1p'];
		$t2s =  $_GET['t2s'];
		$t2e = $_GET['t2e'];
		$t2p =$_GET['t2p'];
		$t3s =  $_GET['t3s'];
		$t3e = $_GET['t3e'];
		$t3p =	$_GET['t3p'];
		// generate and execute a query
		$query = "INSERT INTO usexecutives VALUES('$firstname','$lastname','$birthd','$gender','$not','$t1s','$t1e','$t1p','$t2s','$t2e','$t2p','$t3s','$t3e','$t3p')"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));
	}
	
?>
<head>
	<title>USA submit</title>
	<link rel="stylesheet" href="bootstrap.css">
	<style type='text/css'>
	
	body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	  body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	  .jumbotron {
        margin: 80px 0;
        text-align: center;
     }
    .jumbotron .lead {
        font-size: 24px;
        line-height: 1.25;
    }
	</style>  
</head>

<body>
	<div class='container-fluid'>
	<?php
		if($result){
	?>	
		<!-- Jumbotron -->
      <div class="jumbotron">
        <p class="lead">Data Added Successfully</p>
      </div>
	  <div class="span4 offset7">
	  <a href="./admin_usa.php" class="btn btn-large btn-block btn-success ">Go Back</a>	
	  </div>
	  <?php
	  }else{
	 ?> 
	 
	 <?php
	 }
	 ?>
	</div>
</body>
</html>