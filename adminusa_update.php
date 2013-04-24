<!DOCTYPE html>

<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
	if(isset($_GET['term1start']) && isset($_GET['firstname']) && isset($_GET['lastname']) && isset($_GET['birthdate']) && isset($_GET['not']) && isset($_GET['gender'])){
		$term1start = $_GET['term1start'];
		$firstname = $_GET['firstname'];
		$lastname = $_GET['lastname'];
		$birthdate = $_GET['birthdate'];
		$not = $_GET['not'];
		$gender = $_GET['gender'];
	}
	
?>
<head>

    <title>USA Update</title>
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
	
	<div class='container-fluid'>
		<div class="row-fluid">
		<div class="span5 offset3">
			<form class="form-horizontal" method="get" action="/kyl/adminusa_update2.php">
			  <div class="control-group">
				<label class="control-label" for="firstname">First Name</label>
				<div class="controls">
				  <input class="span10" type="text" id="firstname" name="firstname" value="<?php echo $firstname ?>" disabled>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="lastname">Last Name</label>
				<div class="controls">
				  <input class="span10" type="text" id="lastname" name="lastname" value="<?php echo $lastname ?>" disabled>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="birthdate">Birth Date</label>
				<div class="controls">
				  <input class="span10" type="text" id="birthdate" name="birthdate" value="<?php echo $birthdate ?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="gender">Gender</label>
				<div class="controls">
				  <select id="gender" name="gender"> 
					<option>M</option>
					<option >F</option>
					</select>  
				</div>
			  </div>
			  <div class="control-group">
			  <label for="not1" class="control-label">Number of Terms </label>  
			  <div class="controls">
				<input type="number" name="not1" id="not1" min="1" max="3" step="1" value="<?php echo $not ?>">
			  </div>	 
			  </div>
			  <input type="hidden" name="not" value="<?php echo $not ?>" />
			  <input type="hidden" name="firstname" value="<?php echo $firstname ?>" />
			  <input type="hidden" name="lastname" value="<?php echo $lastname ?>" />
			  <input type="hidden" name="term1start" value="<?php echo $term1start ?>" />
			  <div class="control-group">
				<div class="controls">
				  <a href="admin_usa.php" class="btn btn-large btn-success">Go Back</a>
				  <button type="submit" class="btn btn-large btn-success">Next</button>
				</div>
			  </div>
			</form>
		</div>
		</div>
	</div>
</body>
</html>