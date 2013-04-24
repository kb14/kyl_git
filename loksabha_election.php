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
	
	if(isset($_GET['year'])){
		$year = $_GET['year'];
	}
	$election="Lok Sabha Elections";
	
	if(isset($_GET['total_assets']) && isset($_GET['criminal_cases'])){
		$ta = $_GET['total_assets'];
		$cc = $_GET['criminal_cases'];
		if($ta != 'all' || $cc != 'all'){
			if($cc=='all'){
				$pos = strrpos($ta,"-");
				$l = strlen($ta);
				$ta1 = substr($ta, 0, $pos);
				$ta2 = substr($ta, $pos+1,$l-$pos-1 );
				// generate and execute a query
				$query = "SELECT all_ls.candidate AS candidate, all_ls.constituency AS constituency, all_ls.party AS party, all_ls.criminal_cases AS criminal_cases, all_ls.total_assets AS total_assets
				FROM all_ls,win_ls WHERE all_ls.year='$year' AND win_ls.year='$year' AND all_ls.candidate=win_ls.candidate AND all_ls.party=win_ls.party AND all_ls.constituency=win_ls.constituency  and all_ls.total_assets 
				BETWEEN '$ta1' AND '$ta2' ORDER BY total_assets, constituency"; 
				$result = pg_query($connection, $query) or die("Error in query:
				$query. " .
				pg_last_error($connection));
				$rows = pg_num_rows($result);
			}
			else if($ta=='all'){
				$pos = strrpos($cc,"-");
				$l = strlen($cc);
				$cc1 = substr($cc, 0, $pos);
				$cc2 = substr($cc, $pos+1,$l-$pos-1 );
				// generate and execute a query
				$query = "SELECT all_ls.candidate AS candidate, all_ls.constituency AS constituency, all_ls.party AS party, all_ls.criminal_cases AS criminal_cases, all_ls.total_assets AS total_assets
				FROM all_ls,win_ls WHERE all_ls.year='$year' AND win_ls.year='$year' AND all_ls.candidate=win_ls.candidate AND all_ls.party=win_ls.party AND all_ls.constituency=win_ls.constituency  AND all_ls.criminal_cases 
				BETWEEN '$cc1' AND '$cc2' ORDER BY criminal_cases, constituency"; 
				$result = pg_query($connection, $query) or die("Error in query:
				$query. " .
				pg_last_error($connection));
				$rows = pg_num_rows($result);
			}
			else{
				$pos1 = strrpos($ta,"-");
				$l1 = strlen($ta);
				$ta1 = substr($ta, 0, $pos1);
				$ta2 = substr($ta, $pos1+1,$l1-$pos1-1 );
				
				$pos2 = strrpos($cc,"-");
				$l2 = strlen($cc);
				$cc1 = substr($cc, 0, $pos2);
				$cc2 = substr($cc, $pos2+1,$l2-$pos2-1 );
				// generate and execute a query
				$query = "SELECT all_ls.candidate AS candidate, all_ls.constituency AS constituency, all_ls.party AS party, all_ls.criminal_cases AS criminal_cases, all_ls.total_assets AS total_assets
				FROM all_ls,win_ls WHERE all_ls.year='$year' AND win_ls.year='$year' AND all_ls.candidate=win_ls.candidate AND all_ls.party=win_ls.party AND all_ls.constituency=win_ls.constituency  AND (all_ls.criminal_cases 
				BETWEEN '$cc1' AND '$cc2') AND (all_ls.total_assets BETWEEN '$ta1' and '$ta2') ORDER BY criminal_cases, total_assets, constituency"; 
				$result = pg_query($connection, $query) or die("Error in query:
				$query. " .
				pg_last_error($connection));
				$rows = pg_num_rows($result);
			}
		}
		else{
			// generate and execute a query
			$query = "SELECT all_ls.candidate AS candidate, all_ls.constituency AS constituency, all_ls.party AS party, all_ls.criminal_cases AS criminal_cases, all_ls.total_assets AS total_assets
			FROM all_ls,win_ls WHERE all_ls.year='$year' AND win_ls.year='$year' AND all_ls.candidate=win_ls.candidate AND all_ls.party=win_ls.party AND all_ls.constituency=win_ls.constituency ";	
			$result = pg_query($connection, $query) or die("Error in query:
			$query. " .
			pg_last_error($connection));
			$rows = pg_num_rows($result);
		}
	}
	else{
		// generate and execute a query
		$query = "SELECT all_ls.candidate AS candidate, all_ls.constituency AS constituency, all_ls.party AS party, all_ls.criminal_cases AS criminal_cases, all_ls.total_assets AS total_assets
		FROM all_ls,win_ls WHERE all_ls.year='$year' AND win_ls.year='$year' AND all_ls.candidate=win_ls.candidate AND all_ls.party=win_ls.party AND all_ls.constituency=win_ls.constituency ";	
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$rows = pg_num_rows($result);
	}
	// generate and execute a query
	$query1 = "SELECT party, COUNT(*) as seats FROM win_ls WHERE year='$year' GROUP BY party"; 
	$result1 = pg_query($connection, $query1) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows1 = pg_num_rows($result1);
	 
		
?>
<head>

	<title>Lok Sabha Elections <?php echo $year ?></title>
	<link rel="stylesheet" href="bootstrap.css">
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
	#badb1{
		margin-top: 17px;
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
        var options = {'title':'<?php echo $election.": ".$year?>',
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
	
	<!-- MODAL -->
	<div id="example" class="modal hide fade in" style="display: none; ">  
		<div class="modal-header">  
		<a class="close" data-dismiss="modal">x</a>  
		<h3><?php echo $election.": ".$year ?></h3>  
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
				<a class="lead label label-info" href="./india_loksabha.html">Lok Sabha</a><br>
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
	
		<!-- Filters -->
			<form action="/kyl/loksabha_election.php" method="GET">  
			<div class="row-fluid">
				<div class="control-group span2 ">  
					<label class="control-label" for="select01">Criminal Cases</label>  
					<div class="controls">  
					<select id="select01" name="criminal_cases"> 
					<option>all</option>
					<option value="0-0">0</option>
					<option>1-5</option>  
					<option>5-10</option>
					<option>10-20</option>
					<option>20-30</option>
					<option value="30-100">above 30</option>
					</select>  
					</div>  
				</div>  
				<div class="control-group span2 offset2">  
					<label class="control-label" for="select02">Total Assets</label>  
					<div class="controls">  
					<select id= "select02" name="total_assets">
					<option>all</option>		
					<option value="0-500000">0 - 5 lacs</option>
					<option value="500000-1000000">5 lacs - 10 lacs</option>
					<option value="1000000-5000000">10 lacs - 50 lacs</option>
					<option value="5000000-10000000">50 lacs - 1 Cr</option>
					<option value="10000000-100000000">1 Cr - 10 Cr</option>
					<option value="100000000-500000000">10 Cr - 50 Cr</option>
					<option value="500000000-1000000000">50 Cr - 100 Cr</option>
					<option value="1000000000-2000000000">More than 100 Cr</option>
					</select>  
					</div>  
				</div>
				<input type="hidden" name="year" value="<?php echo $year ?>" />
				<div class="span2 offset2">
					<button type="submit" class="btn btn-large" id="badb1">Filter</button>
				</div>
				
			</div>	
			</form>
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
        <td><a href="./lscandidate_profile.php?winner=<?php echo $row->candidate?>&candidate=<?php echo $row->candidate?>&year=<?php echo $year ?>&constituency=<?php echo $row->constituency ?>"><?php echo ucwords(strtolower($row->candidate)) ?></a></td>  
        <td><a href="./lselection_region.php?year=<?php echo $year ?>&constituency=<?php echo $row->constituency ?>&winner=<?php echo $row->candidate ?>"><?php echo $row->constituency ?></a></td>  
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