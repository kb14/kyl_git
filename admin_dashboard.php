<!DOCTYPE html>

<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
	if(isset($_POST['name']) && isset($_POST['password'])){
		$pass = $_POST['password'];
		$name = $_POST['name'];
	}
	
	require_once __DIR__ . '/db_config.php';
	// open a connection to the database server
	$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
	password=$password");
	if (!$connection)
	{
	die("Could not open connection to database server");
	}
	
	// generate and execute a query
	$query = "SELECT * FROM admin"; 
	$result = pg_query($connection, $query) or die("Error in query:
	$query. " .
	pg_last_error($connection));
	$rows = pg_num_rows($result);
	$i=0;
	if($rows>0){
		for($i=0;$i<$rows;$i++){
			$row = pg_fetch_object($result, $i);
			if($name==$row->name && $pass==$row->password){
				break;
			}	
		}
	}
?>
<head>

    <title>Dashboard</title>
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
	  .jumbotron {
        margin: 80px 0;
        text-align: center;
     }
    .jumbotron .lead {
        font-size: 24px;
        line-height: 1.25;
    }
	.butn{
		margin-top: 100px	
	}
	
	#badb{
		margin-top: 30px
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
              <li><a href="#contact">Crime Data</a></li>
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
	
	<div class='container'>
	<?php
		if($name=="" || $pass == "" || $i==$rows){
	?>
	<!-- Jumbotron -->
      <div class="jumbotron">
        <p class="lead">Enter correct name/password</p>
      </div>
	
	<?php
		}
		else{
	?>
		<!-- Jumbotron -->
      <div class="jumbotron">
        <p class="lead">Update/Add Data by selecting a country/region</p>
      </div>
	  
	  <hr>
	  <div class="row-fluid">
		<div class="span4 offset4 butn" ><a href="#" class="btn btn-large btn-block btn-success">India</a>
		
		<!--<a href="./eu_countries.php" id="badb" class="btn btn-large btn-block btn-success">Europe</a>-->
		
		<a href="./admin_usa.php" id="badb" class="btn btn-large btn-block btn-success">USA</a></div>
	  </div>	
	<?php
	}
	?>
	
	</div>
</body>

</html>	