<!DOCTYPE html>

<html>

<head>

    <title>EU countries</title>
	<link rel="stylesheet" href="bootstrap.css"> 
	
	<style type='text/css'>
	body {
        padding-top: 60px;
        padding-bottom: 40px;
    }
	.jumbotron {
        margin: 35px 0;
        text-align: center;
     }
    .jumbotron .lead {
        font-size: 24px;
        line-height: 1.25;
    }
	#pagi{
		margin-top: 0px;
	}
	table tr td a {
		display:block;
		height:100%;
		width:100%;
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
			  <li><a href="#submit">Admin Panel</a></li>
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
		
		$page = 1;
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}
		$from = ($page-1)*10;
		
		// generate and execute a query
		$query = "SELECT country, COUNT(region) AS cnt1 FROM reg_euro GROUP BY country ORDER BY country  LIMIT 10 OFFSET $from"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$qry = "SELECT COUNT(DISTINCT country) AS cnt FROM reg_euro";
		$res = pg_query($connection, $qry) or die("Error in query:
		$qry. " .
		pg_last_error($connection));
		
		$ris = pg_fetch_object($res, 0);
		$ros = $ris->cnt;
		$rows = pg_num_rows($result);
		$pages = ceil($ros/10) ;
	?>
	
	<div class="container-fluid">
	  <!--Small Jumbotron -->
      <div class="jumbotron">
        <p class="lead">Select a Country</p>
	  </div>
	  
	  <!-- Pagination -->
		<div class="row-fluid">
			<div class="pagination pagination-centered" id="pagi"> 
				<ul>
				<?php
				if ($rows > 0){
				for($i=1;$i<=$pages;$i++){	
				if($page == $i){
				?>
				<li class="active"><a href="./eu_countries.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
				<?php
				}
				else{
				?>
				<li><a href="./eu_countries.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
				<?php
				}
				}
				}
				?>
				</ul>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span8 offset2">
			<table class="table table-bordered table-striped">
			<thead>  
			  <tr>  
				<th>Country</th>  
				<th>Number of Regions</th>
			  </tr>  
			</thead>
			<tbody>
			<?php
			if ($rows > 0){
			// iterate through resultset
			for ($i=0; $i<$rows; $i++){
				$row = pg_fetch_object($result, $i);
			
			?>
			<tr>  
            <td><a href="./eucountry_year.php?country=<?php echo $row->country ?>"><?php echo ucwords(strtolower($row->country)) ?></a></td>  
            <td><?php echo $row->cnt1 ?></td>  
            </tr>
			<?php
			}
			}
			?>	
			</tbody>
			</table>
			</div>
		</div>
	
	</div>
</body>
	
</html>	