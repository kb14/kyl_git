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
		
		// generate and execute a query
		$query = "SELECT party,votes_num FROM european WHERE country='$country' and year='$year' and region='$region'"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));	
		$rows = pg_num_rows($result);
		
		$qry1 = "SELECT region FROM european WHERE country='$country' and year='$year' and region<>'$country' GROUP BY region ORDER BY region"; 
		$res1 = pg_query($connection, $qry1) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$ris1 = pg_num_rows($res1);
?>

<head>
	<title><?php echo $region ?> Election Results</title>
	<link rel="stylesheet" href="bootstrap.css"> 
	
	<style type='text/css'>
	
	body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	   .sidebar-nav {
        padding: 9px 0;
      }
	.jumbotron {
        margin: 10px 0;
        text-align: center;
     }  
	 .jumbotron .lead {
        font-size: 30px;
        line-height: 1.25;
		margin-top: 150px;
    }
	.jumbotron .non-lead {
        font-size: 15px;
        line-height: 1.25;
    }
	#badb{
		margin-top: 300px
	}
	
	#example .modal-body {
		max-height: 550px;
	}
	#example {
		width: 750px; 
		margin: -50px 0 0 -350px; 
	}
	.sidebar-nav {
        padding: 9px 0;
     }
	 @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
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
        data.addColumn('number', 'Votes');
		var rows=[];
		<?php
			for($i=0;$i<$rows;$i++){
				$row = pg_fetch_object($result, $i);
		?>
		rows.push(['<?php echo $row->party ?>', <?php echo (int)$row->votes_num ?>]);
		<?php
			}
		?>
        data.addRows(rows);

        // Set chart options
        var options = {'title':'<?php echo $region.": ".$year?>',
                       'width':500,
                       'height':400};
		var options1 = 	{
                       'width':700,
                       'height':600
					   };		   

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
	
	<!-- MODAL -->
	<div id="example" class="modal hide fade in" style="display: none; ">  
		<div class="modal-header">  
		<a class="close" data-dismiss="modal">x</a>  
		<h3><?php echo $region.": ".$year ?></h3>  
		</div>  
		<div class="modal-body">  
		<div id="chart_div1"></div>
		</div>  
		<div class="modal-footer">  
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
		</div>  
	</div>  
	
	<div class="row-fluid">
	
		<!-- Sidebar -->
		<div class="span2">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Select a Region</li>
              <?php
					if($ris1>0){
						for($i=0;$i<$ris1;$i++){
						$riw1 = pg_fetch_object($res1, $i);
						if($riw1->region==$region){
			  ?>
			  <li class="active"><a tabindex="-1" href="#"><?php echo ucwords(strtolower($region)) ?></a></li>
			  <?php
			  }
			  else{
			  ?>
              <li><a tabindex="-1" href="./euregion_election.php?country=<?php echo $country ?>&year=<?php echo $year ?>&region=<?php echo $riw1->region ?>"><?php echo ucwords(strtolower($riw1->region)) ?></a> </li>
			  <?php
			  }
			  }
			  }
			  ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
	
		<!-- Right side, baby! -->
		<div class="span10">
			<div class="jumbotron">
				<div class="span3">
					<p class="lead"><?php echo $region ?></p><br>
					<p class="non-lead"><?php echo $country."(".$year.")" ?></p>
				</div>
				<!--Div that will hold the pie chart-->
				<div id="chart_div" class="span3 offset1"></div>
				<div class="span2 offset3"><a data-toggle="modal" href="#example" class="btn btn-primary btn-large" id="badb">Expand Chart</a></div>
			</div>
			<div class="row-fluid">
				<div class="span12">
				<table class="table table-bordered">
				<thead>  
				<tr>  
					<th>Party/President</th>
					<th>Number of votes</th>
				</tr>  
				</thead> 
				<tbody>
				<?php
				for($i=0;$i<$rows;$i++){
				$row = pg_fetch_object($result, $i);
				?>
					<tr>
					<td><?php echo $row->party ?></td>
					<td><?php echo $row->votes_num ?></td>
					</tr>	
				<?php
				}
				?>	
				</tbody>
				</table>		
				</div>
			</div>
		</div>
	
	</div>
	</div>
	
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	
    <script type="text/javascript" src="http://localhost/kyl/jquery.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/bootstrap-dropdown.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/bootstrap-modal.js"></script>	

</body>

</html>