<!DOCTYPE html>

<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
	if(isset($_GET['firstname']) && isset($_GET['lastname']) && isset($_GET['birthdate']) && isset($_GET['not']) && isset($_GET['gender'])){
		$first = $_GET['firstname'];
		$last = $_GET['lastname'];
		$not = $_GET['not'];
		$gender= $_GET['gender'];
		$birthd = $_GET['birthdate'];
	}
?>
<head>

    <title>Dashboard USA New2</title>
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
			<form class="form-horizontal" method="get" action="/kyl/usa_new_submit.php">
				<?php
				for($i=1;$i<=$not;$i++){
				?>
				<div class="control-group">
				<label class="control-label" for="<?php echo "t".$i."s" ?>"><?php echo "Term ".$i." Start" ?></label>
				<div class="controls">
				  <input class="span10" type="text" id="<?php echo "t".$i."s" ?>" name="<?php echo "t".$i."s" ?>" placeholder="yyyy-mm-dd">
				</div>
				</div>
				<div class="control-group">
				<label class="control-label" for="<?php echo "t".$i."e" ?>"><?php echo "Term ".$i." End" ?></label>
				<div class="controls">
				  <input class="span10" type="text" id="<?php echo "t".$i."e" ?>" name="<?php echo "t".$i."e" ?>" placeholder="yyyy-mm-dd">
				</div>
				</div>
				<div class="control-group">
				<label class="control-label" for="<?php echo "t".$i."p" ?>"><?php echo "Term ".$i." Party" ?></label>
				<div class="controls">
				  <input class="span10" type="text" id="<?php echo "t".$i."p" ?>" name="<?php echo "t".$i."p" ?>" placeholder="Party">
				</div>
				</div>
				<?php
				}
				?>
				<input type="hidden" name="firstname" value="<?php echo $first ?>" />
				<input type="hidden" name="lastname" value="<?php echo $last ?>" />
				<input type="hidden" name="birthdate" value="<?php echo $birthd ?>" />
				<input type="hidden" name="gender" value="<?php echo $gender ?>" />
				<input type="hidden" name="not" value="<?php echo $not ?>" />
				<div class="control-group">
				<div class="controls">
				  <a href="adminusa_new.php" class="btn btn-large btn-success">Go Back</a>
				  <button type="submit" class="btn btn-large btn-success">Submit</button>
				</div>
			  </div>
			</form>
		</div>
		</div>
	</div>
</body>

</html>	