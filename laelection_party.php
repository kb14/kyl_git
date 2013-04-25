<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<?php
	require_once __DIR__ . '/db_config.php';
		
	// open a connection to the database server
	$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
	password=$password");
	if (!$connection)
	{
	die("Could not open connection to database server");
	}
	if( isset($_GET['party'])){
		
		$party = $_GET['party'];
	}
	
	// generate and execute a query
		$q1 = "create view party_win as 
				SELECT candidate, constituency, state, year
				FROM all_assembly as aa
				WHERE aa.party='$party' "; 
		$r1 = pg_query($connection, $q1) or die("Error in query:
		$q1. " .
		pg_last_error($connection));
		
		
	
		// generate and execute a query
		$query = "SELECT state FROM party_win  GROUP BY state ORDER BY state"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$rows = pg_num_rows($result);
?>	

<head>

	<title><?php echo $party ?></title>
	<link rel="stylesheet" href="bootstrap.css">
	<script type="text/javascript" src="http://localhost/kyl/jquery.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/jquery.tablesorter.js"></script>
	<script type="text/javascript">
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.post("mainsearch.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
	</script>
	<script type="text/javascript"> 
		$(document).ready(function() 
		{ 
			$("#myTable1").tablesorter(); 
		}
		);	
	</script>	
	<style type='text/css'>
	
	body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	  .suggestionsBox {
		position: absolute;
		left: 5px;
		top: 25px;
		margin: 10px 0px 0px 0px;
		width: 225px;
		background-color: #ffffff;
		-moz-border-radius: 6px;
		-webkit-border-radius: 6px;
		  border-radius: 6px;
		-webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		-moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
          box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		-webkit-background-clip: padding-box;
		-moz-background-clip: padding;
          background-clip: padding-box;
		border: 1px solid #ccc;	
		color: transparent;
	}
	.jumbotron {
        margin: 25px 0;
        text-align: center;
     }
    .jumbotron .lead {
        font-size: 18px;
        line-height: 1.25;
		}
	.sugglist a{
		display: block;
		padding: 3px 0px;
	}
	.sugglist a:hover{
	  color: #ffffff;
	  text-decoration: none;
	  background-color: #0081c2;
	  background-image: -moz-linear-gradient(top, #0088cc, #0077b3);
	  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0077b3));
	  background-image: -webkit-linear-gradient(top, #0088cc, #0077b3);
	  background-image: -o-linear-gradient(top, #0088cc, #0077b3);
	  background-image: linear-gradient(to bottom, #0088cc, #0077b3);
	  background-repeat: repeat-x;
	  outline: 0;
	  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0077b3', GradientType=0);

	}
	</style> 
</head>

<body>
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
			  <li><a href="./twitter_search.php">Twitter Search</a></li>              
			  <li><a href="./india_elections_crime.html">Crime Data</a></li>
			  <li><a href="./admin_signin.php">Admin Panel</a></li>

            </ul>
			<form class="navbar-search pull-right">  
			<input type="text" class="search-query" placeholder="Search" onkeyup="lookup(this.value);" onblur="fill();"> 
			<div class="suggestionsBox" id="suggestions" style="display: none;">

				<div class="sugglist" id="autoSuggestionsList">
				</div>
			</div>	
			</form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	
	<div class="container-fluid">
		<div class="jumbotron">
        <p class="lead">Success rates for <?php echo $party ?>: </p>
	  </div>
	  <div class="row-fluid">
	  <div class="span12">
		<table class="table table-bordered table-striped" id="myTable1">
			<thead>  
									<tr>  
										<th>State</th>  
										<th>Year</th>
										<th>Success Rate</th>
									</tr>  
			</thead>
			<tbody>
			<?php
			for($i=0;$i<$rows;$i++){
			$row = pg_fetch_object($result, $i);
			$query1 = "SELECT year FROM party_win WHERE state='$row->state' GROUP BY year"; 
		$result1 = pg_query($connection, $query1) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$rows1 = pg_num_rows($result1);
		for($j=0;$j<$rows1;$j++){
		$row1 = pg_fetch_object($result1, $j);
		
		$q2 = "select count(*) as c1 FROM party_win where state='$row->state' and year=$row1->year"; 
		$r2 = pg_query($connection, $q2) or die("Error in query:
		$q2. " .
		pg_last_error($connection));
		$row3 = pg_fetch_object($r2, 0);
		
		$q3 = "select count(*) as c2
			FROM win_assembly as wa
			WHERE wa.party='$party' and wa.year=$row1->year and wa.state='$row->state'"; 
		$r3 = pg_query($connection, $q3) or die("Error in query:
		$q3. " .
		pg_last_error($connection));
		$row4 = pg_fetch_object($r3, 0);
			?>
			<tr>
										<td><?php echo $row->state ?></td>
										<td><?php echo $row1->year ?></td>  
										<td><?php echo ($row4->c2/$row3->c1)*100 ?></td>  
										
										</tr>
			<?php
			}
			}
			$q4 = "drop view party_win"; 
		$r4 = pg_query($connection, $q4);
		
			?>
			</tbody>	
		</table>
		</div>
	  </div>
	</div>
	
</body>	
</html>	