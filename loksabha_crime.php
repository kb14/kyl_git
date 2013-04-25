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
	
	if(isset($_GET['constituency'])){
		
		$cons = $_GET['constituency'];
		
	}
	
	// generate and execute a query
	$query = "SELECT DISTINCT(LOWER(constituency)) as constituency FROM cand_crime_ls ORDER BY constituency"; 
	$result = pg_query($connection, $query) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows = pg_num_rows($result);
	
	// generate and execute a query
	$query2 = "SELECT DISTINCT(year) as year FROM cand_crime_ls WHERE LOWER(constituency)='$cons' ORDER BY year"; 
	$result2 = pg_query($connection, $query2) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows2 = pg_num_rows($result2);
	
?>

<head>

	<title>Lok Sabha Crime</title>
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
			$("#myTable").tablesorter(); 
		}
		);	
	</script>
	<script type="text/javascript"> 
		$(document).ready(function() 
		{ 
			$("#myTable1").tablesorter(); 
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
	  .jumbotron {
        margin: 25px 0;
        text-align: center;
     }
    .jumbotron .lead {
        font-size: 18px;
        line-height: 1.25;
		}
	   .sidebar-nav {
        padding: 9px 0;
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
	<div class="row-fluid">
		<!-- Sidebar -->
		<div class="span2">
          <div class="well sidebar-nav" id="wrap">
            <ul class="nav nav-list" id="list">
              <li class="nav-header" id="header">Select a Constituency</li>
              <?php
					if($rows>0){
						for($i=0;$i<$rows;$i++){
						$row = pg_fetch_object($result, $i);
						if($row->constituency==$cons){
			  ?>
			  <li class="active"><a tabindex="-1" href="#"><?php echo ucwords(strtolower($cons)) ?></a></li>
			  <?php
			  }
			  else{
			  ?>
              <li><a tabindex="-1" href="./loksabha_crime.php?constituency=<?php echo $row->constituency ?>"><?php echo ucwords(strtolower($row->constituency)) ?></a> </li>
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
		<!--Small Jumbotron -->
      <div class="jumbotron">
        <p class="lead"><?php echo ucwords($cons) ?></p>
	  </div>
			<div class="tabbable">
				<ul class="nav nav-tabs"> 
				<?php
				for($i=1;$i<=$rows2;$i++){
					$row = pg_fetch_object($result2, $i-1);
					
					if($i==1){
				?>
				<li class="active"><a href="<?php echo "#".$i ?>" data-toggle="tab"><?php echo $row->year ?></a></li>  
				<?php
				}else{
				?>
				<li><a href="<?php echo "#".$i ?>" data-toggle="tab"><?php echo $row->year ?></a></li>  
				<?php
				}
				}
				?>
				</ul>
				<div class="tab-content">  
					<?php
					for($i=1;$i<=$rows2;$i++){
					$row = pg_fetch_object($result2, $i-1);
					// generate and execute a query
					$query1 = "SELECT * FROM cand_crime_ls WHERE LOWER(constituency)='$cons' AND year='$row->year'"; 
					$result1 = pg_query($connection, $query1) or die("Error in query:
					$query. " .
					pg_last_error($connection));
					$rows1 = pg_num_rows($result1);
					if($i==1){
					?>
					<div class="tab-pane active" id="<?php echo $i ?>">  
					
						<div class="row-fluid">
							<div class="span12">
								<table class="table table-bordered table-striped" id="myTable">
									<thead>  
									<tr>  
										<th>Name</th>  
										<th>Party</th>
										<th>Criminal Cases</th>
										<th>Case Details Accused</th>
										<th>Serious IPC Details</th>
										<th>Other IPC Details</th>
										<th>Action</th>
									</tr>  
									</thead> 
									<tbody>
									<?php
									for($j=0;$j<$rows1;$j++){
									$row1 = pg_fetch_object($result1, $j);
									?>
										<tr>
										<td><?php echo ucwords(strtolower($row1->candidate)) ?></td>
										<td><?php echo $row1->party ?></td>  
										<td><?php echo $row1->criminal_cases ?></td>  
										<td><?php echo $row1->cases_details_accused ?></td> 
										<td><?php echo $row1->serious_ipc_detail ?></td>  
										<td><?php echo $row1->other_ipc_detail ?></td>
										<td><a class="btn" href="./lscrime_update.php?candidate=<?php echo $row1->candidate ?>&party=<?php echo $row1->party ?>&constituency=<?php echo $row1->constituency ?>&year=<?php echo $row->year ?>">Edit</a></td>	
										</tr>
									<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</div>	
					</div>  
					<?php
					}else{
					?>
					<div class="tab-pane" id="<?php echo $i ?>">  
					<div class="row-fluid">
							<div class="span12">
								<table class="table table-bordered table-striped" id="myTable1">
									<thead>  
									<tr>  
										<th>Name</th>  
										<th>Party</th>
										<th>Criminal Cases</th>
										<th>Case Details Accused</th>
										<th>Serious IPC Details</th>
										<th>Other IPC Details</th>
										<th>Action</th>
									</tr>  
									</thead> 
									<tbody>
									<?php
									for($j=0;$j<$rows1;$j++){
									$row1 = pg_fetch_object($result1, $j);
									?>
										<tr>
										<td><?php echo ucwords(strtolower($row1->candidate)) ?></td>
										<td><?php echo $row1->party ?></td>  
										<td><?php echo $row1->criminal_cases ?></td>  
										<td><?php echo $row1->cases_details_accused ?></td> 
										<td><?php echo $row1->serious_ipc_detail ?></td>  
										<td><?php echo $row1->other_ipc_detail ?></td>  	
										<td><a class="btn" href="./lscrime_update.php?candidate=<?php echo $row1->candidate ?>&party=<?php echo $row1->party ?>&constituency=<?php echo $row1->constituency ?>&year=<?php echo $row->year ?>">Edit</a></td>	
										</tr>
									<?php
									}
									?>
									</tbody>
								</table>
							</div>
						</div>	
					</div>	
					<?php
					}
					}
					?>
				</div>  
			</div>
		</div>
	</div>
	</div>
	
	
	<!-- LE Javascript -->
	
	 
	<script src="http://localhost/kyl/bootstrap-tab.js"></script>
</body>	
</html>