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
		// generate and execute a query
		$query = "SELECT state FROM const_state GROUP BY state ORDER BY state"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$rows = pg_num_rows($result);
?>
<head>

    <title>India States</title>
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
	#badb{
		margin-top: 60px;
		width: 220px;
	}
	#badc{
		margin-top: 50px;
	}
	</style>
	
	<script type="text/javascript">
	function populate(s1,s2){
	var s1 = document.getElementById(s1);
	var s2 = document.getElementById(s2);
	s2.innerHTML = "";
	<?php
	for($i=0;$i<$rows;$i++){
	$row = pg_fetch_object($result, $i);
	?>
	if(s1.value == "<?php echo $row->state ?>"){
		var optionArray = [];
		<?php
		// generate and execute a query
		$query1 = "SELECT year FROM win_assembly WHERE state='$row->state' GROUP BY year"; 
		$result1 = pg_query($connection, $query1) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$rows1 = pg_num_rows($result1);
		for($j=0;$j<$rows1;$j++){
		$row1 = pg_fetch_object($result1, $j);
		?>
		optionArray.push("<?php echo $row1->year ?>|<?php echo $row1->year ?>");
		<?php
		}
		?>
	}
	<?php
	}
	?>
	
	for(var option in optionArray){
		var pair = optionArray[option].split("|");
		var newOption = document.createElement("option");
		newOption.value = pair[0];
		newOption.innerHTML = pair[1];
		s2.options.add(newOption);
	}
	}
	</script>
	
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
	
	<div class="container-fluid">
	<!--Small Jumbotron -->
      <div class="jumbotron">
        <p class="lead">Select a State and year of Elections</p>
	  </div>

	  <hr>

		<form action="/kyl/lastate_election.php" method="GET">  
				<div class="row-fluid">
				<div class="control-group span2 offset5 ">  
					<label class="control-label" for="slct1">State</label>  
					<div class="controls">  
					<select id="slct1" name="state" onchange="populate(this.id,'slct2')"> 
					<option value=""></option>
					<?php
					for($i=0;$i<$rows;$i++){
					$row = pg_fetch_object($result, $i);
					?>
					<option value="<?php echo $row->state ?>"><?php echo ucwords(strtolower($row->state)) ?></option>
					<?php
					}
					?>
					</select>  
					</div>  
				</div>  
				</div>
				<div class="row-fluid">
				<div class="control-group span2 offset5" id="badc">  
					<label class="control-label" for="slct2">Year</label>  
					<div class="controls">  
					<select id= "slct2" name="year">
					</select>  
					</div>  
				</div>
				</div>
				<div class="row-fluid">
				<div class="span2 offset5">
					<input type="submit" class="btn btn-large btn-block btn-success" id="badb" value="Go"/>
				</div>
				</div>
					
			</form>
	</div>
	
	
</body>
</html>	