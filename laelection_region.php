<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

<?php
	$election="Lok Sabha";
	require_once __DIR__ . '/db_config.php';
		
	// open a connection to the database server
	$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
	password=$password");
	if (!$connection)
	{
	die("Could not open connection to database server");
	}
	
	if(isset($_GET['year']) && isset($_GET['state']) && isset($_GET['constituency']) && isset($_GET['winner'])){
		$year = $_GET['year'];
		$state = $_GET['state'];
		$cons= $_GET['constituency'];
		$winner = $_GET['winner'];
	}
	
	$query1 = "SELECT constituency,candidate from win_assembly WHERE year='$year' AND state='$state'"; 
	$result1 = pg_query($connection, $query1) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows1 = pg_num_rows($result1);
	
	// generate and execute a query
	$query = "SELECT candidate, party, criminal_cases, total_assets from all_assembly WHERE year='$year' and constituency='$cons' and state='$state'"; 
	$result = pg_query($connection, $query) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows = pg_num_rows($result);

?>
<head>

	<title><?php echo $cons.": ".$year ?></title>
	<link rel="stylesheet" href="bootstrap.css">
	<script type="text/javascript" src="http://localhost/kyl/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/jquery.tablesorter.js"></script>
	<script type="text/javascript"> 
		$(document).ready(function() 
		{ 
			$("#myTable").tablesorter(); 
		}
		);	
	</script>	
	<script> 
 
	(function ($) {
	  jQuery.expr[':'].Contains = function(a,i,m){
		  return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
	  };
	  
	  function listFilter(header, list) {
		var form = $("<form>").attr({"class":"filterform","action":"#"}),
			input = $("<input>").attr({"class":"filterinput","type":"text","id":"inp"});
		$(form).append(input).appendTo(header);
	 
		$(input)
		  .change( function () {
			var filter = $(this).val();
			if(filter) {
			  $(list).find("a:not(:Contains(" + filter + "))").parent().slideUp();
			  $(list).find("a:Contains(" + filter + ")").parent().slideDown();
			} else {
			  $(list).find("li").slideDown();
			}
			return false;
		  })
		.keyup( function () {
			$(this).change();
		});
	  }
	 
	  $(function () {
		listFilter($("#header"), $("#list"));
	  });
	}(jQuery));
	 
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
    }
	.jumbotron .non-lead {
        font-size: 15px;
        line-height: 1.25;
    }
	#badb{
		margin-top: 300px
	}
	#badb1{
		margin-bottom: 10px;
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
	#inp {
		border:1px solid #ccc;
		border-bottom-color:#eee;
		border-right-color:#eee;
		box-sizing:border-box;
		-moz-box-sizing:border-box;
		-webkit-box-sizing:border-box;
		-ms-box-sizing:border-box;
		font-size:1em;
		height:2.25em;
		*height:1.5em;
		line-height:1.5em;
		padding:0.29em 0;
		width:100%;
		margin:0 0 0.75em;
	}
	.filterform {
		width:160px;
		font-size:12px;
		display:block;
	}
	.filterform input {
		height: 30px;
		-moz-border-radius:5px;
		border-radius:5px;
		-webkit-border-radius:5px;
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
	
	<div class="container-fluid">
	
	
	<div class="row-fluid">
	
		<!-- Sidebar -->
		<div class="span2">
		<a href="./lastate_election.php?year=<?php echo $year?>&state=<?php echo $state ?>" class="btn btn-large btn-block btn-primary" id="badb1"><?php echo $state ?></a>	
          <div class="well sidebar-nav" id="wrap">
            <ul class="nav nav-list" id="list">
              <li class="nav-header" id="header">Select a Constituency</li>
              <?php
					if($rows1>0){
						for($i=0;$i<$rows1;$i++){
						$row = pg_fetch_object($result1, $i);
						if($row->constituency==$cons){
			  ?>
			  <li class="active"><a tabindex="-1" href="#"><?php echo ucwords(strtolower($cons)) ?></a></li>
			  <?php
			  }
			  else{
			  ?>
              <li><a tabindex="-1" href="./laelection_region.php?state=<?php echo $state ?>&year=<?php echo $year ?>&constituency=<?php echo $row->constituency ?>&winner=<?php echo $row->candidate ?>"><?php echo ucwords(strtolower($row->constituency)) ?></a> </li>
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
					<p class="lead"><?php echo $cons ?></p><br>
					
					<p class="non-lead"><?php echo $state.": ".$year ?> </p>
					
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
				<table class="table table-bordered table-striped" id="myTable">
				<thead>  
				<tr>  
					<th>Name</th>  
					<th>Party</th>
					<th>Criminal Cases</th>
					<th>Total Assets</th>
				</tr>  
				</thead> 
				<tbody>
				<?php
				for($i=0;$i<$rows;$i++){
				$row = pg_fetch_object($result, $i);
				?>
					<tr>
					<?php
						if($row->candidate==$winner){
					?>
					<td><a href="./lacandidate_profile.php?winner=<?php echo $row->candidate?>&candidate=<?php echo $row->candidate?>&year=<?php echo $year ?>&constituency=<?php echo $cons ?>&state=<?php echo $state ?>"><?php echo ucwords(strtolower($row->candidate)) ?></a>            <span class="label label-info">Winner</span></td>  
					<?php
					}else{
					?>
					<td><a href="./lacandidate_profile.php?winner=<?php echo $winner?>&candidate=<?php echo $row->candidate?>&year=<?php echo $year ?>&constituency=<?php echo $cons ?>&state=<?php echo $state ?>"><?php echo ucwords(strtolower($row->candidate)) ?></a></td>  
					<?php
					}
					?>
					<td><?php echo $row->party ?></td>  
					<td><?php echo $row->criminal_cases ?></td>  
					<td><?php echo $row->total_assets ?></td>  
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
	
	<script type="text/javascript" src="http://localhost/kyl/bootstrap-modal.js"></script>	
	
</body>	
</html>	