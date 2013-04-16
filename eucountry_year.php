<!DOCTYPE html>

<html>

<head>

    <title>EU Country Year</title>
	<link rel="stylesheet" href="bootstrap.css"> 
	
	<style type='text/css'>
	
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
	
	<?php
		require_once __DIR__ . '/db_config.php';
		
		// open a connection to the database server
		$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
		password=$password");
		if (!$connection)
		{
		die("Could not open connection to database server");
		}
		if(isset($_GET['country'])){
			$country = $_GET['country'];
		}
		
		$query = "SELECT year FROM european WHERE country='$country' GROUP BY year"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$rows = pg_num_rows($result);
		
	?>
	
	<div class="container-fluid">
	<div class="jumbotron">
        <p class="lead">Select a year to see election results for that year in <?php echo $country ?></p>
	</div>
	<hr>
	<div class="row-fluid">
		<div class="span2 offset5">
		<?php
			if ($rows > 0){
			for($i=0;$i<$rows;$i++){
				$row = pg_fetch_object($result, $i);
		?>
		<a href="./eucountry_election.php?country=<?php echo $country ?>&year=<?php echo $row->year ?>" class="btn btn-large btn-block btn-link"><?php echo $row->year ?></a>	
		<?php
		}
		}
		?>	
		</div>
	</div>
	
	</div>

</body>
</html>	