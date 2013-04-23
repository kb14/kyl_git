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
	
	if(isset($_GET['year']) && isset($_GET['state'])){
		$year = $_GET['year'];
		$state = $_GET['state'];
	}
	// generate and execute a query
	$query = "SELECT all_assembly.candidate AS candidate, all_assembly.constituency AS constituency, all_assembly.party AS party,
	all_assembly.criminal_cases AS criminal_cases, all_assembly.total_assets AS total_assets
	FROM all_assembly, win_assembly WHERE all_assembly.year='$year' AND all_assembly.state='$state' AND win_assembly.year='$year' AND win_assembly.state='$state'
	AND all_assembly.candidate=win_assembly.candidate AND all_assembly.party=win_assembly.party AND all_assembly.constituency=win_assembly.constituency"; 
	$result = pg_query($connection, $query) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows = pg_num_rows($result);
	
	// generate and execute a query
	$query1 = "SELECT party, COUNT(*) as seats FROM win_assembly WHERE year='$year' AND state='$state' GROUP BY party"; 
	$result1 = pg_query($connection, $query1) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows1 = pg_num_rows($result1);
?>
<head>
	<title><?php echo $state."-".$year ?></title>
	<link rel="stylesheet" href="bootstrap.css">
	<style type='text/css'>
	body {
        padding-top: 60px;
        padding-bottom: 40px;
    }
	.jumbotron {
        margin: 10px 0;
        text-align: center;
     }  
	 .jumbotron .lead {
        font-size: 40px;
        line-height: 1.25;
		margin-top: 50px;
    }
	.jumbotron .non-lead {
        font-size: 20px;
        line-height: 1.25;
    }
	#example .modal-body {
		max-height: 550px;
	}
	#example {
		width: 750px; 
		margin: -50px 0 0 -350px; 
	}
	#badb{
		margin-left: 190px;
	}
	table .header {
    cursor: pointer;
	}
	table .header:after {
	  content: "";
	  float: right;
	  margin-top: 7px;
	  border-width: 0 4px 4px;
	  border-style: solid;
	  border-color: #000000 transparent;
	  visibility: hidden;
	}
	table .headerSortUp, table .headerSortDown {
	  background-color: #f7f7f9;
	  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
	}
	table .header:hover:after {
	  visibility: visible;
	}
	table .headerSortDown:after, table .headerSortDown:hover:after {
	  visibility: visible;
	  filter: alpha(opacity=60);
	  -moz-opacity: 0.6;
	  opacity: 0.6;
	}
	table .headerSortUp:after {
	  border-bottom: none;
	  border-left: 4px solid transparent;
	  border-right: 4px solid transparent;
	  border-top: 4px solid #000000;
	  visibility: visible;
	  -webkit-box-shadow: none;
	  -moz-box-shadow: none;
	  box-shadow: none;
	  filter: alpha(opacity=60);
	  -moz-opacity: 0.6;
	  opacity: 0.6;
	}
	</style>
	<!--Load the AJAX API-->
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Party');
        data.addColumn('number', 'Seats');
		var rows=[];
		<?php
			for($i=0;$i<$rows1;$i++){
				$row = pg_fetch_object($result1, $i);
		?>
		rows.push(['<?php echo $row->party ?>', <?php echo (int)$row->seats ?>]);
		<?php
			}
		?>
        data.addRows(rows);

        // Set chart options
        var options = {'title':'<?php echo $state.": ".$year?>',
                       'width':500,
                       'height':400,
					   'chartArea': {'left': '170'}};
		var options1 = 	{
                       'width':700,
                       'height':600,
					   'chartArea': {'width': '100%', 'height': '80%','left': '70'}};		   

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
		var chart1 = new google.visualization.PieChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
		chart1.draw(data, options1);
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
              <li><a href="./twitter_search.php">Twitter Search</a></li>
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
	<!-- MODAL -->
	<div id="example" class="modal hide fade in" style="display: none; ">  
		<div class="modal-header">  
		<a class="close" data-dismiss="modal">x</a>  
		<h3><?php echo $state.": ".$year ?></h3>  
		</div>  
		<div class="modal-body">  
		<div id="chart_div1"></div>
		</div>  
		<div class="modal-footer">  
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
		</div>  
	</div>  
	<div class="row-fluid">
		
		<div class="span5">
			<div class="jumbotron">
				<a class="lead label label-info" href="./india_states.php"><?php echo $state ?></a><br>
				<p class="non-lead"><?php echo $year ?></p>
			</div>
			<hr>
			<div class="row-fluid">
				<!--Div that will hold the pie chart-->
				<div id="chart_div"></div>
			</div>
			<div><a data-toggle="modal" href="#example" class="btn btn-primary btn-large" id="badb">Expand Chart</a></div>
			
		</div>
		
		<!-- Right side, baby! -->
		<div class="span7">
			<table class="table table-bordered table-striped" id="myTable">
			<thead>  
			  <tr>  
				<th>Name</th>  
				<th>Constituency</th>  
				<th>Party</th>
				<th>Criminal Cases</th>
				<th>Total Assets</th>
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
			<td><a href="./lacandidate_profile.php?winner=<?php echo $row->candidate?>&candidate=<?php echo $row->candidate?>&year=<?php echo $year ?>&constituency=<?php echo $row->constituency ?>&state=<?php echo $state ?>"><?php echo ucwords(strtolower($row->candidate)) ?></a></td>  
			<td><a href="./laelection_region.php?state=<?php echo $state ?>&year=<?php echo $year ?>&constituency=<?php echo $row->constituency ?>&winner=<?php echo $row->candidate ?>"><?php echo ucwords(strtolower($row->constituency)) ?></a></td>  
			<td><?php echo $row->party ?></td>  
			<td><?php echo $row->criminal_cases ?></td>  
			<td><?php echo $row->total_assets ?></td>  
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
	
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	
    <script type="text/javascript" src="http://localhost/kyl/jquery.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/bootstrap-modal.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/jquery.tablesorter.js"></script>
	<script type="text/javascript"> 
		$(document).ready(function() 
		{ 
			$("#myTable").tablesorter(); 
		}
		);	
	</script>

</body>
</html>