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
	
	if(isset($_GET['year']) && isset($_GET['constituency']) && isset($_GET['candidate']) && isset($_GET['winner']) && isset($_GET['state'])){
		$year = $_GET['year'];
		$cons = $_GET['constituency'];
		$cand = $_GET['candidate'];
		$winner = $_GET['winner'];
		$state = $_GET['state'];
	}
	
	// generate and execute a query
	$query = "SELECT candidate FROM all_assembly WHERE constituency='$cons' AND  year='$year' and state='$state'"; 
	$result = pg_query($connection, $query) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows = pg_num_rows($result);
	
	$query1 = "SELECT * FROM all_assembly WHERE constituency='$cons' and year='$year' and candidate = '$cand' AND state='$state'"; 
	$result1 = pg_query($connection, $query1) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows1 = pg_num_rows($result1);
?>

<head>
	<title><?php echo ucwords(strtolower($cand)) ?></title>
	<link rel="stylesheet" href="bootstrap.css">
	<script type="text/javascript" src="http://localhost/kyl/jquery.js"></script>
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
	 @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
	#badb{
		margin-bottom: 10px;
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
	<div class="row-fluid">
		<!-- Sidebar -->
		<div class="span3">
		  <a href="./laelection_region.php?year=<?php echo $year?>&constituency=<?php echo $cons ?>&winner=<?php echo $winner ?>&state=<?php echo $state ?>" class="btn btn-large btn-block btn-primary" id="badb"><?php echo $cons ?></a>	
          <div class="well sidebar-nav" id="wrap">
            <ul class="nav nav-list" id="list">
              <li class="nav-header" id="header">Other candidates from <?php echo $cons ?></li>
              <?php
					if($rows>0){
						for($i=0;$i<$rows;$i++){
						$row = pg_fetch_object($result, $i);
						if($row->candidate==$cand){
			  ?>
			  <li class="active"><a tabindex="-1" href="#"><?php echo ucwords(strtolower($cand)) ?></a></li>
			  <?php
			  }
			  else{
			  ?>
              <li><a tabindex="-1" href="./lacandidate_profile.php?winner=<?php echo $winner ?>&year=<?php echo $year ?>&constituency=<?php echo $cons ?>&candidate=<?php echo $row->candidate ?>&state=<?php echo $state ?>"><?php echo ucwords(strtolower($row->candidate)) ?></a> </li>
			  <?php
			  }
			  }
			  }
			  ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
		
		<!-- Right side, baby! -->
		<div class="span9">
		<table class="table table-bordered table-striped"> 
				<tbody>
				<?php
				if($rows1>0){
					$row= pg_fetch_object($result1, 0);
				?>
				<tr>
					<td><strong>Name</strong></td>  
					<td><?php echo ucwords(strtolower($row->candidate)) ?></td>  
				</tr>
				<tr>
					<td><strong>Party</strong></td>  
					<td><?php echo $row->party ?></td>  
				</tr>
				<tr>
					<td><strong>Education</strong></td>  
					<td><?php echo $row->education ?></td>  
				</tr>
				<tr>
					<td><strong>Criminal Cases</strong></td>  
					<td><?php echo $row->criminal_cases ?></td>  
				</tr>
				<tr>
					<td><strong>Case Details</strong></td>  
					<td><?php echo $row->cases_details_accused ?></td>  
				</tr>
				<tr>
					<td><strong>Serious IPC Details</strong></td>  
					<td><?php echo $row->serious_ipc_detail ?></td>  
				</tr>
				<tr>
					<td><strong>Movable Assets</strong></td>  
					<td>&#8377; <?php echo $row->movable_assets ?></td>  
				</tr>
				<tr>
					<td><strong>Immovable Assets</strong></td>  
					<td>&#8377; <?php echo $row->immovable_assets ?></td>  
				</tr>
				<tr>
					<td><strong>Total Assets</strong></td>  
					<td>&#8377; <?php echo $row->total_assets ?></td>  
				</tr>
				<tr>
					<td><strong>Liabilities</strong></td>  
					<td>&#8377; <?php echo $row->liabilities ?></td>  
				</tr>
				<?php
					if($year>=2011){
				?>
				<tr>
					<td><strong>Self Profession</strong></td>  
					<td><?php echo $row->self_profession ?></td>  
				</tr>
				<tr>
					<td><strong>Spouse Profession</strong></td>  
					<td><?php echo $row->spouse_profession ?></td>  
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