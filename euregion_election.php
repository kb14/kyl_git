<!DOCTYPE html>

<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
		if(isset($_GET['country']) && isset($_GET['year']) && isset($_GET['region'])){
			$country = $_GET['country'];
			$year = $_GET['year'];
			$region = $_GET['region'];
		}
	
		require_once __DIR__ . '/db_config.php';
		
		// open a connection to the database server
		$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
		password=$password");
		if (!$connection){
			die("Could not open connection to database server");
		}

?>

<head>
	<title><?php echo $region ?> Election Results</title>
	<link rel="stylesheet" href="bootstrap.css"> 
	
	<style type='text/css'>
	
	body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	</style> 

</head>

<body>

	<!--Navigation bar -->
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="./index.html">kyl</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="./index.html">Home</a></li>
              <li><a href="#about">Twitter Search</a></li>
              <li><a href="#contact">About</a></li>
			  <li><a href="#submit">Submit Data</a></li>
            </ul>
			<form class="navbar-search pull-right">  
			<input type="text" class="search-query" placeholder="Search">  
			</form>  
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

</body>

</html>