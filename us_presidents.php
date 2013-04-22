<!DOCTYPE html>

<html>

<head>

    <title>US Presidents</title>
	<link rel="stylesheet" href="bootstrap.css"> 
	
	<style type='text/css'>
	
	body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	#badb{
		margin-top: 15px
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
              <li><a href="#contact">About</a></li>
			  <li><a href="#contact">Admin Panel</a></li>

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
		$not_bu = 'all';
		$bd_bu = 'all';
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}
		$from = ($page-1)*10;
		
		if(isset($_GET['numofterms']) && isset($_GET['birthdate'])){
			$not = $_GET['numofterms'];
			$not_bu = $_GET['numofterms'];
			$bd = $_GET['birthdate'];
			$bd_bu = $_GET['birthdate'];
			if($not != 'all' || $bd != 'all'){
				if($bd == 'all'){
					$not = (int) substr($not,2,1);
					// generate and execute a query
					$query = "SELECT firstname, lastname, birtddate, numofterms, term1start FROM usexecutives WHERE numofterms>=$not ORDER BY
					firstname, lastname LIMIT 10 OFFSET $from"; 
					$result = pg_query($connection, $query) or die("Error in query:
					$query. " .
					pg_last_error($connection));
					$qry = "SELECT COUNT(*) AS cnt FROM usexecutives WHERE numofterms>=$not";
				}
				else if ($not == 'all'){
					$frm = substr($bd,0,4)."/01/01";
					$to = substr($bd,5,4)."/01/01";
					// generate and execute a query
					$query = "SELECT firstname, lastname, birtddate, numofterms, term1start FROM usexecutives WHERE birtddate BETWEEN '$frm' and '$to'  ORDER BY
					firstname, lastname LIMIT 10 OFFSET $from"; 
					$result = pg_query($connection, $query) or die("Error in query:
					$query. " .
					pg_last_error($connection));
					$qry = "SELECT COUNT(*) AS cnt FROM usexecutives WHERE birtddate BETWEEN '$frm' and '$to'";
				}
				else{
					$not = (int) substr($not,2,1);
					$frm = substr($bd,0,4)."/01/01";
					$to = substr($bd,5,4)."/01/01";
					$query = "SELECT firstname, lastname, birtddate, numofterms, term1start FROM usexecutives WHERE numofterms>=$not AND birtddate BETWEEN '$frm' and '$to'  ORDER BY
					firstname, lastname LIMIT 10 OFFSET $from"; 
					$result = pg_query($connection, $query) or die("Error in query:
					$query. " .
					pg_last_error($connection));
					$qry = "SELECT COUNT(*) AS cnt FROM usexecutives WHERE numofterms>=$not AND birtddate BETWEEN '$frm' and '$to'"; 
				}
			}
			else{
				// generate and execute a query
				$query = "SELECT firstname, lastname, birtddate, numofterms, term1start FROM usexecutives ORDER BY
				firstname, lastname LIMIT 10 OFFSET $from"; 
				$result = pg_query($connection, $query) or die("Error in query:
				$query. " .
				pg_last_error($connection));
				$qry = "SELECT COUNT(*) AS cnt FROM usexecutives "; 
			}
		}	
		else{		
			// generate and execute a query
			$query = "SELECT firstname, lastname, birtddate, numofterms, term1start FROM usexecutives ORDER BY
			firstname, lastname LIMIT 10 OFFSET $from"; 
			$result = pg_query($connection, $query) or die("Error in query:
			$query. " .
			pg_last_error($connection));
			$qry = "SELECT COUNT(*) AS cnt FROM usexecutives "; 

		}
		//$qry = "SELECT COUNT(*) AS cnt FROM usexecutives "; 
		$res = pg_query($connection, $qry) or die("Error in query:
		$qry. " .
		pg_last_error($connection));
		
		$ris = pg_fetch_object($res, 0);
		$ros = $ris->cnt;
		$rows = pg_num_rows($result);
		
		$pages = ceil($ros/10) ; 	
	?>
	<div class="container-fluid">
		
		<!-- Filters -->
			<form action="/kyl/us_presidents.php" method="GET">  
			<div class="row-fluid">
				<div class="control-group span2 offset2">  
					<label class="control-label" for="select01">Number of Terms</label>  
					<div class="controls">  
					<select id="select01" name="numofterms"> 
					<option>all</option>
					<option>>=2</option>  
					<option>>=3</option>  
					</select>  
					</div>  
				</div>  
				<div class="control-group span2">  
					<label class="control-label" for="select02">Born</label>  
					<div class="controls">  
					<select id= "select02" name="birthdate">
					<option>all</option>		
					<option>1700-1750</option>
					<option>1750-1800</option>
					<option>1800-1850</option>
					<option>1850-1900</option>
					<option>1900-1950</option>
					<option>1950-2000</option>
					</select>  
					</div>  
				</div>
				<div class="span2 offset2">
					<button type="submit" class="btn btn-large" id="badb">Filter</button>
				</div>
			</div>	
			</form>
		
				
		
		<!-- Pagination -->
		<div class="row-fluid">
			<div class="pagination pagination-centered" id="pagi"> 
				<ul>
				<?php
				if ($rows > 0){
				for($i=1;$i<=$pages;$i++){	
				if($page == $i){
				?>
				<li class="active"><a href="./us_presidents.php?page=<?php echo $i ?>&numofterms=<?php echo $not_bu ?>&birthdate=<?php echo $bd_bu ?>"><?php echo $i ?></a></li>
				<?php
				}
				else{
				?>
				<li><a href="./us_presidents.php?page=<?php echo $i ?>&numofterms=<?php echo $not_bu ?>&birthdate=<?php echo $bd_bu ?>"><?php echo $i ?></a></li>
				<?php
				}
				}
				}
				?>
				</ul>
			</div>
		</div>
		
      <div class="row-fluid">
        	
		<div class="span10 offset1">
		<table class="table table-bordered table-striped tbl">
			<thead>  
			  <tr>  
				<th>Name</th>  
				<th>Date of Birth</th>  
				<th>Number of Terms</th>
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
            <td><a href="./uspresidents_profile.php?term1start=<?php echo $row->term1start ?>"><?php echo $row->firstname." ".$row->lastname ?></a></td>  
            <td><?php echo $row->birtddate ?></td>  
            <td><?php echo $row->numofterms ?></td>  
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