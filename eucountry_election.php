<!DOCTYPE html>

<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
		if(isset($_GET['country']) && isset($_GET['year'])){
			$country = $_GET['country'];
			$year = $_GET['year'];
		}
	
		require_once __DIR__ . '/db_config.php';
		
		// open a connection to the database server
		$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
		password=$password");
		if (!$connection){
			die("Could not open connection to database server");
		}
		
		// generate and execute a query
		$query = "SELECT party,votes_num FROM european WHERE country='$country' and year='$year' and region='$country'"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));	
		$rows = pg_num_rows($result);	
		
		$qry = "SELECT region FROM european WHERE country='$country' and year='$year' and region<>'$country' GROUP BY region ORDER BY region"; 
		$res = pg_query($connection, $qry) or die("Error in query:
		$query. " .
		pg_last_error($connection));	
		$ris = pg_num_rows($res);
		
		$qry1 = "SELECT country FROM reg_euro GROUP BY country ORDER BY country"; 
		$res1 = pg_query($connection, $qry1) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$ris1 = pg_num_rows($res1);
		
		$qry2 = "SELECT country,year FROM european GROUP BY year,country ORDER BY country"; 
		$res2 = pg_query($connection, $qry2) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		$ris2 = pg_num_rows($res2);
		
		
?>

<head>
	<title>EU <?php echo $country ?> Election Results</title>
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
	#badt{
		font-size: 24px
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
        var options = {'title':'<?php echo $country.": ".$year?>',
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
              <li><a href="./twitter_search.php">Twitter Search</a></li>
              <li><a href="#contact">About</a></li>
			  <li><a href="#submit">Admin Panel</a></li>
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
		<h3><?php echo $country.": ".$year ?></h3>  
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
              <li class="nav-header">Select a Counrty</li>
              <?php
					if($ris1>0){
						for($i=0;$i<$ris1;$i++){
						$riw1 = pg_fetch_object($res1, $i);
						if($riw1->country==$country){
			  ?>
			  <li class="active dropdown-submenu"><a tabindex="-1" href="#"><?php echo ucwords(strtolower($country)) ?></a>
				<ul class="well well-small dropdown-menu">
					<?php
						if($ris2>0){
						for($j=0;$j<$ris2;$j++){
							$riw2 = pg_fetch_object($res2, $j);
							if($riw1->country==$riw2->country){
					?>
						<li><a href="./eucountry_election.php?country=<?php echo $riw1->country?>&year=<?php echo $riw2->year ?>"><?php echo $riw2->year ?></a></li>
					<?php
					}
					}
					}
					?>		
				</ul>
			  </li>
			  <?php
			  }
			  else{
			  ?>
              <li class="dropdown-submenu"><a tabindex="-1" href="#"><?php echo ucwords(strtolower($riw1->country)) ?></a>
				<ul class="well well-small dropdown-menu">
					<?php
					if($ris2>0){
					for($j=0;$j<$ris2;$j++){
						$riw2 = pg_fetch_object($res2, $j);
						if($riw1->country==$riw2->country){
					?>
						<li><a href="./eucountry_election.php?country=<?php echo $riw1->country?>&year=<?php echo $riw2->year ?>"><?php echo $riw2->year ?></a></li>
					<?php
					}
					}
					}
					?>		
				</ul>
			  </li>
			  <?php
			  }
			  }
			  }
			  ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
		
		
		<div class="span10">
			<div class="jumbotron">
				<div class="span2">
					<p class="lead"><?php echo $country ?></p><br>
					<p class="non-lead">Year: <?php echo $year ?></p>
				</div>
				<!--Div that will hold the pie chart-->
				<div id="chart_div" class="span3 offset1"></div>
				<div class="span2 offset3"><a data-toggle="modal" href="#example" class="btn btn-primary btn-large" id="badb">Expand Chart</a></div>
			</div>
			

		
		
			<div class="row-fluid">
			<div class="well span12" id="badt">Select a region for regional results:</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
				<?php
					if ($ris > 0){
					for($i=0;$i<$ris;$i++){
						$riw = pg_fetch_object($res, $i);
				?>
				
				<a href="./euregion_election.php?country=<?php echo $country ?>&year=<?php echo $year ?>&region=<?php echo $riw->region ?>" class="btn btn-large btn-link"><?php echo $riw->region ?></a>	
				<?php
				}
				}
				?>
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